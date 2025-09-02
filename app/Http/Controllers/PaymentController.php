<?php

namespace App\Http\Controllers;

use App\Models\PricingItem;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mollie\Laravel\Facades\Mollie;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        // Allow either a posted pricing_item_id or a cart in session.
        // Validate user_id is present.
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        $pricingItem = null;
        if ($request->filled('pricing_item_id')) {
            $pricingItem = PricingItem::findOrFail($request->pricing_item_id);
        } else {
            // Attempt to pick first item from session cart
            $sessionCart = session('cart', []);
            if (!empty($sessionCart)) {
                $first = $sessionCart[0];
                $pricingItem = PricingItem::find($first['id']);
            }
        }

        if (!$pricingItem) {
            return back()->with('error', 'No pricing item selected for payment.');
        }

        // Calculate total price and days for packs
        $totalPrice = $pricingItem->price;
        $days = 1; // Default to 1 day

        if ($pricingItem->category->slug === 'our-packs') {
            $days = (int) filter_var($pricingItem->title, FILTER_SANITIZE_NUMBER_INT);
            $totalPrice = $pricingItem->price * $days;
        }

        // Create purchase record
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'pricing_item_id' => $pricingItem->id,
            'days' => $days,
            'amount' => $totalPrice,
            'currency' => 'EUR',
            'status' => 'pending',
            'mollie_payment_id' => null,
        ]);

        try {
            // Create Mollie payment using the Laravel package
            $payment = Mollie::api()->payments->create([
                'amount' => [
                    'currency' => 'EUR',
                    'value' => number_format($totalPrice, 2, '.', ''),
                ],
                'description' => "Imhotion - {$pricingItem->title}",
                'redirectUrl' => route('payment.return', ['purchase' => $purchase->id]),
                'webhookUrl' => route('payment.webhook'),
                'metadata' => [
                    'purchase_id' => $purchase->id,
                    'user_id' => $user->id,
                ],
            ]);

            // Update purchase with Mollie payment ID
            $purchase->update([
                'mollie_payment_id' => $payment->id,
            ]);

            // If AJAX/json request, return URL as JSON for client to follow, otherwise redirect
            if ($request->expectsJson()) {
                return response()->json(['redirect_url' => $payment->getCheckoutUrl()]);
            }

            return redirect($payment->getCheckoutUrl());

        } catch (\Exception $e) {
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

                return redirect('/client')->with('success', 'Payment successful! Welcome to Imhotion.');
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
                        $purchase->update(['status' => 'completed']);
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
