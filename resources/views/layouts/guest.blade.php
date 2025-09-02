<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Imhotion') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Brand CSS with fonts and styling -->
        <link rel="stylesheet" href="{{ asset('css/brand.css') }}">
        
        <!-- Alpine.js for mobile menu -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- TailwindCSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body style="font-family: var(--font-sans); background: var(--brand-bg);">
        <!-- Header Navigation -->
        <header class="header" x-data="{ mobileOpen: false }">
            <div class="header-content-with-logo">
                <!-- Logo on left -->
                <div class="logo">
                    <img src="{{ asset('images/imhotion.jpg') }}" alt="Imhotion Logo" class="logo-image">
                    <span>Imhotion</span>
                </div>
                
                <!-- Desktop Navigation - Right side -->
                <div class="nav-links">
                    <a href="/">Home</a>
                    <a href="/#pricing">Pricing</a>
                    @auth
                        <a href="/client">{{ Auth::user()->name }}</a>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                        </form>
                    @else
                        <a href="/login">Login</a>
                        <a href="/register">Sign Up</a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn" @click="mobileOpen = !mobileOpen">
                    ☰
                </button>
                
                <!-- Mobile Navigation -->
                <div class="mobile-menu" :class="{ 'active': mobileOpen }">
                    <a href="/">Home</a>
                    <a href="/#pricing">Pricing</a>
                    @auth
                        <a href="/client">{{ Auth::user()->name }}</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                        </form>
                    @else
                        <a href="/login">Login</a>
                        <a href="/register">Sign Up</a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main style="min-height: calc(100vh - 150px);">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <img src="{{ asset('images/imhotion.jpg') }}" alt="Imhotion" class="footer-logo">
                    <p>Professional marketing solutions for your business growth.</p>
                </div>
                <div class="footer-section">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="#">Digital Marketing</a></li>
                        <li><a href="#">Content Creation</a></li>
                        <li><a href="#">Brand Strategy</a></li>
                        <li><a href="#">Analytics</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <ul>
                        <li>info@imhotion.com</li>
                        <li>+31 (0) 123 456 789</li>
                        <li>Amsterdam, Netherlands</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Imhotion. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
