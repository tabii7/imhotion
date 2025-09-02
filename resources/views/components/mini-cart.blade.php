@php
use App\Models\PricingItem;

// Handle backward compatibility with existing session logic
$cart = $cart ?? session('cart', []);
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

<div class="mini-cart-component">
    <h4 class="mini-cart-title">Your cart</h4>

    @if(empty($resolved))
        {{-- Cart is hidden when empty --}}
    @else
        <div class="cart-table">
            <div class="cart-header">
                <div class="col qty">QTY</div>
                <div class="col title">TITLE</div>
                <div class="col unit">UNIT</div>
                <div class="col line">TOTAL</div>
            </div>

            @foreach($resolved as $item)
                @php $line = $item['price'] * $item['qty']; @endphp
                <div class="cart-row">
                    <div class="col qty">
                        <div class="qty-controls">
                            <button class="qty-btn" onclick="updateQty({{ $item['id'] }}, -1)">−</button>
                            <span class="qty-value">{{ $item['qty'] }}</span>
                            <button class="qty-btn" onclick="updateQty({{ $item['id'] }}, 1)">+</button>
                        </div>
                    </div>
                    <div class="col title">
                        <div class="title-main">
                            <strong>{{ $item['title'] }}</strong>
                            @if(!empty($item['description']))
                                <span class="title-desc"> - {{ $item['description'] }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col unit">{{ $currency }}{{ number_format($item['price'], 0) }}</div>
                    <div class="col line">
                        @if($item['qty'] > 1)
                            <div class="line-breakdown">({{ $item['qty'] }}x{{ $currency }}{{ number_format($item['price'], 0) }})</div>
                        @endif
                        <div class="line-total">{{ $currency }}{{ number_format($line, 2, '.', ',') }}</div>
                    </div>
                </div>
            @endforeach

            <div class="cart-summary">
                <div class="summary-row">
                    <div class="label">Subtotal</div>
                    <div class="value">{{ $currency }}{{ number_format($subtotal, 2, '.', ',') }}</div>
                </div>
                @if($discount > 0)
                <div class="summary-row">
                    <div class="label">Discount</div>
                    <div class="value">-{{ $currency }}{{ number_format($discount, 2, '.', ',') }}</div>
                </div>
                @endif
                <div class="summary-row total">
                    <div class="label">Total</div>
                    <div class="value">{{ $currency }}{{ number_format($total, 2, '.', ',') }}</div>
                </div>

                <div class="vat-note">The invoice does not include VAT due to article 21 of Law 37/1992</div>

            <div class="payment-methods">
                <span>Secure payment via</span>
                <div class="payment-icons">
                    <img src="/images/logos/ideal.svg" alt="iDEAL">
                    <img src="/images/logos/mastercard.svg" alt="Mastercard">
                    <img src="/images/logos/visa.svg" alt="Visa">
                    <img src="/images/logos/paypal.svg" alt="PayPal">
                    <img src="/images/logos/apple-pay.svg" alt="Apple Pay">
                </div>
            </div>
            </div>

            <div class="mini-cart-actions">
                @if(count($resolved) === 1)
                    {{-- Single item - use existing payment flow --}}
                    <form method="POST" action="{{ route('payment.create') }}">
                        @csrf
                        <input type="hidden" name="pricing_item_id" value="{{ $resolved[0]['id'] }}">
                        <button type="submit" class="btn btn-primary">Checkout Now</button>
                    </form>
                @else
                    {{-- Multiple items - TODO: implement multi-item checkout --}}
                    <form method="POST" action="{{ route('payment.create') }}">
                        @csrf
                        <input type="hidden" name="pricing_item_id" value="{{ $resolved[0]['id'] ?? '' }}">
                        <button type="submit" class="btn btn-primary">Checkout Now</button>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
.mini-cart-component{
    font-family:var(--font-sans, system-ui);
    background:#ffffff;
    color:#1f2937;
    padding:12px;
    border-radius:8px;
    margin-bottom:12px;
    border: 1px solid #d1d5db;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.mini-cart-title{
    font-weight:700;
    margin-bottom:8px;
    font-size:16px;
    display:flex;
    align-items:center;
    gap:6px;
    color:#1f2937;
}
.mini-cart-title::before{
    content:'ðŸ›’';
    font-size:18px;
}
.cart-header,.cart-row{
    display:grid;
    grid-template-columns:100px 1fr 80px 100px;
    gap:8px;
    align-items:center;
    padding:4px 8px;
}
.cart-header{
    color:#6b7280;
    font-weight:700;
    font-size:11px;
    text-transform:uppercase;
    border-bottom:1px solid #e5e7eb;
    margin-bottom:6px;
    padding-bottom:4px;
}
.cart-row{
    background:#f9fafb;
    border:1px solid #e5e7eb;
    border-radius:6px;
    margin-bottom:4px;
    padding:8px;
    transition:all 0.2s ease;
}
.cart-row:hover{
    background:#f3f4f6;
    border-color:#d1d5db;
}
.qty-controls{
    display:flex;
    align-items:center;
    gap:4px;
    justify-content:center;
}
.qty-btn{
    width:20px;
    height:20px;
    border:1px solid #d1d5db;
    background:#ffffff;
    border-radius:4px;
    cursor:pointer;
    font-size:12px;
    font-weight:700;
    color:#374151;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:all 0.2s;
}
.qty-btn:hover{
    background:#f3f4f6;
    border-color:#9ca3af;
}
.qty-value{
    font-weight:700;
    color:#1f2937;
    font-size:14px;
    min-width:20px;
    text-align:center;
}
.title-main{
    font-weight:400;
    color:#1f2937;
    font-size:14px;
    line-height:1.2;
}
.title-main strong{
    font-weight:700;
}
.title-desc{
    color:#6b7280;
    font-weight:400;
}
.col.unit{
    text-align:right;
    color:#374151;
    font-weight:600;
    font-size:13px;
}
.col.line{
    text-align:right;
}
.line-breakdown{
    font-size:10px;
    color:#6b7280;
    margin-bottom:1px;
}
.line-total{
    font-weight:700;
    color:#1f2937;
    font-size:14px;
}
.cart-summary{
    margin-top:8px;
    padding-top:8px;
    border-top:1px solid #e5e7eb;
}
.summary-row{
    display:flex;
    justify-content:space-between;
    padding:3px 4px;
    color:#6b7280;
    font-size:13px;
}
.summary-row.total{
    font-size:16px;
    font-weight:800;
    color:#1f2937;
    border-top:1px solid #d1d5db;
    margin-top:6px;
    padding-top:8px;
}
.vat-note{
    margin-top:8px;
    font-size:10px;
    color:#6b7280;
    text-align:center;
    padding:6px;
    background:#f3f4f6;
    border-radius:4px;
}
.payment-methods{
    margin-top:8px;
    text-align:center;
    color:#6b7280;
    font-size:11px;
}
.payment-icons{
    display:flex;
    justify-content:center;
    gap:6px;
    margin-top:4px;
}
.payment-icons img{
    height:18px;
    opacity:0.7;
    transition:opacity 0.2s;
}
.payment-icons img:hover{
    opacity:1;
}
.mini-cart-actions{
    display:flex;
    justify-content:center;
    margin-top:10px;
}
.btn{
    padding:8px 16px;
    border-radius:6px;
    border:none;
    background:#10a37f;
    color:white;
    font-weight:600;
    cursor:pointer;
    transition:all 0.2s ease;
    font-size:13px;
}
.btn:hover{
    background:#0d8b69;
    transform:translateY(-1px);
}
</style>

<script>
function updateQty(itemId, change) {
    fetch('/dashboard/update-cart-qty', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
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
</script>

@endif
<div class="mini-cart-container" style="margin: 20px 0;">
    <div class="mini-cart-box">
        @if(!empty($resolved) && count($resolved) > 0)
            <!-- Cart with resolved items -->
            <div class="cart-header">
                <span class="cart-title">Items in your cart</span>
                <button class="cart-buy-btn" onclick="showSection('services')">Order more days</button>
            </div>

            <div class="cart-items">
                @php $total = 0; @endphp
                @foreach($resolved as $item)
                    @php
                        $qty = $item['qty'] ?? ($item['quantity'] ?? 1);
                        $price = $item['price'] ?? 0;
                        $line = $price * $qty;
                        $total += $line;
                    @endphp
                    <div class="cart-item">
                        <span class="item-name">{{ $item['title'] ?? 'Item' }}</span>
                        <span class="item-price">€{{ number_format($line, 2) }}</span>
                    </div>
                @endforeach

                <div class="cart-total">
                    <strong>Total: €{{ number_format($total, 2) }}</strong>
                </div>
            </div>
        @else
            <!-- Empty cart -->
            <div class="cart-header">
                <span class="cart-title">No items in your cart</span>
                <a href="#services" class="cart-buy-btn" onclick="showSection('services')">Order more days</a>
            </div>
        @endif
    </div>
</div>

<style>
.mini-cart-container {
    width: 100%;
    margin: 5px 0;
}

.mini-cart-box {
    background: transparent;
    border: none;
    border-radius: 15px;
    padding: 6px 12px;
    min-height: 35px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.cart-header {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
}

.cart-title {
    color: #ffffff;
    font-size: 16px;
    font-weight: 600;
}

.cart-buy-btn {
    background: #3366cc;
    color: #ffffff;
    padding: 4px 12px;
    border: 1px solid #7fa7e1;
    border-radius: 18px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: opacity 0.2s ease;
}

.cart-buy-btn:hover {
    opacity: 0.9;
}

.cart-items {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 0;
    border-bottom: 1px solid #7fa7e1;
}

.cart-item:last-child {
    border-bottom: none;
}

.item-name {
    color: #ffffff;
    font-size: 14px;
    flex: 1;
}

.item-price {
    color: #ffffff;
    font-size: 14px;
    font-weight: 600;
}

.cart-total {
    padding-top: 8px;
    text-align: right;
    color: #ffffff;
    font-size: 16px;
    border-top: 1px solid #7fa7e1;
}

@media (max-width: 768px) {
    .cart-header {
        flex-direction: column;
        gap: 8px;
        align-items: stretch;
    }

    .cart-title {
        text-align: center;
    }

    .cart-buy-btn {
        align-self: center;
    }
}
</style>

<script>
function proceedToCheckout() {
    window.location.href = '/payment/create';
}
</script>
