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
$cartCount = count($resolved);
@endphp

<!-- Floating Cart Button -->
<div class="fixed bottom-6 right-6 z-50">
    <button 
        id="floating-cart-btn" 
        class="bg-brand-primary hover:bg-brand-primary/90 text-white p-4 rounded-full shadow-lg transition-all duration-300 hover:scale-110 relative"
        onclick="toggleFloatingCart()"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
        </svg>
        @if($cartCount > 0)
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold">
                {{ $cartCount }}
            </span>
        @endif
    </button>
</div>

<!-- Floating Cart Overlay -->
<div id="floating-cart-overlay" class="fixed inset-0 bg-black/50 z-40 hidden" onclick="closeFloatingCart()"></div>

<!-- Floating Cart Panel -->
<div id="floating-cart-panel" class="fixed top-0 right-0 h-full w-96 bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full">
        <!-- Cart Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Shopping Cart</h2>
            <button onclick="closeFloatingCart()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-6">
            @if(!empty($resolved))
                <div class="space-y-4">
                    @foreach($resolved as $item)
                        @php $line = $item['price'] * $item['qty']; @endphp
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $item['title'] }}</h3>
                                @if(!empty($item['description']))
                                    <p class="text-sm text-gray-500">{{ $item['description'] }}</p>
                                @endif
                                <p class="text-sm font-medium text-gray-900">{{ $currency }}{{ number_format($item['price'], 0) }} each</p>
                            </div>
                            
                            <!-- Quantity Controls -->
                            <div class="flex items-center space-x-2">
                                <button 
                                    onclick="updateQty({{ $item['id'] }}, -1)" 
                                    class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600 hover:text-gray-800"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <span class="w-8 text-center font-medium">{{ $item['qty'] }}</span>
                                <button 
                                    onclick="updateQty({{ $item['id'] }}, 1)" 
                                    class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600 hover:text-gray-800"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Line Total -->
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">{{ $currency }}{{ number_format($line, 2) }}</p>
                                <button 
                                    onclick="removeFromCart({{ $item['id'] }})" 
                                    class="text-red-500 hover:text-red-700 text-sm mt-1"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Your cart is empty</p>
                    <p class="text-gray-400 text-sm mt-2">Add some items to get started</p>
                </div>
            @endif
        </div>

        @if(!empty($resolved))
            <!-- Cart Footer -->
            <div class="border-t border-gray-200 p-6">
                <!-- Summary -->
                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">{{ $currency }}{{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Discount</span>
                            <span class="font-medium text-green-600">-{{ $currency }}{{ number_format($discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-2">
                        <span>Total</span>
                        <span>{{ $currency }}{{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <!-- Checkout Button -->
                <button 
                    onclick="proceedToCheckout()" 
                    class="w-full bg-brand-primary hover:bg-brand-primary/90 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200"
                >
                    Checkout Now
                </button>
                
                <!-- VAT Note -->
                <p class="text-xs text-gray-500 text-center mt-3">
                    The invoice does not include VAT due to article 21 of Spanish Law 37/1992
                </p>
            </div>
        @endif
    </div>
</div>

<script>
function toggleFloatingCart() {
    const overlay = document.getElementById('floating-cart-overlay');
    const panel = document.getElementById('floating-cart-panel');
    
    if (panel.classList.contains('translate-x-full')) {
        // Open cart
        overlay.classList.remove('hidden');
        panel.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
    } else {
        // Close cart
        closeFloatingCart();
    }
}

function closeFloatingCart() {
    const overlay = document.getElementById('floating-cart-overlay');
    const panel = document.getElementById('floating-cart-panel');
    
    overlay.classList.add('hidden');
    panel.classList.add('translate-x-full');
    document.body.style.overflow = 'auto';
}

function updateQty(itemId, change) {
    fetch('/update-cart-qty', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            item_id: itemId,
            change: change
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload to update cart display
        }
    })
    .catch(error => console.error('Error:', error));
}

function removeFromCart(itemId) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
        return;
    }
    
    fetch('/remove-from-cart', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            item_id: itemId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload to update cart display
        }
    })
    .catch(error => console.error('Error:', error));
}

function proceedToCheckout() {
    // Create and submit checkout form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("payment.create") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const userId = document.createElement('input');
    userId.type = 'hidden';
    userId.name = 'user_id';
    userId.value = '{{ auth()->id() }}';
    
    form.appendChild(csrfToken);
    form.appendChild(userId);
    document.body.appendChild(form);
    form.submit();
}

// Close cart with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeFloatingCart();
    }
});
</script>
