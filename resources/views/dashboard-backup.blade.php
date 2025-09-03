<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard — Imhotion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="/css/brand.css" rel="stylesheet">
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
            --sidebar: #1e293b;
            --sidebar-text: #cbd5e1;
            --sidebar-active: #3b82f6;
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
            padding: 16px;
        }

        .nav-group {
            margin-bottom: 24px;
        }

        .nav-group-title {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
            padding: 0 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--sidebar-text);
            transition: all 0.2s;
            margin-bottom: 4px;
        }

        .nav-link:hover {
            background: rgba(59, 130, 246, 0.1);
            color: white;
        }

        .nav-link.active {
            background: var(--sidebar-active);
            color: white;
        }

        .nav-link svg {
            width: 20px;
            height: 20px;
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
            padding: 32px;
        }

        .grid {
            display: grid;
            gap: 24px;
            margin-bottom: 32px;
        }

        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            margin: 0 0 8px;
            font-size: 14px;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .card .big {
            font-size: 32px;
            font-weight: 700;
            color: var(--text);
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

        .btn-success {
            background: var(--ok);
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--line);
            text-align: left;
        }

        th {
            color: var(--muted);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            background: #f8fafc;
        }

        tr:hover {
            background: #f8fafc;
        }

        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-completed {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .status-failed {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .cart-section {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
        }

        .cart-section .cart-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .cart-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .payment-methods {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .payment-methods img {
            height: 24px;
            opacity: 0.8;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--muted);
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            opacity: 0.5;
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

            .grid-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
                    <div class="nav-group-title">Main</div>
                    <a href="#overview" onclick="showSection('overview')" id="nav-overview" class="nav-link active">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        Overview
                    </a>
                    <a href="#services" onclick="showSection('services')" id="nav-services" class="nav-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Services
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Account</div>
                    <a href="#transactions" onclick="showSection('transactions')" id="nav-transactions" class="nav-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Transactions
                    </a>
                    <a href="#profile" onclick="showSection('profile')" id="nav-profile" class="nav-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">External</div>
                    <a href="/client" class="nav-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Client Area
                    </a>
                    <a href="/" class="nav-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Homepage
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>Dashboard</h1>
                <div class="user-menu">
                    <span>{{ Auth::user()->name }}</span>
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
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

                <!-- Overview Section -->
                <div id="section-overview" class="section active">

                    <!-- Cart Section -->
                    @if(session('selected_plan_for_payment'))
                        @php
                            $selectedPlan = $pricingItems->find(session('selected_plan_for_payment'));
                        @endphp
                        @if($selectedPlan)
                            <div class="cart-sectioExplore Client Arean">
                                <div class="cart-header">
                                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z"/>
                                    </svg>
                                    <h2 style="margin: 0; font-size: 20px;">Your Cart</h2>
                                </div>

                                <div class="cart-item">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                        <div>
                                            <h3 style="margin: 0; color: white; font-size: 16px;">{{ $selectedPlan->title }}</h3>
                                            <p style="margin: 4px 0 0; opacity: 0.8; font-size: 14px;">{{ $selectedPlan->category->title }}</p>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="font-size: 24px; font-weight: 700;">€{{ number_format($selectedPlan->price, 0) }}</div>
                                            <div style="opacity: 0.8; font-size: 12px;">per {{ str_replace('per_', '', $selectedPlan->price_unit) }}</div>
                                        </div>
                                    </div>

                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div class="payment-methods">
                                            <span style="font-size: 12px; opacity: 0.8;">Secure payment via</span>
                                            <img src="https://mollie.com/img/payment-methods/ideal.svg" alt="iDEAL">
                                            <img src="https://mollie.com/img/payment-methods/creditcard.svg" alt="Credit Card">
                                            <img src="https://mollie.com/img/payment-methods/paypal.svg" alt="PayPal">
                                        </div>

                                        <form method="POST" action="{{ route('dashboard.add-to-cart') }}">
                                            @csrf
                                            <input type="hidden" name="pricing_item_id" value="{{ session('selected_plan_for_payment') }}">
                                            <button type="submit" class="btn btn-success">
                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                </svg>
                                                Checkout Now
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Client Area - Exact Match to Reference -->
                    <div style="background: #0a1428; border-radius: 12px; padding: 40px; color: #ffffff;">
                        <style>
                            .exact-client-area {
                                color: #ffffff;
                                font-family: system-ui, -apple-system, sans-serif;
                            }
                            .exact-stats-grid {
                                display: grid;
                                grid-template-columns: repeat(3, 1fr);
                                gap: 12px;
                                margin: 0 auto 40px auto;
                                max-width: 500px;
                            }
                            .exact-stat-card {
                                background: #001f4c;
                                border: 1px solid #7fa7e1;
                                border-radius: 12px;
                                padding: 16px 12px;
                                text-align: center;
                                min-height: 70px;
                                display: flex;
                                flex-direction: column;
                                justify-content: center;
                                transition: transform 0.2s ease, background-color 0.2s ease;
                                cursor: pointer;
                            }
                            .exact-stat-card:hover {
                                transform: scale(1.05);
                                background: #002a66;
                            }
                            .exact-stat-title {
                                color: #ffffff;
                                font-size: 13px;
                                font-weight: 500;
                                margin-bottom: 8px;
                                letter-spacing: 0.3px;
                            }
                            .exact-stat-value {
                                color: #ffffff;
                                font-size: 14px;
                                font-weight: 600;
                                padding: 4px 12px;
                                border: 1px solid #7fa7e1;
                                border-radius: 12px;
                                display: inline-block;
                                min-width: 40px;
                                transition: background-color 0.2s ease;
                            }
                            .exact-stat-value.active-finalized {
                                background: #19355e;
                            }
                            .exact-stat-value.balance {
                                background: #2e5182;
                            }
                            .exact-stat-value.active-finalized:hover {
                                background: #1c3d68;
                            }
                            .exact-stat-value.balance:hover {
                                background: #345a90;
                            }
                            .exact-section-title {
                                color: #ffffff;
                                font-size: 22px;
                                font-weight: 600;
                                margin-bottom: 25px;
                                letter-spacing: 0.3px;
                            }
                            .exact-project-item {
                                background: #1a2f4a;
                                border: 1px solid #2d4a6b;
                                border-radius: 15px;
                                padding: 20px 25px;
                                margin-bottom: 12px;
                                display: flex;
                                align-items: center;
                                min-height: 80px;
                            }
                            .exact-project-number {
                                width: 40px;
                                height: 40px;
                                background: #3366cc;
                                color: #ffffff;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-weight: 700;
                                font-size: 16px;
                                flex-shrink: 0;
                                margin-right: 20px;
                            }
                            .exact-project-info {
                                flex: 1;
                                min-width: 0;
                            }
                            .exact-project-name {
                                color: #ffffff;
                                font-size: 16px;
                                font-weight: 600;
                                margin-bottom: 4px;
                                line-height: 1.3;
                            }
                            .exact-project-topic {
                                color: #8fa8cc;
                                font-size: 14px;
                                font-weight: 400;
                                line-height: 1.2;
                            }
                            .exact-project-delivery {
                                color: #ffffff;
                                font-size: 14px;
                                font-weight: 500;
                                margin: 0 25px;
                                white-space: nowrap;
                                flex-shrink: 0;
                            }
                            .exact-project-actions {
                                display: flex;
                                gap: 10px;
                                flex-shrink: 0;
                            }
                            .exact-status-btn {
                                padding: 8px 16px;
                                border-radius: 18px;
                                font-size: 13px;
                                font-weight: 600;
                                border: none;
                                cursor: pointer;
                                transition: opacity 0.2s ease;
                                white-space: nowrap;
                            }
                            .exact-status-btn:hover {
                                opacity: 0.9;
                            }
                            .exact-status-completed {
                                background: #16a34a;
                                color: #ffffff;
                            }
                            .exact-status-in_progress {
                                background: #a16207;
                                color: #ffffff;
                            }
                            .exact-status-pending {
                                background: #d4931a;
                                color: #ffffff;
                            }
                            .exact-status-new {
                                background: #0891b2;
                                color: #ffffff;
                            }
                            .exact-status-finalized {
                                background: #6b7280;
                                color: #ffffff;
                            }
                            .exact-status-cancelled {
                                background: #dc2626;
                                color: #ffffff;
                            }
                            .exact-download-btn {
                                background: #3366cc;
                                color: #ffffff;
                                padding: 8px 16px;
                                border-radius: 18px;
                                font-size: 13px;
                                font-weight: 600;
                                border: none;
                                cursor: pointer;
                                transition: opacity 0.2s ease;
                                white-space: nowrap;
                            }
                            .exact-download-btn:hover {
                                opacity: 0.9;
                            }
                            .exact-empty-message {
                                color: #8fa8cc;
                                font-style: italic;
                                font-size: 14px;
                                padding: 20px 0;
                                text-align: left;
                            }
                            .exact-projects-section {
                                margin-bottom: 45px;
                            }
                            @media (max-width: 768px) {
                                .exact-stats-grid {
                                    grid-template-columns: 1fr;
                                    gap: 15px;
                                }
                                .exact-project-item {
                                    flex-direction: column;
                                    text-align: center;
                                    padding: 20px;
                                }
                                .exact-project-info {
                                    margin: 15px 0;
                                    text-align: center;
                                }
                                .exact-project-delivery {
                                    margin: 10px 0;
                                    text-align: center;
                                }
                                .exact-project-actions {
                                    justify-content: center;
                                    flex-wrap: wrap;
                                }
                            }
                        </style>

                        <div class="exact-client-area">
                            <!-- Stats Grid -->
                            <div class="exact-stats-grid">
                                <div class="exact-stat-card">
                                    <div class="exact-stat-title">Active</div>
                                    <div class="exact-stat-value active-finalized">{{ $counts['active'] }}</div>
                                </div>
                                <div class="exact-stat-card">
                                    <div class="exact-stat-title">Balance</div>
                                    <div class="exact-stat-value balance">{{ $counts['balance'] }} Days</div>
                                </div>
                                <div class="exact-stat-card">
                                    <div class="exact-stat-title">Finalized</div>
                                    <div class="exact-stat-value active-finalized">{{ $counts['finalized'] }}</div>
                                </div>
                            </div>

                            <!-- Active Projects -->
                            <div class="exact-projects-section">
                                <h3 class="exact-section-title">Active Projects</h3>
                                @forelse($active as $index => $p)
                                    <div class="exact-project-item">
                                        <div class="exact-project-number">{{ $loop->iteration }}</div>
                                        <div class="exact-project-info">
                                            <div class="exact-project-name">{{ e($p->title) }}</div>
                                            <div class="exact-project-topic">{{ $p->notes ? e($p->notes) : 'Topic' }}</div>
                                        </div>
                                        <div class="exact-project-delivery">
                                            {{ $p->delivery_date ? 'Delivery by ' . $p->delivery_date->format('j F') : 'Delivery by TBD' }}
                                        </div>
                                        <div class="exact-project-actions">
                                            @if($p->status === 'completed')
                                                <button class="exact-status-btn exact-status-completed">Completed</button>
                                            @elseif($p->status === 'in_progress')
                                                <button class="exact-status-btn exact-status-in_progress">In Progress</button>
                                            @elseif($p->status === 'pending')
                                                <button class="exact-status-btn exact-status-pending">Pending</button>
                                            @elseif($p->status === 'new')
                                                <button class="exact-status-btn exact-status-new">New</button>
                                            @else
                                                <button class="exact-status-btn exact-status-new">New</button>
                                            @endif
                                            <button class="exact-download-btn">Download Files</button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="exact-empty-message">There are currently no active projects.</div>
                                @endforelse
                            </div>

                            <!-- Finalized Projects -->
                            <div class="exact-projects-section">
                                <h3 class="exact-section-title">Finalized Projects</h3>
                                @if($finalized->count() > 0)
                                    @foreach($finalized as $p)
                                        <div class="exact-project-item">
                                            <div class="exact-project-number">{{ $loop->iteration }}</div>
                                            <div class="exact-project-info">
                                                <div class="exact-project-name">{{ e($p->title) }}</div>
                                                <div class="exact-project-topic">{{ $p->notes ? e($p->notes) : 'Topic' }}</div>
                                            </div>
                                            <div class="exact-project-delivery">
                                                {{ $p->delivery_date ? 'Delivered on ' . $p->delivery_date->format('j F') : 'Delivered on TBD' }}
                                            </div>
                                            <div class="exact-project-actions">
                                                @if($p->status === 'finalized')
                                                    <button class="exact-status-btn exact-status-finalized">Finalized</button>
                                                @elseif($p->status === 'cancelled')
                                                    <button class="exact-status-btn exact-status-cancelled">Cancelled</button>
                                                @else
                                                    <button class="exact-status-btn exact-status-finalized">Finalized</button>
                                                @endif
                                                <button class="exact-download-btn">Download Files</button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="exact-empty-message">There are currently no finalized projects.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                <!-- Services Section -->
                <div id="section-services" class="section">
                    <div class="card">
                        <h2 style="margin: 0 0 24px; font-size: 18px;">Available Services</h2>

                        @foreach($pricingItems->groupBy('category.title') as $categoryTitle => $items)
                            <div style="margin-bottom: 32px;">
                                <h3 style="font-size: 16px; margin: 0 0 16px; color: var(--text);">{{ $categoryTitle }}</h3>

                                <div class="grid grid-3">
                                    @foreach($items as $item)
                                        <div class="card" style="border: 2px solid var(--line); transition: all 0.2s;" onmouseover="this.style.borderColor='var(--brand)'" onmouseout="this.style.borderColor='var(--line)'">
                                            <h4 style="margin: 0 0 8px; font-size: 16px;">{{ $item->title }}</h4>
                                            @if($item->description)
                                                <p style="margin: 0 0 16px; color: var(--muted); font-size: 14px; line-height: 1.4;">{{ $item->description }}</p>
                                            @endif
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <div>
                                                    <div style="font-size: 24px; font-weight: 700; color: var(--brand);">€{{ number_format($item->price, 0) }}</div>
                                                    <div style="font-size: 12px; color: var(--muted);">per {{ str_replace('per_', '', $item->price_unit) }}</div>
                                                </div>
                                                <form method="POST" action="{{ route('dashboard.add-to-cart') }}">
                                                    @csrf
                                                    <input type="hidden" name="pricing_item_id" value="{{ $item->id }}">
                                                    <button type="submit" class="btn">Add to Cart</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Transactions Section -->
                <div id="section-transactions" class="section">
                    <div class="card">
                        <h2 style="margin: 0 0 24px; font-size: 18px;">Transaction History</h2>

                        @if($userPurchases->count() > 0)
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Service</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userPurchases as $purchase)
                                        <tr>
                                            <td style="font-family: monospace; color: var(--muted);">#{{ str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}</td>
                                            <td>
                                                @if($purchase->pricingItem)
                                                    <div style="font-weight: 600;">{{ $purchase->pricingItem->title }}</div>
                                                    <div style="font-size: 12px; color: var(--muted);">{{ optional($purchase->pricingItem->category)->title ?? '' }}</div>
                                                @else
                                                    @php $items = is_array($purchase->payment_data) ? $purchase->payment_data : json_decode($purchase->payment_data, true) ?? []; @endphp
                                                    <div style="font-weight: 600;">Multiple items</div>
                                                    <div style="font-size: 12px; color: var(--muted);">
                                                        @foreach($items as $it)
                                                            <div>{{ $it['title'] ?? 'Item' }} @if(($it['qty'] ?? 1) > 1) (x{{ $it['qty'] }}) @endif</div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td style="font-weight: 600;">€{{ number_format($purchase->amount, 2) }}</td>
                                            <td>
                                                <span class="status status-{{ $purchase->status === 'completed' ? 'completed' : ($purchase->status === 'failed' ? 'failed' : 'pending') }}">
                                                    {{ ucfirst($purchase->status) }}
                                                </span>
                                            </td>
                                            <td style="color: var(--muted);">{{ $purchase->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <a href="/client" class="btn btn-sm">Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <h3 style="margin: 0 0 8px; font-size: 18px;">No transactions yet</h3>
                                <p style="margin: 0; font-size: 14px;">Your purchase history will appear here</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Profile Section -->
                <div id="section-profile" class="section">
                    <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 24px;">
                        <div class="card">
                            <h2 style="margin: 0 0 24px; font-size: 18px;">Profile Information</h2>

                            <div style="margin-bottom: 24px;">
                                <label style="display: block; font-weight: 600; margin-bottom: 4px;">Full Name</label>
                                <div style="color: var(--muted);">{{ Auth::user()->name }}</div>
                            </div>

                            <div style="margin-bottom: 24px;">
                                <label style="display: block; font-weight: 600; margin-bottom: 4px;">Email Address</label>
                                <div style="color: var(--muted);">{{ Auth::user()->email }}</div>
                            </div>

                            <div style="margin-bottom: 24px;">
                                <label style="display: block; font-weight: 600; margin-bottom: 4px;">Member Since</label>
                                <div style="color: var(--muted);">{{ Auth::user()->created_at->format('F d, Y') }}</div>
                            </div>

                            <div style="padding-top: 24px; border-top: 1px solid var(--line);">
                                <a href="/client" class="btn" style="margin-right: 12px;">View Client Area</a>
                                <a href="/" class="btn" style="background: var(--muted);">Homepage</a>
                            </div>
                        </div>

                        <div class="card">
                            <h3 style="margin: 0 0 16px; font-size: 16px;">Account Stats</h3>

                            <div style="margin-bottom: 16px; padding: 16px; background: #f8fafc; border-radius: 8px;">
                                <div style="font-size: 24px; font-weight: 700; color: var(--brand);">{{ $userPurchases->count() }}</div>
                                <div style="font-size: 12px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.025em;">Total Purchases</div>
                            </div>

                            <div style="margin-bottom: 16px; padding: 16px; background: #f0fdf4; border-radius: 8px;">
                                <div style="font-size: 24px; font-weight: 700; color: var(--ok);">€{{ number_format($userPurchases->sum('amount'), 0) }}</div>
                                <div style="font-size: 12px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.025em;">Total Spent</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });

            // Remove active class from all nav links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });

            // Show selected section
            document.getElementById('section-' + sectionName).classList.add('active');

            // Add active class to selected nav link
            const navLink = document.getElementById('nav-' + sectionName);
            if (navLink) {
                navLink.classList.add('active');
            }
        }

        // Mobile menu toggle (if needed later)
        function toggleMobileMenu() {
            document.querySelector('.sidebar').classList.toggle('open');
        }
    </script>
</body>
</html>

                <!-- Home Section -->
                <div id="section-home" class="section active">

                    <!-- Virtual Cart at Top -->
                    @if(session('selected_plan_for_payment'))
                        @php
                            $selectedPlan = $pricingItems->find(session('selected_plan_for_payment'));
                        @endphp
                        @if($selectedPlan)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-blue-900 mb-2" style="font-family: var(--font-sans)">
                                            🛒 Your Cart
                                        </h3>
                                        <p class="text-blue-700 text-sm mb-4" style="font-family: var(--font-sans)">
                                            Review your selected items and proceed to secure checkout via Mollie
                                        </p>

                                        <div class="bg-white rounded-lg p-4 border border-blue-300 mb-4">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <h4 class="font-semibold text-gray-900" style="font-family: var(--font-sans)">
                                                        {{ $selectedPlan->title }}
                                                    </h4>
                                                    <p class="text-sm text-gray-600" style="font-family: var(--font-sans)">
                                                        {{ $selectedPlan->category->title }}
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-xl font-bold text-blue-600" style="font-family: var(--font-sans)">
                                                        €{{ number_format($selectedPlan->price, 0) }}
                                                    </div>
                                                    <div class="text-sm text-gray-500" style="font-family: var(--font-sans)">
                                                        per {{ str_replace('per_', '', $selectedPlan->price_unit) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <img src="https://mollie.com/img/payment-methods/ideal.svg" alt="iDEAL" class="h-6">
                                        <img src="https://mollie.com/img/payment-methods/creditcard.svg" alt="Credit Card" class="h-6">
                                        <img src="https://mollie.com/img/payment-methods/paypal.svg" alt="PayPal" class="h-6">
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('dashboard.add-to-cart') }}" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="pricing_item_id" value="{{ session('selected_plan_for_payment') }}">
                                    <button type="submit"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors flex items-center justify-center"
                                            style="font-family: var(--font-sans)">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        Proceed to Secure Checkout
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif

                    <!-- Available Services -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-8">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900" style="font-family: var(--font-sans)">
                                Available Services
                            </h2>
                        </div>
                        <div class="p-6">
                            @foreach($pricingItems->groupBy('category.title') as $categoryTitle => $items)
                                <div class="mb-8 last:mb-0">
                                    <h3 class="text-lg font-medium text-gray-800 mb-4" style="font-family: var(--font-sans)">
                                        {{ $categoryTitle }}
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($items as $item)
                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-blue-300 transition-colors">
                                                <h4 class="text-lg font-semibold text-gray-900 mb-2" style="font-family: var(--font-sans)">
                                                    {{ $item->title }}
                                                </h4>
                                                @if($item->description)
                                                    <p class="text-gray-600 text-sm mb-4" style="font-family: var(--font-sans)">
                                                        {{ $item->description }}
                                                    </p>
                                                @endif
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xl font-bold text-blue-600" style="font-family: var(--font-sans)">
                                                        €{{ number_format($item->price, 0) }}
                                                        <span class="text-sm text-gray-500">/{{ str_replace('per_', '', $item->price_unit) }}</span>
                                                    </span>
                                                    <form method="POST" action="{{ route('dashboard.add-to-cart') }}">
                                                        @csrf
                                                        <input type="hidden" name="pricing_item_id" value="{{ $item->id }}">
                                                        <button type="submit"
                                                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                                                style="font-family: var(--font-sans); background-color: var(--brand-primary);">
                                                            Add to Cart
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Client Area Preview -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900" style="font-family: var(--font-sans)">
                                Your Projects Overview
                            </h2>
                        </div>
                        <div class="p-6">
                            <!-- Stats Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <h3 class="text-sm font-medium text-blue-900 mb-1" style="font-family: var(--font-sans)">Active Projects</h3>
                                    <div class="text-2xl font-bold text-blue-600" style="font-family: var(--font-sans)">
                                        {{ $userPurchases->where('status', 'completed')->count() }}
                                    </div>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                    <h3 class="text-sm font-medium text-green-900 mb-1" style="font-family: var(--font-sans)">Balance (days)</h3>
                                    <div class="text-2xl font-bold text-green-600" style="font-family: var(--font-sans)">
                                        {{ rand(5, 30) }}
                                    </div>
                                    <div class="text-xs text-green-700 mt-1" style="font-family: var(--font-sans)">Top-up available</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h3 class="text-sm font-medium text-gray-700 mb-1" style="font-family: var(--font-sans)">Total Spent</h3>
                                    <div class="text-2xl font-bold text-gray-900" style="font-family: var(--font-sans)">
                                        €{{ number_format($userPurchases->sum('amount'), 0) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Projects Table -->
                            @if($userPurchases->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Service</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Amount</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Date</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($userPurchases->take(5) as $purchase)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900" style="font-family: var(--font-sans)">
                                                            @if($purchase->pricingItem)
                                                                {{ $purchase->pricingItem->title }}
                                                            @else
                                                                @php $items = is_array($purchase->payment_data) ? $purchase->payment_data : json_decode($purchase->payment_data, true) ?? []; @endphp
                                                                {{ $items[0]['title'] ?? 'Multiple items' }}
                                                            @endif
                                                        </div>
                                                        <div class="text-sm text-gray-500" style="font-family: var(--font-sans)">
                                                            {{ $purchase->pricingItem->category->title }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                            {{ $purchase->status === 'completed' ? 'bg-green-100 text-green-800' :
                                                               ($purchase->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}"
                                                            style="font-family: var(--font-sans)">
                                                            {{ ucfirst($purchase->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" style="font-family: var(--font-sans)">
                                                        €{{ number_format($purchase->amount, 2) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" style="font-family: var(--font-sans)">
                                                        {{ $purchase->created_at->format('M d, Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <a href="/client" class="text-blue-600 hover:text-blue-900 font-medium" style="font-family: var(--font-sans)">
                                                            View Details
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if($userPurchases->count() > 5)
                                    <div class="mt-4 text-center">
                                        <a href="/client" class="text-blue-600 hover:text-blue-900 font-medium" style="font-family: var(--font-sans)">
                                            View All Projects →
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8">
                                    <div class="text-gray-400 mb-4">
                                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2" style="font-family: var(--font-sans)">
                                        No projects yet
                                    </h3>
                                    <p class="text-gray-600 mb-4" style="font-family: var(--font-sans)">
                                        Get started by purchasing one of our services above.
                                    </p>
                                    <a href="/client" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                                       style="font-family: var(--font-sans); background-color: var(--brand-primary);">
                                        Explore Client Area
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Transactions Section -->
                <div id="section-transactions" class="section" style="display: none;">
                    <div class="bg-white rounded-lg shadow-md border border-gray-200">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900" style="font-family: var(--font-sans)">
                                Transaction History
                            </h2>
                        </div>
                        <div class="p-6">
                            @if($userPurchases->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Transaction ID</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Service</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Amount</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: var(--font-sans)">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($userPurchases as $purchase)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900" style="font-family: var(--font-mono)">
                                                        #{{ str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900" style="font-family: var(--font-sans)">
                                                            @if($purchase->pricingItem)
                                                                {{ $purchase->pricingItem->title }}
                                                            @else
                                                                @php $items = is_array($purchase->payment_data) ? $purchase->payment_data : json_decode($purchase->payment_data, true) ?? []; @endphp
                                                                {{ $items[0]['title'] ?? 'Multiple items' }}
                                                            @endif
                                                        </div>
                                                        <div class="text-sm text-gray-500" style="font-family: var(--font-sans)">
                                                            {{ $purchase->pricingItem->category->title }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900" style="font-family: var(--font-sans)">
                                                        €{{ number_format($purchase->amount, 2) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                            {{ $purchase->status === 'completed' ? 'bg-green-100 text-green-800' :
                                                               ($purchase->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}"
                                                            style="font-family: var(--font-sans)">
                                                            {{ ucfirst($purchase->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" style="font-family: var(--font-sans)">
                                                        {{ $purchase->created_at->format('M d, Y H:i') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="text-gray-400 mb-4">
                                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2" style="font-family: var(--font-sans)">
                                        No transactions yet
                                    </h3>
                                    <p class="text-gray-600" style="font-family: var(--font-sans)">
                                        Your purchase history will appear here once you make your first order.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Profile Section -->
                <div id="section-profile" class="section" style="display: none;">
                    <div class="bg-white rounded-lg shadow-md border border-gray-200">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900" style="font-family: var(--font-sans)">
                                Profile Settings
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Profile Info -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4" style="font-family: var(--font-sans)">
                                        Account Information
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700" style="font-family: var(--font-sans)">Full Name</label>
                                            <div class="mt-1 text-sm text-gray-900" style="font-family: var(--font-sans)">{{ Auth::user()->name }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700" style="font-family: var(--font-sans)">Email Address</label>
                                            <div class="mt-1 text-sm text-gray-900" style="font-family: var(--font-sans)">{{ Auth::user()->email }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700" style="font-family: var(--font-sans)">Member Since</label>
                                            <div class="mt-1 text-sm text-gray-900" style="font-family: var(--font-sans)">{{ Auth::user()->created_at->format('F d, Y') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Account Stats -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4" style="font-family: var(--font-sans)">
                                        Account Statistics
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-blue-900" style="font-family: var(--font-sans)">Total Purchases</div>
                                                    <div class="text-2xl font-bold text-blue-600" style="font-family: var(--font-sans)">{{ $userPurchases->count() }}</div>
                                                </div>
                                                <div class="text-blue-400">
                                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                                        <path d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-green-900" style="font-family: var(--font-sans)">Total Spent</div>
                                                    <div class="text-2xl font-bold text-green-600" style="font-family: var(--font-sans)">€{{ number_format($userPurchases->sum('amount'), 0) }}</div>
                                                </div>
                                                <div class="text-green-400">
                                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-8 flex space-x-4">
                                <a href="/client"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors"
                                   style="font-family: var(--font-sans); background-color: var(--brand-primary);">
                                    View Full Client Area
                                </a>
                                <a href="/"
                                   class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition-colors"
                                   style="font-family: var(--font-sans);">
                                    Back to Homepage
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.nav-link.active {
    background-color: var(--brand-primary);
    color: white;
}
.nav-link.active:hover {
    background-color: var(--brand-primary);
    color: white;
}
</style>

<script>
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.section').forEach(section => {
        section.style.display = 'none';
    });

    // Remove active class from all nav links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });

    // Show selected section
    document.getElementById('section-' + sectionName).style.display = 'block';

    // Add active class to selected nav link
    const navLink = document.getElementById('nav-' + sectionName);
    if (navLink) {
        navLink.classList.add('active');
    }
}

// Client area toggle function
function toggleClientRow(id) {
    // close others
    document.querySelectorAll('[id^="details-"]').forEach(el => {
        if (el.id !== 'details-' + id) el.style.display = 'none';
    });
    document.querySelectorAll('.plus').forEach(btn => {
        if (btn.getAttribute('aria-controls') !== 'details-' + id) btn.setAttribute('aria-expanded', 'false');
    });

    const details = document.getElementById('details-' + id);
    const btn = document.querySelector('.plus[aria-controls="details-' + id + '"]');
    const isOpen = details && details.style.display !== 'none';

    if (!details) return;

    if (isOpen) {
        details.style.display = 'none';
        if (btn) btn.setAttribute('aria-expanded', 'false');
    } else {
        details.style.display = '';
        if (btn) btn.setAttribute('aria-expanded', 'true');
    }
}

// Mobile menu toggle (if needed later)
function toggleMobileMenu() {
    document.querySelector('.sidebar').classList.toggle('open');
}
</script>
</body>
</html>
