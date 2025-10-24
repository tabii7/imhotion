<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Imhotion') }} - Developer Dashboard</title>
    
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
    transition: none !important;
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

.sidebar.collapsed ~ .content-area {
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

.user-avatar-small {
    width: 32px;
    height: 32px;
    background: #667eea;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 14px;
}

.user-info-small {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.user-name-small {
    color: #ffffff;
    font-size: 14px;
    font-weight: 500;
}

.user-role-small {
    color: #8b8b8b;
    font-size: 12px;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: #2a2a2a;
    border: 1px solid #3a3a3a;
    border-radius: 8px;
    padding: 8px 0;
    min-width: 200px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    display: none;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: #ffffff;
    text-decoration: none;
    transition: background 0.3s ease;
}

.dropdown-item:hover {
    background: #3a3a3a;
}

.dropdown-item i {
    width: 16px;
    font-size: 14px;
}

.content-area {
    padding: 32px;
    background: #0f0f0f;
    margin-left: 280px;
    min-height: calc(100vh - 80px);
}
</style>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Header -->
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <div class="sidebar-logo">T</div>
                <div class="sidebar-title">Team</div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <a href="{{ route('developer.dashboard') }}" class="nav-item {{ request()->routeIs('developer.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('developer.projects') }}" class="nav-item {{ request()->routeIs('developer.projects*') ? 'active' : '' }}">
                <i class="fas fa-tasks"></i>
                <span>Projects</span>
            </a>
            <a href="{{ route('developer.time-logs') }}" class="nav-item {{ request()->routeIs('developer.time-logs*') ? 'active' : '' }}">
                <i class="fas fa-clock"></i>
                <span>Time Logs</span>
            </a>
            <a href="{{ route('developer.profile') }}" class="nav-item {{ request()->routeIs('developer.profile*') ? 'active' : '' }}">
                <i class="fas fa-user-edit"></i>
                <span>Profile</span>
            </a>
        </nav>

        <!-- Footer -->
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ Auth::user()->experience_level ?? 'Developer' }}</div>
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
                <h1 class="header-title">@yield('page-title', 'Dashboard')</h1>
                <p class="header-subtitle">Welcome back, {{ Auth::user()->name }}!</p>
            </div>
            <div class="header-right">
                <div class="text-sm text-gray-400 mr-4">{{ now()->format('M d, Y \a\t g:i A') }}</div>
                
                <!-- User Menu -->
                <div class="user-menu">
                    <button class="user-menu-btn" onclick="toggleUserMenu()">
                        <div class="user-avatar-small">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <div class="user-info-small">
                            <div class="user-name-small">{{ Auth::user()->name }}</div>
                            <div class="user-role-small">{{ Auth::user()->experience_level ?? 'Developer' }}</div>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    
                    <div class="dropdown-menu" id="userDropdown">
                        <a href="{{ route('developer.profile') }}" class="dropdown-item">
                            <i class="fas fa-user-edit"></i>
                            <span>Profile Settings</span>
                        </a>
                        <a href="{{ route('developer.dashboard') }}" class="dropdown-item">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('developer.projects') }}" class="dropdown-item">
                            <i class="fas fa-tasks"></i>
                            <span>Projects</span>
                        </a>
                        <a href="{{ route('developer.time-logs') }}" class="dropdown-item">
                            <i class="fas fa-clock"></i>
                            <span>Time Logs</span>
                        </a>
                        <hr style="border: none; border-top: 1px solid #3a3a3a; margin: 8px 0;">
                        <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
                
                <!-- Logout Form -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>
</div>

<script>
// Sidebar toggle functionality
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.content-area');
    
    if (sidebar.classList.contains('collapsed')) {
        sidebar.classList.remove('collapsed');
        sidebar.style.width = '280px';
        mainContent.style.marginLeft = '280px';
    } else {
        sidebar.classList.add('collapsed');
        sidebar.style.width = '80px';
        mainContent.style.marginLeft = '80px';
    }
}

// User menu toggle functionality
function toggleUserMenu() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
}

// Close user menu when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.querySelector('.user-menu');
    const dropdown = document.getElementById('userDropdown');
    
    if (!userMenu.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

// Initialize sidebar on page load - simplified to prevent blinking
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.content-area');
    
    // Simple initialization without excessive DOM manipulation
    if (sidebar) {
        sidebar.style.display = 'flex';
        sidebar.style.visibility = 'visible';
        sidebar.style.opacity = '1';
    }
    
    if (mainContent) {
        mainContent.style.marginLeft = '280px';
    }
});

function updateAvailability() {
    fetch('{{ route("developer.update-availability") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            is_available: !{{ Auth::user()->is_available ? 'true' : 'false' }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
</body>
</html>
