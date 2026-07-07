<?php
include_once '../includes/config.php';

if (!isset($_POST['content'])) {
    echo json_encode(['ok' => false, 'message' => 'No se recibió el contenido del archivo.']);
    exit;
}

$content = $_POST['content'];
$file_path = '/Applications/XAMPP/xamppfiles/htdocs/tarificador/endpoints/tel_endpoints.conf';

// 1. Escribir al archivo
if (file_put_contents($file_path, $content) === false) {
    echo json_encode(['ok' => false, 'message' => 'No se pudo guardar el archivo. Verifique permisos.']);
    exit;
}

// 2. Parsear el archivo para sincronizar con la DB
$lines = explode("\n", $content);
$extensions = [];
$current_exten = null;

foreach ($lines as $line) {
    $line = trim($line);
    if (empty($line) || strpos($line, ';') === 0) continue; // skip comments and empty lines

    // Detect section headers e.g. [1000] or [1000](endpoint-basic) or [auth1000]
    if (preg_match('/^\[(.*?)\]/', $line, $matches)) {
        $section_name = $matches[1];
        
        if (preg_match('/^(\d+)$/', $section_name, $m)) {
            $current_exten = $m[1];
            if (!isset($extensions[$current_exten])) $extensions[$current_exten] = [];
        } elseif (preg_match('/^auth(\d+)$/', $section_name, $m)) {
            $current_exten = $m[1];
            if (!isset($extensions[$current_exten])) $extensions[$current_exten] = [];
        } else {
            $current_exten = null;
        }
        continue;
    }

    // Parse key=value
    if ($current_exten !== null && strpos($line, '=') !== false) {
        list($key, $val) = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val);
        $extensions[$current_exten][$key] = $val;
    }
}

// 3. Sincronizar con la base de datos (INSERT, UPDATE, DELETE)
$db_cols = ['callerid', 'auth', 'aors', 'mailboxes', 'voicemail_extension', 'transport', 'media_encryption', 'media_encryption_optimistic', 'password', 'username', 'voicemail_extension2', 'tipo_extension'];

// Obtener las extensiones actuales en la BD
$db_extensions = [];
$res = mysqli_query($link, "SELECT extension FROM tbla_extensiones");
if ($res) {
    while($row = mysqli_fetch_assoc($res)) {
        $db_extensions[] = $row['extension'];
    }
}

$parsed_extensions = array_keys($extensions);

foreach ($extensions as $exten_num => $data) {
    $exten_num_safe = mysqli_real_escape_string($link, $exten_num);
    
    // Mapear 'type' a 'tipo_extension' para la BD
    if (isset($data['type'])) {
        $data['tipo_extension'] = $data['type'];
    }

    if (in_array($exten_num, $db_extensions)) {
        // UPDATE
        $updates = [];
        foreach ($db_cols as $col) {
            if (isset($data[$col])) {
                $escaped = mysqli_real_escape_string($link, $data[$col]);
                $updates[] = "$col = '$escaped'";
            }
        }
        if (!empty($updates)) {
            $sql = "UPDATE tbla_extensiones SET " . implode(", ", $updates) . " WHERE extension = '$exten_num_safe'";
            mysqli_query($link, $sql);
        }
    } else {
        // INSERT (Nueva Extensión)
        $insert_cols = ['extension'];
        $insert_vals = ["'$exten_num_safe'"];
        foreach ($db_cols as $col) {
            if (isset($data[$col])) {
                $escaped = mysqli_real_escape_string($link, $data[$col]);
                $insert_cols[] = $col;
                $insert_vals[] = "'$escaped'";
            } else {
                $insert_cols[] = $col;
                $insert_vals[] = "''";
            }
        }
        $sql = "INSERT INTO tbla_extensiones (" . implode(", ", $insert_cols) . ") VALUES (" . implode(", ", $insert_vals) . ")";
        mysqli_query($link, $sql);
    }
}

// Borrar extensiones que están en la BD pero que fueron eliminadas del archivo conf
foreach ($db_extensions as $db_ext) {
    if (!in_array($db_ext, $parsed_extensions)) {
        $db_ext_safe = mysqli_real_escape_string($link, $db_ext);
        mysqli_query($link, "DELETE FROM tbla_extensiones WHERE extension = '$db_ext_safe'");
    }
}

// 4. Ejecutar comandos del sistema
$command = "sudo /usr/bin/cp -r /var/www/ucs/endpoints/tel_endpoints.conf /etc/asterisk/pjsip.d";
exec($command, $output, $returnCode);

if ($returnCode !== 0) {
    echo json_encode(['ok' => false, 'message' => 'Guardado en servidor pero falló al copiar a /etc/asterisk/pjsip.d']);
    exit;
}

$reloadCommand = "sudo systemctl reload asterisk";
exec($reloadCommand, $reloadOutput, $reloadReturnCode);

if ($reloadReturnCode !== 0) {
    echo json_encode(['ok' => false, 'message' => 'Guardado y copiado, pero falló al recargar Asterisk.']);
    exit;
}

echo json_encode(['ok' => true, 'message' => 'Archivo guardado, base de datos sincronizada y Asterisk recargado correctamente.']);
?>
