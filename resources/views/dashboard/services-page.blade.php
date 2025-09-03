@extends('layouts.dashboard')

@section('title', 'Services')
@section('page-title', 'Services')

@section('content')
    <!-- Mini Cart Component -->
    @include('components.mini-cart')

    <!-- Services Section -->
    <div style="background: #0a1428; border-radius: 12px; padding: 20px; color: #ffffff;">
        <div class="services-section">
            <h2 style="color: #ffffff; font-size: 20px; font-weight: 600; margin-bottom: 25px; font-family: var(--font-sans)">
                Available Services
            </h2>

            @foreach($pricingItems->groupBy('category.name') as $categoryName => $items)
                <div style="margin-bottom: 35px;">
                    <h3 style="color: #7fa7e1; font-size: 16px; font-weight: 600; margin: 0 0 20px; text-transform: uppercase; letter-spacing: 0.05em;">
                        {{ $categoryName }}
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        @foreach($items as $item)
                            <div style="background: #001f4c; border: 1px solid #7fa7e1; border-radius: 12px; padding: 20px; transition: all 0.3s ease;"
                                 onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 25px rgba(0, 31, 76, 0.4)'"
                                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">

                                <h4 style="color: #ffffff; font-size: 18px; font-weight: 600; margin: 0 0 12px;">
                                    {{ $item->title }}
                                </h4>

                                @if($item->description)
                                    <p style="color: #8fa8cc; font-size: 14px; line-height: 1.5; margin: 0 0 20px;">
                                        {{ $item->description }}
                                    </p>
                                @endif

                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
                                    <div>
                                        <div style="color: #ffffff; font-size: 28px; font-weight: 700; margin-bottom: 4px;">
                                            €{{ number_format($item->price, 0) }}
                                        </div>
                                        <div style="color: #8fa8cc; font-size: 12px; font-weight: 500;">
                                            per {{ str_replace('per_', '', $item->price_unit) }}
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('dashboard.add-to-cart') }}" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="pricing_item_id" value="{{ $item->id }}">
                                        <button type="submit"
                                                style="background: #3366cc; color: #ffffff; padding: 12px 20px; border: 1px solid #7fa7e1; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;"
                                                onmouseover="this.style.opacity='0.9'; this.style.transform='scale(1.05)'"
                                                onmouseout="this.style.opacity='1'; this.style.transform='scale(1)'">
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if($pricingItems->count() === 0)
                <div style="text-align: center; padding: 40px;">
                    <div style="color: #8fa8cc; margin-bottom: 20px;">
                        <svg style="width: 48px; height: 48px; margin: 0 auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 style="color: #ffffff; font-size: 18px; font-weight: 600; margin-bottom: 8px;">
                        No services available
                    </h3>
                    <p style="color: #8fa8cc; font-size: 14px;">
                        Services will appear here once they are configured by the administrator.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('styles')
<style>
@media (max-width: 768px) {
    .services-section div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
