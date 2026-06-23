<?php
// controller/license_status.php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('America/Managua');

include_once '../includes/config.php';

$resp = [
    'subscription'      => 'Business', // cámbialo si lo quieres dinámico
    'activation_key'    => null,
    'activated_at'      => null,
    'expire_at'         => null,
    'licensed_devices'  => $devices,        // valor de referencia; ajusta si lo lees de BD
    'active_devices'    => $devices           // se puede calcular abajo
];

try {
    // 1) Ruta a licencias.txt
    $path = realpath(__DIR__ . '/../licencias.txt');
    if (!$path || !is_readable($path)) {
        throw new Exception('No puedo leer licencias.txt');
    }

    // 2) Leer la última línea NO vacía del archivo
    $file = new SplFileObject($path, 'r');
    $file->seek(PHP_INT_MAX);
    $lastLine = trim((string)$file->current());

    while ($file->key() > 0 && $lastLine === '') {
        $file->seek($file->key() - 1);
        $lastLine = trim((string)$file->current());
    }

    // 3) Parsear con un patrón estricto:
    //    "Licencia: <texto>, Fecha: YYYY-MM-DD HH:MM:SS"
    $re = '/^Licencia:\s*(.+?)\s*,\s*Fecha:\s*([0-9]{4}-[0-9]{2}-[0-9]{2}\s+[0-9]{2}:[0-9]{2}:[0-9]{2})$/u';
    if (!preg_match($re, $lastLine, $m)) {
        throw new Exception('Formato inesperado en licencias.txt. Última línea leída: ' . $lastLine);
    }

    $activationKey = trim($m[1]);
    $activatedAt   = trim($m[2]);

    // 4) Calcular expiración (+1 año exacto)
    $dt     = new DateTime($activatedAt);
    $expire = (clone $dt)->modify('+1 year');

    $resp['activation_key'] = $activationKey;
    $resp['activated_at']   = $dt->format('Y-m-d H:i:s');
    $resp['expire_at']      = $expire->format('Y-m-d H:i:s');

    // 5) (Opcional) contar dispositivos activos desde una tabla
    // Descomenta y ajusta a tu esquema si aplica.
    /*
    if ($link) {
        $q = mysqli_query($link, "SELECT COUNT(*) AS c FROM mobile_traking");
        if ($q && ($row = mysqli_fetch_assoc($q))) {
            $resp['active_devices'] = (int)$row['c'];
        }
    }
    */

    echo json_encode(['ok' => true, 'data' => $resp], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
