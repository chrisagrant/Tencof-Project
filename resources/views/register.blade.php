<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Ten Coffee</title>
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
          Oxygen, Ubuntu, Cantarell, sans-serif;
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
      }

      .register-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        width: 100%;
        max-width: 450px;
        animation: slideUp 0.5s ease-out;
      }

      @keyframes slideUp {
        from {
          opacity: 0;
          transform: translateY(30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .register-header {
        background: linear-gradient(135deg, #000000 0%, #333333 100%);
        color: white;
        padding: 40px 30px;
        text-align: center;
      }

      .register-header .coffee-icon {
        font-size: 48px;
        margin-bottom: 15px;
        animation: bounce 2s ease-in-out infinite;
      }

      @keyframes bounce {
        0%,
        100% {
          transform: translateY(0);
        }
        50% {
          transform: translateY(-10px);
        }
      }

      .register-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
      }

      .register-header p {
        font-size: 14px;
        opacity: 0.9;
      }

      .register-body {
        padding: 40px 30px;
      }

      .form-group {
        margin-bottom: 20px;
      }

      .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
      }

      .form-group input,
      .form-group select {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
        background-color: #f8f8f8;
        font-family: inherit;
      }

      .form-group input:focus,
      .form-group select:focus {
        outline: none;
        border-color: #000;
        background-color: white;
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05);
      }

      .form-group input::placeholder {
        color: #999;
      }

      .btn-register {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #000000 0%, #333333 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        margin-top: 10px;
      }

      .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
      }

      .btn-register:active {
        transform: translateY(0);
      }

      .divider {
        text-align: center;
        margin: 25px 0;
        position: relative;
      }

      .divider::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background-color: #e0e0e0;
      }

      .divider span {
        background-color: white;
        padding: 0 15px;
        position: relative;
        color: #999;
        font-size: 13px;
      }

      .login-link {
        text-align: center;
        font-size: 14px;
        color: #666;
      }

      .login-link a {
        color: #000;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .login-link a:hover {
        text-decoration: underline;
      }

      .alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
        display: none;
      }

      .alert.error {
        background-color: #fee;
        color: #c33;
        border: 1px solid #fcc;
      }

      .alert.success {
        background-color: #efe;
        color: #3c3;
        border: 1px solid #cfc;
      }

      .role-info {
        background-color: #f0f0f0;
        border-radius: 10px;
        padding: 12px;
        margin-top: 10px;
        font-size: 12px;
        color: #666;
      }

      .role-info strong {
        color: #333;
      }

      @media (max-width: 480px) {
        .register-container {
          border-radius: 15px;
        }
        .register-header {
          padding: 30px 20px;
        }
        .register-header h1 {
          font-size: 24px;
        }
        .register-body {
          padding: 30px 20px;
        }
      }
    </style>
  </head>
  <body>
    <div class="register-container">
      <div class="register-header">
        <div class="coffee-icon">☕</div>
        <h1>Daftar Akun</h1>
        <p>Ten Coffee Inventory System</p>
      </div>

      <div class="register-body">
        <div id="alert" class="alert"></div>

        <form id="registerForm">
          <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input
              type="text"
              id="name"
              placeholder="Masukkan nama lengkap"
              required
            />
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input
              type="email"
              id="email"
              placeholder="Masukkan email"
              required
            />
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              placeholder="Minimal 6 karakter"
              required
              minlength="6"
            />
          </div>

          <div class="form-group">
            <label for="confirmPassword">Konfirmasi Password</label>
            <input
              type="password"
              id="confirmPassword"
              placeholder="Ulangi password"
              required
              minlength="6"
            />
          </div>

          <div class="form-group">
            <label for="role">Role</label>
            <select id="role" required>
              <option value="">Pilih Role</option>
              <option value="owner">Owner</option>
              <option value="admin">Admin</option>
              <option value="kasir">Kasir</option>
            </select>
            <div class="role-info">
              <strong>Owner:</strong> Full access semua fitur<br />
              <strong>Admin:</strong> Kelola inventory (tanpa user
              management)<br />
              <strong>Kasir:</strong> View-only stock & history
            </div>
          </div>

          <button type="submit" class="btn-register">Daftar</button>
        </form>

        <div class="divider">
          <span>atau</span>
        </div>

        <div class="login-link">
          Sudah punya akun? <a href="{{ url('/login') }}">Masuk di sini</a>
        </div>
      </div>
    </div>

    <script src="{{ asset('js/auth.js') }}"></script>
    <script>
      // Initialize users
      initializeUsers();

      // Check if already logged in
      if (isAuthenticated()) { 
        window.location.href = "/";
      }

      const registerForm = document.getElementById("registerForm");
      const alertDiv = document.getElementById("alert");

      function showAlert(message, type = "error") {
        alertDiv.textContent = message;
        alertDiv.className = `alert ${type}`;
        alertDiv.style.display = "block";

        setTimeout(() => {
          alertDiv.style.display = "none";
        }, 5000);
      }

      registerForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const confirmPassword =
          document.getElementById("confirmPassword").value;
        const role = document.getElementById("role").value;

        // Validation
        if (password !== confirmPassword) {
          showAlert("Password dan konfirmasi password tidak cocok", "error");
          return;
        }

        if (password.length < 6) {
          showAlert("Password minimal 6 karakter", "error");
          return;
        }

        if (!role) {
          showAlert("Silakan pilih role", "error");
          return;
        }

        register(name, email, password, role);
      });
    </script>
  </body>
</html>
