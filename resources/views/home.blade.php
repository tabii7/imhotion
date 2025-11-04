@extends('layouts.app')

@section('content')
<!-- Success Messages -->
@if(session('success'))
    <div class="bg-green-500 text-white px-4 py-2 rounded-lg mx-4 mb-4 text-center">
        {{ session('success') }}
    </div>
@endif

<!-- Floating Cart Button -->
@include('components.floating-cart')

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
      
      <a href="#services" 
         class="text-white hover:text-brand-primary-200 hover:bg-white/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3"
         onclick="event.preventDefault(); document.getElementById('services').scrollIntoView({ behavior: 'smooth' });">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
        Services
      </a>
      
      <a href="#how-it-works" 
         class="text-white hover:text-brand-primary-200 hover:bg-white/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3"
         onclick="event.preventDefault(); document.getElementById('how-it-works').scrollIntoView({ behavior: 'smooth' });">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
        </svg>
        How It Works
      </a>
      
      <a href="#pricing" 
         class="text-white hover:text-brand-primary-200 hover:bg-white/10 px-4 py-3 rounded-lg transition-all duration-300 flex items-center gap-3"
         onclick="event.preventDefault(); document.getElementById('pricing').scrollIntoView({ behavior: 'smooth' });">
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

<!-- Hero Section -->
<section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden pt-20 pb-32 px-4 md:px-8 mt-4" style="margin-top: 7%;">
  <div class="absolute top-4 right-4 bottom-4 left-4 md:top-8 md:right-8 md:bottom-8 md:left-8 bg-gradient-to-br from-brand-dark via-slate-900 to-brand-dark opacity-90 rounded-lg"></div>
  <div class="absolute top-4 right-4 bottom-4 left-4 md:top-8 md:right-8 md:bottom-8 md:left-8 rounded-lg" style="background-image: radial-gradient(circle at 20% 50%, rgba(0, 102, 255, 0.1) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(153, 194, 255, 0.1) 0%, transparent 50%);"></div>
  
  <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-8 lg:px-12 text-center">
    <div class="animate-fade-in-up">
      <h1 class="text-5xl md:text-7xl font-bold text-white mb-8 md:mb-10 leading-tight">
        Professional Development
        <span class="bg-gradient-to-r from-brand-primary to-brand-primary-200 bg-clip-text text-transparent">Services</span>
        <br>That Scale With You
      </h1>
      <p class="text-xl md:text-2xl text-gray-300 mb-10 md:mb-12 max-w-3xl mx-auto leading-relaxed">
        Get expert development services on-demand. Buy development days, manage projects, and track progress all in one place.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mt-12 md:mt-16" style="margin: 55px;">
        <a href="#pricing" class="bg-brand-primary hover:bg-brand-primary/90 text-white px-6 py-3 rounded-lg font-semibold text-base transition-all duration-300 transform hover:scale-105 shadow-lg shadow-brand-primary/30 flex items-center gap-2"
           onclick="event.preventDefault(); document.getElementById('pricing').scrollIntoView({ behavior: 'smooth' });">
          View Pricing
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
          </svg>
        </a>
        <a href="/register" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white px-6 py-3 rounded-lg font-semibold text-base transition-all duration-300 transform hover:scale-105">
          Get Started Free
        </a>
      </div>
    </div>

    <!-- Stats Section -->
    <div class="mt-24 md:mt-32 grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12 max-w-4xl mx-auto">
      <div class="text-center">
        <div class="text-4xl md:text-5xl font-bold text-brand-primary mb-3">100+</div>
        <div class="text-gray-400 text-sm md:text-base">Projects Delivered</div>
      </div>
      <div class="text-center">
        <div class="text-4xl md:text-5xl font-bold text-brand-primary mb-3">50+</div>
        <div class="text-gray-400 text-sm md:text-base">Happy Clients</div>
      </div>
      <div class="text-center">
        <div class="text-4xl md:text-5xl font-bold text-brand-primary mb-3">24/7</div>
        <div class="text-gray-400 text-sm md:text-base">Support</div>
      </div>
      <div class="text-center">
        <div class="text-4xl md:text-5xl font-bold text-brand-primary mb-3">99%</div>
        <div class="text-gray-400 text-sm md:text-base">Satisfaction</div>
      </div>
    </div>
  </div>

  <!-- Scroll Indicator -->
  <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
    </svg>
  </div>
</section>

<!-- Services/Features Section -->
<section id="services" class="py-24 md:py-32 bg-gradient-to-b from-transparent to-brand-dark/50 px-4 md:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8">
    <div class="text-center mb-20 md:mb-24">
      <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 md:mb-8">Why Choose Imhotion?</h2>
      <p class="text-xl md:text-2xl text-gray-400 max-w-2xl mx-auto">
        Everything you need to bring your development projects to life
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
      <!-- Feature 1 -->
      <div class="group bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-8 md:p-10 transition-all duration-300 hover:border-brand-primary-200/40 hover:-translate-y-2 hover:shadow-2xl hover:shadow-brand-primary/20">
        <div class="w-16 h-16 bg-brand-primary/20 rounded-xl flex items-center justify-center mb-8 group-hover:bg-brand-primary/30 transition-colors">
          <svg class="w-8 h-8 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h3 class="text-2xl font-semibold text-white mb-5">Flexible Pricing</h3>
        <p class="text-gray-400 leading-relaxed">
          Choose from daily rates or package deals. Pay only for what you need, with options that fit any budget.
        </p>
      </div>

      <!-- Feature 2 -->
      <div class="group bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-8 md:p-10 transition-all duration-300 hover:border-brand-primary-200/40 hover:-translate-y-2 hover:shadow-2xl hover:shadow-brand-primary/20">
        <div class="w-16 h-16 bg-brand-primary/20 rounded-xl flex items-center justify-center mb-8 group-hover:bg-brand-primary/30 transition-colors">
          <svg class="w-8 h-8 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
          </svg>
        </div>
        <h3 class="text-2xl font-semibold text-white mb-5">Project Tracking</h3>
        <p class="text-gray-400 leading-relaxed">
          Monitor your projects in real-time. Track progress, view updates, and download files all from your dashboard.
        </p>
      </div>

      <!-- Feature 3 -->
      <div class="group bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-8 md:p-10 transition-all duration-300 hover:border-brand-primary-200/40 hover:-translate-y-2 hover:shadow-2xl hover:shadow-brand-primary/20">
        <div class="w-16 h-16 bg-brand-primary/20 rounded-xl flex items-center justify-center mb-8 group-hover:bg-brand-primary/30 transition-colors">
          <svg class="w-8 h-8 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h3 class="text-2xl font-semibold text-white mb-5">Time Management</h3>
        <p class="text-gray-400 leading-relaxed">
          Efficient time tracking ensures transparent billing. Know exactly how your development hours are being used.
        </p>
      </div>

      <!-- Feature 4 -->
      <div class="group bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-8 md:p-10 transition-all duration-300 hover:border-brand-primary-200/40 hover:-translate-y-2 hover:shadow-2xl hover:shadow-brand-primary/20">
        <div class="w-16 h-16 bg-brand-primary/20 rounded-xl flex items-center justify-center mb-8 group-hover:bg-brand-primary/30 transition-colors">
          <svg class="w-8 h-8 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
        </div>
        <h3 class="text-2xl font-semibold text-white mb-5">Expert Team</h3>
        <p class="text-gray-400 leading-relaxed">
          Work with experienced developers who understand your needs and deliver quality results on time.
        </p>
      </div>

      <!-- Feature 5 -->
      <div class="group bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-8 md:p-10 transition-all duration-300 hover:border-brand-primary-200/40 hover:-translate-y-2 hover:shadow-2xl hover:shadow-brand-primary/20">
        <div class="w-16 h-16 bg-brand-primary/20 rounded-xl flex items-center justify-center mb-8 group-hover:bg-brand-primary/30 transition-colors">
          <svg class="w-8 h-8 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
          </svg>
        </div>
        <h3 class="text-2xl font-semibold text-white mb-5">Secure & Reliable</h3>
        <p class="text-gray-400 leading-relaxed">
          Your data and projects are protected with enterprise-grade security. Regular backups and secure file storage.
        </p>
      </div>

      <!-- Feature 6 -->
      <div class="group bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-8 md:p-10 transition-all duration-300 hover:border-brand-primary-200/40 hover:-translate-y-2 hover:shadow-2xl hover:shadow-brand-primary/20">
        <div class="w-16 h-16 bg-brand-primary/20 rounded-xl flex items-center justify-center mb-8 group-hover:bg-brand-primary/30 transition-colors">
          <svg class="w-8 h-8 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
          </svg>
        </div>
        <h3 class="text-2xl font-semibold text-white mb-5">Fast Delivery</h3>
        <p class="text-gray-400 leading-relaxed">
          Get your projects done quickly without compromising quality. Efficient workflows and dedicated resources.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-24 md:py-32 bg-gradient-to-b from-brand-dark/50 to-transparent px-4 md:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8">
    <div class="text-center mb-20 md:mb-24">
      <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 md:mb-8">How It Works</h2>
      <p class="text-xl md:text-2xl text-gray-400 max-w-2xl mx-auto">
        Get started in just a few simple steps
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-10 relative">
      <!-- Step 1 -->
      <div class="relative">
        <div class="bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-8 md:p-10 text-center h-full">
          <div class="w-20 h-20 bg-brand-primary rounded-full flex items-center justify-center mx-auto mb-8 text-3xl font-bold text-white">
            1
          </div>
          <h3 class="text-2xl font-semibold text-white mb-5">Sign Up</h3>
          <p class="text-gray-400">
            Create your free account and get instant access to our platform. No credit card required.
          </p>
        </div>
        <div class="hidden md:block absolute top-1/2 left-full w-full h-0.5 bg-brand-primary/30 transform -translate-y-1/2 -translate-x-1/2 z-0">
          <div class="absolute right-0 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-brand-primary rounded-full"></div>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="relative">
        <div class="bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-8 md:p-10 text-center h-full">
          <div class="w-20 h-20 bg-brand-primary rounded-full flex items-center justify-center mx-auto mb-8 text-3xl font-bold text-white">
            2
          </div>
          <h3 class="text-2xl font-semibold text-white mb-5">Choose a Plan</h3>
          <p class="text-gray-400">
            Select from our flexible pricing options. Buy development days or choose a package that fits your needs.
          </p>
        </div>
        <div class="hidden md:block absolute top-1/2 left-full w-full h-0.5 bg-brand-primary/30 transform -translate-y-1/2 -translate-x-1/2 z-0">
          <div class="absolute right-0 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-brand-primary rounded-full"></div>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="relative">
        <div class="bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-8 md:p-10 text-center h-full">
          <div class="w-20 h-20 bg-brand-primary rounded-full flex items-center justify-center mx-auto mb-8 text-3xl font-bold text-white">
            3
          </div>
          <h3 class="text-2xl font-semibold text-white mb-5">Start a Project</h3>
          <p class="text-gray-400">
            Submit your project requirements and get matched with a developer. Track progress in real-time.
          </p>
        </div>
        <div class="hidden md:block absolute top-1/2 left-full w-full h-0.5 bg-brand-primary/30 transform -translate-y-1/2 -translate-x-1/2 z-0">
          <div class="absolute right-0 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-brand-primary rounded-full"></div>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="relative">
        <div class="bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-8 md:p-10 text-center h-full">
          <div class="w-20 h-20 bg-brand-primary rounded-full flex items-center justify-center mx-auto mb-8 text-3xl font-bold text-white">
            4
          </div>
          <h3 class="text-2xl font-semibold text-white mb-5">Get Results</h3>
          <p class="text-gray-400">
            Receive your completed project with full documentation. Download files and manage everything from your dashboard.
          </p>
        </div>
      </div>
    </div>

    <div class="text-center mt-12 md:mt-16">
      <a href="/register" class="inline-block bg-brand-primary hover:bg-brand-primary/90 text-white px-6 py-3 rounded-lg font-semibold text-base transition-all duration-300 transform hover:scale-105 shadow-lg shadow-brand-primary/30 mt-6 md:mt-8">
        Get Started Now
      </a>
    </div>
  </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="py-24 md:py-32 bg-gradient-to-b from-transparent to-brand-dark/50 px-4 md:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8">
    <div class="text-center mb-20 md:mb-24">
      <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 md:mb-8">What Our Clients Say</h2>
      <p class="text-xl md:text-2xl text-gray-400 max-w-2xl mx-auto">
        Don't just take our word for it
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10">
      <!-- Testimonial 1 -->
      <div class="bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-8 md:p-10">
        <div class="flex items-center mb-8">
          <div class="flex text-yellow-400">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
          </div>
        </div>
        <p class="text-gray-300 mb-8 leading-relaxed">
          "Imhotion transformed our development workflow. The flexible pricing and project tracking made it easy to manage multiple projects simultaneously."
        </p>
        <div class="flex items-center">
          <div class="w-12 h-12 bg-brand-primary/20 rounded-full flex items-center justify-center text-white font-semibold mr-5">
            JD
          </div>
          <div>
            <div class="text-white font-semibold">John Doe</div>
            <div class="text-gray-400 text-sm">CEO, Tech Startup</div>
          </div>
        </div>
      </div>

      <!-- Testimonial 2 -->
      <div class="bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-8 md:p-10">
        <div class="flex items-center mb-8">
          <div class="flex text-yellow-400">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
          </div>
        </div>
        <p class="text-gray-300 mb-8 leading-relaxed">
          "The time tracking and project management features are outstanding. We can see exactly where our development hours are going, which helps with budgeting."
        </p>
        <div class="flex items-center">
          <div class="w-12 h-12 bg-brand-primary/20 rounded-full flex items-center justify-center text-white font-semibold mr-5">
            SM
          </div>
          <div>
            <div class="text-white font-semibold">Sarah Miller</div>
            <div class="text-gray-400 text-sm">Project Manager</div>
          </div>
        </div>
      </div>

      <!-- Testimonial 3 -->
      <div class="bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-8 md:p-10">
        <div class="flex items-center mb-8">
          <div class="flex text-yellow-400">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
          </div>
        </div>
        <p class="text-gray-300 mb-8 leading-relaxed">
          "Excellent service and support. The developers are skilled, responsive, and always deliver on time. Highly recommend!"
        </p>
        <div class="flex items-center">
          <div class="w-12 h-12 bg-brand-primary/20 rounded-full flex items-center justify-center text-white font-semibold mr-5">
            MW
          </div>
          <div>
            <div class="text-white font-semibold">Michael Wilson</div>
            <div class="text-gray-400 text-sm">Founder, Digital Agency</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Pricing Sections -->
<div id="pricing" class="from-brand-dark to-slate-900 text-white py-24 md:py-32 scroll-mt-20 px-4 md:px-6 lg:px-8">
  <div class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8">

    @foreach($categories as $category)
      @if($category->slug === 'price-per-day')
        <!-- Price per day section -->
        <div class="mb-20 md:mb-24">
          <div class="text-center mb-16 md:mb-20">
            <h2 class="text-4xl md:text-5xl font-semibold mb-6 md:mb-8">{{ $category->name }}</h2>
            <p class="text-lg text-pricing-subtitle">{{ $category->description }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            @foreach($category->items as $item)
              <div class="bg-white/5 backdrop-blur-sm border border-brand-primary-200/20 rounded-2xl p-6 transition-all duration-300 hover:border-brand-primary-200/40 hover:-translate-y-1 hover:shadow-2xl hover:shadow-brand-primary/10">
                <h3 class="text-2xl font-semibold mb-6 text-center">{{ $item->title }}</h3>
                <div class="text-center">
                  <button onclick="addToCart({{ $item->id }}, '{{ $item->title }}', {{ $item->price }}, '{{ $item->category->description ?? '' }}')" class="pricing-badge">
                    <span class="text-lg font-semibold">€{{ number_format($item->price, 0) }}</span>
                    <span class="text-sm ml-1">/{{ str_replace('per_', '', $item->price_unit) }}</span>
                    <div class="arrow">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                      </svg>
                    </div>
                  </button>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      @if($category->slug === 'our-packs')
        <!-- Our Packs section -->
        <div class="mb-20 md:mb-24">
          <div class="text-center mb-16 md:mb-20">
            <h2 class="text-4xl md:text-5xl font-semibold mb-6 md:mb-8">{{ $category->name }}</h2>
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
                  <button onclick="addToCart({{ $item->id }}, '{{ $item->title }}', {{ $item->price }}, '{{ $item->category->description ?? '' }}')" class="btn-purchase">
                    <span>Get {{ $item->title }}</span>
                    <div class="arrow">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                      </svg>
                    </div>
                  </button>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      @if($category->slug === 'addons')
        <!-- Addons section -->
        <div class="mb-20 md:mb-24">
          <div class="text-center mb-16 md:mb-20">
            <h2 class="text-4xl md:text-5xl font-semibold mb-6 md:mb-8">{{ $category->name }}</h2>
            <p class="text-lg text-pricing-subtitle">{{ $category->description }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            @foreach($category->items as $item)
              <div class="bg-white/5 backdrop-blur-sm border-2 border-dashed border-brand-primary-200/20 rounded-2xl p-6 transition-all duration-300 hover:border-brand-primary-200/40 hover:-translate-y-1 hover:shadow-2xl hover:shadow-brand-primary/10 flex flex-col justify-center items-center text-center min-h-[200px]">
                <h3 class="text-2xl font-semibold mb-4">{{ $item->title }}</h3>
                <div>
                  <button onclick="addToCart({{ $item->id }}, '{{ $item->title }}', {{ $item->price }}, '{{ $item->category->description ?? '' }}')" class="pricing-badge">
                    <span class="font-semibold">€{{ number_format($item->price, 0) }}</span>
                    <span class="text-sm ml-1">/{{ str_replace('per_', '', $item->price_unit) }}</span>
                    <div class="arrow">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                      </svg>
                    </div>
                  </button>
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
