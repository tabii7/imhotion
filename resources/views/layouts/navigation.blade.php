<nav class="top-nav" x-data="{ mobileOpen: false }">
    <!-- Desktop Navigation - Centered -->
    <div class="top-nav-center">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="#pricing">Pricing</a>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</a>
        </form>
    </div>
    
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" @click="mobileOpen = !mobileOpen">
        ☰
    </button>
    
    <!-- Mobile Navigation -->
    <div class="mobile-menu" :class="{ 'active': mobileOpen }">
        <a href="{{ route('home') }}">Home</a>
        <a href="#pricing">Pricing</a>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</a>
        </form>
    </div>
</nav>
