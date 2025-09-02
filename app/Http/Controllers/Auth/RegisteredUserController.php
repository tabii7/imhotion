<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Models\User;
use App\Models\PricingItem;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $selectedPlan = null;
        if ($request->has('plan')) {
            $selectedPlan = PricingItem::find($request->plan);
        }
        
        return view('auth.register', compact('selectedPlan'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'pricing_item_id' => ['nullable', 'exists:pricing_items,id'],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            Auth::login($user);

            // If a plan was selected, store it in session and redirect to dashboard for checkout
            if ($request->pricing_item_id) {
                session(['selected_plan_for_payment' => $request->pricing_item_id]);
                return redirect('/dashboard')->with('success', 'Account created! Please review and complete your purchase below.');
            }

            // Otherwise, redirect to dashboard
            return redirect('/dashboard')->with('success', 'Welcome to Imhotion! Explore our services below.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }
    }
}
