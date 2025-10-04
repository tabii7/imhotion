<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Imhotion') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        
        <!-- TailwindCSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js for mobile menu -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans bg-transparent home">
        <!-- Header Navigation -->
        <header class="bg-transparent h-16 flex items-center justify-center px-4 max-w-7xl mx-auto font-sans relative" x-data="{ mobileOpen: false }">
            <div class="w-full flex justify-between items-center relative">
                <!-- Logo on left -->
                <div class="flex items-center gap-3 text-xl font-semibold text-white">
                    <img src="{{ asset('images/imhotion.jpg') }}" alt="Imhotion Logo" class="h-10 w-auto object-contain rounded">
                    <span>Imhotion</span>
                </div>

                <!-- Login/Signup and Hamburger Menu Button on right -->
                <div class="flex items-center gap-4">
                    <!-- Login/Signup Links (hidden on mobile) -->
                    <div class="hidden md:flex items-center gap-4">
                        <a href="/login" class="text-white hover:text-brand-primary-200 transition-colors duration-300 text-sm font-medium">
                            Login
                        </a>
                        <a href="/register" class="bg-brand-primary text-white px-4 py-2 rounded-lg hover:bg-brand-primary/90 transition-colors duration-300 text-sm font-medium">
                            Sign Up
                        </a>
                    </div>
                    
                    <!-- Hamburger Menu Button -->
                    <button class="flex flex-col gap-1 items-center justify-center w-10 h-10 text-white hover:text-brand-primary-200 transition-colors duration-300" @click="mobileOpen = !mobileOpen">
                        <span class="w-6 h-0.5 bg-current rounded transition-all duration-300" :class="mobileOpen ? 'rotate-45 translate-y-1.5' : ''"></span>
                        <span class="w-6 h-0.5 bg-current rounded transition-all duration-300" :class="mobileOpen ? 'opacity-0' : ''"></span>
                        <span class="w-6 h-0.5 bg-current rounded transition-all duration-300" :class="mobileOpen ? '-rotate-45 -translate-y-1.5' : ''"></span>
                    </button>
                </div>

                <!-- Mobile Navigation Menu -->
                <div class="absolute top-16 right-0 bg-black/95 backdrop-blur-md flex-col p-6 gap-2 z-50 min-w-64 rounded-bl-xl shadow-2xl border border-white/10" 
                     :class="mobileOpen ? 'flex' : 'hidden'">
                    
                    <!-- Navigation Links -->
                    <a href="/" 
                       class="text-white hover:text-brand-primary-200 hover:bg-white/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Home
                    </a>
                    
                    <a href="/#pricing" 
                       class="text-white hover:text-brand-primary-200 hover:bg-white/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Pricing
                    </a>
                    
                    <a href="/about" 
                       class="text-white hover:text-brand-primary-200 hover:bg-white/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        About
                    </a>
                    
                    <a href="/contact" 
                       class="text-white hover:text-brand-primary-200 hover:bg-white/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contact
                    </a>
                    
                    <!-- Divider -->
                    <div class="border-t border-white/10 my-2"></div>
                    
                    <!-- Auth Links -->
                    <a href="/login" 
                       class="text-white hover:text-brand-primary-200 hover:bg-white/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Login
                    </a>
                    
                    <a href="/register" 
                       class="bg-brand-primary text-white hover:bg-brand-primary/90 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Sign Up
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="min-h-[calc(100vh-150px)]">
            @yield('content')
        </main>

        <!-- Floating Cart Button -->
        @include('components.floating-cart')

        <!-- Footer -->
        <footer class="bg-transparent text-white py-8 mt-16">
            <div class="max-w-6xl mx-auto px-4 text-center">
                <div class="flex flex-col md:flex-row justify-center gap-8 mb-4">
                    <a href="#" class="text-white hover:text-brand-primary-200 transition-colors duration-300">Privacy Policy</a>
                    <a href="#" class="text-white hover:text-brand-primary-200 transition-colors duration-300">Terms & Conditions</a>
                    <a href="#" class="text-white hover:text-brand-primary-200 transition-colors duration-300">Contact</a>
                    <a href="#" class="text-white hover:text-brand-primary-200 transition-colors duration-300">About</a>
                </div>
                <div class="text-sm text-pricing-subtitle">
                    © {{ date('Y') }} Imhotion. All rights reserved.
                </div>
            </div>
        </footer>
    </body>
</html>
