<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Ten Coffee</title>
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

      .login-container {
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

      .login-header {
        background: linear-gradient(135deg, #000000 0%, #333333 100%);
        color: white;
        padding: 40px 30px;
        text-align: center;
      }

      .login-header .coffee-icon {
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

      .login-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
      }

      .login-header p {
        font-size: 14px;
        opacity: 0.9;
      }

      .login-body {
        padding: 40px 30px;
      }

      .form-group {
        margin-bottom: 25px;
      }

      .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
      }

      .form-group input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
        background-color: #f8f8f8;
      }

      .form-group input:focus {
        outline: none;
        border-color: #000;
        background-color: white;
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05);
      }

      .form-group input::placeholder {
        color: #999;
      }

      .role-selector {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 25px;
      }

      .role-option {
        position: relative;
      }

      .role-option input[type="radio"] {
        position: absolute;
        opacity: 0;
      }

      .role-option label {
        display: block;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #f8f8f8;
        font-size: 13px;
        font-weight: 600;
      }

      .role-option input[type="radio"]:checked + label {
        border-color: #000;
        background-color: #000;
        color: white;
        transform: scale(1.05);
      }

      .role-option label:hover {
        border-color: #666;
      }

      .btn-login {
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
      }

      .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
      }

      .btn-login:active {
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

      .register-link {
        text-align: center;
        font-size: 14px;
        color: #666;
      }

      .register-link a {
        color: #000;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .register-link a:hover {
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

      .demo-credentials {
        background-color: #f0f0f0;
        border-radius: 10px;
        padding: 15px;
        margin-top: 20px;
        font-size: 12px;
        color: #666;
      }

      .demo-credentials h4 {
        margin-bottom: 10px;
        color: #333;
        font-size: 13px;
      }

      .demo-credentials p {
        margin: 5px 0;
        font-family: monospace;
      }

      @media (max-width: 480px) {
        .login-container {
          border-radius: 15px;
        }
        .login-header {
          padding: 30px 20px;
        }
        .login-header h1 {
          font-size: 24px;
        }
        .login-body {
          padding: 30px 20px;
        }
        .role-selector {
          grid-template-columns: 1fr;
        }
      }
    </style>
  </head>
  <body>
    <div class="login-container">
      <div class="login-header">
        <div class="coffee-icon">☕</div>
        <h1>Ten Coffee</h1>
        <p>Inventory Management System</p>
      </div>

      <div class="login-body">
        <div id="alert" class="alert"></div>

        <form id="loginForm">
          <div class="form-group">
            <label for="email">Email</label>
            <input
              type="email"
              id="email"
              placeholder="masukkan email anda"
              required
            />
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              placeholder="masukkan password anda"
              required
            />
          </div>

          <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="divider">
          <span>atau</span>
        </div>

        <div class="register-link">
          Belum punya akun? <a href="{{ url('/register') }}">Daftar di sini</a>
        </div>

        <div class="demo-credentials">
          <h4>🔑 Demo Credentials:</h4>
          <p>
            <strong>Owner:</strong>
            <a
              href="/cdn-cgi/l/email-protection"
              class="__cf_email__"
              data-cfemail="acc3dbc2c9deecd8c9c2cfc3cacac9c982cfc3c1"
              >[email&#160;protected]</a
            >
            / owner123
          </p>
          <p>
            <strong>Admin:</strong>
            <a
              href="/cdn-cgi/l/email-protection"
              class="__cf_email__"
              data-cfemail="48292c252126083c2d262b272e2e2d2d662b2725"
              >[email&#160;protected]</a
            >
            / admin123
          </p>
          <p>
            <strong>Kasir:</strong>
            <a
              href="/cdn-cgi/l/email-protection"
              class="__cf_email__"
              data-cfemail="1c777d6f756e5c6879727f737a7a7979327f7371"
              >[email&#160;protected]</a
            >
            / kasir123
          </p>
        </div>
      </div>
    </div>

    <script
      data-cfasync="false"
      src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"
    ></script>
    <script src="{{ asset('js/auth.js') }}"></script>
    <script>
      // Initialize users
      initializeUsers();

      // Check if already logged in
      if (isAuthenticated()) {
        window.location.href = "/";
      }

      const loginForm = document.getElementById("loginForm");
      const alertDiv = document.getElementById("alert");

      function showAlert(message, type = "error") {
        alertDiv.textContent = message;
        alertDiv.className = `alert ${type}`;
        alertDiv.style.display = "block";

        setTimeout(() => {
          alertDiv.style.display = "none";
        }, 5000);
      }

      loginForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        login(email, password);
      });
    </script>
  </body>
</html>
