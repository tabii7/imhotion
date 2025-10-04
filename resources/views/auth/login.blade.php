@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <img class="mx-auto h-16 w-auto" src="{{ asset('images/imhotion.jpg') }}" alt="Imhotion">
            <h2 class="mt-6 text-center text-3xl font-semibold text-white">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-300">
                Or
                <a href="{{ route('register') }}" class="font-medium text-brand-primary-200 hover:text-brand-primary-200">
                    create a new account
                </a>
            </p>
        </div>

        <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl py-8 px-6 shadow-2xl">
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

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-white">
                        Email address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               value="{{ old('email') }}" autofocus
                               class="appearance-none block w-full px-3 py-3 border border-white/20 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent bg-white/10 text-white sm:text-sm">
                    </div>
                    @if(isset($errors))
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    @endif
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-white">
                        Password
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="appearance-none block w-full px-3 py-3 border border-white/20 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent bg-white/10 text-white sm:text-sm">
                    </div>
                    @if(isset($errors))
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" 
                               class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-white/20 rounded bg-white/10">
                        <label for="remember_me" class="ml-2 block text-sm text-white">
                            Remember me
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-medium text-brand-primary-200 hover:text-brand-primary-200">
                                Forgot your password?
                            </a>
                        </div>
                    @endif
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-colors duration-300">
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
