<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

date_default_timezone_set('America/Managua');

// 1. Cargar el guardián oculto (él mismo comprueba si /client existe)
if (file_exists(__DIR__ . '/includes/.license_guard.php')) {
    require_once __DIR__ . '/includes/.license_guard.php';
}

// 2. Cargar helpers y banner SOLAMENTE si la carpeta client existe
if (is_dir(__DIR__ . '/client') && file_exists(__DIR__ . '/client/license_helpers.php')) {
    require_once __DIR__ . '/client/license_helpers.php';
    if (function_exists('license_client_render_banner')) {
      // license_client_render_banner();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Smart Voice Server - Login</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Fuente opcional -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
  </style>
</head>
<body class="min-h-screen text-white">
  <div class="relative min-h-screen">
    <!-- Fondo -->
    <div
      class="absolute inset-0 bg-no-repeat bg-cover bg-center"
      style="background-image: url('img/Background_Smart-voice-server.jpg');">
    </div>

    <!-- Capa para contraste -->
    <div class="absolute inset-0 bg-blue-900/30"></div>

    <!-- Contenido -->
    <div class="relative z-10 min-h-screen flex items-center justify-center">
      <!-- Form movido hacia la derecha -->
      <div class="w-full max-w-sm space-y-6 bg-transparent
                  transform translate-x-6 md:translate-x-24 lg:translate-x-40 xl:translate-x-52">

        <!-- (opcional) título -->
        <div class="mb-2">
          <h1 class="text-3xl font-semibold tracking-tight">
            <span class="text-yellow-400"></span>
            <span class="text-white"></span>
          </h1>
          <p class="text-sm text-slate-100/80 mt-2"></p>
        </div>

        <!-- Formulario -->
        <form action="login.php" id="Form1" method="POST" class="space-y-4">
          <div>
            <label for="username" class="block text-xs font-medium text-slate-100/80 mb-1">
              Username
            </label>
            <input
              id="email"
              name="email"
              type="text"
              autocomplete="email"
              placeholder="admin"
              class="w-full px-3 py-2 text-sm rounded border border-white/50 bg-transparent
                     text-white placeholder-white/70
                     focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300"
              required
            />
          </div>

          <div>
            <label for="password" class="block text-xs font-medium text-slate-100/80 mb-1">
              Password
            </label>
            <input
              id="password"
              name="password"
              type="password"
              autocomplete="current-password"
              placeholder="••••••••"
              class="w-full px-3 py-2 text-sm rounded border border-white/50 bg-transparent
                     text-white placeholder-white/70
                     focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300"
              required
            />
          </div>

          <!-- <div class="flex items-center text-xs text-slate-100/80 mt-1">
            <input
              id="remember"
              name="remember"
              type="checkbox"
              class="h-3.5 w-3.5 rounded border-white/60 bg-transparent text-blue-500
                     focus:ring-blue-300 focus:ring-1 mr-2"
            />
            <label for="remember">Keep me logged in</label>
          </div> -->

          <button
            type="submit"
            class="mt-2 w-full inline-flex items-center justify-center px-4 py-2.5 rounded
                   bg-white/95 hover:bg-white active:bg-slate-100
                   text-blue-700 font-medium text-sm tracking-wide
                   shadow-md transition">
            Login
          </button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
