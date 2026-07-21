<?php
session_start();
require_once '../includes/auth_check.php';
require_ajax_auth();

include_once '../includes/config.php';
include_once '../includes/security.php';

date_default_timezone_set('America/Managua');

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Metodo no permitido.']);
    exit;
}

$idExtension = isset($_POST['id_extension']) ? (int) $_POST['id_extension'] : 0;
if ($idExtension <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'ID invalido.']);
    exit;
}

$sql = "SELECT id_extension, extension, tipo_extension, callerid, auth, aors, mailboxes, voicemail_extension, transport, media_encryption, media_encryption_optimistic, password, username, voicemail_extension2
        FROM tbla_extensiones
        WHERE id_extension = ?
        LIMIT 1";

$stmt = mysqli_prepare($link, $sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo preparar la consulta.']);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $idExtension);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = $result ? mysqli_fetch_assoc($result) : null;
mysqli_stmt_close($stmt);

if (!$row) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'Extension no encontrada.']);
    exit;
}

$filePath = __DIR__ . '/../endpoints/tel_endpoints.conf';
$fileContents = file_get_contents($filePath);
if ($fileContents === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo leer tel_endpoints.conf.']);
    exit;
}

$pattern = '/;===============EXTENSION\\s+' . preg_quote($row['extension'], '/') . '(?:\\r?\\n).*?(?=(;===============EXTENSION\\s+\\d+)|\\z)/s';
if (!preg_match($pattern, $fileContents, $matches)) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'No se encontro el bloque en tel_endpoints.conf.']);
    exit;
}

$confText = rtrim($matches[0], "\r\n");

echo json_encode(['ok' => true, 'data' => $row, 'conf_text' => $confText]);
