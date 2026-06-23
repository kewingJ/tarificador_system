<?php
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
    
    echo json_encode(['ok' => true, 'message' => 'Cambios procesados correctamente.']);
?>