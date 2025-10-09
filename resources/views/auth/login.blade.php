@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <img class="mx-auto h-16 w-auto" src="{{ asset('images/imhotion.jpg') }}" alt="Imhotion">
            <h2 class="mt-6 text-center text-3xl font-semibold text-white">
                @if(isset($admin) && $admin)
                    Admin Login
                @else
                    Sign in to your account
                @endif
            </h2>
            @if(!isset($admin) || !$admin)
            <p class="mt-2 text-center text-sm text-gray-300">
                Or
                <a href="{{ route('register') }}" class="font-medium text-brand-primary-200 hover:text-brand-primary-200">
                    create a new account
                </a>
            </p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-xl py-8 px-6 w-full max-w-md">
            @if(isset($errors) && $errors->any())
                <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 text-red-200 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ isset($admin) && $admin ? route('admin.login.post') : route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email address *
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               value="{{ old('email', 'administrator@imhotion.com') }}" autofocus
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 sm:text-sm">
                    </div>
                    @if(isset($errors))
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    @endif
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password *
                    </label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 sm:text-sm">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password-toggle-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    @if(isset($errors))
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                        Remember me
                    </label>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        Sign in
                    </button>
                </div>
            </form>
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/20"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-transparent text-gray-300">Or sign in with</span>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="w-full inline-flex justify-center py-3 px-4 border border-white/20 rounded-lg shadow-sm bg-white/10 text-sm font-medium text-white hover:bg-white/20 transition-colors duration-300">
                        <img src="{{ asset('images/google-g-64.png') }}" alt="Google" class="h-5 w-5 mr-3"> Sign in with Google
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
// Check if user should be redirected to checkout after login
document.addEventListener('DOMContentLoaded', function() {
    const shouldRedirect = localStorage.getItem('redirect_to_checkout');
    if (shouldRedirect === 'true') {
        // Clear the flag
        localStorage.removeItem('redirect_to_checkout');
        
        // Add a hidden input to the login form to indicate redirect
        const form = document.querySelector('form');
        if (form) {
            const redirectInput = document.createElement('input');
            redirectInput.type = 'hidden';
            redirectInput.name = 'redirect_to_checkout';
            redirectInput.value = 'true';
            form.appendChild(redirectInput);
        }
    }
});
</script>
