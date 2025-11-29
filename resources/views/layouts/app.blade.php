<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ten Coffee System')</title>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <style>
        .logout-form { display: inline; }
        .alert-danger { color: #e74c3c; font-size: 0.9em; margin-top: 5px; }

        /* Helper untuk Link Sidebar agar tampil seperti button */
        a.nav-item { text-decoration: none; }
    </style>
</head>
<body>
<div id="app">
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>☕ Ten Coffee</h1>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                <span>Dashboard</span>
            </a>

            @if(Auth::user()->role->value != 'kasir' && Auth::user()->role->value != 'admin')
                <a href="{{ route('bahan-bakus.index') }}" class="nav-item {{ Request::routeIs('bahan-bakus*') ? 'active' : '' }}">
                    <span class="nav-icon">📦</span>
                    <span>Bahan Baku</span>
                </a>

                <a href="{{ route('satuans.index') }}" class="nav-item {{ Request::routeIs('satuans*') ? 'active' : '' }}">
                    <span class="nav-icon">⚖️</span>
                    <span>Satuan</span>
                </a>

                <a href="{{ route('suppliers.index') }}" class="nav-item {{ Request::routeIs('suppliers*') ? 'active' : '' }}">
                    <span class="nav-icon">🏭</span>
                    <span>Supplier</span>
                </a>

                <a href="{{ route('stocks.index') }}" class="nav-item {{ Request::routeIs('stocks*') ? 'active' : '' }}">
                    <span class="nav-icon">📈</span>
                    <span>Stock Masuk</span>
                </a>
            @endif

            @if(Auth::user()->role->value == 'admin')
                <a href="{{ route('users.index') }}" class="nav-item {{ Request::routeIs('users*') ? 'active' : '' }}" class="nav-item">
                    <span class="nav-icon">👥</span>
                    <span>Users</span>
                </a>
            @endif

            @if(Auth::user()->role->value != 'admin')
                <a href="{{ route('pengeluaran.create') }}" class="nav-item {{ Request::routeIs('pengeluaran*') ? 'active' : '' }}">
                    <span class="nav-icon">📤</span>
                    <span>Barang Keluar</span>
                </a>
            @endif

            <a href="{{ route('stock-histories.index') }}" class="nav-item {{ Request::routeIs('stock-histories*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span>
                <span>Stock History</span>
            </a>

            <a href="{{ route('laporan.stok') }}" class="nav-item {{ Request::routeIs('laporan*') ? 'active' : '' }}">
                <span class="nav-icon">📑</span>
                <span>Laporan View</span>
            </a>

            <a href="{{ route('logs.stok-habis') }}" class="nav-item {{ Request::routeIs('logs*') ? 'active' : '' }}">
                <span class="nav-icon">⚠️</span>
                <span>Log Habis</span>
            </a>

        </nav>
    </div>

    <div class="main-content">
        <div class="header">
            <h2 id="page-title">@yield('title', 'Dashboard')</h2>

            <div class="header-actions">
                <div class="user-info">
                    <span>{{ Auth::user()->name }}</span>
                    <span class="user-role">
                            {{ ucfirst(is_string(Auth::user()->role) ? Auth::user()->role : Auth::user()->role->value) }}
                        </span>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout" onclick="return confirm('Yakin ingin logout?')">Logout</button>
                </form>
            </div>
        </div>

        <div class="content-area">
            @yield('content')
        </div>
    </div>
</div>

<div id="toast-container" class="toast-container">
    @if(session('success'))
        <div class="toast success" style="display: flex;">
            <span class="toast-icon">✓</span>
            <span class="toast-message">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="toast error" style="display: flex;">
            <span class="toast-icon">✕</span>
            <span class="toast-message">{{ session('error') }}</span>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('.toast');
        if (toasts.length > 0) {
            setTimeout(() => {
                toasts.forEach(t => t.remove());
            }, 3500);
        }
    });
</script>
</body>
</html>
