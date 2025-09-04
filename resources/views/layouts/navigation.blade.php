<nav class="top-nav" x-data="{ mobileOpen: false }">
    <div class="nav-container">
        <!-- Desktop Navigation - Left side -->
        <div class="nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="#pricing">Pricing</a>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</a>
            </form>
        </div>

        <!-- Logo on right -->
        <div class="logo">
            <img src="{{ asset('images/imhotion.jpg') }}" alt="Imhotion Logo" class="logo-image">
            <span>Imhotion</span>
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
    </div>
</nav>
