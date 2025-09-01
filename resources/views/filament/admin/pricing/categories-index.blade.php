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
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold">Pricing Categories</h1>
            <a href="{{ $createUrl }}" class="text-sm text-indigo-600 hover:underline">Create Category</a>
        </div>

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
