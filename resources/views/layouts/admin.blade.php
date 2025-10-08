<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Imhotion') }} - Admin Panel</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/jpeg" sizes="32x32" href="{{ asset('images/imhotion.jpg') }}">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Filament Brand Styles -->
    @include('filament.admin.brand-styles')
    <link rel="stylesheet" href="{{ asset('resources/css/filament/admin/theme.css') }}">
</head>
<body class="filament-app" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="fi-sidebar fixed inset-y-0 left-0 z-50 w-64 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0" 
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 border-b border-slate-700">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/imhotion.jpg') }}" alt="Imhotion Logo" class="fi-brand-logo">
                    <div>
                        <h1 class="fi-brand">Imhotion</h1>
                        <p class="text-slate-400 text-xs">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-8 px-4">
                <div class="fi-sidebar-nav space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="fi-sidebar-nav-item flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'fi-sidebar-nav-item-active' : '' }}">
                        <i class="fi-sidebar-nav-item-icon fas fa-tachometer-alt mr-3 text-lg"></i>
                        <span class="fi-sidebar-nav-item-label">Dashboard</span>
                    </a>

                    <!-- Reports -->
                    <a href="{{ route('admin.custom-reports.index') }}" 
                       class="fi-sidebar-nav-item flex items-center px-4 py-3 text-sm font-medium">
                        <i class="fi-sidebar-nav-item-icon fas fa-chart-bar mr-3 text-lg"></i>
                        <span class="fi-sidebar-nav-item-label">Reports</span>
                    </a>

                    <!-- Projects -->
                    <a href="{{ route('admin.custom-projects.index') }}" 
                       class="fi-sidebar-nav-item flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.custom-projects*') ? 'fi-sidebar-nav-item-active' : '' }}">
                        <i class="fi-sidebar-nav-item-icon fas fa-project-diagram mr-3 text-lg"></i>
                        <span class="fi-sidebar-nav-item-label">Projects</span>
                    </a>

                    <!-- Developers -->
                    <a href="{{ route('admin.developers.index') }}" 
                       class="fi-sidebar-nav-item flex items-center px-4 py-3 text-sm font-medium">
                        <i class="fi-sidebar-nav-item-icon fas fa-users mr-3 text-lg"></i>
                        <span class="fi-sidebar-nav-item-label">Developers</span>
                    </a>

                    <!-- Users -->
                    <a href="{{ route('filament.admin.resources.users.index') }}" 
                       class="fi-sidebar-nav-item flex items-center px-4 py-3 text-sm font-medium">
                        <i class="fi-sidebar-nav-item-icon fas fa-user-cog mr-3 text-lg"></i>
                        <span class="fi-sidebar-nav-item-label">Users</span>
                    </a>

                    <!-- Settings -->
                    <a href="#" 
                       class="fi-sidebar-nav-item flex items-center px-4 py-3 text-sm font-medium">
                        <i class="fi-sidebar-nav-item-icon fas fa-cog mr-3 text-lg"></i>
                        <span class="fi-sidebar-nav-item-label">Settings</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:ml-64">
            <!-- Top Navigation -->
            <header class="fi-header bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        
                        <h1 class="fi-header-heading text-2xl font-semibold text-gray-900 dark:text-white ml-4 lg:ml-0">
                            @yield('page-title', 'Admin Panel')
                        </h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="fi-header-action p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full">
                            <i class="fas fa-bell text-lg"></i>
                        </button>

                        <!-- User Menu -->
                        <div class="fi-header-user-menu flex items-center space-x-3">
                            <div class="flex items-center space-x-3">
                                <div class="fi-header-user-avatar w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="hidden md:block">
                                    <p class="fi-header-user-name text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                    <p class="fi-header-user-email text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            
                            <!-- Logout -->
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="fi-header-logout text-gray-400 hover:text-gray-500 text-sm">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="fi-main flex-1 p-6">
                @if(session('success'))
                    <div class="fi-alert fi-alert-success bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="fi-alert fi-alert-danger bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
         @click="sidebarOpen = false">
    </div>
</body>
</html>