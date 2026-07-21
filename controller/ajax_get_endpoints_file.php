<?php
session_start();
require_once '../includes/auth_check.php';
require_ajax_auth();

header('Content-Type: application/json; charset=utf-8');

$file_path = '/Applications/XAMPP/xamppfiles/htdocs/tarificador/endpoints/tel_endpoints.conf';

if (!file_exists($file_path)) {
    echo json_encode(['ok' => false, 'message' => 'El archivo tel_endpoints.conf no existe.']);
    exit;
}

$content = file_get_contents($file_path);
if ($content === false) {
    echo json_encode(['ok' => false, 'message' => 'No se pudo leer el archivo. Verifique permisos.']);
    exit;
}

echo json_encode(['ok' => true, 'data' => $content]);
?>
