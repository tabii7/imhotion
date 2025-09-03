<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard â€” Imhotion</title>
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

        .grid {
            display: grid;
            gap: 16px;
            margin-bottom: 20px;
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
                    <div class="nav-group-title">Dashboard</div>
                    <a href="#overview" onclick="showSection('overview')" id="nav-overview" class="nav-link active">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Home
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

                <!-- Overview Section -->
                <div id="section-overview" class="section active">

                    <!-- Improved Cart Section -->
                    @include('components.mini-cart')

                    <!-- Client Area - Exact Match to Reference -->
                    <div style="background: #0a1428; border-radius: 12px; padding: 20px; color: #ffffff;">
                        <style>
                            .exact-client-area {
                                color: #ffffff;
                                font-family: system-ui, -apple-system, sans-serif;
                            }
                            .exact-stats-grid {
                                display: grid;
                                grid-template-columns: repeat(3, 1fr);
                                gap: 12px;
                                margin: 0 auto 20px auto;
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
                                background: #001f4c;
                                border: 1px solid #7fa7e1;
                                border-radius: 15px;
                                padding: 3px 6px;
                                margin-bottom: 12px;
                                display: flex;
                                align-items: center;
                                min-height: 65px;
                                transition: all 2s ease;
                                cursor: pointer;
                            }
                            .exact-project-item:hover {
                                background: #33527a; /* 20% lighter than #001f4c */
                                transform: scale(1.05);
                            }
                            .exact-project-item.finalized {
                                background: #121e2f;
                            }
                            .exact-project-item.finalized:hover {
                                background: #394c66; /* 20% lighter than #121e2f */
                                transform: scale(1.05);
                            }
                            .exact-project-item.finalized .exact-project-number {
                                background: #2e3b4d; /* 10% lighter than #1a2a40 */
                            }
                            .exact-project-number {
                                width: 50px;
                                height: 32px;
                                background: #4a6b95; /* 10% lighter than #3d5a87 */
                                color: #ffffff;
                                border: 1px solid #7fa7e1;
                                border-radius: 8px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-weight: 700;
                                font-size: 14px;
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
                                border: 1px solid #7fa7e1;
                                border-radius: 18px;
                                font-size: 13px;
                                font-weight: 600;
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
                                border: 1px solid #7fa7e1;
                                border-radius: 18px;
                                font-size: 13px;
                                font-weight: 600;
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
                                    <div class="exact-project-item" onclick="openProjectModal({ id: {{ $p->id }}, title: {{ json_encode($p->title) }}, topic: {{ json_encode($p->topic ?? 'Topic') }}, status: {{ json_encode($p->status) }}, delivery_date: {{ json_encode($p->delivery_date ? $p->delivery_date->format('Y-m-d') : null) }}, start_date: {{ json_encode($p->start_date ? $p->start_date->format('Y-m-d') : null) }}, end_date: {{ json_encode($p->end_date ? $p->end_date->format('Y-m-d') : null) }}, day_budget: {{ $p->day_budget ?? 0 }}, days_used: {{ $p->days_used ?? 0 }}, notes: {{ json_encode($p->notes ?? '') }}, client_name: {{ json_encode($p->user ? $p->user->name : 'Unknown Client') }}, client_email: {{ json_encode($p->user ? $p->user->email : null) }} });" style="cursor: pointer;">
                                        <div class="exact-project-number">25{{ str_pad($p->id, 2, '0', STR_PAD_LEFT) }}</div>
                                        <div class="exact-project-info">
                                            <div class="exact-project-name">{{ e($p->title) }}</div>
                                            <div class="exact-project-topic">{{ $p->topic ? e($p->topic) : 'Topic' }}</div>
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
                                            <button class="exact-download-btn" onclick="event.stopPropagation(); openProjectModal({ id: {{ $p->id }}, title: {{ json_encode($p->title) }}, topic: {{ json_encode($p->topic ?? 'Topic') }}, status: {{ json_encode($p->status) }}, delivery_date: {{ json_encode($p->delivery_date ? $p->delivery_date->format('Y-m-d') : null) }}, start_date: {{ json_encode($p->start_date ? $p->start_date->format('Y-m-d') : null) }}, end_date: {{ json_encode($p->end_date ? $p->end_date->format('Y-m-d') : null) }}, day_budget: {{ $p->day_budget ?? 0 }}, days_used: {{ $p->days_used ?? 0 }}, notes: {{ json_encode($p->notes ?? '') }}, client_name: {{ json_encode($p->user ? $p->user->name : 'Unknown Client') }}, client_email: {{ json_encode($p->user ? $p->user->email : null) }} }); switchModalTab('files');">Download Files</button>
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
                                        <div class="exact-project-item finalized" onclick="openProjectModal({ id: {{ $p->id }}, title: {{ json_encode($p->title) }}, topic: {{ json_encode($p->topic ?? 'Topic') }}, status: {{ json_encode($p->status) }}, delivery_date: {{ json_encode($p->delivery_date ? $p->delivery_date->format('Y-m-d') : null) }}, start_date: {{ json_encode($p->start_date ? $p->start_date->format('Y-m-d') : null) }}, end_date: {{ json_encode($p->end_date ? $p->end_date->format('Y-m-d') : null) }}, day_budget: {{ $p->day_budget ?? 0 }}, days_used: {{ $p->days_used ?? 0 }}, notes: {{ json_encode($p->notes ?? '') }}, client_name: {{ json_encode($p->user ? $p->user->name : 'Unknown Client') }}, client_email: {{ json_encode($p->user ? $p->user->email : null) }} });" style="cursor: pointer;">
                                            <div class="exact-project-number">25{{ str_pad($p->id, 2, '0', STR_PAD_LEFT) }}</div>
                                            <div class="exact-project-info">
                                                <div class="exact-project-name">{{ e($p->title) }}</div>
                                                <div class="exact-project-topic">{{ $p->topic ? e($p->topic) : 'Topic' }}</div>
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
                                                <button class="exact-download-btn" onclick="event.stopPropagation(); openProjectModal({ id: {{ $p->id }}, title: {{ json_encode($p->title) }}, topic: {{ json_encode($p->topic ?? 'Topic') }}, status: {{ json_encode($p->status) }}, delivery_date: {{ json_encode($p->delivery_date ? $p->delivery_date->format('Y-m-d') : null) }}, start_date: {{ json_encode($p->start_date ? $p->start_date->format('Y-m-d') : null) }}, end_date: {{ json_encode($p->end_date ? $p->end_date->format('Y-m-d') : null) }}, day_budget: {{ $p->day_budget ?? 0 }}, days_used: {{ $p->days_used ?? 0 }}, notes: {{ json_encode($p->notes ?? '') }}, client_name: {{ json_encode($p->user ? $p->user->name : 'Unknown Client') }}, client_email: {{ json_encode($p->user ? $p->user->email : null) }} }); switchModalTab('files');">Download Files</button>
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
                    @include('dashboard.services')
                </div>

                <!-- Transactions Section -->
                <div id="section-transactions" class="section">
                    @include('dashboard.transactions')
                </div>

                <!-- Profile Section -->
                <div id="section-profile" class="section">
                    @include('dashboard.profile')
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

        // Respect URL hash on initial page load (e.g. /dashboard#services)
        document.addEventListener('DOMContentLoaded', function() {
            try {
                var hash = window.location.hash || '';
                if (hash && hash.startsWith('#')) {
                    var section = hash.slice(1);
                    var target = document.getElementById('section-' + section);
                    if (target) {
                        showSection(section);
                        return;
                    }
                }
            } catch (e) {
                console.error('Error handling initial hash navigation:', e);
            }

            // Default to overview if nothing matched
            if (document.getElementById('section-overview')) {
                showSection('overview');
            }
        });

        // Mobile menu toggle (if needed later)
        function toggleMobileMenu() {
            document.querySelector('.sidebar').classList.toggle('open');
        }

        // Project Modal Functions
        let currentProjectData = null;

        // Function to open project modal
        function openProjectModal(projectData) {
            console.log('openProjectModal called with:', projectData);

            try {
                currentProjectData = projectData;

                // Check if modal elements exist
                const modal = document.getElementById('projectModal');
                if (!modal) {
                    console.error('Modal element not found!');
                    alert('Modal not found - check if component is included');
                    return;
                }

                // Populate modal with project data
                const titleElement = document.getElementById('modalProjectTitle');
                if (titleElement) {
                    titleElement.textContent = projectData.title || 'Project Title';
                } else {
                    console.error('Modal title element not found!');
                }

                const numberElement = document.getElementById('modalProjectNumber');
                if (numberElement) {
                    numberElement.textContent = '25' + String(projectData.id).padStart(2, '0');
                }
                // also populate the header project number near the close button
                const numberHeaderEl = document.getElementById('modalProjectNumberHeader');
                if (numberHeaderEl) {
                    numberHeaderEl.textContent = '25' + String(projectData.id).padStart(2, '0');
                }

                const topicElement = document.getElementById('modalProjectTopic');
                if (topicElement) {
                    topicElement.textContent = projectData.topic || 'Topic';
                }
                // populate header topic value
                const topicHeaderValue = document.getElementById('modalProjectTopicHeaderValue');
                if (topicHeaderValue) {
                    topicHeaderValue.textContent = projectData.topic || '';
                }

                // Status
                const statusElement = document.getElementById('modalProjectStatus');
                if (statusElement) {
                    statusElement.textContent = formatStatus(projectData.status);
                    statusElement.className = 'project-modal-status ' + projectData.status;
                }

                // Delivery date
                const deliveryText = projectData.delivery_date ?
                    (projectData.status === 'finalized' ? 'Delivered on ' : 'Delivery by ') + formatDate(projectData.delivery_date) :
                    'Delivery TBD';
                const deliveryElement = document.getElementById('modalProjectDelivery');
                if (deliveryElement) {
                    deliveryElement.textContent = deliveryText;
                }

                // Client info
                const clientNameElement = document.getElementById('modalClientName');
                if (clientNameElement) {
                    clientNameElement.textContent = projectData.client_name || 'Client Name';
                }

                const clientEmailElement = document.getElementById('modalClientEmail');
                if (clientEmailElement) {
                    clientEmailElement.textContent = projectData.client_email ? '(' + projectData.client_email + ')' : '';
                }

                // Budget info
                const budget = projectData.day_budget || 0;
                const used = projectData.days_used || 0;
                const remaining = Math.max(0, budget - used);

                const budgetElement = document.getElementById('modalBudget');
                if (budgetElement) {
                    budgetElement.textContent = budget + ' days';
                }

                const remainingElement = document.getElementById('modalRemainingDays');
                if (remainingElement) {
                    remainingElement.textContent = remaining + ' remaining';
                }

                // Dates
                const startDateElement = document.getElementById('modalStartDate');
                if (startDateElement) {
                    startDateElement.textContent = projectData.start_date ? formatDate(projectData.start_date) : 'Not set';
                }
                // also set the small header start date display
                const startHeader = document.getElementById('modalStartDateHeader');
                if (startHeader) startHeader.textContent = projectData.start_date ? formatDate(projectData.start_date) : 'Not set';

                const endDateElement = document.getElementById('modalEndDate');
                if (endDateElement) {
                    endDateElement.textContent = projectData.end_date ? formatDate(projectData.end_date) : 'Not set';
                }
                const endHeader = document.getElementById('modalEndDateHeader');
                if (endHeader) endHeader.textContent = projectData.end_date ? formatDate(projectData.end_date) : 'Not set';

                const daysUsedElement = document.getElementById('modalDaysUsed');
                if (daysUsedElement) {
                    daysUsedElement.textContent = used + ' days';
                }

                // Notes
                const notesElement = document.getElementById('modalNotes');
                if (notesElement) {
                    notesElement.textContent = projectData.notes || 'No notes available';
                }

                // Full page link
                const fullPageElement = document.getElementById('modalViewFullPage');
                if (fullPageElement) {
                    fullPageElement.href = '/projects/' + projectData.id;
                }

                // Load files
                loadProjectFiles(projectData.id);

                // Show modal
                console.log('Showing modal...');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                console.log('Modal should be visible now');

            } catch (error) {
                console.error('Error in openProjectModal:', error);
                alert('Error opening modal: ' + error.message);
            }
        }

        // Function to close project modal
        function closeProjectModal() {
            document.getElementById('projectModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            currentProjectData = null;
        }

        // Function to switch modal tabs
        function switchModalTab(tabName) {
            // Update tab buttons
            document.querySelectorAll('.project-modal-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            // Update tab content
            document.querySelectorAll('.modal-tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(tabName + 'Tab').classList.add('active');
        }

        // Function to toggle files view
        function toggleFilesView(view) {
            const container = document.getElementById('filesList');
            const buttons = document.querySelectorAll('.view-toggle-btn');

            // Update buttons
            buttons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.view === view) {
                    btn.classList.add('active');
                }
            });

            // Update container class
            container.className = 'files-container ' + view + '-view';
        }

        // Function to load project files
        function loadProjectFiles(projectId) {
            // Show loading state
            const container = document.getElementById('filesList');
            container.innerHTML = '<div class="no-files-message">Loading files...</div>';

            // Fetch files from server
            fetch('/api/projects/' + projectId + '/files')
                .then(response => response.json())
                .then(files => {
                    if (files.length === 0) {
                        container.innerHTML = '<div class="no-files-message">No files available for this project</div>';
                        return;
                    }

                    container.innerHTML = '';
                    files.forEach(file => {
                        const fileElement = createFileElement(file);
                        container.appendChild(fileElement);
                    });
                })
                .catch(error => {
                    console.error('Error loading files:', error);
                    container.innerHTML = '<div class="no-files-message">Error loading files</div>';
                });
        }

        // Function to create file element
        function createFileElement(file) {
            const div = document.createElement('div');
            div.className = 'file-item';
            div.onclick = () => openFileInLightbox(file);

            const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(file.filename);
            const isVideo = /\.(mp4|webm|ogg|mov)$/i.test(file.filename);
            const isPdf = /\.pdf$/i.test(file.filename);
            const isDoc = /\.(doc|docx|txt|rtf)$/i.test(file.filename);
            const isZip = /\.(zip|rar|7z|tar|gz)$/i.test(file.filename);

            let iconOrThumbnail = '';
            if (isImage && file.thumbnail_url) {
                iconOrThumbnail = `<img src="${file.thumbnail_url}" alt="${file.name}" class="file-thumbnail">`;
            } else if (isVideo && file.poster_url) {
                iconOrThumbnail = `<img src="${file.poster_url}" alt="${file.name}" class="file-thumbnail">`;
            } else {
                let iconText = 'FILE';
                if (isImage) iconText = 'IMG';
                else if (isVideo) iconText = 'VID';
                else if (isPdf) iconText = 'PDF';
                else if (isDoc) iconText = 'DOC';
                else if (isZip) iconText = 'ZIP';

                iconOrThumbnail = `<div class="file-icon">${iconText}</div>`;
            }

            div.innerHTML = `
                ${iconOrThumbnail}
                <div class="file-info">
                    <div class="file-name">${file.name}</div>
                    <div class="file-size">${formatFileSize(file.size)}</div>
                </div>
            `;

            return div;
        }

        // Function to open file in lightbox
        function openFileInLightbox(file) {
            const lightbox = document.getElementById('fileLightbox');
            const body = document.getElementById('lightboxBody');

            const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(file.filename);
            const isVideo = /\.(mp4|webm|ogg|mov)$/i.test(file.filename);

            if (isImage) {
                body.innerHTML = `<img src="${file.url}" alt="${file.name}">`;
            } else if (isVideo) {
                body.innerHTML = `<video controls autoplay><source src="${file.url}" type="video/mp4"></video>`;
            } else {
                // For other files, offer download
                body.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #64748b;">
                        <div style="font-size: 48px; margin-bottom: 20px;">ðŸ“„</div>
                        <h3>${file.name}</h3>
                        <p>File size: ${formatFileSize(file.size)}</p>
                        <a href="${file.url}" download class="download-all-btn" style="display: inline-flex; margin-top: 20px;">
                            Download File
                        </a>
                    </div>
                `;
            }

            lightbox.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        // Function to close lightbox
        function closeLightbox() {
            document.getElementById('fileLightbox').style.display = 'none';
            document.body.style.overflow = 'hidden'; // Keep modal body overflow hidden
        }

        // Function to download all files
        function downloadAllFiles() {
            if (!currentProjectData) return;

            // Create download link for all files
            window.location.href = '/projects/' + currentProjectData.id + '/download-all';
        }

        // Helper functions
        function formatStatus(status) {
            const statusMap = {
                'new': 'New',
                'pending': 'Pending',
                'in_progress': 'In Progress',
                'completed': 'Completed',
                'finalized': 'Finalized',
                'cancelled': 'Cancelled'
            };
            return statusMap[status] || status;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('project-modal-overlay')) {
                closeProjectModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (document.getElementById('fileLightbox').style.display === 'flex') {
                    closeLightbox();
                } else if (document.getElementById('projectModal').style.display === 'flex') {
                    closeProjectModal();
                }
            }
        });
    </script>

    <!-- Include Project Modal Component -->
    @include('components.project-modal')
</body>
</html>
