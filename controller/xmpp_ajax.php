<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once '../includes/config.php';
include_once '../includes/security.php';
include_once 'xmpp_helper.php';

date_default_timezone_set('America/Managua');
xmpp_ensure_schema($link);

function xmpp_ajax_response($success, $message, $extra = array())
{
    echo json_encode(array_merge(array(
        'success' => $success,
        'message' => $message
    ), $extra), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function xmpp_post($key, $default = '')
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
}

function xmpp_run_logged_command($link, $accion, $command, $payload, $successMessage)
{
    $required = xmpp_require_config($link);
    if (!$required['success']) {
        xmpp_ajax_response(false, $required['message']);
    }

    $apiResult = xmpp_api_request($required['config'], $command, $payload, 15);
    $commandSuccess = $apiResult['success'];
    $responseData = xmpp_extract_response_data($apiResult);

    if ($commandSuccess && is_array($responseData) && isset($responseData['res']) && (string) $responseData['res'] !== '0' && $responseData['res'] !== '') {
        $commandSuccess = false;
    }

    if ($commandSuccess && is_int($responseData) && $responseData !== 0) {
        $commandSuccess = false;
    }

    $estado = $commandSuccess ? 'exito' : 'error';
    $message = $commandSuccess ? $successMessage : $apiResult['message'];
    if (!$commandSuccess && $apiResult['success']) {
        $message = 'ejabberd ejecuto el comando pero devolvio codigo de error.';
    }

    xmpp_log($link, $accion, array('command' => $command, 'payload' => $payload), $apiResult, $estado, $message);
    xmpp_ajax_response($commandSuccess, $message, array('api' => xmpp_redact_sensitive($apiResult)));
}

function xmpp_run_list_command($link, $command, $payload)
{
    $required = xmpp_require_config($link);
    if (!$required['success']) {
        xmpp_ajax_response(false, $required['message']);
    }

    $apiResult = xmpp_api_request($required['config'], $command, $payload, 15);
    if (!$apiResult['success']) {
        xmpp_ajax_response(false, $apiResult['message'], array('api' => xmpp_redact_sensitive($apiResult)));
    }

    xmpp_ajax_response(true, 'Datos cargados correctamente.', array('data' => xmpp_extract_response_data($apiResult)));
}

$id = isset($_SESSION['id_u']) ? $_SESSION['id_u'] : '';
$activo = isset($_SESSION['activo']) ? $_SESSION['activo'] : '';
$tipo = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : '';

if (empty($id) || empty($activo) || $tipo != 1) {
    xmpp_ajax_response(false, 'Sesion expirada o sin permisos para administrar XMPP.', array('unauthorized' => true));
}

$action = xmpp_post('action');

switch ($action) {
    case 'get_config':
        xmpp_ajax_response(true, 'Configuracion cargada.', array('config' => xmpp_public_config(xmpp_get_config($link))));
        break;

    case 'save_config':
        $result = xmpp_save_config(
            $link,
            xmpp_post('api_url'),
            xmpp_post('api_user'),
            isset($_POST['api_password']) ? (string) $_POST['api_password'] : '',
            xmpp_post('xmpp_host'),
            xmpp_post('ssl_verify', '1') === '0' ? 0 : 1
        );

        xmpp_log(
            $link,
            'guardar_configuracion',
            array(
                'api_url' => xmpp_post('api_url'),
                'api_user' => xmpp_post('api_user'),
                'api_password' => isset($_POST['api_password']) ? (string) $_POST['api_password'] : '',
                'xmpp_host' => xmpp_post('xmpp_host'),
                'ssl_verify' => xmpp_post('ssl_verify', '1') === '0' ? 0 : 1
            ),
            $result,
            $result['success'] ? 'exito' : 'error',
            $result['message']
        );

        xmpp_ajax_response($result['success'], $result['message'], array('config' => xmpp_public_config(xmpp_get_config($link))));
        break;

    case 'test_connection':
        $config = xmpp_get_config($link);
        $formPassword = isset($_POST['api_password']) ? (string) $_POST['api_password'] : '';

        if (!$config) {
            $config = array(
                'api_url' => xmpp_post('api_url'),
                'api_user' => xmpp_post('api_user'),
                'api_password_plain' => $formPassword,
                'xmpp_host' => xmpp_post('xmpp_host'),
                'ssl_verify' => xmpp_post('ssl_verify', '1') === '0' ? 0 : 1
            );
        } else {
            if (xmpp_post('api_url') !== '') {
                $config['api_url'] = rtrim(xmpp_post('api_url'), '/');
            }
            if (xmpp_post('api_user') !== '') {
                $config['api_user'] = xmpp_post('api_user');
            }
            if ($formPassword !== '') {
                $config['api_password_plain'] = $formPassword;
            }
            if (xmpp_post('xmpp_host') !== '') {
                $config['xmpp_host'] = xmpp_post('xmpp_host');
            }
            $config['ssl_verify'] = xmpp_post('ssl_verify', '1') === '0' ? 0 : 1;
        }

        if (empty($config['api_url']) || empty($config['api_user']) || empty($config['api_password_plain'])) {
            xmpp_ajax_response(false, 'Complete URL, usuario y contrasena API antes de probar la conexion.');
        }

        $payload = array();
        $apiResult = xmpp_api_request($config, 'status', $payload, 15);
        $estado = $apiResult['success'] ? 'exito' : 'error';
        $message = $apiResult['success'] ? 'Conexion exitosa con el API de ejabberd.' : $apiResult['message'];
        xmpp_update_connection_status($link, $estado);
        xmpp_log($link, 'probar_conexion', array('command' => 'status', 'config' => $config), $apiResult, $estado, $message);

        xmpp_ajax_response($apiResult['success'], $message, array('api' => xmpp_redact_sensitive($apiResult), 'config' => xmpp_public_config(xmpp_get_config($link))));
        break;

    case 'list_users':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $host = xmpp_post('host', $required['config']['xmpp_host']);
        xmpp_run_list_command($link, 'registered_users', array('host' => $host));
        break;

    case 'create_user':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $username = xmpp_post('username');
        $password = isset($_POST['password']) ? (string) $_POST['password'] : '';
        $host = xmpp_post('host', $required['config']['xmpp_host']);

        if (!xmpp_valid_username($username) || $password === '' || !xmpp_valid_host($host)) {
            xmpp_ajax_response(false, 'Revise username, host y contrasena del usuario XMPP.');
        }

        xmpp_run_logged_command(
            $link,
            'crear_usuario',
            'register',
            array('user' => $username, 'host' => $host, 'password' => $password),
            'Usuario XMPP creado correctamente.'
        );
        break;

    case 'change_password':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $username = xmpp_post('username');
        $newPassword = isset($_POST['new_password']) ? (string) $_POST['new_password'] : '';
        $host = xmpp_post('host', $required['config']['xmpp_host']);

        if (!xmpp_valid_username($username) || $newPassword === '' || !xmpp_valid_host($host)) {
            xmpp_ajax_response(false, 'Revise username, host y nueva contrasena.');
        }

        xmpp_run_logged_command(
            $link,
            'cambiar_password',
            'change_password',
            array('user' => $username, 'host' => $host, 'newpass' => $newPassword),
            'Contrasena actualizada correctamente.'
        );
        break;

    case 'delete_user':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $username = xmpp_post('username');
        $host = xmpp_post('host', $required['config']['xmpp_host']);

        if (!xmpp_valid_username($username) || !xmpp_valid_host($host)) {
            xmpp_ajax_response(false, 'Usuario u host XMPP no valido.');
        }

        xmpp_run_logged_command(
            $link,
            'eliminar_usuario',
            'unregister',
            array('user' => $username, 'host' => $host),
            'Usuario XMPP eliminado correctamente.'
        );
        break;

    case 'list_roster':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $username = xmpp_post('username');
        $host = xmpp_post('host', $required['config']['xmpp_host']);

        if (!xmpp_valid_username($username) || !xmpp_valid_host($host)) {
            xmpp_ajax_response(false, 'Ingrese un usuario y host validos para listar contactos.');
        }

        xmpp_run_list_command($link, 'get_roster', array('user' => $username, 'host' => $host));
        break;

    case 'add_contact':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $localUser = xmpp_post('localuser');
        $localServer = xmpp_post('localserver', $required['config']['xmpp_host']);
        $contactUser = xmpp_post('contact_user');
        $contactServer = xmpp_post('contact_server', $required['config']['xmpp_host']);
        $nick = xmpp_post('nick');
        $group = xmpp_post('group');

        if (!xmpp_valid_username($localUser) || !xmpp_valid_host($localServer) || !xmpp_valid_username($contactUser) || !xmpp_valid_host($contactServer)) {
            xmpp_ajax_response(false, 'Revise usuario origen, contacto y hosts.');
        }

        xmpp_run_logged_command(
            $link,
            'agregar_contacto',
            'add_rosteritem',
            array(
                'localuser' => $localUser,
                'localhost' => $localServer,
                'user' => $contactUser,
                'host' => $contactServer,
                'nick' => $nick,
                'groups' => xmpp_split_list($group),
                'subs' => 'both'
            ),
            'Contacto agregado correctamente.'
        );
        break;

    case 'delete_contact':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $localUser = xmpp_post('localuser');
        $localServer = xmpp_post('localserver', $required['config']['xmpp_host']);
        $contactUser = xmpp_post('contact_user');
        $contactServer = xmpp_post('contact_server', $required['config']['xmpp_host']);

        if (!xmpp_valid_username($localUser) || !xmpp_valid_host($localServer) || !xmpp_valid_username($contactUser) || !xmpp_valid_host($contactServer)) {
            xmpp_ajax_response(false, 'Datos de contacto no validos.');
        }

        xmpp_run_logged_command(
            $link,
            'eliminar_contacto',
            'delete_rosteritem',
            array('localuser' => $localUser, 'localhost' => $localServer, 'user' => $contactUser, 'host' => $contactServer),
            'Contacto eliminado correctamente.'
        );
        break;

    case 'list_groups':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $host = xmpp_post('host', $required['config']['xmpp_host']);
        xmpp_run_list_command($link, 'srg_list', array('host' => $host));
        break;

    case 'group_info':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $group = xmpp_post('group');
        $host = xmpp_post('host', $required['config']['xmpp_host']);
        xmpp_run_list_command($link, 'srg_get_info', array('group' => $group, 'host' => $host));
        break;

    case 'group_members':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $group = xmpp_post('group');
        $host = xmpp_post('host', $required['config']['xmpp_host']);
        xmpp_run_list_command($link, 'srg_get_members', array('group' => $group, 'host' => $host));
        break;

    case 'create_group':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $group = xmpp_post('group');
        $host = xmpp_post('host', $required['config']['xmpp_host']);
        $label = xmpp_post('name', $group);
        $description = xmpp_post('description');
        $display = xmpp_post('display', $group);
        if ($display === '') {
            $display = $group;
        }

        if (!xmpp_valid_username($group) || !xmpp_valid_host($host)) {
            xmpp_ajax_response(false, 'Nombre de grupo u host no valido.');
        }

        xmpp_run_logged_command(
            $link,
            'crear_grupo',
            'srg_create',
            array('group' => $group, 'host' => $host, 'label' => $label, 'description' => $description, 'display' => xmpp_split_list($display)),
            'Grupo creado correctamente.'
        );
        break;

    case 'update_group':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $group = xmpp_post('group');
        $host = xmpp_post('host', $required['config']['xmpp_host']);
        $label = xmpp_post('name', $group);
        $description = xmpp_post('description');
        $display = xmpp_post('display', $group);
        if ($display === '') {
            $display = $group;
        }

        if (!xmpp_valid_username($group) || !xmpp_valid_host($host)) {
            xmpp_ajax_response(false, 'Nombre de grupo u host no valido.');
        }

        $apiResults = array();
        $ok = true;

        $labelResult = xmpp_api_request($required['config'], 'srg_set_info', array(
            'group' => $group,
            'host' => $host,
            'key' => 'label',
            'value' => $label
        ), 15);
        $apiResults[] = $labelResult;
        $ok = $ok && $labelResult['success'];

        $descriptionResult = xmpp_api_request($required['config'], 'srg_set_info', array(
            'group' => $group,
            'host' => $host,
            'key' => 'description',
            'value' => $description
        ), 15);
        $apiResults[] = $descriptionResult;
        $ok = $ok && $descriptionResult['success'];

        foreach (xmpp_split_list($display) as $displayGroup) {
            $displayResult = xmpp_api_request($required['config'], 'srg_add_displayed', array(
                'group' => $group,
                'host' => $host,
                'add' => $displayGroup
            ), 15);
            $apiResults[] = $displayResult;
            $ok = $ok && $displayResult['success'];
        }

        $message = $ok ? 'Grupo actualizado correctamente.' : 'No se pudo actualizar todo el grupo. Revise el detalle tecnico en logs.';
        xmpp_log($link, 'editar_grupo', array(
            'group' => $group,
            'host' => $host,
            'label' => $label,
            'description' => $description,
            'display' => xmpp_split_list($display)
        ), $apiResults, $ok ? 'exito' : 'error', $message);

        xmpp_ajax_response($ok, $message, array('api' => xmpp_redact_sensitive($apiResults)));
        break;

    case 'delete_group':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $group = xmpp_post('group');
        $host = xmpp_post('host', $required['config']['xmpp_host']);

        if (!xmpp_valid_username($group) || !xmpp_valid_host($host)) {
            xmpp_ajax_response(false, 'Nombre de grupo u host no valido.');
        }

        xmpp_run_logged_command(
            $link,
            'eliminar_grupo',
            'srg_delete',
            array('group' => $group, 'host' => $host),
            'Grupo eliminado correctamente.'
        );
        break;

    case 'add_group_member':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $username = xmpp_post('username');
        $host = xmpp_post('host', $required['config']['xmpp_host']);
        $group = xmpp_post('group');
        $groupHost = xmpp_post('grouphost', $required['config']['xmpp_host']);

        if (!xmpp_valid_username($username) || !xmpp_valid_host($host) || !xmpp_valid_username($group) || !xmpp_valid_host($groupHost)) {
            xmpp_ajax_response(false, 'Revise usuario, grupo y hosts.');
        }

        xmpp_run_logged_command(
            $link,
            'agregar_miembro_grupo',
            'srg_user_add',
            array('user' => $username, 'host' => $host, 'group' => $group, 'grouphost' => $groupHost),
            'Miembro agregado al grupo correctamente.'
        );
        break;

    case 'remove_group_member':
        $required = xmpp_require_config($link);
        if (!$required['success']) {
            xmpp_ajax_response(false, $required['message']);
        }
        $username = xmpp_post('username');
        $host = xmpp_post('host', $required['config']['xmpp_host']);
        $group = xmpp_post('group');
        $groupHost = xmpp_post('grouphost', $required['config']['xmpp_host']);

        if (!xmpp_valid_username($username) || !xmpp_valid_host($host) || !xmpp_valid_username($group) || !xmpp_valid_host($groupHost)) {
            xmpp_ajax_response(false, 'Revise usuario, grupo y hosts.');
        }

        xmpp_run_logged_command(
            $link,
            'quitar_miembro_grupo',
            'srg_user_del',
            array('user' => $username, 'host' => $host, 'group' => $group, 'grouphost' => $groupHost),
            'Miembro removido del grupo correctamente.'
        );
        break;

    case 'logs':
        xmpp_ajax_response(true, 'Logs cargados.', array(
            'logs' => xmpp_get_logs($link, xmpp_post('accion'), xmpp_post('estado'), 150)
        ));
        break;

    case 'log_detail':
        $log = xmpp_get_log_detail($link, xmpp_post('id'));
        if (!$log) {
            xmpp_ajax_response(false, 'No se encontro el log solicitado.');
        }
        xmpp_ajax_response(true, 'Detalle cargado.', array('log' => $log));
        break;

    default:
        xmpp_ajax_response(false, 'Accion no reconocida.');
        break;
}
