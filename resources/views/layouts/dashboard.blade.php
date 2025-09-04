<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Dashboard') — Imhotion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="/css/brand.css" rel="stylesheet">
    <link href="/css/dashboard-theme.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <style>
        :root {
            --bg: #f8fafc;
            --panel: #ffffff;
            --muted: #64748b;
            --text: #0f172a;
            --brand: #3b82f6;
            --ok: #10b981;
            --warn: #f59e0b;
            --bad: #ef4444;
            --line: #e2e8f0;
            --sidebar: #0a1428;
            --sidebar-text: #8fa8cc;
            --sidebar-active: #001f4c;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: var(--font-sans), system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.5;
        }

        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--sidebar);
            color: var(--sidebar-text);
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid #334155;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: white;
        }

        .sidebar-logo img {
            width: 32px;
            height: 32px;
        }

        .sidebar-logo span {
            font-size: 18px;
            font-weight: 700;
        }

        .sidebar-nav {
            padding: 20px 16px;
        }

        .nav-group {
            margin-bottom: 30px;
        }

        .nav-group-title {
            font-size: 11px;
            color: #7fa7e1;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 12px;
            padding: 0 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--sidebar-text);
            transition: all 0.3s ease;
            margin-bottom: 6px;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .nav-link:hover {
            background: rgba(127, 167, 225, 0.1);
            color: #ffffff;
            border: 1px solid rgba(127, 167, 225, 0.3);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: var(--sidebar-active);
            color: #ffffff;
            border: 1px solid #7fa7e1;
            box-shadow: 0 2px 8px rgba(0, 31, 76, 0.3);
        }

        .nav-link svg {
            width: 22px;
            height: 22px;
            flex-shrink: 0;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            background: var(--bg);
        }

        .main-header {
            background: white;
            border-bottom: 1px solid var(--line);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .main-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--text);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--brand);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .main-body {
            padding: 20px;
        }

        .btn {
            border: 0;
            background: var(--brand);
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-body {
                padding: 16px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="/" class="sidebar-logo">
                    <img src="/images/imhotion-blue.png" alt="Imhotion">
                    <span>Imhotion</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-group">
                    <div class="nav-group-title">Dashboard</div>
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Home
                    </a>
                    <a href="{{ route('dashboard.services') }}" class="nav-link {{ request()->routeIs('dashboard.services') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Services
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Account</div>
                    <a href="{{ route('dashboard.transactions') }}" class="nav-link {{ request()->routeIs('dashboard.transactions') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Transactions
                    </a>
                    <a href="{{ route('dashboard.profile') }}" class="nav-link {{ request()->routeIs('dashboard.profile') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <div class="user-menu">
                    <span>{{ Auth::user()->name }}</span>
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    @if(isset($pricingItems) && $pricingItems->count() > 0)
                        <form method="POST" action="{{ route('dashboard.add-to-cart') }}" style="margin:0 8px 0 0;">
                            @csrf
                            <input type="hidden" name="pricing_item_id" value="{{ $pricingItems->first()->id }}">
                            <button type="submit" class="btn btn-sm" title="Add test product to cart">Add test product</button>
                        </form>
                        @if($pricingItems->count() > 1)
                            <form method="POST" action="{{ route('dashboard.add-to-cart') }}" style="margin:0 8px 0 0;">
                                @csrf
                                <input type="hidden" name="pricing_item_id" value="{{ $pricingItems->skip(1)->first()->id }}">
                                <button type="submit" class="btn btn-sm" title="Add second test product">Add test product 2</button>
                            </form>
                        @else
                            {{-- duplicate first as a second test button when only one product exists --}}
                            <form method="POST" action="{{ route('dashboard.add-to-cart') }}" style="margin:0 8px 0 0;">
                                @csrf
                                <input type="hidden" name="pricing_item_id" value="{{ $pricingItems->first()->id }}">
                                <button type="submit" class="btn btn-sm" title="Add test product duplicate">Add test product 2</button>
                            </form>
                        @endif
                    @endif
                    <a href="{{ route('logout') }}" class="btn btn-sm"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </header>

            <div class="main-body">
                @if(session('success'))
                    <div style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @yield('scripts')
</body>
</html>
