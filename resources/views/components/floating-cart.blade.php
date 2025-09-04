@php
// All cart data will be handled by JavaScript localStorage
$currency = '€';
$cartCount = 0; // Will be updated by JavaScript
@endphp

<!-- Floating Cart Button -->
<div class="fixed bottom-6 right-6 z-50">
    <button 
        id="floating-cart-btn" 
        class="bg-brand-primary hover:bg-brand-primary/90 text-white p-4 rounded-full shadow-lg transition-all duration-300 hover:scale-110 relative overflow-visible"
        onclick="toggleFloatingCart()"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
        </svg>
        <!-- Cart count badge will be added by JavaScript -->
    </button>
</div>

<!-- Floating Cart Overlay -->
<div id="floating-cart-overlay" class="fixed inset-0 bg-black/50 z-40 hidden" onclick="closeFloatingCart()"></div>

<!-- Floating Cart Panel -->
<div id="floating-cart-panel" class="fixed top-0 right-0 h-full w-96 bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full">
        <!-- Cart Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800" style="color: #1f2937 !important;">Shopping Cart</h2>
            <button onclick="closeFloatingCart()" class="text-gray-500 hover:text-gray-700" style="color: #6b7280 !important;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-6">
            <div id="cart-items" class="space-y-4">
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #d1d5db !important;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                    </svg>
                    <p class="text-gray-500 text-lg" style="color: #6b7280 !important;">Your cart is empty</p>
                    <p class="text-gray-400 text-sm mt-2" style="color: #9ca3af !important;">Add some items to get started</p>
                </div>
            </div>
        </div>

        <!-- Cart Footer -->
        <div id="cart-footer" class="border-t border-gray-200 p-6 hidden">
            <!-- Summary -->
            <div class="space-y-2 mb-6">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600" style="color: #4b5563 !important;">Subtotal</span>
                    <span id="cart-subtotal" class="font-medium" style="color: #111827 !important;">€0.00</span>
                </div>
                <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-2">
                    <span style="color: #111827 !important;">Total</span>
                    <span id="cart-total" class="font-medium" style="color: #111827 !important;">€0.00</span>
                </div>
            </div>

            <!-- Checkout Button -->
            <button onclick="proceedToCheckout()" class="w-full bg-brand-primary hover:bg-brand-primary/90 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200">
                Checkout Now
            </button>
            
            <!-- VAT Note -->
            <p class="text-xs text-gray-500 text-center mt-3" style="color: #6b7280 !important;">
                The invoice does not include VAT due to article 21 of Spanish Law 37/1992
            </p>
        </div>
    </div>
</div>

<script>
// Global cart functions - all using localStorage
function toggleFloatingCart() {
    const overlay = document.getElementById('floating-cart-overlay');
    const panel = document.getElementById('floating-cart-panel');
    
    if (panel.classList.contains('translate-x-full')) {
        // Open cart
        overlay.classList.remove('hidden');
        panel.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
        updateCartDisplay(); // Refresh cart when opening
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

function addToCart(itemId, title, price, description) {
    // Find the button that was clicked
    const button = event.target;
    const originalText = button.textContent;
    
    // Disable button and show loading
    button.disabled = true;
    button.textContent = 'Adding...';
    button.classList.add('opacity-50', 'cursor-not-allowed');
    
    try {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const existingItem = cart.find(item => item.id == itemId);
        
        if (existingItem) {
            existingItem.qty += 1;
        } else {
            cart.push({
                id: itemId,
                title: title,
                price: parseFloat(price),
                description: description,
                qty: 1
            });
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartDisplay();
        showNotification('Item added to cart!', 'success');
    } catch (error) {
        console.error('Error:', error);
        showNotification('Failed to add item to cart', 'error');
    } finally {
        // Re-enable button
        button.disabled = false;
        button.textContent = originalText;
        button.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

function updateQty(itemId, change) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const itemIndex = cart.findIndex(item => item.id == itemId);
    
    if (itemIndex !== -1) {
        cart[itemIndex].qty += change;
        if (cart[itemIndex].qty <= 0) {
            cart.splice(itemIndex, 1);
        }
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
}

function removeFromCart(itemId) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
        return;
    }
    
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    cart = cart.filter(item => item.id != itemId);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
}

function updateCartDisplay() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const cartCount = cart.reduce((sum, item) => sum + item.qty, 0);
    
    // Update floating cart button count
    const floatingCartBtn = document.getElementById('floating-cart-btn');
    if (floatingCartBtn) {
        const badge = floatingCartBtn.querySelector('.absolute');
        if (cartCount > 0) {
            if (badge) {
                badge.textContent = cartCount;
            } else {
                const newBadge = document.createElement('span');
                newBadge.className = 'absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold';
                newBadge.style.color = 'white';
                newBadge.textContent = cartCount;
                floatingCartBtn.appendChild(newBadge);
            }
        } else if (badge) {
            badge.remove();
        }
    }
    
    // Update cart items display
    const cartItems = document.getElementById('cart-items');
    const cartFooter = document.getElementById('cart-footer');
    
    if (cart.length > 0) {
        // Render cart items
        cartItems.innerHTML = cart.map(item => `
            <div class="p-4 bg-gray-50 rounded-lg">
                <!-- Title -->
                <h3 class="font-medium text-gray-900 mb-2" style="color: #111827 !important;">${item.title}</h3>
                
                <!-- Description -->
                ${item.description ? `<p class="text-sm text-gray-500 mb-3" style="color: #6b7280 !important;">${item.description}</p>` : ''}
                
                <!-- Controls Row -->
                <div class="flex items-center justify-between">
                    <!-- Price -->
                    <p class="text-sm font-medium text-gray-900" style="color: #111827 !important;">€${item.price} each</p>
                    
                    <!-- Quantity Controls -->
                    <div class="flex items-center space-x-2">
                        <button 
                            onclick="updateQty(${item.id}, -1)" 
                            class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600 hover:text-gray-800"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <span class="w-8 text-center font-medium">${item.qty}</span>
                        <button 
                            onclick="updateQty(${item.id}, 1)" 
                            class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600 hover:text-gray-800"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Total and Remove -->
                    <div class="text-right">
                        <p class="font-semibold text-gray-900" style="color: #111827 !important;">€${(item.price * item.qty).toFixed(2)}</p>
                        <button 
                            onclick="removeFromCart(${item.id})" 
                            class="text-red-500 hover:text-red-700 text-sm mt-1"
                            style="color: #ef4444 !important;"
                        >
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
        
        // Show footer and update totals
        cartFooter.classList.remove('hidden');
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        document.getElementById('cart-subtotal').textContent = `€${subtotal.toFixed(2)}`;
        document.getElementById('cart-total').textContent = `€${subtotal.toFixed(2)}`;
    } else {
        // Show empty cart
        cartItems.innerHTML = `
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #d1d5db !important;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <p class="text-gray-500 text-lg" style="color: #6b7280 !important;">Your cart is empty</p>
                <p class="text-gray-400 text-sm mt-2" style="color: #9ca3af !important;">Add some items to get started</p>
            </div>
        `;
        cartFooter.classList.add('hidden');
    }
}

function proceedToCheckout() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    if (cart.length === 0) {
        showNotification('Your cart is empty!', 'error');
        return;
    }
    
    // Store cart in localStorage for checkout page
    localStorage.setItem('checkout_cart', JSON.stringify(cart));
    
    // Redirect to checkout
    window.location.href = '/checkout';
}

function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
});

// Close cart with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeFloatingCart();
    }
});
</script>
