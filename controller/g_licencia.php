<?php
include_once '../includes/config.php';
include_once '../includes/security.php';

date_default_timezone_set('America/Managua');

session_start();
require_once '../includes/auth_check.php';
require_ajax_auth();
$id = $_SESSION['id_u'];

if (!empty($_POST['licencia'])) {
    $licencia = clean(mysqli_real_escape_string($link,$_POST['licencia']));
    $fecha = date('Y-m-d H:i:s');

    // Guardar en el archivo
    file_put_contents(
        "../licencias.txt",
        "Licencia: $licencia, Fecha: $fecha\n",
        FILE_APPEND | LOCK_EX
    );

    // === Actualizar bandera en includes/config.php ===
    $configPath = realpath( '../includes/config.php');
    if ($configPath && is_writable($configPath)) {
        //@copy($configPath, $configPath . '.bak');
        $cfg = file_get_contents($configPath);

        // Cambia $activar_sistema = false; a true
        $pattern = '/(\$activar_sistema\s*=\s*)(true|false)\s*;/i';
        $replacement = '$1true;';
        $newCfg = preg_replace($pattern, $replacement, $cfg, 1, $count);

        if ($count === 0) {
            $newCfg .= PHP_EOL . '$activar_sistema = true;' . PHP_EOL;
        }

        $ok = file_put_contents($configPath, $newCfg, LOCK_EX);
        if ($ok === false) {
            echo 'No se pudo escribir en config.php';
        }
    } else {
        echo 'config.php no existe o no es escribible';
    }

    echo "bien";
} else {
    echo "mal";
}
