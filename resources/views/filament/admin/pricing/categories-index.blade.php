@php
/**
 * Admin Pricing categories index
 * Variables provided by the Page:
 * - $categories (collection of PricingCategory with items)
 * - $createUrl (url to create a category)
 */
@endphp

@extends('layouts.admin-shell')

@section('content')
    @include('filament.admin.brand-styles')
    <div class="filament-page relative">
    <div>
        {{-- fixed top-right button so it always appears above Filament's header/search --}}
        <a href="{{ $createUrl }}" class="fixed top-20 right-5 z-[9999] inline-flex items-center gap-2 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-lg" style="backdrop-filter: blur(6px);">
            <x-heroicon-o-plus class="w-4 h-4" />
            Add Category
        </a>

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold">Pricing Categories</h1>

        @foreach($categories as $category)
            <section class="mb-6">
                <h2 class="text-lg font-medium">{{ $category->name }}</h2>
                @if($category->description)
                    <p class="text-sm text-gray-600 mb-2">{{ $category->description }}</p>
                @endif

                <ul class="list-none pl-0">
                    @foreach($category->items as $item)
                        <li class="py-2 border-b last:border-b-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-semibold">{{ $item->title }}</div>
                                    <div class="text-sm text-gray-700">€{{ number_format($item->price, 0) }} / {{ str_replace('per_','',$item->price_unit) }}</div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    @if($item->discount_percent)
                                        <span class="text-green-600">-{{ $item->discount_percent }}%</span>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endforeach
    </div>
@endsection

@section('after-scripts')
    {{-- floating quick-add button for convenience --}}
    <a href="{{ $createUrl }}" class="fixed bottom-5 right-5 bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-full shadow-lg z-50 hidden md:inline-flex items-center justify-center" title="Add category">
        <x-heroicon-o-plus class="w-5 h-5" />
    </a>
@endsection
