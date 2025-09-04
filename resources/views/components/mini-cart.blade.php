@php
use App\Models\PricingItem;

// Handle backward compatibility with existing session logic
$cart = $cart ?? session('cart', []);
// Normalize cart items: allow legacy integer IDs or inconsistent shapes
$cart = array_map(function($it){
    if (!is_array($it)) {
        return ['id' => $it, 'qty' => 1];
    }
    return $it;
}, $cart ?: []);
$selectedPlanId = session('selected_plan_for_payment');

// If we have the old session format, convert it to the new cart format
if ($selectedPlanId && empty($cart)) {
    $cart = [['id' => $selectedPlanId, 'qty' => 1]];
}

$resolved = [];
if (!empty($cart)) {
    $ids = collect($cart)->pluck('id')->filter()->unique()->values()->all();
    $rows = $ids ? PricingItem::with('category')->whereIn('id', $ids)->get()->keyBy('id') : collect();
    foreach ($cart as $it) {
        $id = $it['id'] ?? null;
        $qty = max(1, intval($it['qty'] ?? 1));
        $product = $rows->get($id);
        $resolved[] = [
            'id' => $id,
            'title' => $product ? ($product->title ?? 'Item') : ($it['title'] ?? 'Item'),
            'description' => $product && $product->category ? ($product->category->description ?? null) : ($it['description'] ?? null),
            'price' => $product ? (float) ($product->price ?? 0) : (float) ($it['price'] ?? 0),
            'qty' => $qty,
            'image' => $product ? (isset($product->slug) ? '/images/logos/' . $product->slug . '.svg' : null) : ($it['image'] ?? null),
        ];
    }
}

$currency = '€';
$subtotal = collect($resolved)->reduce(function($carry, $i){ return $carry + ($i['price'] * $i['qty']); }, 0);
$discount = $discount ?? 0;
$tax = $tax ?? 0;
$total = $subtotal - $discount + $tax;
@endphp

@if(!empty($resolved))
<div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
    <h4 class="text-lg font-semibold text-gray-900 mb-4">Your cart</h4>

    <div class="space-y-4">
        <!-- Cart Header -->
        <div class="grid grid-cols-12 gap-4 text-xs font-medium text-gray-500 uppercase tracking-wide border-b border-gray-200 pb-2">
            <div class="col-span-2">QTY</div>
            <div class="col-span-5">TITLE</div>
            <div class="col-span-2">UNIT</div>
            <div class="col-span-2">TOTAL</div>
            <div class="col-span-1"></div>
        </div>

        <!-- Cart Items -->
        @foreach($resolved as $item)
            @php $line = $item['price'] * $item['qty']; @endphp
            <div class="grid grid-cols-12 gap-4 items-center py-3 border-b border-gray-100" data-item-id="{{ $item['id'] }}">
                <!-- Quantity -->
                <div class="col-span-2">
                    <div class="flex items-center border border-gray-300 rounded-lg">
                        <button class="flex-1 px-2 py-1 text-gray-600 hover:bg-gray-100 transition-colors" onclick="updateQty({{ $item['id'] }}, -1)">−</button>
                        <span class="px-3 py-1 text-sm font-medium">{{ $item['qty'] }}</span>
                        <button class="flex-1 px-2 py-1 text-gray-600 hover:bg-gray-100 transition-colors" onclick="updateQty({{ $item['id'] }}, 1)">+</button>
                    </div>
                </div>
                
                <!-- Title -->
                <div class="col-span-5">
                    <div class="font-medium text-gray-900">
                        {{ $item['title'] }}
                        @if(!empty($item['description']))
                            <span class="text-gray-500 text-sm"> - {{ $item['description'] }}</span>
                        @endif
                    </div>
                </div>
                
                <!-- Unit Price -->
                <div class="col-span-2 text-sm text-gray-600">
                    {{ $currency }}{{ number_format($item['price'], 0) }}
                </div>
                
                <!-- Line Total -->
                <div class="col-span-2">
                    @if($item['qty'] > 1)
                        <div class="text-xs text-gray-500 mb-1">
                            {{ $item['qty'] }} x {{ $currency }}{{ number_format($item['price'], 0) }}
                        </div>
                    @endif
                    <div class="font-medium text-gray-900">
                        {{ $currency }}{{ number_format($line, 2, '.', ',') }}
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="col-span-1">
                    <form class="delete-form" method="POST" action="{{ route('remove-from-cart') }}">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                        <button type="button" class="text-red-500 hover:text-red-700 transition-colors" title="Delete item" data-confirm="Are you sure you want to delete this item from the cart?">✕</button>
                    </form>
                </div>
            </div>
        @endforeach

        <!-- Cart Summary -->
        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium" id="subtotal-value">{{ $currency }}{{ number_format($subtotal, 2, '.', ',') }}</span>
            </div>
            
            @if($discount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Discount</span>
                    <span class="font-medium text-green-600">-{{ $currency }}{{ number_format($discount, 2, '.', ',') }}</span>
                </div>
            @endif
            
            <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-2">
                <span>Total</span>
                <span id="total-value">{{ $currency }}{{ number_format($total, 2, '.', ',') }}</span>
            </div>
        </div>

        <!-- VAT Note -->
        <div class="text-xs text-gray-500 text-center">
            The invoice does not include VAT due to article 21 of Spanish Law 37/1992
        </div>

        <!-- Payment Methods -->
        <div class="text-center">
            <div class="text-xs text-gray-500 mb-2">Secure payment via</div>
            <div class="flex justify-center items-center gap-2 flex-wrap">
                <img src="/images/logos/apple-pay.svg" alt="Apple Pay" class="h-6 w-auto">
                <img src="/images/logos/visa.svg" alt="Visa" class="h-6 w-auto">
                <img src="/images/logos/mastercard.svg" alt="Mastercard" class="h-6 w-auto">
                <img src="/images/logos/google-pay.svg" alt="Google Pay" class="h-6 w-auto">
                <img src="/images/logos/bancontact.svg" alt="Bancontact" class="h-6 w-auto">
                <img src="/images/logos/ideal.svg" alt="iDEAL" class="h-6 w-auto">
                <img src="/images/logos/klarna.svg" alt="Klarna" class="h-6 w-auto">
                <img src="/images/logos/paypal.svg" alt="PayPal" class="h-6 w-auto">
            </div>
        </div>

        <!-- Checkout Actions -->
        <div class="pt-4">
            @if(count($resolved) === 1)
                {{-- Single item - use existing payment flow --}}
                <form id="mini-cart-checkout-form-single" method="POST" action="{{ route('payment.create') }}">
                    @csrf
                    <input type="hidden" name="pricing_item_id" value="{{ $resolved[0]['id'] }}">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <button type="submit" class="w-full bg-brand-primary text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-600 transition-colors duration-200">
                        Checkout Now
                    </button>
                </form>
            @else
                {{-- Multiple items - submit cart totals to payment.create --}}
                <form id="mini-cart-checkout-form-multi" method="POST" action="{{ route('payment.create') }}">
                    @csrf
                    <input type="hidden" name="cart_data" value="{{ json_encode($resolved) }}">
                    <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                    <input type="hidden" name="discount" value="{{ $discount }}">
                    <input type="hidden" name="tax" value="{{ $tax }}">
                    <input type="hidden" name="total" value="{{ $total }}">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <button type="submit" class="w-full bg-brand-primary text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-600 transition-colors duration-200">
                        Checkout Now
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
function updateQty(itemId, change) {
    fetch('{{ route("update-cart-qty") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            item_id: itemId,
            qty_change: change
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating quantity: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating quantity');
    });
}

// Handle delete button clicks
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('delete-btn') || e.target.closest('.delete-btn')) {
        const button = e.target.classList.contains('delete-btn') ? e.target : e.target.closest('.delete-btn');
        const confirmMessage = button.getAttribute('data-confirm') || 'Are you sure you want to delete this item?';
        
        if (confirm(confirmMessage)) {
            const form = button.closest('.delete-form');
            if (form) {
                form.submit();
            }
        }
    }
});
</script>
@endif