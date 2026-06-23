<?php
if (!defined('XMPP_HELPER_LOADED')) {
    define('XMPP_HELPER_LOADED', true);

    function xmpp_ensure_schema($link)
    {
        $configSql = "
            CREATE TABLE IF NOT EXISTS xmpp_config (
                id INT UNSIGNED NOT NULL PRIMARY KEY,
                api_url VARCHAR(255) NOT NULL,
                api_user VARCHAR(150) NOT NULL,
                api_password TEXT NOT NULL,
                xmpp_host VARCHAR(150) NOT NULL,
                ssl_verify TINYINT(1) NOT NULL DEFAULT 1,
                ultimo_estado VARCHAR(30) DEFAULT NULL,
                fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                fecha_actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        $logsSql = "
            CREATE TABLE IF NOT EXISTS xmpp_logs (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                modulo VARCHAR(80) NOT NULL DEFAULT 'xmpp',
                accion VARCHAR(120) NOT NULL,
                request_data MEDIUMTEXT NULL,
                response_data MEDIUMTEXT NULL,
                estado VARCHAR(30) NOT NULL,
                mensaje TEXT NULL,
                fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_xmpp_logs_fecha (fecha),
                INDEX idx_xmpp_logs_accion (accion),
                INDEX idx_xmpp_logs_estado (estado)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        mysqli_query($link, $configSql);
        mysqli_query($link, $logsSql);

        $columnCheck = mysqli_query($link, "SHOW COLUMNS FROM xmpp_config LIKE 'ssl_verify'");
        if ($columnCheck && mysqli_num_rows($columnCheck) === 0) {
            mysqli_query($link, "ALTER TABLE xmpp_config ADD COLUMN ssl_verify TINYINT(1) NOT NULL DEFAULT 1 AFTER xmpp_host");
        }
    }

    function xmpp_secret_encode($value)
    {
        return base64_encode((string) $value);
    }

    function xmpp_secret_decode($value)
    {
        $decoded = base64_decode((string) $value, true);
        return $decoded === false ? (string) $value : $decoded;
    }

    function xmpp_public_config($config)
    {
        if (!$config) {
            return null;
        }

        return array(
            'id' => (int) $config['id'],
            'api_url' => $config['api_url'],
            'api_user' => $config['api_user'],
            'xmpp_host' => $config['xmpp_host'],
            'ssl_verify' => isset($config['ssl_verify']) ? (int) $config['ssl_verify'] : 1,
            'ultimo_estado' => $config['ultimo_estado'],
            'fecha_creacion' => $config['fecha_creacion'],
            'fecha_actualizacion' => $config['fecha_actualizacion'],
            'password_configurado' => !empty($config['api_password'])
        );
    }

    function xmpp_get_config($link)
    {
        xmpp_ensure_schema($link);
        $stmt = mysqli_prepare($link, "SELECT * FROM xmpp_config WHERE id = 1 LIMIT 1");
        if (!$stmt) {
            return null;
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $config = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($config) {
            $config['api_password_plain'] = xmpp_secret_decode($config['api_password']);
        }

        return $config;
    }

    function xmpp_save_config($link, $apiUrl, $apiUser, $apiPassword, $xmppHost, $sslVerify)
    {
        xmpp_ensure_schema($link);

        $apiUrl = rtrim(trim($apiUrl), '/');
        $apiUser = trim($apiUser);
        $apiPassword = (string) $apiPassword;
        $xmppHost = trim($xmppHost);
        $sslVerify = (int) $sslVerify === 0 ? 0 : 1;

        if (!filter_var($apiUrl, FILTER_VALIDATE_URL)) {
            return array('success' => false, 'message' => 'La URL del API no es valida.');
        }

        if ($apiUser === '') {
            return array('success' => false, 'message' => 'El usuario API es obligatorio.');
        }

        if (!preg_match('/^[A-Za-z0-9.-]+$/', $xmppHost)) {
            return array('success' => false, 'message' => 'El host XMPP solo debe contener letras, numeros, puntos o guiones.');
        }

        $current = xmpp_get_config($link);
        if ($apiPassword === '' && $current) {
            $apiPassword = $current['api_password_plain'];
        }

        if ($apiPassword === '') {
            return array('success' => false, 'message' => 'La contrasena API es obligatoria.');
        }

        $encodedPassword = xmpp_secret_encode($apiPassword);
        $stmt = mysqli_prepare(
            $link,
            "INSERT INTO xmpp_config (id, api_url, api_user, api_password, xmpp_host, ssl_verify, ultimo_estado, fecha_creacion, fecha_actualizacion)
             VALUES (1, ?, ?, ?, ?, ?, 'sin_probar', NOW(), NOW())
             ON DUPLICATE KEY UPDATE
                api_url = VALUES(api_url),
                api_user = VALUES(api_user),
                api_password = VALUES(api_password),
                xmpp_host = VALUES(xmpp_host),
                ssl_verify = VALUES(ssl_verify),
                fecha_actualizacion = NOW()"
        );

        if (!$stmt) {
            return array('success' => false, 'message' => 'No se pudo preparar el guardado de configuracion.');
        }

        mysqli_stmt_bind_param($stmt, 'ssssi', $apiUrl, $apiUser, $encodedPassword, $xmppHost, $sslVerify);
        $ok = mysqli_stmt_execute($stmt);
        $error = mysqli_error($link);
        mysqli_stmt_close($stmt);

        if (!$ok) {
            return array('success' => false, 'message' => 'No se pudo guardar la configuracion: ' . $error);
        }

        return array('success' => true, 'message' => 'Configuracion guardada correctamente.');
    }

    function xmpp_update_connection_status($link, $status)
    {
        $stmt = mysqli_prepare($link, "UPDATE xmpp_config SET ultimo_estado = ?, fecha_actualizacion = NOW() WHERE id = 1");
        if (!$stmt) {
            return;
        }
        mysqli_stmt_bind_param($stmt, 's', $status);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    function xmpp_redact_sensitive($value)
    {
        if (is_array($value)) {
            $redacted = array();
            foreach ($value as $key => $item) {
                if (preg_match('/pass|password|contrasena|secret|token/i', (string) $key)) {
                    $redacted[$key] = $item === '' ? '' : '********';
                } else {
                    $redacted[$key] = xmpp_redact_sensitive($item);
                }
            }
            return $redacted;
        }

        return $value;
    }

    function xmpp_json_encode($value)
    {
        return json_encode(xmpp_redact_sensitive($value), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    function xmpp_log($link, $accion, $requestData, $responseData, $estado, $mensaje)
    {
        xmpp_ensure_schema($link);
        $requestJson = xmpp_json_encode($requestData);
        $responseJson = xmpp_json_encode($responseData);
        $modulo = 'xmpp';

        $stmt = mysqli_prepare(
            $link,
            "INSERT INTO xmpp_logs (modulo, accion, request_data, response_data, estado, mensaje, fecha)
             VALUES (?, ?, ?, ?, ?, ?, NOW())"
        );

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, 'ssssss', $modulo, $accion, $requestJson, $responseJson, $estado, $mensaje);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    function xmpp_get_logs($link, $accion, $estado, $limit)
    {
        xmpp_ensure_schema($link);
        $limit = (int) $limit;
        if ($limit <= 0 || $limit > 500) {
            $limit = 100;
        }

        $where = array();
        $types = '';
        $params = array();

        if ($accion !== '') {
            $where[] = 'accion = ?';
            $types .= 's';
            $params[] = $accion;
        }

        if ($estado !== '') {
            $where[] = 'estado = ?';
            $types .= 's';
            $params[] = $estado;
        }

        $sql = "SELECT id, modulo, accion, estado, mensaje, fecha FROM xmpp_logs";
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= " ORDER BY fecha DESC LIMIT " . $limit;

        $stmt = mysqli_prepare($link, $sql);
        if (!$stmt) {
            return array();
        }

        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $logs = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $logs[] = $row;
        }
        mysqli_stmt_close($stmt);

        return $logs;
    }

    function xmpp_get_log_detail($link, $id)
    {
        xmpp_ensure_schema($link);
        $id = (int) $id;
        $stmt = mysqli_prepare($link, "SELECT * FROM xmpp_logs WHERE id = ? LIMIT 1");
        if (!$stmt) {
            return null;
        }

        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        return $row;
    }

    function xmpp_api_url($baseUrl, $command)
    {
        $baseUrl = rtrim($baseUrl, '/');
        if (preg_match('#/admin$#', $baseUrl)) {
            $baseUrl = preg_replace('#/admin$#', '', $baseUrl);
        }
        if (preg_match('#/api$#', $baseUrl)) {
            return $baseUrl . '/' . rawurlencode($command);
        }

        return $baseUrl . '/api/' . rawurlencode($command);
    }

    function xmpp_api_request($config, $command, $payload, $timeout)
    {
        if (!function_exists('curl_init')) {
            return array(
                'success' => false,
                'http_code' => 0,
                'curl_error' => 'La extension cURL de PHP no esta disponible.',
                'raw' => '',
                'json' => null,
                'message' => 'No se puede llamar al API porque PHP no tiene cURL habilitado.'
            );
        }

        $url = xmpp_api_url($config['api_url'], $command);
        $body = json_encode((object) $payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $config['api_user'] . ':' . $config['api_password_plain']);

        if (isset($config['ssl_verify']) && (int) $config['ssl_verify'] === 0) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        $raw = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = null;
        if ($raw !== false && $raw !== '') {
            $decoded = json_decode($raw, true);
        }

        $success = ($curlError === '' && $httpCode >= 200 && $httpCode < 300);
        $message = $success ? 'Comando ejecutado correctamente.' : 'No se pudo ejecutar el comando.';

        if ($curlError !== '') {
            $message = 'Error de conexion: ' . $curlError;
            if (stripos($curlError, 'HTTP/0.9') !== false) {
                $message .= '. La URL/puerto configurado no esta respondiendo como API HTTP de ejabberd. Verifique que use el puerto web de ejabberd (normalmente 5280), el protocolo correcto http/https, y que /api este publicado con mod_http_api. No use puertos XMPP como 5222 o 5269.';
            }
            if (stripos($curlError, 'SSL') !== false || stripos($curlError, 'certificate') !== false || stripos($curlError, 'subject name') !== false) {
                $message .= '. Si el servidor aun no tiene SSL valido, use http:// en la URL del API o desactive temporalmente la validacion SSL en la configuracion.';
            }
        } elseif ($httpCode === 401 || $httpCode === 403) {
            $message = 'Autenticacion rechazada por ejabberd.';
            if (is_array($decoded) && !empty($decoded['message'])) {
                $message = 'ejabberd rechazo la operacion: ' . $decoded['message'];
            }
        } elseif ($httpCode === 404) {
            $message = 'Endpoint no encontrado. Verifique que mod_http_api este habilitado y que la URL base sea correcta.';
        } elseif ($httpCode < 200 || $httpCode >= 300) {
            $message = 'ejabberd respondio HTTP ' . $httpCode . '.';
            if (is_array($decoded) && !empty($decoded['message'])) {
                $message .= ' ' . $decoded['message'];
            }
        }

        if (is_array($decoded)) {
            $possibleStatus = isset($decoded['status']) ? strtolower((string) $decoded['status']) : '';
            if ($possibleStatus === 'error') {
                $success = false;
                $message = isset($decoded['message']) ? $decoded['message'] : 'ejabberd devolvio estado de error.';
            }
        }

        return array(
            'success' => $success,
            'url' => $url,
            'command' => $command,
            'http_code' => $httpCode,
            'curl_error' => $curlError,
            'raw' => $raw === false ? '' : $raw,
            'json' => $decoded,
            'message' => $message
        );
    }

    function xmpp_require_config($link)
    {
        $config = xmpp_get_config($link);
        if (!$config || empty($config['api_url']) || empty($config['api_user']) || empty($config['api_password_plain']) || empty($config['xmpp_host'])) {
            return array('success' => false, 'message' => 'Primero guarde la configuracion de ejabberd.');
        }

        return array('success' => true, 'config' => $config);
    }

    function xmpp_valid_username($username)
    {
        return preg_match('/^[A-Za-z0-9_.-]+$/', $username);
    }

    function xmpp_valid_host($host)
    {
        return preg_match('/^[A-Za-z0-9.-]+$/', $host);
    }

    function xmpp_extract_list($value)
    {
        if (is_array($value)) {
            if (isset($value['res'])) {
                return xmpp_extract_list($value['res']);
            }
            if (isset($value['users'])) {
                return xmpp_extract_list($value['users']);
            }
            if (isset($value['groups'])) {
                return xmpp_extract_list($value['groups']);
            }
            if (isset($value['contacts'])) {
                return xmpp_extract_list($value['contacts']);
            }
            if (isset($value['members'])) {
                return xmpp_extract_list($value['members']);
            }

            $list = array();
            foreach ($value as $item) {
                if (is_scalar($item)) {
                    $list[] = (string) $item;
                } elseif (is_array($item)) {
                    $list[] = $item;
                }
            }
            return $list;
        }

        if (is_string($value)) {
            $value = trim($value);
            if ($value === '') {
                return array();
            }
            return preg_split('/[\r\n,]+/', $value, -1, PREG_SPLIT_NO_EMPTY);
        }

        return array();
    }

    function xmpp_extract_response_data($apiResult)
    {
        if (isset($apiResult['json']) && $apiResult['json'] !== null) {
            return $apiResult['json'];
        }

        return $apiResult['raw'];
    }

    function xmpp_split_list($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return array();
        }

        $items = preg_split('/[;,]+/', $value, -1, PREG_SPLIT_NO_EMPTY);
        $clean = array();
        foreach ($items as $item) {
            $item = trim($item);
            if ($item !== '') {
                $clean[] = $item;
            }
        }

        return $clean;
    }
}
