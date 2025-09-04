@php
// All cart data will be handled by JavaScript localStorage
$currency = '€';
$cartCount = 0; // Will be updated by JavaScript
@endphp

<!-- Floating Cart Button -->
<div class="fixed bottom-6 right-6 z-50">
    <button 
        id="floating-cart-btn" 
        class="bg-brand-primary hover:bg-brand-primary/90 text-white p-4 rounded-full shadow-lg transition-all duration-300 hover:scale-110 relative z-50"
        onclick="toggleFloatingCart()"
    >
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" xml:space="preserve"><path fill="#18CEF6" d="M26.029 58.156c-1.683 0-3.047 1.334-3.047 2.979 0 1.646 1.364 2.979 3.047 2.979s3.047-1.333 3.047-2.979c0-1.645-1.364-2.979-3.047-2.979zm17.795 0c-1.682 0-3.046 1.334-3.046 2.979 0 1.646 1.364 2.979 3.046 2.979 1.683 0 3.047-1.333 3.047-2.979 0-1.645-1.364-2.979-3.047-2.979zM22.515 26.997l5.416 14.5h21.793l6.189-14.5H22.515z"/><path fill="#233251" d="m58.753 13-9.67 28.181H23.85l-6.527-17.968h29.111v-2.27H14.036l7.722 21.258-6.281 10.643h35.794v-2.271H19.494l4.207-7.125h27.051l9.67-28.18H71V13H58.753zm-33.4 41.861c-3.134.002-5.674 2.484-5.676 5.548.002 3.065 2.542 5.548 5.676 5.549 3.133-.002 5.672-2.485 5.672-5.549 0-3.064-2.539-5.546-5.672-5.548zm0 8.827c-1.853-.003-3.35-1.468-3.353-3.279.003-1.81 1.5-3.274 3.353-3.277 1.849.003 3.349 1.467 3.352 3.277-.003 1.812-1.503 3.276-3.352 3.279zm17.794-8.827c-3.134.002-5.673 2.484-5.674 5.548.001 3.065 2.54 5.548 5.674 5.549 3.134-.002 5.672-2.485 5.674-5.549-.002-3.064-2.54-5.546-5.674-5.548zm0 8.827c-1.851-.003-3.349-1.468-3.352-3.279.003-1.81 1.501-3.274 3.352-3.277 1.851.003 3.35 1.467 3.353 3.277-.003 1.812-1.502 3.276-3.353 3.279z"/></svg>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
        </svg>
        <!-- Cart count badge will be added by JavaScript -->
    </button>
</div>

<!-- Floating Cart Overlay -->
<div id="floating-cart-overlay" class="fixed inset-0 bg-black/50 z-40 hidden" onclick="closeFloatingCart()"></div>

<!-- Floating Cart Panel -->
<div id="floating-cart-panel" class="fixed top-0 right-0 h-full w-96 bg-sidebar-bg shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full">
        <!-- Cart Header -->
        <div class="flex items-center justify-between p-6 border-b border-slate-700/50">
            <h2 class="text-xl font-semibold text-white">Shopping Cart</h2>
            <button onclick="closeFloatingCart()" class="text-sidebar-text hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-6">
            <div id="cart-items" class="space-y-4">
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-sidebar-text mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                    </svg>
                    <p class="text-sidebar-text text-lg">Your cart is empty</p>
                    <p class="text-sidebar-text/70 text-sm mt-2">Add some items to get started</p>
                </div>
            </div>
        </div>

        <!-- Cart Footer -->
        <div id="cart-footer" class="border-t border-slate-700/50 p-6 hidden">
            <!-- Summary -->
            <div class="space-y-2 mb-6">
                <div class="flex justify-between text-sm">
                    <span class="text-sidebar-text">Subtotal</span>
                    <span id="cart-subtotal" class="font-medium text-white">€0.00</span>
                </div>
                <div class="flex justify-between text-lg font-semibold border-t border-slate-700/50 pt-2">
                    <span class="text-white">Total</span>
                    <span id="cart-total" class="font-medium text-white">€0.00</span>
                </div>
            </div>

            <!-- Checkout Button -->
            <button onclick="proceedToCheckout()" class="w-full bg-brand-primary hover:bg-brand-primary/90 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200">
                @auth
                    Checkout Now
                @else
                    Login to Checkout
                @endauth
            </button>
            
            <!-- VAT Note -->
            <p class="text-xs text-sidebar-text/70 text-center mt-3 text-white">
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
    const button = document.getElementById('floating-cart-btn');
    
    if (panel.classList.contains('translate-x-full')) {
        // Open cart
        overlay.classList.remove('hidden');
        panel.classList.remove('translate-x-full');
        button.style.display = 'none'; // Hide button when cart is open
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
    const button = document.getElementById('floating-cart-btn');
    
    overlay.classList.add('hidden');
    panel.classList.add('translate-x-full');
    button.style.display = 'block'; // Show button when cart is closed
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
    
    // Debug: log cart info
    console.log('Cart items:', cart);
    console.log('Cart count:', cartCount);
    
    // Update floating cart button count
    const floatingCartBtn = document.getElementById('floating-cart-btn');
    if (floatingCartBtn) {
        const badge = floatingCartBtn.querySelector('.absolute');
        if (cartCount > 0) {
            if (badge) {
                badge.textContent = cartCount;
            } else {
                const newBadge = document.createElement('span');
                newBadge.className = 'absolute bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold z-10';
                newBadge.style.top = '-0.40rem';
                newBadge.style.right = '-0.40rem';
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
            <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700/50">
                <!-- Title -->
                <h3 class="font-medium text-white mb-2">${item.title}</h3>
                
                <!-- Description -->
                ${item.description ? `<p class="text-sm text-sidebar-text mb-3">${item.description}</p>` : ''}
                
                <!-- Controls Row -->
                <div class="flex items-center justify-between">
                    <!-- Price -->
                    <p class="text-sm font-medium text-white">€${item.price} each</p>
                    
                    <!-- Quantity Controls -->
                    <div class="flex items-center space-x-2">
                        <button 
                            onclick="updateQty(${item.id}, -1)" 
                            class="w-8 h-8 rounded-full bg-slate-700 hover:bg-slate-600 flex items-center justify-center text-sidebar-text hover:text-white transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <span class="w-8 text-center font-medium text-white">${item.qty}</span>
                        <button 
                            onclick="updateQty(${item.id}, 1)" 
                            class="w-8 h-8 rounded-full bg-slate-700 hover:bg-slate-600 flex items-center justify-center text-sidebar-text hover:text-white transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Total and Remove -->
                    <div class="text-right">
                        <p class="font-semibold text-white">€${(item.price * item.qty).toFixed(2)}</p>
                        <button 
                            onclick="removeFromCart(${item.id})" 
                            class="text-red-400 hover:text-red-300 text-sm mt-1 transition-colors"
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
                <svg class="w-16 h-16 text-sidebar-text mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <p class="text-sidebar-text text-lg">Your cart is empty</p>
                <p class="text-sidebar-text/70 text-sm mt-2">Add some items to get started</p>
            </div>
        `;
        cartFooter.classList.add('hidden');
    }
}

function proceedToCheckout() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    // Validate cart
    if (cart.length === 0) {
        showNotification('Your cart is empty!', 'error');
        return;
    }
    
    // Check if user is authenticated
    @guest
    // User is not authenticated, redirect to login
    localStorage.setItem('redirect_to_checkout', 'true');
    showNotification('Please login to continue with checkout', 'info');
    setTimeout(() => {
        window.location.href = '/login';
    }, 1500);
    return;
    @endguest
    
    // Validate each item
    for (let item of cart) {
        if (!item.id || !item.title || !item.price || !item.qty) {
            showNotification('Invalid cart item detected. Please refresh and try again.', 'error');
            return;
        }
        if (item.qty <= 0) {
            showNotification('Invalid quantity detected. Please refresh and try again.', 'error');
            return;
        }
        if (item.price <= 0) {
            showNotification('Invalid price detected. Please refresh and try again.', 'error');
            return;
        }
    }
    
    // Calculate total
    const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    if (total <= 0) {
        showNotification('Invalid total amount. Please refresh and try again.', 'error');
        return;
    }
    
    // Store cart in localStorage for checkout page
    localStorage.setItem('checkout_cart', JSON.stringify(cart));
    
    // Show loading notification
    showNotification('Redirecting to checkout...', 'success');
    
    // Create and submit checkout form
    setTimeout(() => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/payment/create';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const cartData = document.createElement('input');
        cartData.type = 'hidden';
        cartData.name = 'cart_data';
        cartData.value = JSON.stringify(cart);
        
        const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const totalInput = document.createElement('input');
        totalInput.type = 'hidden';
        totalInput.name = 'total';
        totalInput.value = total;
        
        form.appendChild(csrfToken);
        form.appendChild(cartData);
        form.appendChild(totalInput);
        document.body.appendChild(form);
        form.submit();
    }, 1000);
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
    // Force update cart display to ensure badge shows
    setTimeout(() => {
        updateCartDisplay();
    }, 100);
    
    // Add test item to cart for debugging (remove this later)
    localStorage.setItem('cart', JSON.stringify([{id: 1, title: 'Test Item', price: 100, qty: 2}]));
    updateCartDisplay();
});

// Close cart with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeFloatingCart();
    }
});
</script>
