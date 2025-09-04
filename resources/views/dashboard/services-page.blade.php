@extends('layouts.dashboard')

@section('title', 'Services')
@section('page-title', 'Services')

@section('content')
    <!-- Services Section with Dark Blue Theme -->
    @php
        // Defensive defaults to avoid fatal errors when variables are missing
        $pricingItems = $pricingItems ?? collect();
    @endphp
    <div class="bg-slate-900 rounded-xl p-6 text-white">
        <div class="services-section">
            <h2 class="text-white text-xl font-semibold mb-6 font-sans">
                Available Services
            </h2>

            @foreach($pricingItems->groupBy('category.name') as $categoryName => $items)
                <div class="mb-8">
                    <h3 class="text-blue-300 text-base font-semibold mb-5 uppercase tracking-wide">
                        {{ $categoryName }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($items as $item)
                            <div class="bg-slate-800 border border-blue-300/30 rounded-xl p-6 transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/10">

                                <h4 class="text-white text-lg font-semibold mb-3">
                                    {{ $item->title }}
                                </h4>

                                @if($item->description)
                                    <p class="text-slate-300 text-sm leading-relaxed mb-5">
                                        {{ $item->description }}
                                    </p>
                                @endif

                                <div class="flex justify-between items-center mt-auto">
                                    <div>
                                        <div class="text-white text-2xl font-bold mb-1">
                                            €{{ number_format($item->price, 0) }}
                                        </div>
                                        <div class="text-slate-300 text-xs font-medium">
                                            per {{ str_replace('per_', '', $item->price_unit) }}
                                        </div>
                                    </div>

                                    <button onclick="addToCart({{ $item->id }}, '{{ $item->title }}', {{ $item->price }}, '{{ $item->category->description ?? '' }}')"
                                            class="bg-brand-primary hover:bg-brand-primary/90 text-white px-5 py-3 rounded-lg font-semibold transition-all duration-200 hover:scale-105">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if($pricingItems->count() === 0)
                <div class="text-center py-10">
                    <div class="text-slate-300 mb-5">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-white text-lg font-semibold mb-2">
                        No services available
                    </h3>
                    <p class="text-slate-300 text-sm">
                        Services will appear here once they are configured by the administrator.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection

