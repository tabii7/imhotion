<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom Admin Styles with Light/Dark Mode Support */
        :root {
            --admin-bg-primary: #f8fafc;
            --admin-bg-secondary: #ffffff;
            --admin-bg-sidebar: #1e293b;
            --admin-text-primary: #1f2937;
            --admin-text-secondary: #6b7280;
            --admin-border: #e5e7eb;
            --admin-card-bg: #ffffff;
            --admin-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        [data-theme="dark"] {
            --admin-bg-primary: #0f172a;
            --admin-bg-secondary: #1e293b;
            --admin-bg-sidebar: #0f172a;
            --admin-text-primary: #f1f5f9;
            --admin-text-secondary: #94a3b8;
            --admin-border: #334155;
            --admin-card-bg: #1e293b;
            --admin-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
        }
        
        .admin-sidebar {
            background: linear-gradient(180deg, var(--admin-bg-sidebar) 0%, #0f172a 100%);
        }
        
        .admin-sidebar .nav-item {
            transition: all 0.2s ease;
        }
        
        .admin-sidebar .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(2px);
        }
        
        .admin-sidebar .nav-item.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .admin-card {
            background: var(--admin-card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--admin-border);
            box-shadow: var(--admin-shadow);
            color: var(--admin-text-primary);
        }
        
        .admin-stats-card {
            background: var(--admin-card-bg);
            border: 1px solid var(--admin-border);
            transition: all 0.3s ease;
            color: var(--admin-text-primary);
        }
        
        .admin-stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }
        
        .admin-header {
            background: var(--admin-bg-secondary);
            border-bottom: 1px solid var(--admin-border);
            backdrop-filter: blur(10px);
            color: var(--admin-text-primary);
        }
        
        .admin-main {
            background: var(--admin-bg-primary);
            min-height: 100vh;
            color: var(--admin-text-primary);
        }
        
        .main-content-area {
            width: calc(100% - 16rem); /* 16rem = 256px (sidebar width) */
            margin-left: 16rem;
        }
        
        .admin-text-primary {
            color: var(--admin-text-primary);
        }
        
        .admin-text-secondary {
            color: var(--admin-text-secondary);
        }
        
        .admin-bg-primary {
            background-color: var(--admin-bg-primary);
        }
        
        .admin-bg-secondary {
            background-color: var(--admin-bg-secondary);
        }
        
        .admin-border {
            border-color: var(--admin-border);
        }
        
        .admin-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .admin-button {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        
        .admin-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }
        
        .admin-button-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        }
        
        .admin-button-secondary:hover {
            box-shadow: 0 6px 16px rgba(107, 114, 128, 0.4);
        }
        
        .admin-button-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .admin-button-success:hover {
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }
        
        .admin-button-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .admin-button-danger:hover {
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        }
        
        /* Custom Scrollbar */
        .admin-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .admin-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .admin-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        .admin-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Loading Animation */
        .admin-loading {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .admin-sidebar.open {
                transform: translateX(0);
            }
            
            .main-content-area {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
        
        @media (min-width: 769px) {
            .admin-sidebar {
                transform: translateX(0) !important;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen admin-main flex">
        <!-- Sidebar -->
        <aside id="admin-sidebar" class="admin-sidebar w-64 fixed top-0 left-0 h-full lg:translate-x-0 transform transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
                <div class="p-4 h-full flex flex-col">
                    <!-- Logo -->
                    <div class="flex items-center mb-8">
                        <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center mr-3">
                            <i class="fas fa-cube text-white text-sm"></i>
                        </div>
                        <h2 class="text-white font-bold text-lg">Imhotion Admin</h2>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="space-y-6 flex-1 overflow-y-auto">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.dashboard') ? 'bg-white bg-opacity-10' : '' }}">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>

                        <!-- Management Section -->
                        <div>
                            <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Management</h3>
                            <div class="space-y-1">
                                <a href="{{ route('admin.users') }}" class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-300 hover:text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.users*') ? 'bg-white bg-opacity-10 text-white' : '' }}">
                                    <i class="fas fa-users mr-3"></i>
                                    Users
                                    @php
                                        $userCount = \App\Models\User::count();
                                    @endphp
                                    @if($userCount > 0)
                                    <span class="ml-auto bg-blue-500 text-white text-xs rounded-full px-2 py-0.5">{{ $userCount }}</span>
                @endif
                                </a>
                            </div>
                        </div>

                        <!-- Team & Projects Section -->
                        <div>
                            <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Team & Projects</h3>
                            <div class="space-y-1">
                                <a href="{{ route('admin.developers.index') }}" class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-300 hover:text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.developers*') ? 'bg-white bg-opacity-10 text-white' : '' }}">
                                    <i class="fas fa-users mr-3"></i>
                                    Developers
                                </a>
                                <a href="{{ route('admin.projects') }}" class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-300 hover:text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.projects*') ? 'bg-white bg-opacity-10 text-white' : '' }}">
                                    <i class="fas fa-folder mr-3"></i>
                                    Projects
                                </a>
                            </div>
                        </div>

                        <!-- Business Section -->
                        <div>
                            <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Business</h3>
                            <div class="space-y-1">
                                <a href="{{ route('admin.pricing') }}" class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-300 hover:text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.pricing*') ? 'bg-white bg-opacity-10 text-white' : '' }}">
                                    <i class="fas fa-dollar-sign mr-3"></i>
                                    Pricing
                                </a>
                                <a href="{{ route('admin.reports') }}" class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-300 hover:text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.reports*') ? 'bg-white bg-opacity-10 text-white' : '' }}">
                                    <i class="fas fa-chart-bar mr-3"></i>
                                    Reports
                                </a>
                            </div>
                        </div>

                        <!-- System Section -->
                        <div>
                            <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">System</h3>
                            <div class="space-y-1">
                                <a href="{{ route('admin.settings') }}" class="nav-item group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-300 hover:text-white hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.settings*') ? 'bg-white bg-opacity-10 text-white' : '' }}">
                                    <i class="fas fa-cog mr-3"></i>
                                    Settings
                                </a>
                            </div>
                        </div>
                        
                        <!-- Logout -->
                        <div class="pt-4 border-t border-gray-600">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-item group flex items-center w-full px-3 py-2 text-sm font-medium rounded-md text-gray-300 hover:text-white hover:bg-white hover:bg-opacity-10">
                                    <i class="fas fa-sign-out-alt mr-3"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </nav>
                    </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col ml-64 main-content-area min-h-screen">
                <!-- Admin Header -->
                <header class="admin-header shadow-sm sticky top-0 z-40">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between items-center py-4">
                            <div class="flex items-center">
                                <button id="sidebar-toggle" class="lg:hidden mr-4 p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                                    <i class="fas fa-bars"></i>
                                </button>
                            </div>

                            <div class="flex items-center space-x-4">
                                <!-- Current Time and Date -->
                                <div class="text-right">
                                    <div class="text-sm font-medium admin-text-primary" id="current-time">{{ now()->format('H:i') }}</div>
                                    <div class="text-xs admin-text-secondary" id="current-date">{{ now()->format('l, F j, Y') }}</div>
                                </div>
                                
                                <!-- Theme Toggle -->
                                <button id="theme-toggle" class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors">
                                    <i id="theme-icon" class="fas fa-sun"></i>
                                </button>
                                
                                <!-- Notifications -->
                                <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md">
                                    <i class="fas fa-bell"></i>
                                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
                                </button>

                                <!-- User Menu -->
                                @auth
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <span class="sr-only">Open user menu</span>
                                        <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                        </div>
                                        <span class="ml-2 text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                                        <i class="fas fa-chevron-down ml-1 text-gray-500"></i>
                                    </button>
                                    
                                    <!-- Dropdown Menu -->
                                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-user mr-2"></i>Profile
                                        </a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-cog mr-2"></i>Settings
                                        </a>
                                        <hr class="my-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                            </button>
                                    </form>
                                    </div>
                                </div>
                                @endauth
                                </div>
                            </div>
                        </div>
                    </header>

                <!-- Main Content -->
                <main class="flex-1 p-8 w-full">
                    @yield('content')
                </main>
            </div>
    </div>

    <!-- JavaScript for sidebar toggle and theme switching -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('admin-sidebar');
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            
            // Sidebar toggle functionality
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                        sidebar.classList.remove('open');
                    }
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('open');
                }
            });
            
            // Theme toggle functionality
            if (themeToggle && themeIcon) {
                // Get saved theme or default to light
                const savedTheme = localStorage.getItem('admin-theme') || 'light';
                document.documentElement.setAttribute('data-theme', savedTheme);
                updateThemeIcon(savedTheme);
                
                themeToggle.addEventListener('click', function() {
                    const currentTheme = document.documentElement.getAttribute('data-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    
                    document.documentElement.setAttribute('data-theme', newTheme);
                    localStorage.setItem('admin-theme', newTheme);
                    updateThemeIcon(newTheme);
                });
            }
            
            function updateThemeIcon(theme) {
                if (themeIcon) {
                    themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
                }
            }
        });
    </script>
</body>
</html>