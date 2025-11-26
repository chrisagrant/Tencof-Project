<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ten Coffee - Inventory Management System</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  </head>
  <body>
    <div id="app">
      <div class="sidebar">
        <div class="sidebar-header">
          <h1>☕ Ten Coffee</h1>
        </div>
        <nav class="sidebar-nav">
          <button class="nav-item active" data-page="dashboard">
            <span class="nav-icon">📊</span>
            <span>Dashboard</span>
          </button>
          <button class="nav-item" data-page="bahan-baku" id="nav-bahan-baku">
            <span class="nav-icon">📦</span>
            <span>Bahan Baku</span>
          </button>
          <button class="nav-item" data-page="satuan" id="nav-satuan">
            <span class="nav-icon">⚖️</span>
            <span>Satuan</span>
          </button>
          <button class="nav-item" data-page="supplier" id="nav-supplier">
            <span class="nav-icon">🏭</span>
            <span>Supplier</span>
          </button>
          <button class="nav-item" data-page="stock" id="nav-stock">
            <span class="nav-icon">📈</span>
            <span>Stock</span>
          </button>
          <button class="nav-item" data-page="stock-history">
            <span class="nav-icon">📋</span>
            <span>Stock History</span>
          </button>
          <button class="nav-item" data-page="users" id="nav-users">
            <span class="nav-icon">👥</span>
            <span>Users</span>
          </button>
        </nav>
      </div>

      <div class="main-content">
        <div class="header">
          <h2 id="page-title">Dashboard</h2>
          <div class="header-actions">
            <input
              type="text"
              id="search-input"
              class="search-box"
              placeholder="Search..."
            />
            <div class="user-info">
              <span id="user-name">{{ Auth::user()->name ?? 'User' }}</span>
              <span class="user-role" id="user-role">{{ strtoupper(Auth::user()->role ?? 'Role') }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
              @csrf
              <button type="submit" class="btn-logout">Logout</button>
            </form>
          </div>
        </div>

        <div class="content-area" id="content">
          <!-- Content will be rendered here -->
        </div>
      </div>
    </div>

    <div id="toast-container" class="toast-container"></div>

    <div id="modal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 id="modal-title">Modal</h3>
          <button class="modal-close" id="modal-close" type="button">&times;</button>
        </div>
        <div class="modal-body" id="modal-body">
          <!-- Form content will be rendered here -->
        </div>
      </div>
    </div>

    <script>
      // Setup CSRF token for all AJAX requests
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      
      window.apiHeaders = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      };
    </script>
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
  </body>
</html>
