@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900" style="font-family: var(--font-sans)">
                Welcome back, {{ Auth::user()->name }}!
            </h1>
            <div class="text-sm text-gray-600" style="font-family: var(--font-sans)">
                Dashboard
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                <p style="font-family: var(--font-sans)">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar Navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-blue-600 px-6 py-4" style="background-color: var(--brand-primary);">
                        <h2 class="text-white font-semibold" style="font-family: var(--font-sans)">Navigation</h2>
                    </div>
                    <nav class="p-2">
                        <a href="#home" onclick="showSection('home')" id="nav-home" 
                           class="nav-link active flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md mb-1 transition-colors"
                           style="font-family: var(--font-sans)">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            Home
                        </a>
                        <a href="#transactions" onclick="showSection('transactions')" id="nav-transactions" 
                           class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md mb-1 transition-colors"
                           style="font-family: var(--font-sans)">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Transactions
                        </a>
                        <a href="#profile" onclick="showSection('profile')" id="nav-profile" 
                           class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md transition-colors"
                           style="font-family: var(--font-sans)">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-3">
                
                <!-- Home Section -->
                <div id="section-home" class="section active">
                    
                    <!-- Virtual Cart at Top -->
                    @if(session('selected_plan_for_payment'))
                        @php
                            $selectedPlan = $pricingItems->find(session('selected_plan_for_payment'));
                        @endphp
                        @if($selectedPlan)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-blue-900 mb-2" style="font-family: var(--font-sans)">
                                            🛒 Your Cart
                                        </h3>
                                        <p class="text-blue-700 text-sm mb-4" style="font-family: var(--font-sans)">
                                            Review your selected items and proceed to secure checkout via Mollie
                                        </p>
                                        
                                        <div class="bg-white rounded-lg p-4 border border-blue-300 mb-4">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <h4 class="font-semibold text-gray-900" style="font-family: var(--font-sans)">
                                                        {{ $selectedPlan->title }}
                                                    </h4>
                                                    <p class="text-sm text-gray-600" style="font-family: var(--font-sans)">
                                                        {{ $selectedPlan->category->title }}
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-xl font-bold text-blue-600" style="font-family: var(--font-sans)">
                                                        €{{ number_format($selectedPlan->price, 0) }}
                                                    </div>
                                                    <div class="text-sm text-gray-500" style="font-family: var(--font-sans)">
                                                        per {{ str_replace('per_', '', $selectedPlan->price_unit) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <img src="https://mollie.com/img/payment-methods/ideal.svg" alt="iDEAL" class="h-6">
                                        <img src="https://mollie.com/img/payment-methods/creditcard.svg" alt="Credit Card" class="h-6">
                                        <img src="https://mollie.com/img/payment-methods/paypal.svg" alt="PayPal" class="h-6">
                                    </div>
                                </div>
                                
                                <form method="POST" action="{{ route('dashboard.add-to-cart') }}" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="pricing_item_id" value="{{ session('selected_plan_for_payment') }}">
                                    <button type="submit" 
                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors flex items-center justify-center"
                                            style="font-family: var(--font-sans)">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        Proceed to Secure Checkout
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif

                    <!-- Available Services -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-8">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900" style="font-family: var(--font-sans)">
                                Available Services
                            </h2>
                        </div>
                        <div class="p-6">
                            @foreach($pricingItems->groupBy('category.title') as $categoryTitle => $items)
                                <div class="mb-8 last:mb-0">
                                    <h3 class="text-lg font-medium text-gray-800 mb-4" style="font-family: var(--font-sans)">
                                        {{ $categoryTitle }}
                                    </h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($items as $item)
                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-blue-300 transition-colors">
                                                <h4 class="text-lg font-semibold text-gray-900 mb-2" style="font-family: var(--font-sans)">
                                                    {{ $item->title }}
                                                </h4>
                                                @if($item->description)
                                                    <p class="text-gray-600 text-sm mb-4" style="font-family: var(--font-sans)">
                                                        {{ $item->description }}
                                                    </p>
                                                @endif
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xl font-bold text-blue-600" style="font-family: var(--font-sans)">
                                                        €{{ number_format($item->price, 0) }}
                                                        <span class="text-sm text-gray-500">/{{ str_replace('per_', '', $item->price_unit) }}</span>
                                                    </span>
                                                    <form method="POST" action="{{ route('dashboard.add-to-cart') }}">
                                                        @csrf
                                                        <input type="hidden" name="pricing_item_id" value="{{ $item->id }}">
                                                        <button type="submit" 
                                                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                                                style="font-family: var(--font-sans); background-color: var(--brand-primary);">
                                                            Add to Cart
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Client Area Preview -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900" style="font-family: var(--font-sans)">
                                Your Projects Overview
                            </h2>
                        </div>
                        <div class="p-6">
                            <!-- Stats Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <h3 class="text-sm font-medium text-blue-900 mb-1" style="font-family: var(--font-sans)">Active Projects</h3>
                                    <div class="text-2xl font-bold text-blue-600" style="font-family: var(--font-sans)">
                                        {{ $userPurchases->where('status', 'completed')->count() }}
                                    </div>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                    <h3 class="text-sm font-medium text-green-900 mb-1" style="font-family: var(--font-sans)">Balance (days)</h3>
                                    <div class="text-2xl font-bold text-green-600" style="font-family: var(--font-sans)">
                                        {{ rand(5, 30) }}
                                    </div>
                                    <div class="text-xs text-green-700 mt-1" style="font-family: var(--font-sans)">Top-up available</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h3 class="text-sm font-medium text-gray-700 mb-1" style="font-family: var(--font-sans)">Total Spent</h3>
                                    <div class="text-2xl font-bold text-gray-900" style="font-family: var(--font-sans)">
                                        €{{ number_format($userPurchases->sum('amount'), 0) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Projects Table -->
                            @if($userPurchases->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Service</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Amount</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Date</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($userPurchases->take(5) as $purchase)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900" style="font-family: var(--font-sans)">
                                                            {{ $purchase->pricingItem->title }}
                                                        </div>
                                                        <div class="text-sm text-gray-500" style="font-family: var(--font-sans)">
                                                            {{ $purchase->pricingItem->category->title }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                            {{ $purchase->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                               ($purchase->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}"
                                                            style="font-family: var(--font-sans)">
                                                            {{ ucfirst($purchase->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" style="font-family: var(--font-sans)">
                                                        €{{ number_format($purchase->amount, 2) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" style="font-family: var(--font-sans)">
                                                        {{ $purchase->created_at->format('M d, Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <a href="/client" class="text-blue-600 hover:text-blue-900 font-medium" style="font-family: var(--font-sans)">
                                                            View Details
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                @if($userPurchases->count() > 5)
                                    <div class="mt-4 text-center">
                                        <a href="/client" class="text-blue-600 hover:text-blue-900 font-medium" style="font-family: var(--font-sans)">
                                            View All Projects →
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8">
                                    <div class="text-gray-400 mb-4">
                                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2" style="font-family: var(--font-sans)">
                                        No projects yet
                                    </h3>
                                    <p class="text-gray-600 mb-4" style="font-family: var(--font-sans)">
                                        Get started by purchasing one of our services above.
                                    </p>
                                    <a href="/client" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                                       style="font-family: var(--font-sans); background-color: var(--brand-primary);">
                                        Explore Client Area
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Transactions Section -->
                <div id="section-transactions" class="section" style="display: none;">
                    <div class="bg-white rounded-lg shadow-md border border-gray-200">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900" style="font-family: var(--font-sans)">
                                Transaction History
                            </h2>
                        </div>
                        <div class="p-6">
                            @if($userPurchases->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Transaction ID</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Service</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Amount</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($userPurchases as $purchase)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900" style="font-family: var(--font-mono)">
                                                        #{{ str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900" style="font-family: var(--font-sans)">
                                                            {{ $purchase->pricingItem->title }}
                                                        </div>
                                                        <div class="text-sm text-gray-500" style="font-family: var(--font-sans)">
                                                            {{ $purchase->pricingItem->category->title }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900" style="font-family: var(--font-sans)">
                                                        €{{ number_format($purchase->amount, 2) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                            {{ $purchase->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                               ($purchase->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}"
                                                            style="font-family: var(--font-sans)">
                                                            {{ ucfirst($purchase->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" style="font-family: var(--font-sans)">
                                                        {{ $purchase->created_at->format('M d, Y H:i') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="text-gray-400 mb-4">
                                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2" style="font-family: var(--font-sans)">
                                        No transactions yet
                                    </h3>
                                    <p class="text-gray-600" style="font-family: var(--font-sans)">
                                        Your purchase history will appear here once you make your first order.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Profile Section -->
                <div id="section-profile" class="section" style="display: none;">
                    <div class="bg-white rounded-lg shadow-md border border-gray-200">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900" style="font-family: var(--font-sans)">
                                Profile Settings
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Profile Info -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4" style="font-family: var(--font-sans)">
                                        Account Information
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700" style="font-family: var(--font-sans)">Full Name</label>
                                            <div class="mt-1 text-sm text-gray-900" style="font-family: var(--font-sans)">{{ Auth::user()->name }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700" style="font-family: var(--font-sans)">Email Address</label>
                                            <div class="mt-1 text-sm text-gray-900" style="font-family: var(--font-sans)">{{ Auth::user()->email }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700" style="font-family: var(--font-sans)">Member Since</label>
                                            <div class="mt-1 text-sm text-gray-900" style="font-family: var(--font-sans)">{{ Auth::user()->created_at->format('F d, Y') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Account Stats -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4" style="font-family: var(--font-sans)">
                                        Account Statistics
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-blue-900" style="font-family: var(--font-sans)">Total Purchases</div>
                                                    <div class="text-2xl font-bold text-blue-600" style="font-family: var(--font-sans)">{{ $userPurchases->count() }}</div>
                                                </div>
                                                <div class="text-blue-400">
                                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                                        <path d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-green-900" style="font-family: var(--font-sans)">Total Spent</div>
                                                    <div class="text-2xl font-bold text-green-600" style="font-family: var(--font-sans)">€{{ number_format($userPurchases->sum('amount'), 0) }}</div>
                                                </div>
                                                <div class="text-green-400">
                                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-8 flex space-x-4">
                                <a href="/client" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors"
                                   style="font-family: var(--font-sans); background-color: var(--brand-primary);">
                                    View Full Client Area
                                </a>
                                <a href="/" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition-colors"
                                   style="font-family: var(--font-sans);">
                                    Back to Homepage
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.nav-link.active {
    background-color: var(--brand-primary);
    color: white;
}
.nav-link.active:hover {
    background-color: var(--brand-primary);
    color: white;
}
</style>

<script>
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.section').forEach(section => {
        section.style.display = 'none';
    });
    
    // Remove active class from all nav links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    
    // Show selected section
    document.getElementById('section-' + sectionName).style.display = 'block';
    
    // Add active class to selected nav link
    document.getElementById('nav-' + sectionName).classList.add('active');
}
</script>
@endsection
