<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PricingItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    /**
     * Add item to cart
     */
    public function addToCart(Request $request): JsonResponse
    {
        $request->validate([
            'pricing_item_id' => 'required|exists:pricing_items,id'
        ]);

        $pricingItemId = $request->pricing_item_id;

        // Get current cart from session
        $cart = session('cart', []);

        // Check if item already exists in cart
        $existingKey = null;
        foreach ($cart as $key => $item) {
            if ($item['id'] == $pricingItemId) {
                $existingKey = $key;
                break;
            }
        }

        if ($existingKey !== null) {
            // Increment quantity if item exists
            $cart[$existingKey]['qty'] += 1;
        } else {
            // Add new item to cart
            $cart[] = [
                'id' => $pricingItemId,
                'qty' => 1
            ];
        }

        // Store updated cart in session
        session(['cart' => $cart]);

        // Also set the old session format for backward compatibility
        session(['selected_plan_for_payment' => $pricingItemId]);

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully!',
            'cart_count' => count($cart),
            'cart' => $cart
        ]);
    }

    /**
     * Update item quantity in cart
     */
    public function updateCartQty(Request $request): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|integer',
            'change' => 'required|integer'
        ]);

        $itemId = $request->item_id;
        $change = $request->change;

        $cart = session('cart', []);

        // Find the item in cart and update quantity
        foreach ($cart as $key => &$item) {
            if ($item['id'] == $itemId) {
                $item['qty'] += $change;

                // Remove item if quantity becomes 0 or less
                if ($item['qty'] <= 0) {
                    unset($cart[$key]);
                    // Re-index array to avoid gaps
                    $cart = array_values($cart);
                }
                break;
            }
        }

        session(['cart' => $cart]);

        // Recompute totals and per-line amounts
        $ids = collect($cart)->pluck('id')->filter()->values()->all();
        $items = $ids ? PricingItem::whereIn('id', $ids)->get()->keyBy('id') : collect();

        $lines = [];
        $subtotal = 0;
        foreach ($cart as $c) {
            $id = $c['id'];
            $qty = $c['qty'] ?? ($c['quantity'] ?? 1);
            $price = isset($items[$id]) ? (float) $items[$id]->price : (float) ($c['price'] ?? 0);
            $line = $price * $qty;
            $subtotal += $line;
            $lines[$id] = [
                'qty' => $qty,
                'price' => $price,
                'line' => $line,
            ];
        }

        $discount = 0;
        $tax = 0;
        $total = $subtotal - $discount + $tax;

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'lines' => $lines,
            'subtotal' => $subtotal,
            'total' => $total,
            'cart_count' => count($cart),
            'empty' => empty($cart)
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|integer'
        ]);

        $itemId = $request->item_id;
        $cart = session('cart', []);

        foreach ($cart as $key => $it) {
            if (($it['id'] ?? null) == $itemId) {
                unset($cart[$key]);
                break;
            }
        }

        $cart = array_values($cart);
        session(['cart' => $cart]);

        // If cart is now empty, also clear legacy selected plan session
        if (empty($cart)) {
            session()->forget('selected_plan_for_payment');
        }

        // Recompute totals
        $ids = collect($cart)->pluck('id')->filter()->values()->all();
        $items = $ids ? PricingItem::whereIn('id', $ids)->get()->keyBy('id') : collect();

        $lines = [];
        $subtotal = 0;
        foreach ($cart as $c) {
            $id = $c['id'];
            $qty = $c['qty'] ?? ($c['quantity'] ?? 1);
            $price = isset($items[$id]) ? (float) $items[$id]->price : (float) ($c['price'] ?? 0);
            $line = $price * $qty;
            $subtotal += $line;
            $lines[$id] = [
                'qty' => $qty,
                'price' => $price,
                'line' => $line,
            ];
        }

        $discount = 0;
        $tax = 0;
        $total = $subtotal - $discount + $tax;

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'lines' => $lines,
            'subtotal' => $subtotal,
            'total' => $total,
            'cart_count' => count($cart),
            'empty' => empty($cart)
        ]);
    }

    /**
     * Get cart contents
     */
    public function getCart(): JsonResponse
    {
        $cart = session('cart', []);
        
        // Normalize cart items
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
        $discount = 0;
        $tax = 0;
        $total = $subtotal - $discount + $tax;

        return response()->json([
            'success' => true,
            'cart' => $resolved,
            'subtotal' => $subtotal,
            'total' => $total,
            'cart_count' => count($resolved)
        ]);
    }
}
