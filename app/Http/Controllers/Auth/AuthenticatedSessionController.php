<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Check if user should be redirected to checkout
        if ($request->has('redirect_to_checkout') && $request->redirect_to_checkout === 'true') {
            // Redirect to dashboard first, then JavaScript will handle the checkout
            return redirect()->route('dashboard')->with('redirect_to_checkout', true);
        }

        // Redirect based on user role
        $user = Auth::user();
        if ($user->isAdministrator() || $user->isAdmin()) {
            return redirect()->intended(route('administrator.dashboard', absolute: false));
        } elseif ($user->isDeveloper()) {
            return redirect()->intended(route('developer.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
