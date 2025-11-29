<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
              <span id="user-name">{{ Auth::user()->name }}</span>
              <span class="user-role" id="user-role">{{ ucfirst(Auth::user()->role->value ?? Auth::user()->role) }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
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

    <div id="toast-container" class="toast-container">
        @if(session('success'))
            <div class="toast success" style="display: flex;">
                <span class="toast-icon">✓</span>
                <span class="toast-message">{{ session('success') }}</span>
            </div>
            <script>
                setTimeout(() => {
                    document.querySelector('.toast.success').remove();
                }, 3000);
            </script>
        @endif
    </div>

    <div id="modal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 id="modal-title">Modal</h3>
          <button class="modal-close" id="modal-close">&times;</button>
        </div>
        <div class="modal-body" id="modal-body">
          <!-- Form content will be rendered here -->
        </div>
      </div>
    </div>

    <script>
        const laravelUser = {
            id: {{ Auth::id() }},
            name: "{{ Auth::user()->name }}",
            email: "{{ Auth::user()->email }}",
            role: "{{ Auth::user()->role->value ?? Auth::user()->role }}"
        };

        function getCurrentUser() {
            return laravelUser;
        }

        function requireAuth() { return true; }
        function isAuthenticated() { return true; }

        function hasRole(role) {
            return laravelUser.role === role;
        }
    </script>

    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            applyRoleBasedAccess();
        });

        function applyRoleBasedAccess() {
            const user = getCurrentUser();

            console.log("Logged in as:", user.role);

            // Kasir role restrictions
            if (user.role === "kasir") {
                // Hide management buttons for kasir
                const restrictedPages = ["bahan-baku", "satuan", "supplier", "stock"];
                restrictedPages.forEach((page) => {
                    // Sembunyikan Nav Item
                    const navItem = document.querySelector(`[data-page="${page}"]`);
                    if (navItem) navItem.style.display = "none";
                });

                const usersNav = document.getElementById("nav-users");
                if (usersNav) usersNav.style.display = "none";
            }

            if (user.role === "admin") {
                const usersNav = document.getElementById("nav-users");
                if (usersNav) {
                    usersNav.style.display = "none";
                }
            }
        }
    </script>
  </body>
</html>
