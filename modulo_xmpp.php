<?php
    include_once 'includes/config.php';
    include_once 'includes/security.php';
    include_once 'controller/xmpp_helper.php';

    date_default_timezone_set('America/Managua');
    session_start();

    $id = isset($_SESSION['id_u']) ? $_SESSION['id_u'] : '';
    $nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
    $apellido = isset($_SESSION['apellido']) ? $_SESSION['apellido'] : '';
    $activo = isset($_SESSION['activo']) ? $_SESSION['activo'] : '';
    $tipo = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : '';

    $nombre = explode(' ', $nombre);
    @$nombre = $nombre[0];
    $apellido = explode(' ', $apellido);
    @$apellido = $apellido[0];

    if (empty($id) || empty($activo) || $tipo != 1) {
        header("Location: index.php");
        exit;
    }

    xmpp_ensure_schema($link);
    $xmppConfig = xmpp_public_config(xmpp_get_config($link));
    $defaultHost = $xmppConfig ? $xmppConfig['xmpp_host'] : '';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Modulo XMPP</title>
        <link rel="shortcut icon" href="img/logo_icono.png">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
        <link rel="stylesheet" href="assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
        <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="assets/dist/css/skins/_all-skins.min.css">
        <link rel="stylesheet" href="assets/plugins/pace/pace.min.css">
        <link rel="stylesheet" href="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        <link rel="stylesheet" href="assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="css/cerulean/bootstrap.css" media="screen">
        <script type="text/javascript" src="js/notifIt.js"></script>
        <link rel="stylesheet" type="text/css" href="css/notifIt.css">
        <link rel="stylesheet" type="text/css" href="css/c3.css">
        <link rel="stylesheet" type="text/css" href="assets/helpers/boilerplate.css">
        <link rel="stylesheet" type="text/css" href="assets/helpers/grid.css">
        <link rel="stylesheet" type="text/css" href="assets/helpers/utils.css">
        <link rel="stylesheet" type="text/css" href="assets/icons/fontawesome/fontawesome.css">
        <link rel="stylesheet" type="text/css" href="assets/snippets/user-profile.css">
        <link rel="stylesheet" type="text/css" href="assets/snippets/mobile-navigation.css">
        <link rel="stylesheet" type="text/css" href="assets/themes/frontend/layout.css">
        <link rel="stylesheet" type="text/css" href="assets/themes/components/default.css">
        <link rel="stylesheet" type="text/css" href="assets/helpers/frontend-responsive.css">

        <style type="text/css">
            .skin-blue .main-header .navbar{
                background-image: url('img/header.png');
            }

            div.shadow {
                background: #ecf0f5;
            }

            img.logo {
                position:relative;
                max-width:100%;
                max-height:100%;
            }

            .table {
                color: #495057 !important;
            }

            .tabs-nav li a,
            .main-header .header-nav > li > a,
            .hero-heading,
            .hero-text,
            h1, h2, h3, h4, h5, h6,
            .main-header .header-nav > li > a,
            .hero-heading,
            .hero-text {
                font-family: "Raleway", "Helvetica Neue", Helvetica, Arial, sans-serif;
                font-weight: 300;
                font-family: monospace;
            }

            .bg-topbar {
                background: #fff;
                border-bottom-color: #eee;
            }

            .bg-header {
                background: #fff;
            }

            .sticky-active .main-header {
                box-shadow: 0 0 1px 2px rgba(0, 0, 0, 0.05);
            }

            .main-header .header-nav > li > ul {
                background: #253035;
            }

            .main-header .header-nav > li > ul li a:hover {
                background: #2b373d;
            }

            .xmpp-status {
                margin-top: 10px;
            }

            .xmpp-status .label {
                font-size: 12px;
            }

            .xmpp-action-bar {
                padding: 10px 0;
                min-height: 52px;
            }

            .xmpp-muted {
                color: #777;
                font-size: 12px;
            }

            .xmpp-pre {
                background: #f7f7f7;
                border: 1px solid #ddd;
                border-radius: 4px;
                color: #333;
                max-height: 320px;
                overflow: auto;
                padding: 10px;
                white-space: pre-wrap;
                word-break: break-word;
            }

            .xmpp-inline-form {
                border-top: 1px solid #eee;
                margin-top: 15px;
                padding-top: 15px;
            }

            .xmpp-members-title {
                margin-top: 0;
            }

            .xmpp-group-selector {
                border: 1px solid #ddd;
                border-radius: 4px;
                max-height: 260px;
                overflow-y: auto;
                padding: 10px;
            }

            .xmpp-group-selector .checkbox {
                margin-bottom: 8px;
                margin-top: 0;
            }

            .xmpp-group-selector-empty {
                color: #777;
                margin: 0;
            }
        </style>

        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/php_file_tree_jquery.js" type="text/javascript"></script>
    </head>

    <body class="hold-transition skin-blue layout-top-nav">
        <div class="wrapper">
            <div class="main-header bg-header wow fadeInDown animated animated" style="visibility: visible;">
                <div class="container">
                    <a href="home.php" class="header-logo" style="width: 40px;"></a>

                    <ul class="header-nav collapse">
                        <li>
                            <a href="home.php" title="">
                                <i class="fa fa-home"></i>
                                Inicio
                            </a>
                        </li>

                        <li>
                            <a href="user.php" title="">
                                <img src="img/user.png" width="20%">
                                Usuarios
                            </a>
                        </li>

                        <li>
                            <a href="prefijos.php" title="">
                                <img src="img/prefijo.png" width="20%">
                                Prefijos
                            </a>
                        </li>

                        <?php
                            if($look_view_audio) {
                                echo '
                                    <li>
                                        <a href="listAudio.php" title="">
                                            <img src="img/audio.png" width="10%">
                                            Audios grabados
                                        </a>
                                    </li>
                                ';
                            }
                        ?>

                        <li class="active">
                            <a href="modulo_xmpp.php" title="">
                                <img src="img/conversation_chat.png" width="40%">
                                IM
                            </a>
                        </li>

                        <li>
                            <a href="salir.php" title="">
                                <img src="img/salir.png" width="20%">
                                Salir
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="content-wrapper">
                <section class="content-header">
                    <h1>Modulo XMPP</h1>
                    <ol class="breadcrumb">
                        <li><a href="modulo_xmpp.php"><i class="fa fa-comments-o"></i> XMPP</a></li>
                    </ol>
                </section>

                <section class="content">
                    <div id="xmppAlert" class="alert" style="display:none;"></div>

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_configuracion" data-toggle="tab">
                                    <i class="fa fa-cog"></i> Configuracion
                                </a>
                            </li>
                            <li>
                                <a href="#tab_usuarios" data-toggle="tab">
                                    <i class="fa fa-user"></i> Usuarios XMPP
                                </a>
                            </li>
                            <li>
                                <a href="#tab_contactos" data-toggle="tab">
                                    <i class="fa fa-address-book"></i> Contactos
                                </a>
                            </li>
                            <li>
                                <a href="#tab_grupos" data-toggle="tab">
                                    <i class="fa fa-users"></i> Grupos
                                </a>
                            </li>
                            <li>
                                <a href="#tab_logs" data-toggle="tab">
                                    <i class="fa fa-list-alt"></i> Logs o Resultados
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_configuracion">
                                <div class="row">
                                    <div class="col-md-8">
                                        <form id="formConfigXmpp" autocomplete="off">
                                            <div class="form-group col-md-6">
                                                <label>URL del API ejabberd</label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-link"></i>
                                                    </div>
                                                    <input type="url" name="api_url" id="api_url" class="form-control" placeholder="https://xmpp.netsoluciones.com:5280">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Host XMPP</label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-server"></i>
                                                    </div>
                                                    <input type="text" name="xmpp_host" id="xmpp_host" class="form-control" placeholder="netsoluciones.com">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Usuario API</label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-user-secret"></i>
                                                    </div>
                                                    <input type="text" name="api_user" id="api_user" class="form-control" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Password API</label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-key"></i>
                                                    </div>
                                                    <input type="password" name="api_password" id="api_password" class="form-control" autocomplete="new-password" placeholder="Dejar vacio para conservar el actual">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>
                                                    <input type="checkbox" name="ssl_verify" id="ssl_verify" value="0">
                                                    No validar certificado SSL temporalmente
                                                </label>
                                                <p class="xmpp-muted">
                                                    Use esta opcion solo mientras el servidor no tenga certificado valido. Si ejabberd esta sin SSL, lo recomendado es guardar la URL como http://xmpp.netsoluciones.com:5280.
                                                </p>
                                            </div>

                                            <div class="text-center col-md-12">
                                                <button class="btn btn-primary" type="button" id="btnGuardarConfig">
                                                    <i class="fa fa-save"></i> Guardar configuracion
                                                </button>
                                                <button class="btn btn-success" type="button" id="btnProbarConexion">
                                                    <i class="fa fa-plug"></i> Probar conexion
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="box box-primary">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Estado</h3>
                                            </div>
                                            <div class="box-body">
                                                <p><strong>API:</strong> <span id="estadoApiUrl">Sin configurar</span></p>
                                                <p><strong>Host:</strong> <span id="estadoXmppHost">Sin configurar</span></p>
                                                <p><strong>Validacion SSL:</strong> <span id="estadoSslVerify" class="label label-default">activa</span></p>
                                                <p><strong>Ultimo estado:</strong> <span id="estadoConexionLabel" class="label label-default">sin_probar</span></p>
                                                <p class="xmpp-muted">El password se guarda codificado y no se muestra en pantalla. Si el sistema agrega cifrado central mas adelante, el helper ya concentra ese cambio.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_usuarios">
                                <div class="xmpp-action-bar">
                                    <label>Lista de usuarios XMPP</label>
                                    <button data-toggle="modal" data-target="#ModalCrearUsuarioXmpp" type="button" class="btn btn-success pull-right">
                                        <i class="fa fa-plus"></i> Crear usuario
                                    </button>
                                    <button type="button" class="btn btn-primary pull-right" id="btnRecargarUsuarios" style="margin-right: 5px;">
                                        <i class="fa fa-refresh"></i> Recargar
                                    </button>
                                </div>

                                <table id="tablaUsuariosXmpp" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Usuario</th>
                                            <th class="text-center">JID</th>
                                            <th class="text-center">Host</th>
                                            <th class="text-center">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div class="tab-pane" id="tab_contactos">
                                <form id="formBuscarContactos" class="row" autocomplete="off">
                                    <div class="form-group col-md-4">
                                        <label>Usuario origen</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input type="text" name="username" id="contactos_username" class="form-control" placeholder="usuario">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Host</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-server"></i>
                                            </div>
                                            <input type="text" name="host" id="contactos_host" class="form-control xmpp-host-field" value="<?php echo htmlspecialchars($defaultHost, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i> Listar contactos
                                            </button>
                                            <button data-toggle="modal" data-target="#ModalAgregarContacto" type="button" class="btn btn-success">
                                                <i class="fa fa-plus"></i> Agregar
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <table id="tablaContactosXmpp" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Contacto</th>
                                            <th class="text-center">Alias</th>
                                            <th class="text-center">Grupo</th>
                                            <th class="text-center">Suscripcion</th>
                                            <th class="text-center">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div class="tab-pane" id="tab_grupos">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="xmpp-action-bar">
                                            <label>Shared Roster Groups</label>
                                            <button data-toggle="modal" data-target="#ModalGrupoXmpp" type="button" class="btn btn-success pull-right" id="btnNuevoGrupo">
                                                <i class="fa fa-plus"></i> Crear grupo
                                            </button>
                                            <button type="button" class="btn btn-primary pull-right" id="btnRecargarGrupos" style="margin-right: 5px;">
                                                <i class="fa fa-refresh"></i> Recargar
                                            </button>
                                        </div>

                                        <table id="tablaGruposXmpp" class="table table-bordered table-striped" width="100%">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Grupo</th>
                                                    <th class="text-center">Host</th>
                                                    <th class="text-center">Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="box box-primary">
                                            <div class="box-header with-border">
                                                <h3 class="box-title xmpp-members-title">Miembros del grupo</h3>
                                            </div>
                                            <div class="box-body">
                                                <p><strong>Grupo seleccionado:</strong> <span id="grupoSeleccionadoTexto">Ninguno</span></p>
                                                <p class="xmpp-muted">Seleccione un grupo en la tabla para ver sus miembros.</p>

                                                <div class="xmpp-inline-form">
                                                    <table id="tablaMiembrosGrupo" class="table table-bordered table-striped" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Usuario</th>
                                                                <th>Opciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_logs">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Accion</label>
                                        <select id="filtroLogAccion" class="form-control">
                                            <option value="">Todas</option>
                                            <option value="probar_conexion">Probar conexion</option>
                                            <option value="guardar_configuracion">Guardar configuracion</option>
                                            <option value="crear_usuario">Crear usuario</option>
                                            <option value="cambiar_password">Cambiar password</option>
                                            <option value="eliminar_usuario">Eliminar usuario</option>
                                            <option value="agregar_contacto">Agregar contacto</option>
                                            <option value="eliminar_contacto">Eliminar contacto</option>
                                            <option value="crear_grupo">Crear grupo</option>
                                            <option value="editar_grupo">Editar grupo</option>
                                            <option value="eliminar_grupo">Eliminar grupo</option>
                                            <option value="agregar_miembro_grupo">Agregar miembro grupo</option>
                                            <option value="quitar_miembro_grupo">Quitar miembro grupo</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Estado</label>
                                        <select id="filtroLogEstado" class="form-control">
                                            <option value="">Todos</option>
                                            <option value="exito">Exito</option>
                                            <option value="error">Error</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="button" class="btn btn-primary" id="btnRecargarLogs">
                                                <i class="fa fa-refresh"></i> Recargar logs
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <table id="tablaLogsXmpp" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Accion</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Mensaje</th>
                                            <th class="text-center">Detalle</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="modal fade" id="ModalCrearUsuarioXmpp">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h5 class="modal-title">Crear usuario XMPP</h5>
                            </div>
                            <div class="modal-body">
                                <form id="formCrearUsuarioXmpp" autocomplete="off">
                                    <div class="form-group col-md-6">
                                        <label>Username</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                            <input type="text" name="username" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Host</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-server"></i></div>
                                            <input type="text" name="host" class="form-control xmpp-host-field" value="<?php echo htmlspecialchars($defaultHost, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Password</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-key"></i></div>
                                            <input type="password" name="password" class="form-control" autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Nombre visible opcional</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-id-card"></i></div>
                                            <input type="text" name="display_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="ModalCambiarPasswordXmpp">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h5 class="modal-title">Cambiar password XMPP</h5>
                            </div>
                            <div class="modal-body">
                                <form id="formCambiarPasswordXmpp" autocomplete="off">
                                    <input type="hidden" name="username" id="pass_username">
                                    <input type="hidden" name="host" id="pass_host">
                                    <div class="form-group col-md-12">
                                        <label>Usuario</label>
                                        <input type="text" id="pass_usuario_label" class="form-control" readonly>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Nueva password</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-key"></i></div>
                                            <input type="password" name="new_password" class="form-control" autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Actualizar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="ModalAgregarContacto">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h5 class="modal-title">Agregar contacto</h5>
                            </div>
                            <div class="modal-body">
                                <form id="formAgregarContacto" autocomplete="off">
                                    <div class="form-group col-md-6">
                                        <label>Usuario origen</label>
                                        <input type="text" name="localuser" id="agregar_localuser" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Host origen</label>
                                        <input type="text" name="localserver" id="agregar_localserver" class="form-control xmpp-host-field" value="<?php echo htmlspecialchars($defaultHost, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Contacto destino</label>
                                        <input type="text" name="contact_user" class="form-control" placeholder="usuario">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Host destino</label>
                                        <input type="text" name="contact_server" class="form-control xmpp-host-field" value="<?php echo htmlspecialchars($defaultHost, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Alias</label>
                                        <input type="text" name="nick" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Grupo o categoria</label>
                                        <input type="text" name="group" class="form-control">
                                    </div>
                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="ModalGrupoXmpp">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h5 class="modal-title" id="tituloModalGrupoXmpp">Crear grupo</h5>
                            </div>
                            <div class="modal-body">
                                <form id="formGrupoXmpp" autocomplete="off">
                                    <input type="hidden" name="modo" id="grupo_modo" value="crear">
                                    <div class="form-group col-md-6">
                                        <label>Nombre del grupo</label>
                                        <input type="text" name="group" id="grupo_group" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Host</label>
                                        <input type="text" name="host" id="grupo_host" class="form-control xmpp-host-field" value="<?php echo htmlspecialchars($defaultHost, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Display name</label>
                                        <input type="text" name="name" id="grupo_name" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Label visible</label>
                                        <input type="text" name="display" id="grupo_display" class="form-control">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Descripcion</label>
                                        <textarea name="description" id="grupo_description" class="form-control" rows="3"></textarea>
                                    </div>
                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="ModalAgregarUsuarioGrupoXmpp">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h5 class="modal-title">Agregar usuario a grupos</h5>
                            </div>
                            <div class="modal-body">
                                <form id="formAgregarUsuarioGrupoXmpp" autocomplete="off">
                                    <input type="hidden" name="username" id="grupo_usuario_username">
                                    <input type="hidden" name="host" id="grupo_usuario_host">
                                    <div class="form-group col-md-12">
                                        <label>Usuario seleccionado</label>
                                        <input type="text" id="grupo_usuario_label" class="form-control" readonly>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Seleccione uno o varios grupos</label>
                                        <div id="listaGruposUsuarioXmpp" class="xmpp-group-selector">
                                            <p class="xmpp-group-selector-empty">No hay grupos disponibles.</p>
                                        </div>
                                    </div>
                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="submit" id="btnGuardarUsuarioGrupoXmpp"><i class="fa fa-save"></i> Agregar a grupos</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="ModalDetalleLogXmpp">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h5 class="modal-title">Detalle del log</h5>
                            </div>
                            <div class="modal-body">
                                <p><strong>Accion:</strong> <span id="logDetalleAccion"></span></p>
                                <p><strong>Estado:</strong> <span id="logDetalleEstado"></span></p>
                                <p><strong>Mensaje:</strong> <span id="logDetalleMensaje"></span></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Request</label>
                                        <pre class="xmpp-pre" id="logDetalleRequest"></pre>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Response</label>
                                        <pre class="xmpp-pre" id="logDetalleResponse"></pre>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shadow">
                <img class="logo" src="img/pie.png"/>
            </div>
        </div>

        <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <script src="assets/bower_components/moment/min/moment.min.js"></script>
        <script src="assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/bower_components/fastclick/lib/fastclick.js"></script>
        <script src="assets/dist/js/adminlte.min.js"></script>
        <script src="assets/dist/js/demo.js"></script>
        <script src="js/d3.v5.min.js" charset="utf-8"></script>
        <script src="js/c3.js"></script>
        <script src="assets/bower_components/PACE/pace.min.js"></script>
        <script src="js/Chart.min.js"></script>
        <script src="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

        <script type="text/javascript">
            $(document).ajaxStart(function () {
                Pace.restart();
            });

            function notificar(tipo, mensaje) {
                notif({
                    msg: mensaje,
                    type: tipo,
                    position: "center"
                });
            }

            function mostrarAlerta(tipo, mensaje) {
                var clase = tipo === 'success' ? 'alert-success' : 'alert-danger';
                $('#xmppAlert').removeClass('alert-success alert-danger').addClass(clase).html(mensaje).show();
            }

            function ocultarAlerta() {
                $('#xmppAlert').hide().html('');
            }

            function xmppPost(action, data) {
                data = data || {};
                data.action = action;
                return $.ajax({
                    type: 'POST',
                    url: 'controller/xmpp_ajax.php',
                    data: data,
                    dataType: 'json'
                });
            }

            function parseList(data) {
                if (!data) {
                    return [];
                }
                if ($.isArray(data)) {
                    return data;
                }
                if (typeof data === 'object') {
                    if ($.isArray(data.res)) {
                        return data.res;
                    }
                    if ($.isArray(data.users)) {
                        return data.users;
                    }
                    if ($.isArray(data.groups)) {
                        return data.groups;
                    }
                    if ($.isArray(data.contacts)) {
                        return data.contacts;
                    }
                    if ($.isArray(data.members)) {
                        return data.members;
                    }
                    var arr = [];
                    $.each(data, function(key, value) {
                        arr.push(value);
                    });
                    return arr;
                }
                if (typeof data === 'string') {
                    return $.grep(data.split(/[\r\n,]+/), function(item) {
                        return $.trim(item) !== '';
                    });
                }
                return [];
            }

            function htmlEscape(value) {
                return $('<div>').text(value === null || value === undefined ? '' : value).html();
            }

            function prettyJson(value) {
                if (value === null || value === undefined || value === '') {
                    return '';
                }
                try {
                    if (typeof value === 'string') {
                        return JSON.stringify(JSON.parse(value), null, 2);
                    }
                    return JSON.stringify(value, null, 2);
                } catch (e) {
                    return value;
                }
            }

            function statusLabel(estado) {
                if (estado === 'exito') {
                    return '<span class="label label-success">exito</span>';
                }
                if (estado === 'error') {
                    return '<span class="label label-danger">error</span>';
                }
                return '<span class="label label-default">' + htmlEscape(estado || 'sin_probar') + '</span>';
            }

            function renderListaGruposUsuario() {
                var html = '';

                if (!gruposXmppCache.length) {
                    html = '<p class="xmpp-group-selector-empty">No hay grupos disponibles para asignar.</p>';
                    $('#btnGuardarUsuarioGrupoXmpp').prop('disabled', true);
                    $('#listaGruposUsuarioXmpp').html(html);
                    return;
                }

                $.each(gruposXmppCache, function(index, item) {
                    html += '<div class="checkbox">' +
                        '<label>' +
                        '<input type="checkbox" class="grupo-usuario-item" value="' + htmlEscape(item.group + '|' + item.host) + '">' +
                        ' ' + htmlEscape(item.group) + ' <span class="xmpp-muted">(' + htmlEscape(item.host) + ')</span>' +
                        '</label>' +
                        '</div>';
                });

                $('#btnGuardarUsuarioGrupoXmpp').prop('disabled', false);
                $('#listaGruposUsuarioXmpp').html(html);
            }

            function procesarAsignacionUsuarioGrupos(pendientes, payloadBase, resultados) {
                if (!pendientes.length) {
                    return $.Deferred().resolve(resultados).promise();
                }

                var actual = pendientes.shift();
                var payload = {
                    username: payloadBase.username,
                    host: payloadBase.host,
                    group: actual.group,
                    grouphost: actual.host
                };

                return xmppPost('add_group_member', payload).then(function(resp) {
                    resultados.push({
                        success: !!resp.success,
                        message: resp.message,
                        group: actual.group,
                        host: actual.host
                    });
                    return procesarAsignacionUsuarioGrupos(pendientes, payloadBase, resultados);
                }, function() {
                    resultados.push({
                        success: false,
                        message: 'No se pudo completar la solicitud para el grupo.',
                        group: actual.group,
                        host: actual.host
                    });
                    return procesarAsignacionUsuarioGrupos(pendientes, payloadBase, resultados);
                });
            }

            var tablaUsuarios;
            var tablaContactos;
            var tablaGrupos;
            var tablaMiembros;
            var tablaLogs;
            var xmppHostActual = '<?php echo htmlspecialchars($defaultHost, ENT_QUOTES, 'UTF-8'); ?>';
            var grupoSeleccionado = null;
            var gruposXmppCache = [];

            function llenarConfig(config) {
                if (!config) {
                    $('#estadoApiUrl').text('Sin configurar');
                    $('#estadoXmppHost').text('Sin configurar');
                    $('#estadoSslVerify').removeClass().addClass('label label-default').text('activa');
                    $('#ssl_verify').prop('checked', false);
                    $('#estadoConexionLabel').removeClass().addClass('label label-default').text('sin_probar');
                    return;
                }

                $('#api_url').val(config.api_url || '');
                $('#api_user').val(config.api_user || '');
                $('#xmpp_host').val(config.xmpp_host || '');
                $('#api_password').val('');
                $('#ssl_verify').prop('checked', parseInt(config.ssl_verify, 10) === 0);
                $('#estadoApiUrl').text(config.api_url || 'Sin configurar');
                $('#estadoXmppHost').text(config.xmpp_host || 'Sin configurar');
                if (parseInt(config.ssl_verify, 10) === 0) {
                    $('#estadoSslVerify').removeClass().addClass('label label-warning').text('desactivada');
                } else {
                    $('#estadoSslVerify').removeClass().addClass('label label-success').text('activa');
                }
                $('#estadoConexionLabel').replaceWith(statusLabel(config.ultimo_estado || 'sin_probar').replace('<span', '<span id="estadoConexionLabel"'));

                if (config.xmpp_host) {
                    xmppHostActual = config.xmpp_host;
                    $('.xmpp-host-field').val(xmppHostActual);
                }
            }

            function cargarConfig() {
                xmppPost('get_config').done(function(resp) {
                    if (resp.success) {
                        llenarConfig(resp.config);
                    }
                });
            }

            function cargarUsuarios() {
                xmppPost('list_users', { host: xmppHostActual }).done(function(resp) {
                    if (!resp.success) {
                        mostrarAlerta('error', resp.message);
                        return;
                    }

                    var usuarios = parseList(resp.data);
                    var rows = [];
                    $.each(usuarios, function(index, item) {
                        var username = '';
                        var host = xmppHostActual;

                        if (typeof item === 'object') {
                            username = item.user || item.username || item.name || item.jid || '';
                            host = item.host || item.server || xmppHostActual;
                        } else {
                            username = item;
                        }

                        if (username.indexOf('@') !== -1) {
                            var parts = username.split('@');
                            username = parts[0];
                            host = parts[1] || host;
                        }

                        var jid = username + '@' + host;
                        var opciones = '<div class="btn-group dropup">' +
                            '<button type="button" class="btn btn-primary">Opciones</button>' +
                            '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">' +
                            '<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>' +
                            '<ul class="dropdown-menu pull-right" role="menu">' +
                            '<li><a href="#0" class="btnAgregarUsuarioGrupoXmpp" data-username="' + htmlEscape(username) + '" data-host="' + htmlEscape(host) + '"><i class="fa fa-users"></i> Agregar a grupo</a></li>' +
                            '<li class="divider"></li>' +
                            '<li><a href="#0" class="btnPassXmpp" data-username="' + htmlEscape(username) + '" data-host="' + htmlEscape(host) + '"><i class="fa fa-key"></i> Cambiar password</a></li>' +
                            '<li><a href="#0" class="btnEliminarUsuarioXmpp" data-username="' + htmlEscape(username) + '" data-host="' + htmlEscape(host) + '"><i class="fa fa-trash"></i> Eliminar</a></li>' +
                            '</ul></div>';

                        rows.push([index + 1, htmlEscape(username), htmlEscape(jid), htmlEscape(host), opciones]);
                    });

                    tablaUsuarios.clear().rows.add(rows).draw();
                });
            }

            function cargarContactos() {
                var username = $('#contactos_username').val();
                var host = $('#contactos_host').val() || xmppHostActual;
                if ($.trim(username) === '') {
                    mostrarAlerta('error', 'Ingrese el usuario origen para listar contactos.');
                    return;
                }

                xmppPost('list_roster', { username: username, host: host }).done(function(resp) {
                    if (!resp.success) {
                        mostrarAlerta('error', resp.message);
                        return;
                    }

                    var contactos = parseList(resp.data);
                    var rows = [];
                    $.each(contactos, function(index, item) {
                        var contact = '';
                        var nick = '';
                        var group = '';
                        var subs = '';
                        var contactUser = '';
                        var contactServer = host;

                        if (typeof item === 'object') {
                            contact = item.jid || item.user || item.username || item.contact || '';
                            nick = item.nick || item.name || item.alias || '';
                            group = $.isArray(item.groups) ? item.groups.join(', ') : (item.group || '');
                            subs = item.subscription || item.subs || '';
                            contactUser = item.user || item.username || '';
                            contactServer = item.server || item.host || contactServer;
                        } else {
                            contact = item;
                        }

                        if (!contactUser && contact.indexOf('@') !== -1) {
                            var parts = contact.split('@');
                            contactUser = parts[0];
                            contactServer = parts[1] || contactServer;
                        } else if (!contactUser) {
                            contactUser = contact;
                        }

                        var opciones = '<a href="#0" style="color: #C71C22;" class="btnEliminarContacto" data-localuser="' + htmlEscape(username) + '" data-localserver="' + htmlEscape(host) + '" data-contactuser="' + htmlEscape(contactUser) + '" data-contactserver="' + htmlEscape(contactServer) + '"><i class="fa fa-trash"></i> Eliminar</a>';

                        rows.push([index + 1, htmlEscape(contact || (contactUser + '@' + contactServer)), htmlEscape(nick), htmlEscape(group), htmlEscape(subs), opciones]);
                    });

                    tablaContactos.clear().rows.add(rows).draw();
                });
            }

            function cargarGrupos() {
                xmppPost('list_groups', { host: xmppHostActual }).done(function(resp) {
                    if (!resp.success) {
                        gruposXmppCache = [];
                        renderListaGruposUsuario();
                        mostrarAlerta('error', resp.message);
                        return;
                    }

                    var grupos = parseList(resp.data);
                    var rows = [];
                    gruposXmppCache = [];
                    $.each(grupos, function(index, item) {
                        var group = '';
                        var host = xmppHostActual;

                        if (typeof item === 'object') {
                            group = item.group || item.name || item.id || '';
                            host = item.host || xmppHostActual;
                        } else {
                            group = item;
                        }

                        var opciones = '<div class="btn-group dropup">' +
                            '<button type="button" class="btn btn-primary">Opciones</button>' +
                            '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">' +
                            '<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>' +
                            '<ul class="dropdown-menu pull-right" role="menu">' +
                            '<li><a href="#0" class="btnVerMiembrosGrupo" data-group="' + htmlEscape(group) + '" data-host="' + htmlEscape(host) + '"><i class="fa fa-users"></i> Ver miembros</a></li>' +
                            '<li><a href="#0" class="btnEditarGrupo" data-group="' + htmlEscape(group) + '" data-host="' + htmlEscape(host) + '"><i class="fa fa-edit"></i> Editar</a></li>' +
                            '<li><a href="#0" class="btnEliminarGrupo" data-group="' + htmlEscape(group) + '" data-host="' + htmlEscape(host) + '"><i class="fa fa-trash"></i> Eliminar</a></li>' +
                            '</ul></div>';

                        rows.push([index + 1, htmlEscape(group), htmlEscape(host), opciones]);
                        gruposXmppCache.push({
                            group: group,
                            host: host
                        });
                    });

                    tablaGrupos.clear().rows.add(rows).draw();
                    renderListaGruposUsuario();
                });
            }

            function cargarMiembrosGrupo(group, host) {
                grupoSeleccionado = { group: group, host: host };
                $('#grupoSeleccionadoTexto').text(group + '@' + host);

                xmppPost('group_members', { group: group, host: host }).done(function(resp) {
                    if (!resp.success) {
                        mostrarAlerta('error', resp.message);
                        return;
                    }

                    var miembros = parseList(resp.data);
                    var rows = [];
                    $.each(miembros, function(index, item) {
                        var user = typeof item === 'object' ? (item.user || item.username || item.jid || '') : item;
                        var userHost = host;
                        if (user.indexOf('@') !== -1) {
                            var parts = user.split('@');
                            user = parts[0];
                            userHost = parts[1] || host;
                        }
                        var opciones = '<a href="#0" style="color: #C71C22;" class="btnQuitarMiembroGrupo" data-username="' + htmlEscape(user) + '" data-host="' + htmlEscape(userHost) + '" data-group="' + htmlEscape(group) + '" data-grouphost="' + htmlEscape(host) + '"><i class="fa fa-trash"></i> Quitar</a>';
                        rows.push([htmlEscape(user + '@' + userHost), opciones]);
                    });

                    tablaMiembros.clear().rows.add(rows).draw();
                });
            }

            function cargarLogs() {
                xmppPost('logs', {
                    accion: $('#filtroLogAccion').val(),
                    estado: $('#filtroLogEstado').val()
                }).done(function(resp) {
                    if (!resp.success) {
                        mostrarAlerta('error', resp.message);
                        return;
                    }

                    var rows = [];
                    $.each(resp.logs || [], function(index, log) {
                        rows.push([
                            htmlEscape(log.fecha),
                            htmlEscape(log.accion),
                            statusLabel(log.estado),
                            htmlEscape(log.mensaje),
                            '<a href="#0" class="btnVerDetalleLog" data-id="' + htmlEscape(log.id) + '"><i class="fa fa-eye"></i> Ver</a>'
                        ]);
                    });

                    tablaLogs.clear().rows.add(rows).draw();
                });
            }

            $(document).ready(function () {
                $('.sidebar-menu').tree();

                var dataTableLang = {
                    "search": "Buscar:",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Sin registros",
                    "zeroRecords": "No se encontraron resultados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                };

                tablaUsuarios = $('#tablaUsuariosXmpp').DataTable({ language: dataTableLang });
                tablaContactos = $('#tablaContactosXmpp').DataTable({ language: dataTableLang });
                tablaGrupos = $('#tablaGruposXmpp').DataTable({ language: dataTableLang });
                tablaMiembros = $('#tablaMiembrosGrupo').DataTable({ language: dataTableLang, paging: false, searching: false, info: false });
                tablaLogs = $('#tablaLogsXmpp').DataTable({ language: dataTableLang, order: [[0, 'desc']] });

                cargarConfig();
                cargarUsuarios();
                cargarGrupos();
                cargarLogs();

                $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
                    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                });

                $('#btnGuardarConfig').on('click', function(e) {
                    e.preventDefault();
                    ocultarAlerta();
                    xmppPost('save_config', $('#formConfigXmpp').serializeArray().reduce(function(obj, item) {
                        obj[item.name] = item.value;
                        return obj;
                    }, {})).done(function(resp) {
                        llenarConfig(resp.config);
                        mostrarAlerta(resp.success ? 'success' : 'error', resp.message);
                        notificar(resp.success ? 'success' : 'error', resp.message);
                        if (resp.success) {
                            cargarUsuarios();
                            cargarGrupos();
                            cargarLogs();
                        }
                    });
                });

                $('#btnProbarConexion').on('click', function(e) {
                    e.preventDefault();
                    ocultarAlerta();
                    xmppPost('test_connection', $('#formConfigXmpp').serializeArray().reduce(function(obj, item) {
                        obj[item.name] = item.value;
                        return obj;
                    }, {})).done(function(resp) {
                        llenarConfig(resp.config);
                        mostrarAlerta(resp.success ? 'success' : 'error', resp.message);
                        notificar(resp.success ? 'success' : 'error', resp.message);
                        cargarLogs();
                    });
                });

                $('#btnRecargarUsuarios').on('click', cargarUsuarios);
                $('#btnRecargarGrupos').on('click', cargarGrupos);
                $('#btnRecargarLogs, #filtroLogAccion, #filtroLogEstado').on('click change', cargarLogs);

                $('#formCrearUsuarioXmpp').on('submit', function(e) {
                    e.preventDefault();
                    xmppPost('create_user', $(this).serializeArray().reduce(function(obj, item) {
                        obj[item.name] = item.value;
                        return obj;
                    }, {})).done(function(resp) {
                        mostrarAlerta(resp.success ? 'success' : 'error', resp.message);
                        notificar(resp.success ? 'success' : 'error', resp.message);
                        if (resp.success) {
                            $('#ModalCrearUsuarioXmpp').modal('hide');
                            $('#formCrearUsuarioXmpp')[0].reset();
                            $('.xmpp-host-field').val(xmppHostActual);
                            cargarUsuarios();
                            cargarLogs();
                        }
                    });
                });

                $(document).on('click', '.btnPassXmpp', function(e) {
                    e.preventDefault();
                    var username = $(this).data('username');
                    var host = $(this).data('host');
                    $('#pass_username').val(username);
                    $('#pass_host').val(host);
                    $('#pass_usuario_label').val(username + '@' + host);
                    $('#ModalCambiarPasswordXmpp').modal('show');
                });

                $(document).on('click', '.btnAgregarUsuarioGrupoXmpp', function(e) {
                    e.preventDefault();
                    var username = $(this).data('username');
                    var host = $(this).data('host');

                    $('#grupo_usuario_username').val(username);
                    $('#grupo_usuario_host').val(host);
                    $('#grupo_usuario_label').val(username + '@' + host);

                    renderListaGruposUsuario();
                    $('#ModalAgregarUsuarioGrupoXmpp').modal('show');
                });

                $('#formCambiarPasswordXmpp').on('submit', function(e) {
                    e.preventDefault();
                    xmppPost('change_password', $(this).serializeArray().reduce(function(obj, item) {
                        obj[item.name] = item.value;
                        return obj;
                    }, {})).done(function(resp) {
                        mostrarAlerta(resp.success ? 'success' : 'error', resp.message);
                        notificar(resp.success ? 'success' : 'error', resp.message);
                        if (resp.success) {
                            $('#ModalCambiarPasswordXmpp').modal('hide');
                            $('#formCambiarPasswordXmpp')[0].reset();
                            cargarLogs();
                        }
                    });
                });

                $(document).on('click', '.btnEliminarUsuarioXmpp', function(e) {
                    e.preventDefault();
                    var username = $(this).data('username');
                    var host = $(this).data('host');
                    if (!confirm('Desea eliminar el usuario ' + username + '@' + host + '?')) {
                        return;
                    }
                    xmppPost('delete_user', { username: username, host: host }).done(function(resp) {
                        mostrarAlerta(resp.success ? 'success' : 'error', resp.message);
                        notificar(resp.success ? 'success' : 'error', resp.message);
                        if (resp.success) {
                            cargarUsuarios();
                            cargarLogs();
                        }
                    });
                });

                $('#formBuscarContactos').on('submit', function(e) {
                    e.preventDefault();
                    cargarContactos();
                });

                $('#ModalAgregarContacto').on('show.bs.modal', function() {
                    $('#agregar_localuser').val($('#contactos_username').val());
                    $('#agregar_localserver').val($('#contactos_host').val() || xmppHostActual);
                });

                $('#formAgregarContacto').on('submit', function(e) {
                    e.preventDefault();
                    xmppPost('add_contact', $(this).serializeArray().reduce(function(obj, item) {
                        obj[item.name] = item.value;
                        return obj;
                    }, {})).done(function(resp) {
                        mostrarAlerta(resp.success ? 'success' : 'error', resp.message);
                        notificar(resp.success ? 'success' : 'error', resp.message);
                        if (resp.success) {
                            $('#ModalAgregarContacto').modal('hide');
                            $('#formAgregarContacto')[0].reset();
                            $('.xmpp-host-field').val(xmppHostActual);
                            cargarContactos();
                            cargarLogs();
                        }
                    });
                });

                $(document).on('click', '.btnEliminarContacto', function(e) {
                    e.preventDefault();
                    if (!confirm('Desea eliminar este contacto?')) {
                        return;
                    }
                    xmppPost('delete_contact', {
                        localuser: $(this).data('localuser'),
                        localserver: $(this).data('localserver'),
                        contact_user: $(this).data('contactuser'),
                        contact_server: $(this).data('contactserver')
                    }).done(function(resp) {
                        mostrarAlerta(resp.success ? 'success' : 'error', resp.message);
                        notificar(resp.success ? 'success' : 'error', resp.message);
                        if (resp.success) {
                            cargarContactos();
                            cargarLogs();
                        }
                    });
                });

                $('#btnNuevoGrupo').on('click', function() {
                    $('#tituloModalGrupoXmpp').text('Crear grupo');
                    $('#grupo_modo').val('crear');
                    $('#grupo_group').prop('readonly', false);
                    $('#formGrupoXmpp')[0].reset();
                    $('.xmpp-host-field').val(xmppHostActual);
                });

                $(document).on('click', '.btnEditarGrupo', function(e) {
                    e.preventDefault();
                    var group = $(this).data('group');
                    var host = $(this).data('host');
                    $('#tituloModalGrupoXmpp').text('Editar grupo');
                    $('#grupo_modo').val('editar');
                    $('#grupo_group').val(group).prop('readonly', true);
                    $('#grupo_host').val(host);
                    $('#grupo_name').val(group);
                    $('#grupo_display').val(group);
                    $('#grupo_description').val('');
                    $('#ModalGrupoXmpp').modal('show');

                    xmppPost('group_info', { group: group, host: host }).done(function(resp) {
                        var info = resp.success ? resp.data : null;
                        if (info && typeof info === 'object') {
                            if (info.res && typeof info.res === 'object') {
                                info = info.res;
                            }
                            $('#grupo_name').val(info.label || info.name || info.group || group);
                            $('#grupo_display').val($.isArray(info.display) ? info.display.join(', ') : (info.display || info.displayed || info.label || group));
                            $('#grupo_description').val(info.description || '');
                        }
                    });
                });

                $('#formGrupoXmpp').on('submit', function(e) {
                    e.preventDefault();
                    var data = $(this).serializeArray().reduce(function(obj, item) {
                        obj[item.name] = item.value;
                        return obj;
                    }, {});
                    var action = data.modo === 'editar' ? 'update_group' : 'create_group';
                    xmppPost(action, data).done(function(resp) {
                        mostrarAlerta(resp.success ? 'success' : 'error', resp.message);
                        notificar(resp.success ? 'success' : 'error', resp.message);
                        if (resp.success) {
                            $('#ModalGrupoXmpp').modal('hide');
                            cargarGrupos();
                            cargarLogs();
                        }
                    });
                });

                $(document).on('click', '.btnEliminarGrupo', function(e) {
                    e.preventDefault();
                    var group = $(this).data('group');
                    var host = $(this).data('host');
                    if (!confirm('Desea eliminar el grupo ' + group + '?')) {
                        return;
                    }
                    xmppPost('delete_group', { group: group, host: host }).done(function(resp) {
                        mostrarAlerta(resp.success ? 'success' : 'error', resp.message);
                        notificar(resp.success ? 'success' : 'error', resp.message);
                        if (resp.success) {
                            cargarGrupos();
                            grupoSeleccionado = null;
                            tablaMiembros.clear().draw();
                            $('#grupoSeleccionadoTexto').text('Ninguno');
                            cargarLogs();
                        }
                    });
                });

                $(document).on('click', '.btnVerMiembrosGrupo', function(e) {
                    e.preventDefault();
                    cargarMiembrosGrupo($(this).data('group'), $(this).data('host'));
                });

                $('#formAgregarUsuarioGrupoXmpp').on('submit', function(e) {
                    e.preventDefault();
                    var seleccionados = [];
                    var username = $('#grupo_usuario_username').val();
                    var host = $('#grupo_usuario_host').val();

                    $('.grupo-usuario-item:checked').each(function() {
                        var parts = ($(this).val() || '').split('|');
                        if (parts.length === 2) {
                            seleccionados.push({
                                group: parts[0],
                                host: parts[1]
                            });
                        }
                    });

                    if (!username || !host) {
                        mostrarAlerta('error', 'No se encontro el usuario seleccionado.');
                        return;
                    }

                    if (!seleccionados.length) {
                        mostrarAlerta('error', 'Seleccione al menos un grupo.');
                        return;
                    }

                    $('#btnGuardarUsuarioGrupoXmpp').prop('disabled', true);

                    procesarAsignacionUsuarioGrupos(seleccionados.slice(), {
                        username: username,
                        host: host
                    }, []).done(function(resultados) {
                        var exitos = $.grep(resultados, function(item) {
                            return item.success;
                        });
                        var errores = $.grep(resultados, function(item) {
                            return !item.success;
                        });
                        var mensaje = '';

                        if (exitos.length && !errores.length) {
                            mensaje = 'Usuario agregado correctamente a ' + exitos.length + ' grupo(s).';
                        } else if (exitos.length && errores.length) {
                            mensaje = 'Se agrego el usuario a ' + exitos.length + ' grupo(s), pero hubo errores en: ' +
                                $.map(errores, function(item) {
                                    return item.group + '@' + item.host;
                                }).join(', ') + '.';
                        } else {
                            mensaje = errores[0] && errores[0].message ? errores[0].message : 'No se pudo agregar el usuario a los grupos seleccionados.';
                        }

                        mostrarAlerta(exitos.length ? 'success' : 'error', mensaje);
                        notificar(exitos.length ? 'success' : 'error', mensaje);

                        if (exitos.length) {
                            $('#ModalAgregarUsuarioGrupoXmpp').modal('hide');
                            $('#formAgregarUsuarioGrupoXmpp')[0].reset();

                            if (grupoSeleccionado) {
                                var recargarSeleccionado = false;
                                $.each(exitos, function(index, item) {
                                    if (item.group === grupoSeleccionado.group && item.host === grupoSeleccionado.host) {
                                        recargarSeleccionado = true;
                                        return false;
                                    }
                                });
                                if (recargarSeleccionado) {
                                    cargarMiembrosGrupo(grupoSeleccionado.group, grupoSeleccionado.host);
                                }
                            }

                            cargarLogs();
                        }
                    }).always(function() {
                        $('#btnGuardarUsuarioGrupoXmpp').prop('disabled', false);
                    });
                });

                $(document).on('click', '.btnQuitarMiembroGrupo', function(e) {
                    e.preventDefault();
                    if (!confirm('Desea quitar este miembro del grupo?')) {
                        return;
                    }
                    xmppPost('remove_group_member', {
                        username: $(this).data('username'),
                        host: $(this).data('host'),
                        group: $(this).data('group'),
                        grouphost: $(this).data('grouphost')
                    }).done(function(resp) {
                        mostrarAlerta(resp.success ? 'success' : 'error', resp.message);
                        notificar(resp.success ? 'success' : 'error', resp.message);
                        if (resp.success && grupoSeleccionado) {
                            cargarMiembrosGrupo(grupoSeleccionado.group, grupoSeleccionado.host);
                            cargarLogs();
                        }
                    });
                });

                $(document).on('click', '.btnVerDetalleLog', function(e) {
                    e.preventDefault();
                    xmppPost('log_detail', { id: $(this).data('id') }).done(function(resp) {
                        if (!resp.success) {
                            mostrarAlerta('error', resp.message);
                            return;
                        }
                        $('#logDetalleAccion').text(resp.log.accion);
                        $('#logDetalleEstado').html(statusLabel(resp.log.estado));
                        $('#logDetalleMensaje').text(resp.log.mensaje || '');
                        $('#logDetalleRequest').text(prettyJson(resp.log.request_data));
                        $('#logDetalleResponse').text(prettyJson(resp.log.response_data));
                        $('#ModalDetalleLogXmpp').modal('show');
                    });
                });
            });
        </script>
    </body>
</html>
