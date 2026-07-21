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

$idsRaw = isset($_POST['id_extension']) ? $_POST['id_extension'] : [];
if (!is_array($idsRaw) || count($idsRaw) === 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'No se recibieron extensiones para eliminar.']);
    exit;
}

$ids = [];
foreach ($idsRaw as $idValue) {
    if (!is_scalar($idValue)) {
        continue;
    }

    $id = (int) $idValue;
    if ($id > 0) {
        $ids[$id] = $id;
    }
}
$ids = array_values($ids);

if (count($ids) === 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'IDs de extensiones invalidos.']);
    exit;
}

$idListSql = implode(',', $ids);

$selectSql = "SELECT id_extension, extension FROM tbla_extensiones WHERE id_extension IN ($idListSql)";
$selectResult = mysqli_query($link, $selectSql);
if (!$selectResult) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo consultar las extensiones seleccionadas.']);
    exit;
}

$rows = [];
while ($row = mysqli_fetch_assoc($selectResult)) {
    $rows[] = $row;
}

if (count($rows) === 0) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'No se encontraron extensiones para eliminar.']);
    exit;
}

$filePath = __DIR__ . '/../endpoints/tel_endpoints.conf';
$fileContents = file_get_contents($filePath);
if ($fileContents === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo leer tel_endpoints.conf.']);
    exit;
}

$updatedContents = $fileContents;
foreach ($rows as $row) {
    $extension = trim((string) $row['extension']);
    if ($extension === '') {
        continue;
    }

    $pattern = '/;===============EXTENSION\s+' . preg_quote($extension, '/') . '(?:\r?\n).*?(?=(;===============EXTENSION\s+\d+)|\z)/s';
    $updatedContents = preg_replace($pattern, '', $updatedContents, 1);
}

$updatedContents = preg_replace("/\n{3,}/", "\n\n", $updatedContents);
$updatedContents = ltrim($updatedContents, "\n");
if ($updatedContents !== '' && substr($updatedContents, -1) !== "\n") {
    $updatedContents .= "\n";
}

mysqli_begin_transaction($link);

$deleteSql = "DELETE FROM tbla_extensiones WHERE id_extension IN ($idListSql)";
$deleteResult = mysqli_query($link, $deleteSql);
if (!$deleteResult) {
    mysqli_rollback($link);
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo eliminar en base de datos.']);
    exit;
}

$writeResult = file_put_contents($filePath, $updatedContents, LOCK_EX);
if ($writeResult === false) {
    mysqli_rollback($link);
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo actualizar tel_endpoints.conf.']);
    exit;
}

if (!mysqli_commit($link)) {
    mysqli_rollback($link);
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo confirmar la eliminacion.']);
    exit;
}

$command = "sudo /usr/bin/cp -r /var/www/ucs/endpoints/tel_endpoints.conf /etc/asterisk/pjsip.d";
exec($command, $output, $returnCode);

if ($returnCode !== 0) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Extensiones eliminadas, pero fallo al copiar el archivo de extensiones.']);
    exit;
}

$reloadCommand = "sudo systemctl reload asterisk";
exec($reloadCommand, $reloadOutput, $reloadReturnCode);
if ($reloadReturnCode !== 0) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Extensiones eliminadas, pero fallo al recargar Asterisk.']);
    exit;
}

echo json_encode([
    'ok' => true,
    'message' => count($rows) . ' extension(es) eliminada(s) correctamente.'
]);
?>
