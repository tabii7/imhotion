@extends('layouts.app')

@section('content')
<!-- Theme Toggle -->
<button class="theme-toggle" onclick="toggleTheme()" id="themeToggle">
  <svg id="sunIcon" style="width: 1.5rem; height: 1.5rem; display: none;" fill="currentColor" viewBox="0 0 20 20">
    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
  </svg>
  <svg id="moonIcon" style="width: 1.5rem; height: 1.5rem;" fill="currentColor" viewBox="0 0 20 20">
    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
  </svg>
</button>

<!-- Simple Header for Public Users -->
@guest
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
      <a href="#pricing">Pricing</a>
      <a href="/login">Login</a>
      <a href="/register">Sign Up</a>
    </div>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" @click="mobileOpen = !mobileOpen">
      ☰
    </button>

    <!-- Mobile Navigation -->
    <div class="mobile-menu" :class="{ 'active': mobileOpen }">
      <a href="/">Home</a>
      <a href="#pricing">Pricing</a>
      <a href="/login">Login</a>
      <a href="/register">Sign Up</a>
    </div>
  </div>
</header>
@else
<!-- Header for Authenticated Users -->
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
      <a href="#pricing">Pricing</a>
      <a href="/client">{{ Auth::user()->name }}</a>
      <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
      </form>
    </div>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" @click="mobileOpen = !mobileOpen">
      ☰
    </button>

    <!-- Mobile Navigation -->
    <div class="mobile-menu" :class="{ 'active': mobileOpen }">
      <a href="/">Home</a>
      <a href="#pricing">Pricing</a>
      <a href="/client">{{ Auth::user()->name }}</a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
      </form>
    </div>
  </div>
</header>
@endguest

<!-- Pricing Sections -->
<div id="pricing" class="pricing-dark">
  <div class="container">

    @foreach($categories as $category)
      @if($category->slug === 'price-per-day')
        <!-- Price per day section -->
        <div class="mb-16">
          <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4">{{ $category->name }}</h2>
            <p class="text-lg subtitle">{{ $category->description }}</p>
          </div>

          <div class="grid-2">
            @foreach($category->items as $item)
              <div class="pricing-card">
                <h3 class="text-2xl font-bold mb-4 text-center">{{ $item->title }}</h3>
                <div class="text-center mb-6">
                  <a href="/register?plan={{ $item->id }}" class="pricing-badge">
                    <span class="text-lg font-bold">€{{ number_format($item->price, 0) }}</span>
                    <span style="font-size: 0.875rem; margin-left: 0.25rem;">/{{ str_replace('per_', '', $item->price_unit) }}</span>
                    <div class="arrow">
                      <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                      </svg>
                    </div>
                  </a>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      @if($category->slug === 'our-packs')
        <!-- Our Packs section -->
        <div class="mb-16">
          <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4">{{ $category->name }}</h2>
            <p class="text-lg subtitle">{{ $category->description }}</p>
          </div>

          <div class="grid-3">
            @foreach($category->items as $item)
              @php
                $totalPrice = $item->price * (strpos($item->title, '5') !== false ? 5 : (strpos($item->title, '10') !== false ? 10 : 20));
              @endphp
              <div class="pricing-card">
                <!-- Price badge -->
                <div class="text-center mb-4">
                  <div style="background: #374151; color: white; border-radius: 2rem; padding: 0.25rem 1rem; display: inline-block; font-size: 0.875rem;">
                    €{{ number_format($totalPrice, 0) }}
                  </div>
                </div>

                <h3 class="text-3xl font-bold mb-2 text-center">{{ $item->title }}</h3>
                <p class="text-center mb-4 subtitle">
                  €{{ number_format($item->price, 0) }}/day
                </p>

                @if($item->duration_years)
                  <p class="text-center mb-6" style="color: #6b7280; font-size: 0.875rem;">
                    Valid for {{ $item->duration_years }} {{ $item->duration_years == 1 ? 'year' : 'years' }}
                  </p>
                @endif

                <!-- Features -->
                <ul class="feature-list">
                  @if($item->discount_percent)
                    <li>
                      <svg class="icon check" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                      <span>{{ $item->discount_percent }}% Discount</span>
                    </li>
                  @endif

                  <li>
                    @if($item->has_gift_box)
                      <svg class="icon check" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    @else
                      <svg class="icon cross" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                      </svg>
                    @endif
                    <span class="{{ $item->has_gift_box ? '' : 'disabled' }}">Gift Box</span>
                  </li>

                  <li>
                    @if($item->has_project_files)
                      <svg class="icon check" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    @else
                      <svg class="icon cross" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 0 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                      </svg>
                    @endif
                    <span class="{{ $item->has_project_files ? '' : 'disabled' }}">Projects Files</span>
                  </li>

                  <li>
                    @if($item->has_weekends_included)
                      <svg class="icon check" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    @else
                      <svg class="icon cross" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                      </svg>
                    @endif
                    <span class="{{ $item->has_weekends_included ? '' : 'disabled' }}">Weekends Included</span>
                  </li>
                </ul>

                <!-- CTA Button -->
                <div class="text-center">
                  <a href="/register?plan={{ $item->id }}" class="btn-purchase">
                    <span>Get {{ $item->title }}</span>
                    <div class="arrow" style="margin-left: 0.75rem;">
                      <svg style="width: 0.75rem; height: 0.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                      </svg>
                    </div>
                  </a>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      @if($category->slug === 'addons')
        <!-- Addons section -->
        <div class="mb-16">
          <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4">{{ $category->name }}</h2>
            <p class="text-lg subtitle">{{ $category->description }}</p>
          </div>

          <div class="grid-2">
            @foreach($category->items as $item)
              <div class="pricing-card">
                <h3 class="text-2xl font-bold mb-4">{{ $item->title }}</h3>
                <div class="mb-6">
                  <a href="/register?plan={{ $item->id }}" class="pricing-badge">
                    <span class="font-bold">€{{ number_format($item->price, 0) }}</span>
                    <span style="font-size: 0.875rem; margin-left: 0.25rem;">/{{ str_replace('per_', '', $item->price_unit) }}</span>
                    <div class="arrow">
                      <svg style="width: 0.75rem; height: 0.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                      </svg>
                    </div>
                  </a>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    @endforeach

  </div>
</div>

<script>
function toggleTheme() {
  const body = document.body;
  const sunIcon = document.getElementById('sunIcon');
  const moonIcon = document.getElementById('moonIcon');

  if (body.getAttribute('data-theme') === 'light') {
    body.removeAttribute('data-theme');
    sunIcon.style.display = 'none';
    moonIcon.style.display = 'block';
    localStorage.setItem('theme', 'dark');
  } else {
    body.setAttribute('data-theme', 'light');
    sunIcon.style.display = 'block';
    moonIcon.style.display = 'none';
    localStorage.setItem('theme', 'light');
  }
}

// Load saved theme
document.addEventListener('DOMContentLoaded', function() {
  const savedTheme = localStorage.getItem('theme');
  const sunIcon = document.getElementById('sunIcon');
  const moonIcon = document.getElementById('moonIcon');

  if (savedTheme === 'light') {
    document.body.setAttribute('data-theme', 'light');
    sunIcon.style.display = 'block';
    moonIcon.style.display = 'none';
  }
});
</script>

<!-- Footer -->
<footer class="footer">
  <div class="footer-content">
    <div class="footer-links">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms & Conditions</a>
      <a href="#">Contact</a>
      <a href="#">About</a>
    </div>
    <div class="footer-copyright">
      © {{ date('Y') }} Imhotion. All rights reserved.
    </div>
  </div>
</footer>
@endsection
