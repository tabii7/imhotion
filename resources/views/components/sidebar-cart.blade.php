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

<!-- Sidebar Cart Section -->
<div class="mb-8">
    <div class="text-xs text-blue-300 font-bold uppercase tracking-wider mb-3 px-3">Cart</div>
    
    @auth
        <!-- Authenticated User Cart -->
        <div class="px-3">
            @if(!empty($resolved))
                <!-- Cart Items -->
                <div class="space-y-3 mb-4">
                    @foreach($resolved as $item)
                        @php $line = $item['price'] * $item['qty']; @endphp
                        <div class="bg-white/5 backdrop-blur-sm border border-white/20 rounded-lg p-3">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-white text-sm font-medium">{{ $item['title'] }}</h4>
                                <button onclick="removeFromCart({{ $item['id'] }})" class="text-red-400 hover:text-red-300 text-xs">
                                    ✕
                                </button>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <button onclick="updateCartQty({{ $item['id'] }}, -1)" class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white text-xs">
                                        −
                                    </button>
                                    <span class="text-white text-sm font-medium w-6 text-center">{{ $item['qty'] }}</span>
                                    <button onclick="updateCartQty({{ $item['id'] }}, 1)" class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white text-xs">
                                        +
                                    </button>
                                </div>
                                <span class="text-white text-sm font-semibold">{{ $currency }}{{ number_format($line, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="bg-white/5 backdrop-blur-sm border border-white/20 rounded-lg p-3 mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-300">Subtotal</span>
                        <span class="text-white font-medium">{{ $currency }}{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-3">
                        <span class="text-gray-300">Total</span>
                        <span class="text-white font-bold">{{ $currency }}{{ number_format($total, 2) }}</span>
                    </div>
                    <button onclick="proceedToCheckout()" class="w-full bg-brand-primary hover:bg-brand-primary/90 text-white py-2 px-3 rounded-lg text-sm font-medium transition-colors duration-200">
                        Checkout Now
                    </button>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="bg-white/5 backdrop-blur-sm border border-white/20 rounded-lg p-4 text-center">
                    <div class="text-gray-400 text-sm mb-2">Your cart is empty</div>
                    <div class="text-gray-500 text-xs">Add items from services</div>
                </div>
            @endif
        </div>
    @else
        <!-- Guest User - Login/Register UI -->
        <div class="px-3">
            <div class="bg-white/5 backdrop-blur-sm border border-white/20 rounded-lg p-4 text-center">
                <div class="text-white text-sm mb-3">Sign in to manage your cart</div>
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="block w-full bg-brand-primary hover:bg-brand-primary/90 text-white py-2 px-3 rounded-lg text-sm font-medium transition-colors duration-200 text-center">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="block w-full bg-transparent border border-white/20 hover:bg-white/10 text-white py-2 px-3 rounded-lg text-sm font-medium transition-colors duration-200 text-center">
                        Register
                    </a>
                </div>
            </div>
        </div>
    @endauth
</div>

<script>
function updateCartQty(itemId, change) {
    fetch('/api/cart/update-qty', {
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
            // Update cart display without page reload
            updateCartDisplay(data);
        }
    })
    .catch(error => console.error('Error:', error));
}

function removeFromCart(itemId) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
        return;
    }
    
    fetch('/api/cart/remove', {
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
            // Update cart display without page reload
            updateCartDisplay(data);
        }
    })
    .catch(error => console.error('Error:', error));
}

function addToCart(itemId) {
    fetch('/api/cart/add', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            pricing_item_id: itemId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart display without page reload
            updateCartDisplay(data);
            // Show success message
            showNotification('Item added to cart!', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding item to cart', 'error');
    });
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

function updateCartDisplay(data) {
    // Update floating cart button count
    const floatingCartBtn = document.getElementById('floating-cart-btn');
    if (floatingCartBtn) {
        const badge = floatingCartBtn.querySelector('.absolute');
        if (data.cart_count > 0) {
            if (badge) {
                badge.textContent = data.cart_count;
            } else {
                const newBadge = document.createElement('span');
                newBadge.className = 'absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold';
                newBadge.textContent = data.cart_count;
                floatingCartBtn.appendChild(newBadge);
            }
        } else if (badge) {
            badge.remove();
        }
    }
    
    // Update sidebar cart dynamically
    updateSidebarCart(data);
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg text-white font-medium transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
