<?php

namespace App\Http\Controllers;

use App\Models\PricingItem;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mollie\Laravel\Facades\Mollie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        // Quick guard: ensure Mollie API key is configured
        if (empty(config('mollie.key'))) {
            \Log::error('Mollie key missing in configuration.');
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Payment initialization failed: Mollie API key not configured.'], 500);
            }
            return back()->with('error', 'Payment initialization failed: Mollie API key not configured.');
        }

        // Allow either a posted pricing_item_id or a cart in session.
        // Prefer explicit user_id, otherwise use the authenticated user.
        $user = null;
        if ($request->filled('user_id')) {
            $user = User::findOrFail($request->user_id);
        } elseif (Auth::check()) {
            $user = Auth::user();
        } else {
            return back()->with('error', 'User not authenticated for payment.');
        }

        $pricingItem = null;
        $sessionCart = session('cart', []);

        // If a single pricing_item_id is provided, use that.
        if ($request->filled('pricing_item_id')) {
            $pricingItem = PricingItem::findOrFail($request->pricing_item_id);
            $itemsForSummary = [[
                'id' => $pricingItem->id,
                'title' => $pricingItem->title,
                'price' => (float) $pricingItem->price,
                'qty' => 1,
            ]];
            $totalPrice = (float) $pricingItem->price;
            $days = 1;
            if ($pricingItem->category && $pricingItem->category->slug === 'our-packs') {
                $days = (int) filter_var($pricingItem->title, FILTER_SANITIZE_NUMBER_INT);
                $totalPrice = $pricingItem->price * $days;
                $itemsForSummary[0]['qty'] = $days;
            }
        } else {
            // Build items summary from session cart
            $ids = collect($sessionCart)->pluck('id')->filter()->values()->all();
            $items = $ids ? PricingItem::whereIn('id', $ids)->get()->keyBy('id') : collect();
            $itemsForSummary = [];
            $totalPrice = 0;
            foreach ($sessionCart as $c) {
                $id = $c['id'] ?? null;
                $qty = $c['qty'] ?? ($c['quantity'] ?? 1);
                $pi = $items->get($id);
                if ($pi) {
                    $price = (float) $pi->price;
                    $itemsForSummary[] = [
                        'id' => $pi->id,
                        'title' => $pi->title,
                        'price' => $price,
                        'qty' => $qty,
                    ];
                    $totalPrice += $price * $qty;
                }
            }
            $pricingItem = null; // multi-item purchase
            $days = array_sum(array_column($itemsForSummary, 'qty')) ?: 1;
        }

        if (empty($itemsForSummary)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No pricing item selected for payment.'], 422);
            }
            return back()->with('error', 'No pricing item selected for payment.');
        }

        // Create purchase record (single record representing the whole purchase)
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'pricing_item_id' => $pricingItem ? $pricingItem->id : null,
            'days' => $days,
            'amount' => $totalPrice,
            'currency' => 'EUR',
            'status' => 'pending',
            'mollie_payment_id' => null,
            'payment_data' => $itemsForSummary, // store items summary for admin/reporting
        ]);

        try {
            // Create Mollie payment using the Laravel package
            $payment = Mollie::api()->payments->create([
                'amount' => [
                    'currency' => 'EUR',
                    'value' => number_format($totalPrice, 2, '.', ''),
                ],
                'description' => "Imhotion - Purchase #{$purchase->id}",
                'redirectUrl' => route('payment.return', ['purchase' => $purchase->id]),
                'webhookUrl' => route('payment.webhook'),
                'metadata' => [
                    'purchase_id' => $purchase->id,
                    'user_id' => $user->id,
                    'items' => $itemsForSummary,
                ],
            ]);

            // Update purchase with Mollie payment ID
            $purchase->update([
                'mollie_payment_id' => $payment->id,
            ]);

            // Prepare response payload
            $payload = [
                'redirect_url' => $payment->getCheckoutUrl(),
                'purchase' => [
                    'id' => $purchase->id,
                    'amount' => (float) $purchase->amount,
                    'currency' => $purchase->currency,
                    'days' => $purchase->days,
                    'items' => $itemsForSummary,
                ],
            ];

            // If AJAX/json request, return structured JSON for client to follow, otherwise redirect
            if ($request->expectsJson()) {
                return response()->json($payload);
            }

            return redirect($payment->getCheckoutUrl());

        } catch (\Exception $e) {
            // Log full exception for debugging
            \Log::error('Mollie payment initialization error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Payment initialization failed: ' . $e->getMessage()], 500);
            }

            return back()->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }

    public function paymentReturn(Request $request, Purchase $purchase)
    {
        try {
            // Check payment status with Mollie
            $payment = Mollie::api()->payments->get($purchase->mollie_payment_id);

            if ($payment->isPaid()) {
                $purchase->update(['status' => 'completed']);

                // Log in the user if not already logged in
                if (!Auth::check()) {
                    Auth::login($purchase->user);
                }

                // Clear the selected plan session
                session()->forget('selected_plan_for_payment');
                // Clear cart now that purchase completed
                session()->forget('cart');

                // Save payment metadata and paid timestamp
                $purchase->update([
                    'payment_data' => $payment->toArray(),
                    'paid_at' => now(),
                ]);

                // Idempotent: only credit user days once per purchase
                try {
                    $purchase->refresh();
                    if (empty($purchase->credited)) {
                        DB::beginTransaction();
                        try {
                            $user = User::where('id', $purchase->user_id)->lockForUpdate()->first();
                            if ($user) {
                                $credit = (int) ($purchase->days ?? 0);
                                if (array_key_exists('balance_days', $user->getAttributes())) {
                                    $user->balance_days = ($user->balance_days ?? 0) + $credit;
                                } else {
                                    $user->days_balance = ($user->days_balance ?? 0) + $credit;
                                }
                                $user->save();
                                $purchase->update(['credited' => 1]);
                            } else {
                                \Log::warning('Payment crediting: user not found for purchase ' . $purchase->id);
                            }
                            DB::commit();
                        } catch (\Exception $ex) {
                            DB::rollBack();
                            \Log::error('Failed to credit user for purchase '.$purchase->id.': '.$ex->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Payment return post-processing error for purchase '.$purchase->id.': '.$e->getMessage());
                }

                return redirect('/dashboard')->with('success', 'Payment successful! Welcome to Imhotion.');
            } elseif ($payment->isFailed()) {
                $purchase->update(['status' => 'failed']);
                return redirect('/')->with('error', 'Payment failed. Please try again.');
            } else {
                return redirect('/')->with('info', 'Payment is being processed. You will receive an email confirmation shortly.');
            }
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Unable to verify payment status.');
        }
    }

    public function webhook(Request $request)
    {
        $paymentId = $request->input('id');

        try {
            // Get payment details from Mollie
            $payment = Mollie::api()->payments->get($paymentId);
            $purchaseId = $payment->metadata->purchase_id ?? null;

            if ($purchaseId) {
                $purchase = Purchase::find($purchaseId);

                if ($purchase) {
                    if ($payment->isPaid()) {
                                                $purchase->update([
                                                    'status' => 'completed',
                                                    'payment_data' => $payment->toArray(),
                                                    'paid_at' => now(),
                                                ]);

                                                // Idempotent credit via webhook as well
                                                try {
                                                    $purchase->refresh();
                                                    if (empty($purchase->credited)) {
                                                        DB::beginTransaction();
                                                        try {
                                                            $user = User::where('id', $purchase->user_id)->lockForUpdate()->first();
                                                            if ($user) {
                                                                $credit = (int) ($purchase->days ?? 0);
                                                                if (array_key_exists('balance_days', $user->getAttributes())) {
                                                                    $user->balance_days = ($user->balance_days ?? 0) + $credit;
                                                                } else {
                                                                    $user->days_balance = ($user->days_balance ?? 0) + $credit;
                                                                }
                                                                $user->save();
                                                                $purchase->update(['credited' => 1]);
                                                            } else {
                                                                \Log::warning('Webhook crediting: user not found for purchase ' . $purchase->id);
                                                            }
                                                            DB::commit();
                                                        } catch (\Exception $ex) {
                                                            DB::rollBack();
                                                            \Log::error('Webhook crediting failed for purchase '.$purchase->id.': '.$ex->getMessage());
                                                        }
                                                    }
                                                } catch (\Exception $e) {
                                                    \Log::error('Webhook post-processing error for purchase '.$purchase->id.': '.$e->getMessage());
                                                }
                    } elseif ($payment->isFailed()) {
                        $purchase->update(['status' => 'failed']);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Mollie webhook error: ' . $e->getMessage());
        }

        return response('OK', 200);
    }
}
