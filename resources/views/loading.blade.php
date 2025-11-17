<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Loading - Ten Coffee</title>
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
        height: 100vh;
        overflow: hidden;
      }

      .loading-container {
        text-align: center;
        color: white;
      }

      .logo {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: pulse 2s ease-in-out infinite;
      }

      .coffee-icon {
        font-size: 64px;
        margin-bottom: 30px;
        animation: bounce 1.5s ease-in-out infinite;
      }

      .loading-text {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 30px;
        letter-spacing: 2px;
      }

      .loading-bar {
        width: 300px;
        height: 4px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
        margin: 0 auto;
      }

      .loading-progress {
        height: 100%;
        background: linear-gradient(90deg, #ffffff 0%, #e0e0e0 100%);
        border-radius: 10px;
        animation: loading 2s ease-in-out infinite;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
      }

      .dots {
        display: inline-block;
        margin-left: 5px;
      }

      .dots span {
        animation: blink 1.4s infinite;
        font-size: 24px;
      }

      .dots span:nth-child(2) {
        animation-delay: 0.2s;
      }

      .dots span:nth-child(3) {
        animation-delay: 0.4s;
      }

      @keyframes pulse {
        0%,
        100% {
          transform: scale(1);
          opacity: 1;
        }
        50% {
          transform: scale(1.05);
          opacity: 0.8;
        }
      }

      @keyframes bounce {
        0%,
        100% {
          transform: translateY(0);
        }
        50% {
          transform: translateY(-20px);
        }
      }

      @keyframes loading {
        0% {
          width: 0%;
        }
        50% {
          width: 70%;
        }
        100% {
          width: 100%;
        }
      }

      @keyframes blink {
        0%,
        100% {
          opacity: 0.2;
        }
        50% {
          opacity: 1;
        }
      }

      @media (max-width: 480px) {
        .logo {
          font-size: 36px;
        }
        .coffee-icon {
          font-size: 48px;
        }
        .loading-text {
          font-size: 14px;
        }
        .loading-bar {
          width: 250px;
        }
      }
    </style>
  </head>
  <body>
    <div class="loading-container">
      <div class="coffee-icon">☕</div>
      <div class="logo">Ten Coffee</div>
      <div class="loading-text">Inventory Management System</div>
      <div class="loading-bar">
        <div class="loading-progress"></div>
      </div>
      <div class="loading-text" style="margin-top: 20px; font-size: 14px">
        Loading<span class="dots"
          ><span>.</span><span>.</span><span>.</span></span
        >
      </div>
    </div>

    <script>
      // Redirect to login page after 2.5 seconds
      setTimeout(() => {
        window.location.href = "login.html";
      }, 2500);
    </script>
  </body>
</html>
