@extends('layouts.guest')

@section('content')
<!-- Dark minimal background -->
<div class="min-h-screen bg-gray-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <img class="mx-auto h-16 w-16 rounded-full shadow-lg border-2 border-gray-700" 
                 src="{{ asset('images/imhotion.jpg') }}" alt="Imhotion">
            <h2 class="mt-6 text-3xl font-bold text-white">
                @if(isset($admin) && $admin)
                    Admin Login
                @else
                    Sign in to your account
                @endif
            </h2>
            <p class="mt-2 text-sm text-gray-300">
                @if(isset($admin) && $admin)
                    Access your admin dashboard
                @else
                    Welcome back! Please sign in to continue.
                @endif
            </p>
        </div>

        <!-- Login Form -->
        <div class="bg-gray-800 py-8 px-6 shadow-2xl rounded-lg border border-gray-700">
            @if(isset($errors) && $errors->any())
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-md">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-4a1 1 0 00-1 1v4a1 1 0 102 0V7a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-300">Please fix the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-200 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ isset($admin) && $admin ? route('admin.login.post') : route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">
                        Email address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               value="{{ old('email', 'administrator@imhotion.com') }}" autofocus
                               class="appearance-none block w-full px-3 py-2 border border-gray-600 rounded-md placeholder-gray-400 bg-gray-700 text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    @if(isset($errors))
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    @endif
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300">
                        Password
                    </label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-600 rounded-md placeholder-gray-400 bg-gray-700 text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password-toggle-icon" class="fas fa-eye text-gray-400 hover:text-gray-300"></i>
                        </button>
                    </div>
                    @if(isset($errors))
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" 
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-600 rounded bg-gray-700">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-300">
                        Remember me
                    </label>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Sign in
                    </button>
                </div>
            </form>

            <!-- Social Login -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gray-800 text-gray-400">Or continue with</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('social.redirect', ['provider' => 'google']) }}" 
                       class="w-full inline-flex justify-center py-2 px-4 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-sm font-medium text-gray-300 hover:bg-gray-600">
                        <img src="{{ asset('images/google-g-64.png') }}" alt="Google" class="h-5 w-5 mr-2"> 
                        Google
                    </a>
                </div>
            </div>

            @if(!isset($admin) || !$admin)
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-400">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-medium text-indigo-400 hover:text-indigo-300">
                        Sign up here
                    </a>
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

<script>
// Password toggle functionality
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('password-toggle-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
        `;
    } else {
        passwordInput.type = 'password';
        toggleIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        `;
    }
}

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
    
    // Add smooth animations to form elements
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('scale-105');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('scale-105');
        });
    });
});
</script>
