<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\PricingItem;
use App\Models\Purchase;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with cart and menu options.
     */
    public function index(Request $request, $section = null): View
    {
        $user = Auth::user();
        $pricingItems = PricingItem::with('category')->get();
        $userPurchases = Purchase::where('user_id', $user->id)->with('pricingItem')->get();

        // Add client area data
        $projects = Project::where('user_id', $user->id)
            ->latest()
            ->get();

                // Get current user's projects with proper categorization
        $activeStatuses = ['new', 'pending', 'in_progress', 'completed'];
        $finalizedStatuses = ['cancelled', 'finalized'];

        $active = Project::where('user_id', Auth::id())
            ->whereIn('status', $activeStatuses)
            ->orderBy('created_at', 'desc')
            ->get();

        $finalized = Project::where('user_id', Auth::id())
            ->whereIn('status', $finalizedStatuses)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate days balance: sum of purchased days minus used days
        $purchasedDays = $user->purchases()->where('status', 'paid')->sum('days');
        $usedDays = Project::where('user_id', Auth::id())->sum('used_days');

        // Calculate counts for stats
        $counts = [
            'active' => $active->count(),
            'balance' => max(0, $purchasedDays - $usedDays), // Total purchased days - used days
            'finalized' => $finalized->count(),
        ];

    return view('dashboard', compact('pricingItems', 'userPurchases', 'user', 'active', 'finalized', 'counts'))->with('initialSection', $section);
    }

    /**
     * Display the services page
     */
    public function services(): View
    {
        $user = Auth::user();
        $pricingItems = PricingItem::with('category')->get();

        return view('dashboard.services-page', compact('pricingItems', 'user'));
    }

    /**
     * Display the transactions page
     */
    public function transactions(): View
    {
        $user = Auth::user();
        $userPurchases = Purchase::where('user_id', $user->id)->with('pricingItem')->get();

        return view('dashboard.transactions-page', compact('userPurchases', 'user'));
    }

    /**
     * Display the profile page
     */
    public function profile(): View
    {
        $user = Auth::user();
        $projects = Project::where('user_id', $user->id)->latest()->get();

        $activeStatuses = ['new', 'pending', 'in_progress', 'completed'];
        $finalizedStatuses = ['cancelled', 'finalized'];

        $active = Project::where('user_id', Auth::id())
            ->whereIn('status', $activeStatuses)
            ->orderBy('created_at', 'desc')
            ->get();

        $finalized = Project::where('user_id', Auth::id())
            ->whereIn('status', $finalizedStatuses)
            ->orderBy('created_at', 'desc')
            ->get();

        $counts = [
            'active' => $active->count(),
            'finalized' => $finalized->count(),
        ];

        return view('dashboard.profile-page', compact('user', 'active', 'finalized', 'counts'));
    }

    /**
     * Add item to cart session
     */
    public function addToCart(Request $request)
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

        // Redirect back to the referring page or dashboard
        $redirectTo = $request->header('referer') ?: route('dashboard');
        if (str_contains($redirectTo, '/dashboard')) {
            return redirect()->route('dashboard')->with('success', 'Item added to cart successfully!');
        } else {
            return redirect()->route('home')->with('success', 'Item added to cart successfully!');
        }
    }

    /**
     * Update item quantity in cart via AJAX
     */
    public function updateCartQty(Request $request)
    {
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

        // Recompute totals and per-line amounts to return to the client for a live update
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

        // Simple tax/discount placeholders (existing template expects these variables)
        $discount = 0;
        $tax = 0;
        $total = $subtotal - $discount + $tax;

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'lines' => $lines,
            'subtotal' => $subtotal,
            'total' => $total,
        ]);
    }

    /**
     * Remove an item from the cart
     */
    public function removeFromCart(Request $request)
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

        // If cart is now empty, also clear legacy selected plan session to hide old-format cart
        if (empty($cart)) {
            session()->forget('selected_plan_for_payment');
        }

        // Recompute totals for AJAX clients
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

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'cart' => $cart,
                'lines' => $lines,
                'subtotal' => $subtotal,
                'total' => $total,
                'empty' => empty($cart),
            ]);
        }

        // Redirect back to the referring page or dashboard
        $redirectTo = $request->header('referer') ?: route('dashboard');
        if (str_contains($redirectTo, '/dashboard')) {
            return redirect()->route('dashboard')->with('success', 'Item removed from cart.');
        } else {
            return redirect()->route('home')->with('success', 'Item removed from cart.');
        }
    }
}
