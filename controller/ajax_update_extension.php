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
$confText = isset($_POST['conf_text']) ? trim($_POST['conf_text']) : '';

if ($idExtension <= 0 || $confText === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$parseBlock = function ($text) {
    $data = [
        'extension' => '',
        'tipo_extension' => '',
        'callerid' => '',
        'auth' => '',
        'aors' => '',
        'mailboxes' => '',
        'voicemail_extension' => '',
        'transport' => '',
        'media_encryption' => '',
        'media_encryption_optimistic' => '',
        'password' => '',
        'username' => '',
        'voicemail_extension2' => ''
    ];

    $headerExtension = '';
    $currentSectionType = '';
    $text = str_replace("\r\n", "\n", $text);
    $lines = explode("\n", $text);

    foreach ($lines as $line) {
        $line = rtrim($line);
        if ($line === '') {
            continue;
        }

        if (preg_match('/^;\s*=+\s*EXTENSION\s+(\d+)/i', $line, $match)) {
            if ($headerExtension === '') {
                $headerExtension = $match[1];
            }
            continue;
        }

        if (preg_match('/^\[([^\]]+)\]\(([^\)]+)\)\s*$/', $line, $match)) {
            $sectionName = trim($match[1]);
            $sectionType = trim($match[2]);
            $currentSectionType = $sectionType;
            if (strpos($sectionType, 'endpoint-') === 0) {
                $data['extension'] = $sectionName;
                $data['tipo_extension'] = $sectionType;
            }
            continue;
        }

        if (preg_match('/^([a-zA-Z_]+)=(.*)$/', $line, $match)) {
            $key = strtolower($match[1]);
            $value = $match[2];

            if (strpos($currentSectionType, 'endpoint-') === 0) {
                switch ($key) {
                    case 'callerid':
                        $data['callerid'] = $value;
                        break;
                    case 'auth':
                        $data['auth'] = $value;
                        break;
                    case 'aors':
                        $data['aors'] = $value;
                        break;
                    case 'mailboxes':
                        $data['mailboxes'] = $value;
                        break;
                    case 'voicemail_extension':
                        $data['voicemail_extension'] = $value;
                        break;
                    case 'transport':
                        $data['transport'] = $value;
                        break;
                    case 'media_encryption':
                        $data['media_encryption'] = $value;
                        break;
                    case 'media_encryption_optimistic':
                        $data['media_encryption_optimistic'] = $value;
                        break;
                }
            } elseif ($currentSectionType === 'auth-userpass') {
                if ($key === 'password') {
                    $data['password'] = $value;
                } elseif ($key === 'username') {
                    $data['username'] = $value;
                }
            } elseif ($currentSectionType === 'aor-single-reg') {
                if ($key === 'voicemail_extension') {
                    $data['voicemail_extension2'] = $value;
                }
            }
        }
    }

    if ($headerExtension !== '' && $data['extension'] !== '' && $headerExtension !== $data['extension']) {
        return ['ok' => false, 'message' => 'El encabezado de la extension no coincide con el bloque.'];
    }

    if ($data['extension'] === '' && $headerExtension !== '') {
        $data['extension'] = $headerExtension;
    }

    if ($data['extension'] === '' || !preg_match('/^\d+$/', $data['extension'])) {
        return ['ok' => false, 'message' => 'Extension invalida en el bloque.'];
    }

    $required = [
        'tipo_extension',
        'auth',
        'aors',
        'mailboxes',
        'voicemail_extension',
        'transport',
        'password',
        'username',
        'voicemail_extension2'
    ];

    foreach ($required as $field) {
        if ($data[$field] === '') {
            return ['ok' => false, 'message' => 'Faltan datos obligatorios en el bloque.'];
        }
    }

    return ['ok' => true, 'data' => $data];
};

$normalizedConf = str_replace("\r\n", "\n", $confText);
$trimmedConf = ltrim($normalizedConf);
if (!preg_match('/^;\s*=+\s*EXTENSION\s+\d+/i', $trimmedConf)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'El bloque debe iniciar con el encabezado de la extension.']);
    exit;
}

if (preg_match_all('/^;\s*=+\s*EXTENSION\s+\d+/mi', $normalizedConf, $headerMatches) && count($headerMatches[0]) > 1) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Solo se permite un bloque por edicion.']);
    exit;
}

$parsed = $parseBlock($normalizedConf);
if (!$parsed['ok']) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => $parsed['message']]);
    exit;
}

$newRow = $parsed['data'];

$sqlOld = "SELECT id_extension, extension, tipo_extension, callerid, auth, aors, mailboxes, voicemail_extension, transport, media_encryption, media_encryption_optimistic, password, username, voicemail_extension2
           FROM tbla_extensiones
           WHERE id_extension = ?
           LIMIT 1";
$stmtOld = mysqli_prepare($link, $sqlOld);
if (!$stmtOld) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo preparar la consulta.']);
    exit;
}
mysqli_stmt_bind_param($stmtOld, 'i', $idExtension);
mysqli_stmt_execute($stmtOld);
$resultOld = mysqli_stmt_get_result($stmtOld);
$oldRow = $resultOld ? mysqli_fetch_assoc($resultOld) : null;
mysqli_stmt_close($stmtOld);

if (!$oldRow) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'Extension no encontrada.']);
    exit;
}

if ($oldRow['extension'] !== $newRow['extension']) {
    $sqlDup = "SELECT id_extension FROM tbla_extensiones WHERE extension = ? AND id_extension <> ? LIMIT 1";
    $stmtDup = mysqli_prepare($link, $sqlDup);
    if ($stmtDup) {
        mysqli_stmt_bind_param($stmtDup, 'si', $newRow['extension'], $idExtension);
        mysqli_stmt_execute($stmtDup);
        $dupResult = mysqli_stmt_get_result($stmtDup);
        $dupRow = $dupResult ? mysqli_fetch_assoc($dupResult) : null;
        mysqli_stmt_close($stmtDup);
        if ($dupRow) {
            http_response_code(409);
            echo json_encode(['ok' => false, 'message' => 'La extension ya existe.']);
            exit;
        }
    }
}

$filePath = __DIR__ . '/../endpoints/tel_endpoints.conf';
$fileContents = file_get_contents($filePath);
if ($fileContents === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo leer tel_endpoints.conf.']);
    exit;
}

if ($oldRow['extension'] !== $newRow['extension']) {
    $patternNew = '/;===============EXTENSION\s+' . preg_quote($newRow['extension'], '/') . '(?:\r?\n)/';
    if (preg_match($patternNew, $fileContents)) {
        http_response_code(409);
        echo json_encode(['ok' => false, 'message' => 'La extension ya existe en tel_endpoints.conf.']);
        exit;
    }
}

$patternOld = '/;===============EXTENSION\s+' . preg_quote($oldRow['extension'], '/') . '(?:\r?\n).*?(?=(;===============EXTENSION\s+\d+)|\z)/s';
if (!preg_match($patternOld, $fileContents)) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'No se encontro el bloque en tel_endpoints.conf.']);
    exit;
}

$normalizedConf = rtrim($normalizedConf, "\r\n") . "\n\n";
$updatedContents = preg_replace($patternOld, $normalizedConf, $fileContents, 1);
if ($updatedContents === null) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error al actualizar el bloque en tel_endpoints.conf.']);
    exit;
}

$updateSql = "UPDATE tbla_extensiones SET extension = ?, tipo_extension = ?, callerid = ?, auth = ?, aors = ?, mailboxes = ?, voicemail_extension = ?, transport = ?, media_encryption = ?, media_encryption_optimistic = ?, password = ?, username = ?, voicemail_extension2 = ? WHERE id_extension = ? LIMIT 1";
$stmtUpdate = mysqli_prepare($link, $updateSql);
if (!$stmtUpdate) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo preparar la actualizacion.']);
    exit;
}

mysqli_stmt_bind_param(
    $stmtUpdate,
    'sssssssssssssi',
    $newRow['extension'],
    $newRow['tipo_extension'],
    $newRow['callerid'],
    $newRow['auth'],
    $newRow['aors'],
    $newRow['mailboxes'],
    $newRow['voicemail_extension'],
    $newRow['transport'],
    $newRow['media_encryption'],
    $newRow['media_encryption_optimistic'],
    $newRow['password'],
    $newRow['username'],
    $newRow['voicemail_extension2'],
    $idExtension
);

if (!mysqli_stmt_execute($stmtUpdate)) {
    mysqli_stmt_close($stmtUpdate);
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo actualizar en BD.']);
    exit;
}
mysqli_stmt_close($stmtUpdate);

$writeResult = file_put_contents($filePath, $updatedContents, LOCK_EX);
if ($writeResult === false) {
    $revertSql = "UPDATE tbla_extensiones SET extension = ?, tipo_extension = ?, callerid = ?, auth = ?, aors = ?, mailboxes = ?, voicemail_extension = ?, transport = ?, media_encryption = ?, media_encryption_optimistic = ?, password = ?, username = ?, voicemail_extension2 = ? WHERE id_extension = ? LIMIT 1";
    $stmtRevert = mysqli_prepare($link, $revertSql);
    if ($stmtRevert) {
        mysqli_stmt_bind_param(
            $stmtRevert,
            'sssssssssssssi',
            $oldRow['extension'],
            $oldRow['tipo_extension'],
            $oldRow['callerid'],
            $oldRow['auth'],
            $oldRow['aors'],
            $oldRow['mailboxes'],
            $oldRow['voicemail_extension'],
            $oldRow['transport'],
            $oldRow['media_encryption'],
            $oldRow['media_encryption_optimistic'],
            $oldRow['password'],
            $oldRow['username'],
            $oldRow['voicemail_extension2'],
            $idExtension
        );
        mysqli_stmt_execute($stmtRevert);
        mysqli_stmt_close($stmtRevert);
    }

    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo escribir tel_endpoints.conf.']);
    exit;
}

// lanzar comando "sudo /usr/bin/cp -r /var/www/ucs/endpoints/ext_endpoints.conf"
$command = "sudo /usr/bin/cp -r /var/www/ucs/endpoints/tel_endpoints.conf /etc/asterisk/pjsip.d";
exec($command, $output, $returnCode);

if ($returnCode !== 0) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error al copiar el archivo de extensiones.']);
    exit;
}

// lanzar comando "sudo systemctl reload asterisk"
$reloadCommand = "sudo systemctl reload asterisk";
exec($reloadCommand, $reloadOutput, $reloadReturnCode);
if ($reloadReturnCode !== 0) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error al recargar Asterisk.']);
    exit;
}

echo json_encode(['ok' => true, 'message' => 'Extension actualizada.']);
