<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Purchase;

class ClientPurchaseController extends Controller
{
    public function create()
    {
        $price = (int) DB::table('settings')->where('key','day_price_cents')->value('value');
        return view('client.buy-days', [
            'dayPriceCents' => $price ?: 0,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'days' => ['required','integer','min:1','max:100'],
        ]);

        $priceCents = (int) DB::table('settings')->where('key','day_price_cents')->value('value');
        $total = $priceCents * $data['days'];

        // Create a pending purchase (integrate Mollie later)
        $purchase = Purchase::create([
            'user_id'      => $request->user()->id,
            'days'         => $data['days'],
            'price_cents'  => $total,
            'status'       => 'pending',
            'provider'     => 'mollie',
            'provider_id'  => null,
            'meta'         => null,
        ]);

        // For now: instantly mark as paid and add balance (until Mollie is wired)
        $purchase->update(['status' => 'paid']);
        $request->user()->increment('balance_days', $data['days']);

        return redirect('/client')->with('ok', 'Days added to your balance.');
    }
}
