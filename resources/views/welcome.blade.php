<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>IMHOTION — Client Access</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="m-0 text-blue-100 font-sans bg-gradient-radial from-blue-600 via-blue-700 to-blue-900 min-h-screen flex flex-col">
  <!-- Navigation -->
  <header class="sticky top-0 z-50 bg-gradient-to-b from-slate-800/65 via-slate-800/35 to-transparent backdrop-blur-lg border-b border-white/10">
    <nav class="flex items-center gap-5 px-4 sm:px-8 max-w-7xl mx-auto">
      <a href="/" class="flex items-center gap-3 flex-shrink-0 text-white no-underline font-extrabold tracking-wider">
        <img src="/images/imhotion.jpg" alt="Imhotion" class="w-9 h-9 object-cover rounded-lg shadow-2xl">
        <span class="text-sm opacity-95">IMHOTION</span>
      </a>
      
      <div class="ml-auto mr-auto flex gap-6">
        <a href="/" class="text-white/85 no-underline font-semibold text-sm hover:text-white transition-colors">Home</a>
        <a href="/#pricing" class="text-white/85 no-underline font-semibold text-sm hover:text-white transition-colors">Pricing</a>
        <a href="/about" class="text-white/85 no-underline font-semibold text-sm hover:text-white transition-colors">About</a>
        <a href="/contact" class="text-white/85 no-underline font-semibold text-sm hover:text-white transition-colors">Contact</a>
      </div>
      
      <div class="flex gap-2.5 items-center">
        <a href="/login" class="appearance-none border-0 cursor-pointer px-4 py-2.5 rounded-lg font-bold tracking-wide bg-transparent text-white border border-white/25 hover:bg-white/10 transition-colors">
          Login
        </a>
        <a href="/register" class="appearance-none border-0 cursor-pointer px-4 py-2.5 rounded-lg font-bold tracking-wide bg-gradient-to-br from-cyan-400 to-brand-primary text-white shadow-lg shadow-blue-500/45 hover:brightness-105 transition-all">
          Sign Up
        </a>
      </div>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="grid place-items-center px-5 py-10 sm:py-20 relative overflow-hidden">
    <div class="text-center max-w-4xl">
      <img src="/images/imhotion.jpg" alt="Imhotion Logo" 
           class="w-full max-w-md mx-auto aspect-[2/1] object-cover object-center rounded-2xl shadow-2xl shadow-black/45 mb-4 animate-float">
      
      <h1 class="mt-1 text-3xl sm:text-4xl lg:text-5xl font-black tracking-wide text-white">
        Welcome to IMHOTION
      </h1>
      
      <p class="mt-3 mx-auto mb-8 text-slate-300 text-sm sm:text-base lg:text-lg max-w-3xl">
        Your gateway to professional digital solutions. Access your projects, manage your account, and explore our services.
      </p>

      <!-- Login Card -->
      <div class="mt-4 mx-auto w-full max-w-md bg-gradient-to-b from-slate-800/75 to-slate-800/55 border border-white/10 rounded-2xl p-6 shadow-2xl backdrop-blur-lg">
        <h3 class="m-0 mb-3 text-center text-lg text-blue-100 tracking-wide">Client Login</h3>
        
        @if($errors->any())
          <div class="bg-red-900/50 border border-red-400 text-red-200 p-3 rounded-lg mb-3 text-sm">
            @foreach($errors->all() as $error)
              <div>{{ $error }}</div>
            @endforeach
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="grid gap-3">
          @csrf
          
          <div class="grid gap-2">
            <input type="email" name="email" placeholder="Email address" required value="{{ old('email') }}"
                   class="w-full px-4 py-3 rounded-xl border border-white/20 bg-white/10 text-white outline-none placeholder-blue-200/60">
          </div>
          
          <div class="grid gap-2">
            <input type="password" name="password" placeholder="Password" required
                   class="w-full px-4 py-3 rounded-xl border border-white/20 bg-white/10 text-white outline-none placeholder-blue-200/60">
          </div>
          
          <div class="flex gap-2 items-center text-blue-200/75 text-sm">
            <input type="checkbox" name="remember" id="remember" class="w-4 h-4">
            <label for="remember">Remember me</label>
          </div>
          
          <button type="submit" 
                  class="w-full bg-gradient-to-br from-brand-primary to-blue-600 text-white py-3 px-4 rounded-xl font-bold tracking-wide shadow-lg shadow-blue-500/45 hover:brightness-105 transition-all">
            Sign In
          </button>
        </form>
        
        <div class="text-center text-blue-200/75 text-sm mt-3">
          <a href="/register" class="text-cyan-300 hover:text-cyan-200 transition-colors">Don't have an account? Sign up</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Wave Transition -->
  <div class="relative w-full mt-15">
    <div class="relative w-full h-35 overflow-hidden leading-none">
      <svg class="relative block w-full h-full" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-white"></path>
      </svg>
    </div>
  </div>

  <!-- White Section -->
  <section class="bg-white text-gray-900 py-16">
    <div class="max-w-6xl mx-auto px-4 text-center">
      <h2 class="text-3xl font-bold mb-8">Why Choose IMHOTION?</h2>
      
      <div class="grid md:grid-cols-3 gap-8">
        <div class="p-6">
          <div class="w-16 h-16 bg-brand-primary rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-3">Fast Delivery</h3>
          <p class="text-gray-600">Quick turnaround times without compromising quality.</p>
        </div>
        
        <div class="p-6">
          <div class="w-16 h-16 bg-brand-primary rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-3">Quality Assurance</h3>
          <p class="text-gray-600">Every project is thoroughly tested and reviewed.</p>
        </div>
        
        <div class="p-6">
          <div class="w-16 h-16 bg-brand-primary rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-3">24/7 Support</h3>
          <p class="text-gray-600">Round-the-clock assistance for all your needs.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-900 text-white py-8">
    <div class="max-w-6xl mx-auto px-4 text-center">
      <div class="flex flex-col md:flex-row justify-center gap-8 mb-4">
        <a href="#" class="text-white hover:text-brand-primary-200 transition-colors">Privacy Policy</a>
        <a href="#" class="text-white hover:text-brand-primary-200 transition-colors">Terms & Conditions</a>
        <a href="#" class="text-white hover:text-brand-primary-200 transition-colors">Contact</a>
        <a href="#" class="text-white hover:text-brand-primary-200 transition-colors">About</a>
      </div>
      <div class="text-sm text-gray-400">
        © {{ date('Y') }} Imhotion. All rights reserved.
      </div>
    </div>
  </footer>

  <style>
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }
    .animate-float {
      animation: float 6s ease-in-out infinite;
    }
    .bg-gradient-radial {
      background: radial-gradient(1200px 800px at 10% -10%, #1b49ff 0%, #0f33db 45%, #0b1f9f 100%);
    }
  </style>
</body>
</html>