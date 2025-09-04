@extends('layouts.app')

@section('content')
<!-- Mini Cart Component -->
@include('components.mini-cart')

<!-- Theme toggle removed — we keep nightmode only and show sandwich menu instead -->

<!-- Simple Header for Public Users -->
@guest
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
      
      <a href="#pricing" 
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
@endguest

<!-- Pricing Sections -->
<div id="pricing" class="from-brand-dark to-slate-900 text-white py-16">
  <div class="max-w-6xl mx-auto px-4">

    @foreach($categories as $category)
      @if($category->slug === 'price-per-day')
        <!-- Price per day section -->
        <div class="mb-16">
          <div class="text-center mb-12">
            <h2 class="text-4xl font-semibold mb-4">{{ $category->name }}</h2>
            <p class="text-lg text-pricing-subtitle">{{ $category->description }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            @foreach($category->items as $item)
              <div class="bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-6 transition-all duration-300 hover:border-brand-primary-200/40 hover:-translate-y-1 hover:shadow-2xl hover:shadow-brand-primary/10">
                <h3 class="text-2xl font-semibold mb-6 text-center">{{ $item->title }}</h3>
                <div class="text-center">
                  @auth
                    <form method="POST" action="{{ route('add-to-cart') }}" class="inline">
                      @csrf
                      <input type="hidden" name="pricing_item_id" value="{{ $item->id }}">
                      <button type="submit" class="pricing-badge">
                        <span class="text-lg font-semibold">€{{ number_format($item->price, 0) }}</span>
                        <span class="text-sm ml-1">/{{ str_replace('per_', '', $item->price_unit) }}</span>
                        <div class="arrow">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                          </svg>
                        </div>
                      </button>
                    </form>
                  @else
                    <a href="/register?plan={{ $item->id }}" class="pricing-badge">
                      <span class="text-lg font-semibold">€{{ number_format($item->price, 0) }}</span>
                      <span class="text-sm ml-1">/{{ str_replace('per_', '', $item->price_unit) }}</span>
                      <div class="arrow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                      </div>
                    </a>
                  @endauth
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
            <h2 class="text-4xl font-semibold mb-4">{{ $category->name }}</h2>
            <p class="text-lg text-pricing-subtitle">{{ $category->description }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            @foreach($category->items as $item)
              @php
                $totalPrice = $item->price * (strpos($item->title, '5') !== false ? 5 : (strpos($item->title, '10') !== false ? 10 : 20));
                preg_match('/\d+/', $item->title, $__m);
                $__days = $__m[0] ?? null;
              @endphp
              <div class="bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-6 transition-all duration-300 hover:border-brand-primary-200/40 hover:-translate-y-1 hover:shadow-2xl hover:shadow-brand-primary/10 relative flex flex-col justify-between min-h-[450px]">
                <!-- Price badge -->
                <div class="absolute right-4 top-4">
                  <div class="price-total">
                    €{{ number_format($totalPrice, 0) }}
                  </div>
                </div>

                <div class="text-center">
                  <h3 class="text-3xl font-semibold mb-1">{{ $item->title }}</h3>
                  @if($__days)
                    <div class="text-pricing-subtitle text-sm mb-1">{{ $__days }} days</div>
                  @endif
                  <p class="mb-2 text-pricing-subtitle">
                    €{{ number_format($item->price, 0) }}/day
                  </p>

                  @if($item->duration_years)
                    <p class="mb-4 text-gray-400 text-sm">
                      Valid for {{ $item->duration_years }} {{ $item->duration_years == 1 ? 'year' : 'years' }}
                    </p>
                  @endif
                </div>

                <!-- Features -->
                <ul class="space-y-3 mb-6">
                  @if($item->discount_percent)
                    <li class="flex items-center gap-3">
                      <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                      <span>{{ $item->discount_percent }}% Discount</span>
                    </li>
                  @endif

                  <li class="flex items-center gap-3">
                    @if($item->has_gift_box)
                      <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    @else
                      <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                      </svg>
                    @endif
                    <span class="{{ $item->has_gift_box ? '' : 'text-gray-500' }}">Gift Box</span>
                  </li>

                  <li class="flex items-center gap-3">
                    @if($item->has_project_files)
                      <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    @else
                      <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 0 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                      </svg>
                    @endif
                    <span class="{{ $item->has_project_files ? '' : 'text-gray-500' }}">Projects Files</span>
                  </li>

                  <li class="flex items-center gap-3">
                    @if($item->has_weekends_included)
                      <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    @else
                      <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                      </svg>
                    @endif
                    <span class="{{ $item->has_weekends_included ? '' : 'text-gray-500' }}">Weekends Included</span>
                  </li>
                </ul>

                <!-- CTA Button -->
                <div class="text-center">
                  @auth
                    <form method="POST" action="{{ route('add-to-cart') }}" class="inline">
                      @csrf
                      <input type="hidden" name="pricing_item_id" value="{{ $item->id }}">
                      <button type="submit" class="btn-purchase">
                        <span>Add {{ $item->title }} to Cart</span>
                        <div class="arrow">
                          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                          </svg>
                        </div>
                      </button>
                    </form>
                  @else
                    <a href="/register?plan={{ $item->id }}" class="btn-purchase">
                      <span>Get {{ $item->title }}</span>
                      <div class="arrow">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                      </div>
                    </a>
                  @endauth
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
            <h2 class="text-4xl font-semibold mb-4">{{ $category->name }}</h2>
            <p class="text-lg text-pricing-subtitle">{{ $category->description }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            @foreach($category->items as $item)
              <div class="bg-white/5 backdrop-blur-sm border-2 border-dashed border-brand-primary-200/20 rounded-2xl p-6 transition-all duration-300 hover:border-brand-primary-200/40 hover:-translate-y-1 hover:shadow-2xl hover:shadow-brand-primary/10 flex flex-col justify-center items-center text-center min-h-[200px]">
                <h3 class="text-2xl font-semibold mb-4">{{ $item->title }}</h3>
                <div>
                  <a href="/register?plan={{ $item->id }}" class="pricing-badge">
                    <span class="font-semibold">€{{ number_format($item->price, 0) }}</span>
                    <span class="text-sm ml-1">/{{ str_replace('per_', '', $item->price_unit) }}</span>
                    <div class="arrow">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
@endsection
