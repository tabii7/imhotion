<nav class="bg-transparent h-16 flex items-center justify-center px-4 max-w-7xl mx-auto font-sans relative" x-data="{ mobileOpen: false }">
    <div class="w-full flex justify-between items-center relative">
        <!-- Logo on left -->
        <div class="flex items-center gap-3 text-xl font-semibold text-white">
            <img src="{{ asset('images/imhotion.jpg') }}" alt="Imhotion Logo" class="h-10 w-auto object-contain rounded">
            <span>Imhotion</span>
        </div>
        
        <!-- User Avatar and Hamburger Menu Button on right -->
        <div class="flex items-center gap-4">
            <!-- User Avatar -->
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-primary rounded-full flex items-center justify-center text-white text-sm font-semibold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <span class="text-white text-sm font-medium hidden md:block">{{ Auth::user()->name }}</span>
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
            
            <!-- User Info Section -->
            <div class="flex items-center gap-3 px-4 py-3 border-b border-white/10 mb-2">
                <div class="w-10 h-10 bg-brand-primary rounded-full flex items-center justify-center text-white font-semibold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="text-white font-medium">{{ Auth::user()->name }}</div>
                    <div class="text-gray-400 text-sm">{{ Auth::user()->email }}</div>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <a href="{{ route('home') }}" 
               class="text-white hover:text-brand-primary-200 hover:bg-white/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3 {{ request()->routeIs('home') ? 'bg-white/10 text-brand-primary-200' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Home
            </a>
            
            <a href="#pricing" 
               class="text-white hover:text-brand-primary-200 hover:bg-white/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Pricing
            </a>
            
            <a href="{{ route('dashboard') }}" 
               class="text-white hover:text-brand-primary-200 hover:bg-white/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3 {{ request()->routeIs('dashboard') ? 'bg-white/10 text-brand-primary-200' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Dashboard
            </a>
            
            <a href="/profile" 
               class="text-white hover:text-brand-primary-200 hover:bg-white/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profile
            </a>
            
            <a href="/settings" 
               class="text-white hover:text-brand-primary-200 hover:bg-white/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Settings
            </a>
            
            <!-- Divider -->
            <div class="border-t border-white/10 my-2"></div>
            
            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); this.closest('form').submit();"
                   class="text-red-400 hover:text-red-300 hover:bg-red-500/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Log Out
                </a>
            </form>
        </div>
    </div>
</nav>

<!-- Floating Cart Button -->
@include('components.floating-cart')
