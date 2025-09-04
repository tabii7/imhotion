<!-- Services Section with Dark Blue Theme -->
@php
    // Defensive defaults to avoid fatal errors when variables are missing
    $pricingItems = $pricingItems ?? collect();
@endphp
<div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-5 text-white">
    <div class="services-section">
        <h2 style="color: #ffffff; font-size: 20px; font-weight: 600; margin-bottom: 25px; font-family: var(--font-sans)">
            Available Services
        </h2>

    @foreach($pricingItems->groupBy('category.name') as $categoryName => $items)
            <div style="margin-bottom: 35px;">
                <h3 style="color: #7fa7e1; font-size: 16px; font-weight: 600; margin: 0 0 20px; text-transform: uppercase; letter-spacing: 0.05em;">
                    {{ $categoryName }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($items as $item)
                        <div class="bg-white/5 backdrop-blur-sm border border-white/20 rounded-xl p-5 transition-all duration-300 hover:bg-white/10"
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

                                <button onclick="addToCart({{ $item->id }})"
                                        class="bg-brand-primary hover:bg-brand-primary/90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:scale-105">
                                    Add to Cart
                                </button>
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

<style>
@media (max-width: 768px) {
    .services-section div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
