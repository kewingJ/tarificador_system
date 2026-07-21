<?php
// Guardas de acceso centralizadas. Requiere sesion ya iniciada (session_start()).

const APP_IDLE_TIMEOUT_SECONDS = 1200; // 20 minutos de inactividad

// Devuelve false y destruye la sesion si paso el tiempo de inactividad permitido.
// Si la sesion sigue viva, actualiza la marca de "ultimo acceso" (ventana deslizante).
function _app_check_idle_timeout()
{
    if (!empty($_SESSION['ultimo_acceso']) && (time() - $_SESSION['ultimo_acceso']) > APP_IDLE_TIMEOUT_SECONDS) {
        $_SESSION = [];
        session_destroy();
        return false;
    }
    $_SESSION['ultimo_acceso'] = time();
    return true;
}

function require_web_auth($tipoRequerido = null, $redirectPath = 'index.php')
{
    if (empty($_SESSION['id_u']) || empty($_SESSION['activo']) || !_app_check_idle_timeout()) {
        header('Location: ' . $redirectPath);
        exit;
    }
    if ($tipoRequerido !== null && (int) ($_SESSION['tipo_usuario'] ?? 0) !== (int) $tipoRequerido) {
        header('Location: ' . $redirectPath);
        exit;
    }
}

function require_ajax_auth()
{
    if (empty($_SESSION['id_u']) || empty($_SESSION['activo']) || !_app_check_idle_timeout()) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'message' => 'No autorizado.']);
        exit;
    }
}

// Para controladores que tambien se invocan por cron (php archivo.php),
// donde no existe sesion HTTP. Permite CLI sin sesion, exige sesion por HTTP.
function require_ajax_auth_or_cli()
{
    if (php_sapi_name() === 'cli') {
        return;
    }
    require_ajax_auth();
}

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_validate($token)
{
    return !empty($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

// Llamar despues de require_ajax_auth() en endpoints que cambian estado.
function require_csrf()
{
    $token = $_POST['csrf_token'] ?? '';
    if (!csrf_validate($token)) {
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'message' => 'Token de seguridad invalido o expirado. Recarga la pagina.']);
        exit;
    }
}
