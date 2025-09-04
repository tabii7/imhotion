<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Dashboard') — Imhotion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="home text-white font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-70 text-sidebar-text fixed h-screen left-0 top-0 z-50 lg:translate-x-0 -translate-x-full transition-transform duration-300" id="sidebar">
            <div class="p-6 border-slate-700">
                <a href="/" class="flex items-center gap-3 text-white no-underline">
                    <img src="/images/imhotion-blue.png" alt="Imhotion" class="w-8 h-8">
                    <span class="text-lg font-bold">Imhotion</span>
                </a>
            </div>

            <nav class="p-5">
                <div class="mb-8">
                    <div class="text-xs text-blue-300 font-bold uppercase tracking-wider mb-3 px-3">Dashboard</div>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-lg no-underline text-sidebar-text transition-all duration-300 mb-1.5 font-medium border border-transparent hover:bg-sidebar-hover hover:text-white hover:border-blue-300/30 hover:translate-x-1 {{ request()->routeIs('dashboard') ? 'bg-sidebar-active text-white border-blue-300 shadow-lg shadow-brand-dark/30' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Home
                    </a>
                    <a href="{{ route('dashboard.services') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-lg no-underline text-sidebar-text transition-all duration-300 mb-1.5 font-medium border border-transparent hover:bg-sidebar-hover hover:text-white hover:border-blue-300/30 hover:translate-x-1 {{ request()->routeIs('dashboard.services') ? 'bg-sidebar-active text-white border-blue-300 shadow-lg shadow-brand-dark/30' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Services
                    </a>
                </div>
                <div class="mb-8">
                    <div class="text-xs text-blue-300 font-bold uppercase tracking-wider mb-3 px-3">Account</div>
                    <a href="{{ route('dashboard.transactions') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-lg no-underline text-sidebar-text transition-all duration-300 mb-1.5 font-medium border border-transparent hover:bg-sidebar-hover hover:text-white hover:border-blue-300/30 hover:translate-x-1 {{ request()->routeIs('dashboard.transactions') ? 'bg-sidebar-active text-white border-blue-300 shadow-lg shadow-brand-dark/30' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Transactions
                    </a>
                    <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-lg no-underline text-sidebar-text transition-all duration-300 mb-1.5 font-medium border border-transparent hover:bg-sidebar-hover hover:text-white hover:border-blue-300/30 hover:translate-x-1 {{ request()->routeIs('dashboard.profile') ? 'bg-sidebar-active text-white border-blue-300 shadow-lg shadow-brand-dark/30' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-70 bg-transparent">
            <header class="bg-transparent border-slate-700/50 px-8 py-4 flex items-center justify-between">
                <h1 class="m-0 text-2xl font-bold text-white">@yield('page-title', 'Dashboard')</h1>
                <div class="flex items-center gap-4">
                    <span class="text-sidebar-text">{{ Auth::user()->name }}</span>
                    <div class="w-8 h-8 rounded-full bg-brand-primary text-white flex items-center justify-center font-semibold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <a href="{{ route('logout') }}" class="border-0 bg-brand-primary text-white px-3 py-1.5 rounded-lg cursor-pointer font-semibold text-xs no-underline inline-flex items-center gap-2 transition-all duration-200 hover:bg-blue-600 hover:-translate-y-0.5"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </header>

            <div class="p-5">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-500 text-green-800 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-500 text-red-800 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Mobile menu button -->
    <button class="lg:hidden fixed top-4 left-4 z-50 bg-brand-primary text-white p-2 rounded-lg" onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <!-- Floating Cart Button -->
    @include('components.floating-cart')

    @yield('scripts')
</body>
</html>
