<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Imhotion') }} - Administrator Dashboard</title>
    
    <!-- Favicon and App Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/jpeg" sizes="32x32" href="{{ asset('images/imhotion.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/imhotion.jpg') }}">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<style>
/* Dark theme dashboard with collapsible sidebar */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    margin: 0 !important;
    padding: 0 !important;
    background: #0f0f0f !important;
    overflow-x: hidden;
}

.dashboard-container {
    background: #0f0f0f;
    min-height: 100vh;
    display: flex;
    width: 100vw;
    position: relative;
}

.sidebar {
    width: 280px !important;
    background: #1a1a1a !important;
    border-right: 1px solid #2a2a2a !important;
    transition: all 0.3s ease !important;
    position: fixed !important;
    left: 0 !important;
    top: 0 !important;
    height: 100vh !important;
    z-index: 9999 !important;
    display: flex !important;
    flex-direction: column !important;
}

.sidebar.collapsed {
    width: 80px !important;
}

.sidebar-header {
    padding: 24px 20px;
    border-bottom: 1px solid #2a2a2a;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.sidebar-logo {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 18px;
}

.sidebar-title {
    color: #ffffff;
    font-size: 20px;
    font-weight: 600;
    transition: opacity 0.3s ease;
}

.sidebar.collapsed .sidebar-title {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

.sidebar-nav {
    padding: 20px 0;
    flex: 1;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #8b8b8b;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.nav-item:hover {
    background: #2a2a2a;
    color: #ffffff;
}

.nav-item.active {
    background: #2a2a2a;
    color: #ffffff;
    border-left-color: #667eea;
}

.nav-item i {
    width: 20px;
    font-size: 16px;
    margin-right: 12px;
}

.nav-item span {
    transition: opacity 0.3s ease;
}

.sidebar.collapsed .nav-item span {
    opacity: 0;
    width: 0;
    overflow: hidden;
    display: none;
}

.sidebar.collapsed .sidebar-title {
    opacity: 0;
    width: 0;
    overflow: hidden;
    display: none;
}

.sidebar.collapsed .user-info {
    opacity: 0;
    width: 0;
    overflow: hidden;
    display: none;
}

.sidebar.collapsed .sidebar-header {
    padding: 24px 10px;
    text-align: center;
}

.sidebar.collapsed .sidebar-footer {
    padding: 20px 10px;
    text-align: center;
}

.sidebar.collapsed .nav-item {
    padding: 12px 10px;
    justify-content: center;
}

.sidebar.collapsed .nav-item i {
    margin-right: 0;
}

.sidebar-footer {
    padding: 20px;
    border-top: 1px solid #2a2a2a;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    background: #667eea;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

.user-info {
    transition: opacity 0.3s ease;
}

.sidebar.collapsed .user-info {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

.user-name {
    color: #ffffff;
    font-weight: 500;
    font-size: 14px;
}

.user-role {
    color: #8b8b8b;
    font-size: 12px;
}

.toggle-btn {
    background: #2a2a2a;
    border: 1px solid #3a3a3a;
    color: #8b8b8b;
    padding: 8px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.toggle-btn:hover {
    background: #3a3a3a;
    color: #ffffff;
}

.main-content {
    flex: 1;
    margin-left: 280px;
    transition: margin-left 0.3s ease;
    background: #0f0f0f;
}

.sidebar.collapsed + .main-content {
    margin-left: 80px;
}

.top-header {
    background: #1a1a1a;
    border-bottom: 1px solid #2a2a2a;
    padding: 20px 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-left {
    flex: 1;
}

.header-title {
    color: #ffffff;
    font-size: 24px;
    font-weight: 600;
    margin: 0;
}

.header-subtitle {
    color: #8b8b8b;
    font-size: 14px;
    margin: 4px 0 0 0;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 16px;
}

.user-menu {
    position: relative;
    display: inline-block;
}

.user-menu-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #2a2a2a;
    border: 1px solid #3a3a3a;
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.user-menu-btn:hover {
    background: #3a3a3a;
}

.user-menu-btn i {
    font-size: 12px;
}

.user-menu-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 8px;
    min-width: 200px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    z-index: 9999;
    margin-top: 5px;
}

.user-menu-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    color: #8b8b8b;
    text-decoration: none;
    transition: all 0.3s ease;
    border-bottom: 1px solid #2a2a2a;
}

.user-menu-item:last-child {
    border-bottom: none;
}

.user-menu-item:hover {
    background: #2a2a2a;
    color: #ffffff;
}

.user-menu-item i {
    width: 16px;
    font-size: 14px;
}

.content-area {
    padding: 32px;
    min-height: calc(100vh - 80px);
}

/* Mobile responsive */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.mobile-open {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .mobile-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9998;
        display: none;
    }
    
    .mobile-overlay.show {
        display: block;
    }
    
    .mobile-menu-btn {
        display: block;
        background: #2a2a2a;
        border: 1px solid #3a3a3a;
        color: #ffffff;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        margin-right: 16px;
    }
}

@media (min-width: 769px) {
    .mobile-menu-btn {
        display: none;
    }
}

/* Content styling */
.stats-card {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    padding: 24px;
    transition: all 0.3s ease;
}

.stats-card:hover {
    border-color: #3a3a3a;
    transform: translateY(-2px);
}

.stats-number {
    font-size: 32px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 8px;
}

.stats-label {
    color: #8b8b8b;
    font-size: 14px;
    font-weight: 500;
}

.project-card {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 16px;
    transition: all 0.3s ease;
}

.project-card:hover {
    border-color: #3a3a3a;
    transform: translateY(-1px);
}

.project-title {
    color: #ffffff;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
}

.project-description {
    color: #8b8b8b;
    font-size: 14px;
    margin-bottom: 16px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-pending {
    background: #3b82f6;
    color: #ffffff;
}

.status-in-progress {
    background: #10b981;
    color: #ffffff;
}

.status-completed {
    background: #059669;
    color: #ffffff;
}

.status-on-hold {
    background: #f59e0b;
    color: #ffffff;
}

.status-cancelled {
    background: #ef4444;
    color: #ffffff;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #667eea;
    color: #ffffff;
}

.btn-primary:hover {
    background: #5a6fd8;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #2a2a2a;
    color: #ffffff;
    border: 1px solid #3a3a3a;
}

.btn-secondary:hover {
    background: #3a3a3a;
    transform: translateY(-1px);
}

.btn-success {
    background: #10b981;
    color: #ffffff;
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-1px);
}

.btn-danger {
    background: #ef4444;
    color: #ffffff;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-1px);
}
</style>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Header -->
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <div class="sidebar-logo">A</div>
                <div class="sidebar-title">Administrator</div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <a href="{{ route('administrator.dashboard') }}" class="nav-item {{ request()->routeIs('administrator.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('administrator.projects') }}" class="nav-item {{ request()->routeIs('administrator.projects*') ? 'active' : '' }}">
                <i class="fas fa-project-diagram"></i>
                <span>Projects</span>
            </a>
            <a href="{{ route('administrator.developers') }}" class="nav-item {{ request()->routeIs('administrator.developers*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Developers</span>
            </a>
            <a href="{{ route('administrator.reports') }}" class="nav-item {{ request()->routeIs('administrator.reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </nav>

        <!-- Footer -->
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
            <button class="toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="header-left">
                <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="header-title">@yield('page-title', 'Administrator Dashboard')</h1>
                <p class="header-subtitle">@yield('page-subtitle', 'Manage projects, developers, and system performance')</p>
            </div>
            <div class="header-right">
                <div class="user-menu">
                    <button class="user-menu-btn" id="userMenuBtn">
                        <div class="user-avatar" style="width: 32px; height: 32px; font-size: 12px;">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="user-menu-dropdown" id="userMenu" style="display: none;">
                        <a href="#" class="user-menu-item">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </a>
                        <a href="#" class="user-menu-item">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="user-menu-item w-full text-left">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="closeMobileSidebar()"></div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
}

function toggleMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobileOverlay');
    
    sidebar.classList.toggle('mobile-open');
    overlay.classList.toggle('show');
}

function closeMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobileOverlay');
    
    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('show');
}

// Simple dropdown toggle function
function toggleUserMenu() {
    const menu = document.getElementById('userMenu');
    console.log('Toggle clicked, current display:', menu.style.display);
    
    if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.display = 'block';
        menu.style.opacity = '1';
        menu.style.visibility = 'visible';
        console.log('Showing dropdown');
    } else {
        menu.style.display = 'none';
        console.log('Hiding dropdown');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.getElementById('userMenu');
    const userMenuBtn = document.getElementById('userMenuBtn');
    
    if (!userMenuBtn.contains(event.target) && !userMenu.contains(event.target)) {
        userMenu.style.display = 'none';
    }
});

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing user menu...');
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userMenu = document.getElementById('userMenu');
    
    if (userMenuBtn && userMenu) {
        console.log('Adding click listener to button');
        userMenuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Button clicked');
            toggleUserMenu();
        });
    } else {
        console.log('Elements not found:', { userMenuBtn, userMenu });
    }
});

// Close mobile sidebar on window resize
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        closeMobileSidebar();
    }
});
</script>
</body>
</html>