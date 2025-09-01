@extends('layouts.app')

@section('content')
@php
  $price = $dayPriceCents / 100;
@endphp
<div class="container mx-auto py-10 text-slate-100">
  <h1 class="text-3xl font-semibold mb-6">Buy Days</h1>

  <div class="bg-slate-800 rounded-xl p-6 max-w-xl">
    <p class="mb-4 text-slate-300">Current price per day: <span class="font-semibold">€{{ number_format($price, 2) }}</span></p>

    <form method="POST" action="{{ route('client.buydays.store') }}" class="flex items-end gap-3">
      @csrf
      <div class="flex-1">
        <label class="block text-sm text-slate-300 mb-1">Days</label>
        <input type="number" name="days" min="1" max="100" value="1" class="w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2"/>
      </div>
      <button class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500">Add to Balance</button>
    </form>

    <p class="mt-4 text-slate-400 text-sm">Payment provider integration (Mollie) will be added here. For now, days are added instantly for testing.</p>
  </div>
</div>
@endsection
