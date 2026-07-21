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

    $numeracion = isset($_POST['numeracion']) ? trim($_POST['numeracion']) : '';
    $cantidadRaw = isset($_POST['cantidad']) ? trim($_POST['cantidad']) : '';
    $transporte = isset($_POST['transporte']) ? trim($_POST['transporte']) : '';
    $plan = isset($_POST['plan']) ? trim($_POST['plan']) : '';

    if ($numeracion === '' || $cantidadRaw === '' || $transporte === '' || $plan === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    if (!preg_match('/^\d+$/', $numeracion)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Numeracion invalida.']);
        exit;
    }

    if (!preg_match('/^\d+$/', $cantidadRaw)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Cantidad invalida.']);
        exit;
    }

    $cantidad = (int) $cantidadRaw;
    if ($cantidad <= 0) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Cantidad debe ser mayor a cero.']);
        exit;
    }

    $transportesPermitidos = ['udp', 'tcp', 'tls', 'tcp_udp', 'udp_tls'];
    if (!in_array($transporte, $transportesPermitidos, true)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Transporte invalido.']);
        exit;
    }

    $planesPermitidos = ['pro', 'basic', 'mixto'];
    if (!in_array($plan, $planesPermitidos, true)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Plan invalido.']);
        exit;
    }

    $filePath = __DIR__ . '/../endpoints/tel_endpoints.conf';

    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $alphabetLength = strlen($alphabet);

    $generateRandom = function ($length) use ($alphabet, $alphabetLength) {
        $value = '';
        for ($i = 0; $i < $length; $i++) {
            $value .= $alphabet[random_int(0, $alphabetLength - 1)];
        }
        return $value;
    };

    $inicio = (int) $numeracion;
    $blocks = [];
    $rows = [];

    for ($i = 0; $i <= $cantidad; $i++) {
        $extension = $inicio + $i;
        $planActual = $plan;
        if ($plan === 'mixto') {
            $planActual = ($i % 2 === 0) ? 'pro' : 'basic';
        }

        if ($transporte === 'tcp_udp') {
            $transporteActual = ($i % 2 === 0) ? 'tcp' : 'udp';
        } elseif ($transporte === 'udp_tls') {
            $transporteActual = ($i % 2 === 0) ? 'udp' : 'tls';
        } else {
            $transporteActual = $transporte;
        }

        $endpoint = $planActual === 'pro' ? 'endpoint-pro' : 'endpoint-basic';
        $username = $generateRandom(8);
        $password = $generateRandom(12);
        $transportLine = $transporteActual . '-transport';
        $mediaEncryption = $transporteActual !== 'udp' ? 'sdes' : '';
        $mediaEncryptionOptimistic = '';
        if ($transporteActual === 'tls') {
            $mediaEncryptionOptimistic = 'yes';
        } elseif ($transporteActual === 'tcp') {
            $mediaEncryptionOptimistic = 'no';
        }

        $block = ";===============EXTENSION " . $extension . "\n";
        $block .= "[" . $extension . "](" . $endpoint . ")\n";
        $block .= "type=endpoint\n";
        $block .= "callerid=<" . $extension . ">\n";
        $block .= "auth=auth" . $extension . "\n";
        $block .= "aors=" . $extension . "\n";
        $block .= "mailboxes=" . $extension . "@default\n";
        $block .= "voicemail_extension=" . $extension . "\n";
        $block .= "transport=" . $transportLine . "\n";

        if ($transporteActual !== 'udp') {
            $block .= "media_encryption=" . $mediaEncryption . "\n";
            $block .= "media_encryption_optimistic=" . $mediaEncryptionOptimistic . "\n";
        }

        $block .= "\n";
        $block .= "[auth" . $extension . "](auth-userpass)\n";
        $block .= "password=" . $password . "\n";
        $block .= "username=" . $username . "\n\n";
        $block .= "[" . $extension . "](aor-single-reg)\n";
        $block .= "voicemail_extension=" . $extension . "\n";

        $blocks[] = $block;
        $rows[] = [
            'extension' => (string) $extension,
            'tipo_extension' => $endpoint,
            'callerid' => '<'.$extension.'>',
            'auth' => 'auth' . $extension,
            'aors' => (string) $extension,
            'mailboxes' => $extension . '@default',
            'voicemail_extension' => (string) $extension,
            'transport' => $transportLine,
            'media_encryption' => $mediaEncryption,
            'media_encryption_optimistic' => $mediaEncryptionOptimistic,
            'password' => $password,
            'username' => $username,
            'voicemail_extension2' => (string) $extension
        ];
    }

    $payload = implode("\n\n", $blocks) . "\n\n";

    $writeResult = file_put_contents($filePath, $payload, FILE_APPEND | LOCK_EX);
    if ($writeResult === false) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'message' => 'No se pudo escribir tel_endpoints.conf.']);
        exit;
    }

    $insertSql = "INSERT INTO tbla_extensiones (extension, tipo_extension, callerid, auth, aors, mailboxes, voicemail_extension, transport, media_encryption, media_encryption_optimistic, password, username, voicemail_extension2) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = mysqli_prepare($link, $insertSql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'message' => 'No se pudo preparar el guardado en BD.']);
        exit;
    }

    foreach ($rows as $row) {
        $extensionDb = $row['extension'];
        $tipoExtensionDb = $row['tipo_extension'];
        $calleridDb = $row['callerid'];
        $authDb = $row['auth'];
        $aorsDb = $row['aors'];
        $mailboxesDb = $row['mailboxes'];
        $voicemailExtensionDb = $row['voicemail_extension'];
        $transportDb = $row['transport'];
        $mediaEncryptionDb = $row['media_encryption'];
        $mediaEncryptionOptimisticDb = $row['media_encryption_optimistic'];
        $passwordDb = $row['password'];
        $usernameDb = $row['username'];
        $voicemailExtension2Db = $row['voicemail_extension2'];

        mysqli_stmt_bind_param(
            $stmt,
            'sssssssssssss',
            $extensionDb,
            $tipoExtensionDb,
            $calleridDb,
            $authDb,
            $aorsDb,
            $mailboxesDb,
            $voicemailExtensionDb,
            $transportDb,
            $mediaEncryptionDb,
            $mediaEncryptionOptimisticDb,
            $passwordDb,
            $usernameDb,
            $voicemailExtension2Db
        );

        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            http_response_code(500);
            echo json_encode(['ok' => false, 'message' => 'No se pudo guardar en BD.']);
            exit;
        }
    }

    mysqli_stmt_close($stmt);

    // lanzar comando "sudo /usr/bin/cp -r /var/www/ucs/endpoints/ext_endpoints.conf"
    $command = "sudo /usr/bin/cp -r /var/www/ucs/endpoints/tel_endpoints.conf /etc/asterisk/pjsip.d";
    exec($command, $output, $returnCode);

    if ($returnCode !== 0) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'message' => 'Error al copiar el archivo de extensiones.']);
        exit;
    }

    // lanzar comando "sudo systemctl reload asterisk"
    $reloadCommand = "sudo /usr/sbin/asterisk -rx ‘core reload’";
    exec($reloadCommand, $reloadOutput, $reloadReturnCode);
    if ($reloadReturnCode !== 0) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'message' => 'Error al recargar Asterisk.']);
        exit;
    }

    $reloadCommand = "sudo /usr/sbin/asterisk -rx 'pjsip reload’";
    exec($reloadCommand, $reloadOutput, $reloadReturnCode);
    if ($reloadReturnCode !== 0) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'message' => 'Error al recargar Asterisk.']);
        exit;
    }

    echo json_encode(['ok' => true, 'message' => 'Extensiones creadas.']);
