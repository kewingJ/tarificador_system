<?php
    include_once 'includes/config.php';
    include_once 'includes/security.php';
    date_default_timezone_set('America/Managua');

    $licenciaStatus = false;

    session_start();
    require_once 'includes/auth_check.php';
    require_web_auth(1);

    $id = $_SESSION['id_u'];
    $nombre = $_SESSION['nombre'];
    $apellido = $_SESSION['apellido'];
    $activo = $_SESSION['activo'];
    $tipo = $_SESSION['tipo_usuario'];

    if($activar_licencia){
        if(!$activar_sistema){
            $content = 'style="display: none;"';
        }
    }

    /*optener solo el primer nombre y el primer apellido del profesor*/
    $nombre = explode(' ', $nombre);
    @$nombre = $nombre[0];

    $apellido = explode(' ', $apellido);
    @$apellido = $apellido[0];

    $consult = mysqli_query($link,"SELECT * FROM usuario WHERE id_usuario = '$id'");
    $row = mysqli_fetch_array($consult);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Bienvenido <?php echo $nombre.' '.$apellido; ?></title>
        <!-- Favicons -->
        <link rel="shortcut icon" href="img/logo_icono.png">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
        <!-- daterange picker -->
        <link rel="stylesheet" href="assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
            folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="assets/dist/css/skins/_all-skins.min.css">
        <!-- Pace style -->
        <link rel="stylesheet" href="assets/plugins/pace/pace.min.css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        <!-- Select2 -->
        <!-- <link rel="stylesheet" href="assets/bower_components/select2/dist/css/select2.min.css"> -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <!-- DataTables -->
        <link rel="stylesheet" href="assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="css/cerulean/bootstrap.css" media="screen">
        <script type="text/javascript" src="js/notifIt.js"></script>
        <link rel="stylesheet" type="text/css" href="css/notifIt.css">
        <link rel="stylesheet" type="text/css" href="css/c3.css">
        <!-- <link rel="stylesheet" type="text/css" href="css/default/default.css"> -->
        <link href="css/flags16-both.css" rel="stylesheet" type="text/css">
        <link href="css/flags32-both.css" rel="stylesheet" type="text/css">

        <!-- Morris chart -->
        <link rel="stylesheet" href="assets/bower_components/morris.js/morris.css">

        <!-- new -->
        <link rel="stylesheet" type="text/css" href="assets/helpers/boilerplate.css">
        <link rel="stylesheet" type="text/css" href="assets/helpers/grid.css">
        <link rel="stylesheet" type="text/css" href="assets/helpers/utils.css">
        <!-- ICONS -->
        <link rel="stylesheet" type="text/css" href="assets/icons/fontawesome/fontawesome.css">
        <!-- SNIPPETS -->
        <link rel="stylesheet" type="text/css" href="assets/snippets/user-profile.css">
        <link rel="stylesheet" type="text/css" href="assets/snippets/mobile-navigation.css">
        <!-- Frontend theme -->
        <link rel="stylesheet" type="text/css" href="assets/themes/frontend/layout.css">
        <!-- <link rel="stylesheet" type="text/css" href="assets/themes/frontend/color-schemes/default.css"> -->
        <!-- Components theme -->
        <link rel="stylesheet" type="text/css" href="assets/themes/components/default.css">
        <!-- Frontend responsive -->
        <link rel="stylesheet" type="text/css" href="assets/helpers/frontend-responsive.css">

        <link rel="stylesheet" type="text/css" href="assets/elements/social-box.css">
        <link rel="stylesheet" type="text/css" href="assets/elements/tile-box.css">

        
        <script>
            var CSRF_TOKEN = <?php echo json_encode(csrf_token()); ?>;
        </script>

        <script>
            function not1(){
                notif({
                    msg: "Se guardo correctamente",
                    type: "success",
                    position: "center"
                });
            }
        </script>

        <script>
            function not2(){
                notif({
                    msg: "Algunos campos estan vacios",
                    type: "error",
                    position: "center"
                });
            }
        </script>
        
        <script>
            function not3(){
                notif({
                    msg: "Los datos se actualizarón correctamente",
                    type: "success",
                    position: "center"
                });
            }
        </script>

        <script>
            function not4(){
                notif({
                    msg: "Se elimino correctamente",
                    type: "success",
                    position: "center"
                });
            }
        </script>

        <script>
            function not5(){
                notif({
                    msg: "Error! algo salio mal",
                    type: "error",
                    position: "center"
                });
            }
        </script>

        <script>
            function not6(){
                notif({
                    msg: "Datos actualizados!!",
                    type: "success",
                    position: "center"
                });
            }
        </script>

        <script>
            function not7(){
                notif({
                    msg: "archivo actualizado!!",
                    type: "success",
                    position: "center"
                });
            }
        </script>

        <script>
            function not8(){
                notif({
                    msg: "Reporte generado correctamente!!",
                    type: "success",
                    position: "center"
                });
            }
        </script>

        <style type="text/css">
            .skin-blue .main-header .navbar{
                background-image: url('img/header.png');
            }
        </style>

        <style type="text/css">
            .flag.deprecated { color: silver; }
            .flag.island { color: navy; }

            .select2-container--default .select2-selection--single .select2-selection__rendered{
                line-height: 24px;
            }

            .icono-relog{
                float: left;
                font-size: 1.4em;
                padding: 0px 4px;
                color: #212c4c;
            }

            .icono-bandera{
                float: left;
            }
            
            .estiloFila {
                float:left;
                display:inline;
            }
            
            .dosColumnas li {
                width:50%;
            }

            .tresColumnas li {
                width:33.333%;
            }

            .cuatroColumnas li {
                width:25%;
            }

            .cincoColumnas li {
                width:16.666%;
            }

            .morris-hover-point{
                color: #666 !important;
            }

            .progress{
                margin-bottom: 0px !important;
                width: 18em;
            }

            .small-box>.small-box-footer{
                padding: 0px !important;
            }

            .small-box {
                border-radius: 12px;
            }

            .bg-oscuro-gradient, .bg-teal-gradient {
                background: -webkit-gradient(linear, left bottom, left top, color-stop(0, #141e30), color-stop(1, #243b55)) !important;
                background-image: background: -webkit-linear-gradient(bottom, #141e30, #243b55) !important;
                background-image: background: -o-linear-gradient(bottom, #141e30, #243b55) !important;
                background-image: background: linear-gradient(to top, #141e30, #243b55) !important;
                background: -moz-linear-gradient(center bottom, #141e30 0, #243b55 100%) !important;
                background: -o-linear-gradient(#243b55, #141e30) !important;
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#243b55', endColorstr='#141e30', GradientType=0) !important;
                color: #fff;
            }

            #example2_filter {
                display: none !important;
            }

            .nav-tabs-custom>.nav-tabs>li {
                margin-bottom: 0px !important;
                margin-right: 0px !important;
            }

            div.shadow {
                /*position:absolute;*/
                background: #ecf0f5;
                /*max-width:45%;
                max-height:45%;
                top:50%;
                left:50%;
                overflow:visible;*/
            }

            img.logo {
                position:relative;
                max-width:100%;
                max-height:100%;
                /*margin-top:-50%;
                margin-left:-50%;*/
            }

            table.dataTable thead tr {
                background-color: #d5ce07;
            }

        </style>

        <style type="text/css">
            .table {
                color: #495057 !important;
            }
            /* Fonts weight */
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

            /* Top bar menu */
            .bg-topbar {
                background: #fff;
                border-bottom-color: #eee;
            }
            /* Main header */

            .bg-header {
                background: #fff;
            }
            .sticky-active .main-header {
                box-shadow: 0 0 1px 2px rgba(0, 0, 0, 0.05);
            }
            /* Header subnav menu */

            .main-header .header-nav > li > ul {
                background: #253035;
            }
            .main-header .header-nav > li > ul li a:hover {
                background: rgba(255,255,255,0.05);
                color: #dce4e8;
            }
        </style>

        <style>
            .contenedorContacto {
                border: 1px solid #dfe8f1;
            }

            .borderGreen{
                border: 2px solid #2ecc71;
            }

            .borderRed{
                border: 2px solid #cf4436;
            }
        </style>

        <style>
            .timeline-steps {
                display: flex;
                /* justify-content: center; */
                flex-wrap: wrap
            }

            .timeline-steps .timeline-step {
                align-items: center;
                display: flex;
                flex-direction: column;
                position: relative;
                margin: 1rem
            }

            @media (min-width:768px) {
                .timeline-steps .timeline-step:not(:last-child):after {
                    content: "";
                    display: block;
                    border-top: .25rem dotted #3b82f6;
                    width: 3.46rem;
                    position: absolute;
                    left: 7.5rem;
                    top: .3125rem
                }
                .timeline-steps .timeline-step:not(:first-child):before {
                    content: "";
                    display: block;
                    border-top: .25rem dotted #3b82f6;
                    width: 3.8125rem;
                    position: absolute;
                    right: 7.5rem;
                    top: .3125rem
                }
            }

            .timeline-steps .timeline-content {
                width: 10rem;
                text-align: center
            }

            .timeline-steps .timeline-content .inner-circle {
                border-radius: 1.5rem;
                height: 1rem;
                width: 1rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background-color: #3b82f6
            }

            .timeline-steps .timeline-content .inner-circle:before {
                content: "";
                background-color: #3b82f6;
                display: inline-block;
                height: 3rem;
                width: 3rem;
                min-width: 3rem;
                border-radius: 6.25rem;
                opacity: .5
            }

            #total_llamadas i,
            #total_contestadas i,
            #total_duracion i,
            #total_salientes i {
                font-size: 1.25rem; /* tamaño */
                color: #0d6efd;     /* azul bootstrap, por ejemplo */
            }
        </style>

        <style>
            .wizard-ext {
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 6px;
                padding: 20px;
                position: relative;
            }

            .wizard-steps {
                margin: 0;
                padding: 0 0 0 6px;
            }

            .wizard-step {
                list-style: none;
                margin: 0 0 24px;
                padding-left: 40px;
                position: relative;
                color: #9ca3af;
                cursor: default;
            }

            .wizard-step:last-child {
                margin-bottom: 0;
            }

            .wizard-step:after {
                content: "";
                position: absolute;
                left: 13px;
                top: 34px;
                height: calc(100% - 34px);
                width: 2px;
                background: #e5e7eb;
            }

            .wizard-step:last-child:after {
                display: none;
            }

            .wizard-step-number {
                position: absolute;
                left: 0;
                top: 0;
                width: 28px;
                height: 28px;
                border-radius: 50%;
                background: #e5e7eb;
                color: #6b7280;
                text-align: center;
                line-height: 28px;
                font-weight: 600;
                font-size: 13px;
            }

            .wizard-step-title {
                font-weight: 600;
                font-size: 14px;
                display: block;
            }

            .wizard-step.active {
                color: #1f2937;
            }

            .wizard-step.active .wizard-step-number {
                background: #3b82f6;
                color: #fff;
            }

            .wizard-step.completed .wizard-step-number {
                background: #2563eb;
                color: #fff;
            }

            .wizard-step.completed:after {
                background: #93c5fd;
            }

            .wizard-content h3 {
                margin-top: 0;
                margin-bottom: 6px;
                font-size: 22px;
                font-weight: 600;
            }

            .wizard-panel {
                display: none;
            }

            .wizard-panel.active {
                display: block;
            }

            .wizard-actions {
                margin-top: 20px;
                display: flex;
                justify-content: flex-end;
                gap: 10px;
            }

            .wizard-loading {
                display: none;
                position: absolute;
                inset: 0;
                background: rgba(255, 255, 255, 0.85);
                align-items: center;
                justify-content: center;
                flex-direction: column;
                z-index: 5;
                border-radius: 6px;
            }

            .wizard-loading i {
                font-size: 32px;
                color: #3b82f6;
                margin-bottom: 10px;
            }

            .wizard-loading span {
                font-weight: 600;
                color: #1f2937;
            }

            .wizard-ext.is-loading .wizard-loading {
                display: flex;
            }

            .wizard-radio-group {
                display: flex;
                flex-wrap: wrap;
                gap: 18px;
                margin-top: 10px;
            }

            @media (max-width: 991px) {
                .wizard-steps {
                    margin-bottom: 20px;
                }
            }
        </style>

        <script>
            function not9(){
                notif({
                    msg: "XML generado!",
                    type: "success",
                    position: "center"
                });
            }
        </script>

        <style>
            /* Estética oscura similar a la captura */
            .license-dark {
            background: #1f1f1f; color: #e6e6e6; border: 1px solid #333;
            }
            .license-dark .modal-header, .license-dark .modal-footer {
            border-color: #333;
            }
            .kv { display: grid; grid-template-columns: 1fr; gap: 14px; }
            .kv > div { display: flex; justify-content: space-between; align-items: center; }
            .kv span { color: #9aa0a6; margin-right: 16px; }
            .key-row { display: flex; gap: 10px; align-items: center; }
            .btn-inverse { background:#2b2b2b; color:#e6e6e6; border:1px solid #3a3a3a; }
            .btn-inverse:hover { background:#363636; }
            code#ls-key { background:#111; color:#c9e3ff; padding:4px 6px; border-radius:4px; display:inline-block; }
        </style>


        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/php_file_tree_jquery.js" type="text/javascript"></script>
    </head>
    
    <body class="hold-transition skin-blue layout-top-nav">
        <!-- Site wrapper -->
        <div class="wrapper">
            <?php include 'includes/navbar.php'; ?>
            <!-- =============================================== -->
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">

                            <li <?php echo $content; ?> class="dropdown pull-left">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                  Opciones <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li role="presentation">
                                        <a role="menuitem" tabindex="-1" id="update" href="#0">
                                            <small> <i class="fa fa-refresh"></i> Actualizar</small>
                                        </a>
                                    </li>

                                    <li role="presentation">
                                        <a role="menuitem" tabindex="-1" href="#0" data-toggle="modal" data-target="#modalE">
                                            <small> <i class="fa fa-trash"></i> Eliminar</small>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">
                                <i class="fa fa-dashboard"></i> Dashboard
                                </a>
                            </li>

                            <li>
                                <a href="#tab_2" data-toggle="tab">
                                <img src="img/tap2.png" style="width: 20px;"> Trafico de llamadas
                                </a>
                            </li>

                            <li>
                                <a href="#tab_5" data-toggle="tab" id="graficoDuracion">
                                <img src="img/tap3.png" style="width: 20px;"> Top 10 Duracion Extension
                                </a>
                            </li>

                            <li>
                                <a href="#tab_6" data-toggle="tab" id="graficoLlamadas">
                                <i class="fa fa-pie-chart"></i> Grafico Llamadas
                                </a>
                            </li>

                            <li>
                                <a href="#tab_7" data-toggle="tab">
                                <img src="img/tap5.png" style="width: 20px;"> Llamadas Perdidas
                                </a>
                            </li>

                            <li id="ataquesGrafica">
                                <a href="#tab_8" data-toggle="tab">
                                <img src="img/tap6.png" style="width: 20px;"> Lista de ataques
                                </a>
                            </li>

                            <li id="listaLlamadas">
                                <a href="#tab_9" data-toggle="tab">
                                <img src="img/tap7.png" style="width: 20px;"> llamadas concurrentes
                                </a>
                            </li>

                            <li id="listaExtension">
                                <a href="#tab_10" data-toggle="tab">
                                <img src="img/tap8.png" style="width: 20px;"> Extensiones en linea
                                </a>
                            </li>

                            <li id="listaExtension2">
                                <a href="#tab_12" data-toggle="tab">
                                <img src="img/lista_extensiones.png" style="width: 20px;"> Lista de extensiones
                                </a>
                            </li>

                            <li id="tapBook">
                                <a href="#tab_11" data-toggle="tab">
                                <img src="img/phonebook_40497.png" style="width: 20px;"> Agenda
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">

                            <div <?php echo $content; ?> class="tab-pane active" id="tab_1">
                                
                                <div class="row">

                                    <div class="col-md-12" style="padding: 5px 0px;">
                                        <form id="FormCalendario" class="FormCalendario" method="post" autocomplete="off" enctype="multipart/form-data">
                                            <div class="pull-right" style="width: 20%;">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control pull-right" id="reservationCards">
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="tile-box bg-white content-box">
                                            <div class="tile-header">
                                                Total llamadas
                                                <div class="float-right">
                                                </div>
                                            </div>
                                            <div class="tile-content-wrapper">
                                                <img src="img/customer-service_8708809.png" style="width: 10%;float: left;" />
                                                <div class="tile-content" id="total_llamadas">
                                                    <?php
                                                        $total_llamadas = 0;
                                                        $consult = mysqli_query($link,"SELECT count(*) as total FROM cdr_espejo");
                                                        $row = mysqli_fetch_array($consult);
                                                        $total_llamadas = $row['total'];
                                                        echo $total_llamadas;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="tile-box bg-white content-box">
                                            <div class="tile-header">
                                                Llamadas Contestadas
                                                <div class="float-right">
                                                </div>
                                            </div>
                                            <div class="tile-content-wrapper">
                                                <img src="img/customer-service_12154926.png" style="width: 10%;float: left;" />
                                                <div class="tile-content" id="total_contestadas">
                                                <?php
                                                    $total_contestadas = 0;
                                                    $consult = mysqli_query($link,"SELECT count(*) as total FROM cdr_espejo WHERE estado = 'ANSWERED'");
                                                    $row = mysqli_fetch_array($consult);
                                                    $total_contestadas = $row['total'];
                                                    echo $total_contestadas;
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="tile-box bg-white content-box">
                                            <div class="tile-header">
                                                Duracion de llamadas
                                                <div class="float-right">
                                                </div>
                                            </div>
                                            <div class="tile-content-wrapper">
                                                <img src="img/time_8947827.png" style="width: 10%;float: left;" />
                                                <div class="tile-content" id="total_duracion">
                                                <?php
                                                    $hora_texto = "00:00:00";
                                                    $billsec = 0;
                                                    $consultaDuracion = mysqli_query($link,"SELECT billsec FROM cdr");
                                                    while($rowDuracion = mysqli_fetch_array($consultaDuracion)){
                                                        $billsec += $rowDuracion['billsec'];
                                                    }
                                                    if($billsec > 0) {
                                                        $hora_texto = gmdate("H:i:s", $billsec);
                                                    }
                                                    echo $hora_texto;
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="tile-box bg-white content-box">
                                            <div class="tile-header">
                                                Entrantes / Salientes PSTN
                                                <div class="float-right">
                                                </div>
                                            </div>
                                            <div class="tile-content-wrapper">
                                                <img src="img/forward_8862761.png" style="width: 10%;float: left;" />
                                                <div class="tile-content" id="total_salientes">
                                                    <?php
                                                        $entrantes = 0;
                                                        $salientes = 0;
                                                        $consult = mysqli_query($link,"SELECT * FROM estadistica_llamadas");
                                                        $row = mysqli_fetch_array($consult);
                                                        $entrantes = $row['total_entrante'];
                                                        $salientes = $row['total_saliente'];
                                                        echo $entrantes.' / '.$salientes;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br/>

                                <!-- grafico de tiempo por operadores -->
                                <div class="row">
                                    <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
                                </div>
                                
                                <!-- grafica de rango -->
                                <div class="row">
                                    <h3 class="text-center">Graficas de llamadas</h3>
                                    <div class="col-md-4">
                                        <div id="" class="chart_tari1"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <div id="" class="chart_tari2"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <div id="" class="chart_tari3"></div>
                                    </div>
                                </div>
                            </div>

                            <div <?php echo $content; ?> class="tab-pane" id="tab_2">

                                <div class="text-center">
                                    <div class="btn-group">
        
                                        <a href="#0" data-toggle="modal" data-target="#modalReporteGeneral" class="btn btn-primary">
                                            <i class="fa fa-file-pdf-o"></i> Reporte Pdf General
                                        </a>

                                        <a href="controller/reporteExel.php" class="btn btn-primary" id="btnExcel">
                                            <i class="fa fa-file-excel-o"></i> Reporte Excel General
                                        </a>

                                        <a href="#0" data-toggle="modal" data-target="#modalReporte" class="btn btn-primary">
                                            <i class="fa fa-file"></i> Reporte por extension origen
                                        </a>

                                        <a href="#0" data-toggle="modal" data-target="#modalReporteDestino" class="btn btn-primary">
                                            <i class="fa fa-file"></i> Reporte por extension destino
                                        </a>

                                        <a href="#0" data-toggle="modal" data-target="#modalReporteOperador" class="btn btn-primary">
                                            <i class="fa fa-file-pdf-o"></i> Reporte por operador
                                        </a>

                                        <a href="#0" data-toggle="modal" data-target="#modalReporteLlamadas" class="btn btn-primary">
                                            <i class="fa fa-file-pdf-o"></i> Reporte por llamadas
                                        </a>

                                        <a href="#0" id="btn_eliminar" class="btn btn-danger btnDelete" style="display: none;">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </a>
                                                        
                                    </div>
                                </div>

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Origen</th>
                                            <th>Destino</th>
                                            <th>Fecha de llamada</th>
                                            <th>Hora de llamada</th>
                                            <th>Estado</th>
                                            <th>Duración(H:M:S)</th>
                                            <th>Operador</th>
                                            <th>Transferencia</th>
                                            <th>Pais de origen</th>
                                            <th>Pais de destino</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </div>

                            <div <?php echo $content; ?> class="tab-pane" id="tab_5">
                                <form id="FormRa" class="FormRan" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="pull-right" style="width: 20%;">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="rangoFechaUno">
                                        </div>
                                    </div>
                                </form>

                                <div id="container2" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                            </div>

                            <div <?php echo $content; ?> class="tab-pane" id="tab_6">
                                <form id="FormR" class="FormRa" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="pull-right" style="width: 20%;">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="reservationGrafica">
                                        </div>
                                    </div>
                                </form>
                                
                                <div id="container5" style="min-width: 400px; height: 400px; max-width: 650px; margin: 0 auto"></div>
                            </div>

                            <div <?php echo $content; ?> class="tab-pane" id="tab_7">

                                <div class="text-center">
                                    <div class="btn-group">
        
                                        <a href="#0" data-toggle="modal" data-target="#modalReporteGeneral" class="btn btn-primary">
                                            <i class="fa fa-file-pdf-o"></i> Reporte Pdf General
                                        </a>

                                        <a href="controller/reporteExel.php" class="btn btn-primary" id="btnExcel">
                                            <i class="fa fa-file-excel-o"></i> Reporte Excel General
                                        </a>

                                        <a href="#0" data-toggle="modal" data-target="#modalReporte" class="btn btn-primary">
                                            <i class="fa fa-file"></i> Reporte por extension origen
                                        </a>

                                        <a href="#0" data-toggle="modal" data-target="#modalReporteDestino" class="btn btn-primary">
                                            <i class="fa fa-file"></i> Reporte por extension destino
                                        </a>

                                        <a href="#0" data-toggle="modal" data-target="#modalReporteOperador" class="btn btn-primary">
                                            <i class="fa fa-file-pdf-o"></i> Reporte por operador
                                        </a>

                                        <a href="#0" data-toggle="modal" data-target="#modalReporteLlamadas" class="btn btn-primary">
                                            <i class="fa fa-file-pdf-o"></i> Reporte por llamadas
                                        </a>

                                        <a href="#0" id="btn_eliminar" class="btn btn-danger btnDelete" style="display: none;">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </a>
                                                        
                                    </div>
                                </div>

                                <table id="example4" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Origen</th>
                                            <th>Destino</th>
                                            <th>Fecha de llamada</th>
                                            <th>Hora de llamada</th>
                                            <th>Estado</th>
                                            <th>Duración(H:M:S)</th>
                                            <th>Operador</th>
                                            <th>Transferencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div <?php echo $content; ?> class="tab-pane" id="tab_8">
                                <div class="example-box-wrapper clearfix">
                                    <div class="nav-tabs-custom">
                                        <!-- Tabs within a box -->
                                        <ul class="nav nav-tabs">
                                            <li class="active">
                                                <a href="#tap_10" data-toggle="tab">Dashboard</a>
                                            </li>
                                            <li>
                                                <a href="#tap_11" id="graficoP" data-toggle="tab">Ataques por paises</a>
                                            </li>
                                            <li>
                                                <a href="#tap_12" id="graficoIP" data-toggle="tab">Top 5 Ip Bloquedas</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content no-padding">
                                            <div class="col-md-12 chart tab-pane active" id="tap_10">
                                                <div class="row">
                                                    <form id="FormRBB" class="FormRBnn" method="post" autocomplete="off" enctype="multipart/form-data">
                                                        <div class="pull-right" style="width: 20%;">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input type="text" class="form-control pull-right" id="rangoFechaAtaques">
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="chart tab-pane active" id="revenue-chart2" style="position: relative; height: 300px;"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="text-center">
                                                        <select id="dropdown1" class="select2">
                                                            <option value="">Tipo ataques</option>
                                                            <?php
                                                                $consult = mysqli_query($link,"SELECT DISTINCT tipo_bloqueo FROM bloqueo_ataques");
                                                                while($row = mysqli_fetch_array($consult)){
                                                                    if(!empty($row['tipo_bloqueo'])){
                                                                        echo '<option value="'.$row['tipo_bloqueo'].'">'.$row['tipo_bloqueo'].'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <table id="example8" class="table table-bordered table-striped" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Fecha Bloqueo</th>
                                                                <th>Ip Bloqueo</th>
                                                                <th>Tipo Bloqueo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="col-md-12 chart tab-pane" id="tap_11">
                                                <form id="FormRB" class="FormRBn" method="post" autocomplete="off" enctype="multipart/form-data">
                                                    <div class="pull-right" style="width: 20%;">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input type="text" class="form-control pull-right" id="rangoFechaPais">
                                                        </div>
                                                    </div>
                                                </form>
                                                <div id="containerPais" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                                            </div>

                                            <div class="col-md-12 chart tab-pane" id="tap_12">
                                                <form id="FormRB" class="FormRBn" method="post" autocomplete="off" enctype="multipart/form-data">
                                                    <div class="pull-right" style="width: 20%;">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input type="text" class="form-control pull-right" id="rangoFechaTopIp">
                                                        </div>
                                                    </div>
                                                </form>
                                                <div id="containerTopIp" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div <?php echo $content; ?> class="tab-pane" id="tab_9">
                                <div class="row">
                                    <h3 class="text-center">Llamadas Online</h3>
                                    <div id="content_dynamic_tabla_llamada">
                                        <table id="example3" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>De</th>
                                                    <th>Para</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td  colspan="3" class="dataTables_empty text-center">No data available in table</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div <?php echo $content; ?> class="tab-pane" id="tab_10">
                                <div class="row">
                                    <h3 class="text-center">Extensiones en linea</h3>
                                    <div class="col-md-12">
                                        <img src="img/Leyenda_servidores.jpg" style="width: 80px;float: inline-end;">
                                    </div>
                                    <div id="content_dynamic_tabla_extension">
                                        <table id="example5" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Extension</th>
                                                    <th style="display:none">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div <?php echo $content; ?> class="tab-pane" id="tab_11">
                                <div class="row" style="padding: 10px;">
                                    <div class="btn-group pull-right">
                                        <button type="button" class="btn btn-success dropdown-toggle btn-block" data-toggle="dropdown">
                                            Generar XML
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu" style="margin-top: 2px;width: inherit;">
                                            <li id="btnXml">
                                                <a href="#0">
                                                    <i class="fa fa-code"></i> Generar XML Gandstream
                                                </a>
                                            </li>
                                            <li id="btnXmlCisco">
                                                <a href="#0">
                                                    <i class="fa fa-code"></i> Generar XML Cisco
                                                </a>
                                            </li>
                                            <li id="btnXmlSnom">
                                                <a href="#0">
                                                    <i class="fa fa-code"></i> Generar XML Snom
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="btn-group pull-right">
                                        <button data-toggle="modal" data-target="#ModalNA" id="btnModalBook" type="button" class="btn btn-success">
                                            <i class="fa fa-plus"></i> Nuevo directorio
                                        </button>
                                    </div>

                                    <div class="btn-group pull-right">
        
                                        <a href="#0" data-toggle="modal" data-target="#modalListaCargo" class="btn btn-primary">
                                            <i class="fa fa-list"></i> Lista cargos
                                        </a>

                                        <a href="#0" data-toggle="modal" data-target="#modalListaOficina" class="btn btn-primary">
                                            <i class="fa fa-list"></i> Lista oficinas
                                        </a>
                                                        
                                    </div>
                                </div>

                                <div class="row">
                                    <table id="tbla_book" class="table table-bordered table-striped" style="width: 100% !important;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th></th>
                                                <th>Nombres</th>
                                                <th>Apellidos</th>
                                                <th>Extencion</th>
                                                <th>Indice</th>
                                                <th>Tipo</th>
                                                <th class="text-center">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div <?php echo $content; ?> class="tab-pane" id="tab_12">
                                <div class="example-box-wrapper clearfix">
                                    <div class="nav-tabs-custom">
                                        <!-- Tabs within a box -->
                                        <ul class="nav nav-tabs">
                                            <li class="active">
                                                <a href="#tap_lista" data-toggle="tab">
                                                    <i class="fa fa-list"></i> Lista
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#tap_crear" data-toggle="tab">
                                                    <i class="fa fa-plus"></i> Crear extensiones
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content no-padding">
                                            <div class="col-md-12 chart tab-pane active" id="tap_lista">
                                                <div class="row">
                                                    <h3 class="text-center">Lista de Extensiones</h3>
                                                    <div class="col-md-12">
                                                        <img src="img/Leyenda_servidores.jpg" style="width: 80px;float: inline-end;">
                                                    </div>

                                                    <div class="box-header" style="top: 10px;">
                                                        <div class="pull-right">
                                                            <button type="button" id="btnEliminarExtensiones" class="btn btn-danger" style="display: none; margin-right: 8px;">
                                                                <i class="fa fa-trash"></i> Eliminar
                                                            </button>
                                                            <button type="button" id="btnEditEndpointsConf" class="btn btn-primary" style="margin-right: 8px;">
                                                                <i class="fa fa-file-text-o"></i> Editar archivo .conf
                                                            </button>
                                                            <button type="button" id="btnCambios" class="btn btn-success">
                                                                <i class="fa fa-refresh"></i> Aplicar cambios
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <table id="example6" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th style="text-align: center; width: 36px;">
                                                                    <input type="checkbox" id="ext_select_all" title="Seleccionar todo">
                                                                </th>
                                                                <th>#</th>
                                                                <th style="text-align: center;">Extensión</th>
                                                                <th style="text-align: center;">Caller Id</th>
                                                                <th style="text-align: center;">Estado</th>
                                                                <th style="text-align: center;">Editar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>

                                            <div class="col-md-12 chart tab-pane" id="tap_crear">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="box box-primary">
                                                            <div class="box-header with-border">
                                                                <h3 class="box-title">Crear extensiones</h3>
                                                            </div>
                                                            <div class="box-body">
                                                                <div class="wizard-ext" id="wizardExtensiones">
                                                                    <div class="wizard-loading" aria-live="polite" aria-busy="true">
                                                                        <i class="fa fa-circle-o-notch fa-spin"></i>
                                                                        <span>Creando extensiones...</span>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <ul class="wizard-steps">
                                                                                <li class="wizard-step active" data-step="1">
                                                                                    <span class="wizard-step-number">1</span>
                                                                                    <span class="wizard-step-title">Numeración</span>
                                                                                </li>
                                                                                <li class="wizard-step" data-step="2">
                                                                                    <span class="wizard-step-number">2</span>
                                                                                    <span class="wizard-step-title">Cantidad de extensiones</span>
                                                                                </li>
                                                                                <li class="wizard-step" data-step="3">
                                                                                    <span class="wizard-step-number">3</span>
                                                                                    <span class="wizard-step-title">Tipo de transporte</span>
                                                                                </li>
                                                                                <li class="wizard-step" data-step="4">
                                                                                    <span class="wizard-step-number">4</span>
                                                                                    <span class="wizard-step-title">Tipo de plan</span>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <div class="wizard-content">
                                                                                <div class="wizard-panel active" data-step="1">
                                                                                    <h3>Numeración</h3>
                                                                                    <p class="text-muted">Ingresa la numeración base de la extensión.</p>
                                                                                    <div class="form-group">
                                                                                        <label for="wizard_numeracion">Numeración de la extensión</label>
                                                                                        <input type="text" class="form-control" id="wizard_numeracion" name="wizard_numeracion" placeholder="Ej: 1000">
                                                                                        <span class="help-block wizard-error" data-error-for="numeracion"></span>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="wizard-panel" data-step="2">
                                                                                    <h3>Cantidad de extensiones</h3>
                                                                                    <p class="text-muted">Define cuántas extensiones se deben crear.</p>
                                                                                    <div class="form-group">
                                                                                        <label for="wizard_cantidad">Cantidad de extensiones</label>
                                                                                        <input type="number" min="1" step="1" class="form-control" id="wizard_cantidad" name="wizard_cantidad" placeholder="Ej: 10">
                                                                                        <span class="help-block wizard-error" data-error-for="cantidad"></span>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="wizard-panel" data-step="3">
                                                                                    <h3>Tipo de transporte</h3>
                                                                                    <p class="text-muted">Selecciona el transporte que se usará.</p>
                                                                                    <div class="form-group">
                                                                                        <label>Transporte</label>
                                                                                        <div class="wizard-radio-group">
                                                                                            <label class="radio-inline">
                                                                                                <input type="radio" name="wizard_transporte" value="udp"> UDP
                                                                                            </label>
                                                                                            <label class="radio-inline">
                                                                                                <input type="radio" name="wizard_transporte" value="tcp"> TCP
                                                                                            </label>
                                                                                            <label class="radio-inline">
                                                                                                <input type="radio" name="wizard_transporte" value="tls"> TLS
                                                                                            </label>
                                                                                            <label class="radio-inline">
                                                                                                <input type="radio" name="wizard_transporte" value="tcp_udp"> TCP/UDP
                                                                                            </label>
                                                                                            <label class="radio-inline">
                                                                                                <input type="radio" name="wizard_transporte" value="udp_tls"> UDP/TLS
                                                                                            </label>
                                                                                        </div>
                                                                                        <span class="help-block wizard-error" data-error-for="transporte"></span>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="wizard-panel" data-step="4">
                                                                                    <h3>Tipo de plan</h3>
                                                                                    <p class="text-muted">Elige el tipo de plan para las extensiones.</p>
                                                                                    <div class="form-group">
                                                                                        <label>Plan</label>
                                                                                        <div class="wizard-radio-group">
                                                                                            <label class="radio-inline">
                                                                                                <input type="radio" name="wizard_plan" value="pro"> Pro
                                                                                            </label>
                                                                                            <label class="radio-inline">
                                                                                                <input type="radio" name="wizard_plan" value="basic"> Basic
                                                                                            </label>
                                                                                            <label class="radio-inline">
                                                                                                <input type="radio" name="wizard_plan" value="mixto"> Mixto
                                                                                            </label>
                                                                                        </div>
                                                                                        <span class="help-block wizard-error" data-error-for="plan"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="wizard-actions">
                                                                                <button type="button" class="btn btn-default" id="wizardPrev" style="display: none;">Anterior</button>
                                                                                <button type="button" class="btn btn-primary" id="wizardNext">Siguiente</button>
                                                                                <button type="button" class="btn btn-success" id="wizardSubmit" style="display: none;">Crear extensiones</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                </section>
                <!-- /.content -->

                <!-- Modal editar extension -->
                <div class="modal fade" id="modalEditarExtension" role="dialog" aria-labelledby="modalEditarExtensionLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="modalEditarExtensionLabel">Editar extensión</h5>
                            </div>
                            <div class="modal-body">
                                <form id="formEditarExtension" autocomplete="off">
                                    <input type="hidden" id="edit_id_extension" name="id_extension">
                                    <div class="form-group">
                                        <label for="edit_conf_text">Contenido tel_endpoints.conf</label>
                                        <textarea class="form-control" id="edit_conf_text" name="conf_text" rows="16" style="font-family: monospace;"></textarea>
                                        <span class="help-block">Edita el bloque completo de la extension. Respeta el encabezado ";===============EXTENSION".</span>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-default" data-dismiss="modal" type="button"><i class="fa fa-close"></i> Cancelar</button>
                                <button class="btn btn-primary" type="button" id="btnActualizarExtension">
                                    <i class="fa fa-save"></i> Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal activar Licencia-->
                <div class="modal fade" id="modalLicencia" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"></h5>
                            </div>
                            <div class="modal-body">
                                <form id="FormLicencia" class="FormLicencia" action="" method="" autocomplete="off">

                                    <div class="form-group col-md-12">
                                        <label>Licencia</label>
                                        <input class="form-control" type="text" name="licencia" id="licencia" maxlength="10">
                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnGLicencia">
                                            <i class="fa fa-file"></i>  Activar
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>


                <!-- Modal Edit Endpoints Conf -->
                <div class="modal fade" id="modalEditEndpointsConf" tabindex="-1" role="dialog" aria-labelledby="modalEditEndpointsConfLabel">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="modalEditEndpointsConfLabel">Editar tel_endpoints.conf</h4>
                            </div>
                            <div class="modal-body">
                                <div id="editEndpointsAlert" style="display:none;" class="alert"></div>
                                <textarea id="txtEndpointsConfContent" class="form-control" style="font-family: monospace; height: 500px; resize: vertical;"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" id="btnSaveEndpointsConf">Guardar y Sincronizar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal eliminar-->
                <div class="modal fade" id="modalE">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h5 class="modal-title">Eliminar Data</h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form1" class="FormB" action="" method="" autocomplete="off">
                                    <h4 class="text-center">¿Esta seguro de limpiar la tabla?</h4>
                                    <div class="form-group col-md-12">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-list"></i>
                                            </div>
                                            <select class="form-control" name="opc">
                                                <option value="1">Las primeras 50 Filas</option>
                                                <option value="2">Las primeras 100 Filas</option>
                                                <option value="3">Las primeras 150 Filas</option>
                                                <option value="4">Las primeras 200 Filas</option>
                                                <option value="5">Todas las filas</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-danger" type="button" id="btnE"><i class="fa fa-trash"></i> Elimimar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <!-- Modal reporte extension-->
                <div class="modal fade" id="modalReporte" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"></h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form3" class="FormReporte" action="" method="" autocomplete="off">

                                    <div class="form-group col-md-12">
                                        <label>#. Telefono o Celular</label>
                                        <input class="form-control" type="text" name="telefono" id="telefono">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Extension</label>
                                            <select class="form-control select2" style="width: 100%" name="extension" id="extension">
                                                <option value="">Extension</option>
                                                <?php
                                                    $queryExtension = mysqli_query($link,"SELECT DISTINCT origen FROM cdr_espejo");
                                                    while ($rowExtension = mysqli_fetch_array($queryExtension)) {
                                                        echo '<option value="'.$rowExtension['origen'].'">
                                                                '.$rowExtension['origen'].'
                                                              </option>';
                                                    }
                                                ?>
                                            </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Rango Fecha</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" name="rangoFecha" class="form-control pull-right" id="reservation">
                                        </div>
                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnGReporte">
                                            <i class="fa fa-file"></i> Generar Reporte
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal reporte extension Destino-->
                <div class="modal fade" id="modalReporteDestino" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"></h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form3" class="FormReporte" action="" method="" autocomplete="off">

                                    <div class="form-group col-md-12">
                                        <label>#. Telefono o Celular</label>
                                        <input class="form-control" type="text" name="telefonoDestino" id="telefonoDestino">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Extension</label>
                                            <select class="form-control select2" style="width: 100%" name="extensionDestino" id="extensionDestino">
                                                <option value="">Extension</option>
                                                <?php
                                                    $queryExtension = mysqli_query($link,"SELECT DISTINCT destino FROM cdr_espejo");
                                                    while ($rowExtension = mysqli_fetch_array($queryExtension)) {
                                                        $id_cdr = $rowExtension['id_cdr'];
                                                        //verificar con el id_cdr si tiene datos en en el campo userfield
                                                        $queryUno = mysqli_query($link,"SELECT * FROM cdr WHERE id = '$id_cdr'");
                                                        $rowDatosUno = mysqli_fetch_array($queryUno);
                                                        $userfield  = $rowDatosUno['userfield'];
                                                        if(empty($userfield)){
                                                            echo '<option value="'.$rowExtension['destino'].'">
                                                                    '.$rowExtension['destino'].'
                                                                </option>';
                                                        } else {
                                                            echo '<option value="'.$rowExtension['destino'].'">
                                                                    '.$userfield.'
                                                                </option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Rango Fecha</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" name="rangoFechaDestino" class="form-control pull-right" id="reservationDestino">
                                        </div>
                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnGReporteDestino">
                                            <i class="fa fa-file"></i> Generar Reporte
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal reporte operador-->
                <div class="modal fade" id="modalReporteOperador" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"></h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form3" class="FormReporteOperador" action="" method="" autocomplete="off">

                                    <div class="form-group col-md-12">
                                        <label>Operador</label>
                                            <select class="form-control select2" style="width: 100%" name="operador" id="operador">
                                                <option value="">Operador</option>
                                                <?php
                                                    $queryExtension = mysqli_query($link,"SELECT * FROM costo");
                                                    while ($rowExtension = mysqli_fetch_array($queryExtension)) {
                                                        echo '<option value="'.$rowExtension['operador'].'">
                                                                '.$rowExtension['operador'].'
                                                              </option>';
                                                    }
                                                ?>
                                            </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Extension</label>
                                            <select class="form-control select2" style="width: 100%" name="extension" id="extension_operador">
                                                <option value="">Extension</option>
                                                <?php
                                                    $queryExtension = mysqli_query($link,"SELECT DISTINCT origen FROM cdr_espejo");
                                                    while ($rowExtension = mysqli_fetch_array($queryExtension)) {
                                                        echo '<option value="'.$rowExtension['origen'].'">
                                                                '.$rowExtension['origen'].'
                                                              </option>';
                                                    }
                                                ?>
                                            </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Rango Fecha</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" name="rangoFechaOperador" class="form-control pull-right" id="reservationOperador">
                                        </div>
                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnGReporteOperador">
                                            <i class="fa fa-file"></i> Generar Reporte
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal reporte general-->
                <div class="modal fade" id="modalReporteGeneral" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"></h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form4" class="FormReporteGeneral" action="" method="" autocomplete="off">

                                     <div class="form-group col-md-12">
                                        <label>Datos del reporte</label>
                                            <select class="form-control select2" style="width: 100%" name="tipo_reporte" id="tipo_reporte">
                                                <option value="general">Reporte General</option>
                                                <option value="rango">Reporte por rango de fecha</option>
                                            </select>
                                    </div>


                                    <div class="form-group col-md-12">
                                        <label>Rango Fecha</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input disabled="true" type="text" name="rangoFechaGeneral" class="form-control pull-right" id="reservation2">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Ordenamiento</label>
                                            <select class="form-control select2" style="width: 100%" name="tipo_ordenamiento" id="tipo_ordenamiento">
                                                <option value="fecha">Fecha de llamada</option>
                                                <option value="duracion">Duracion de llamada</option>
                                            </select>
                                    </div>

                                    <div class="form-control col-md-12 text-center" style="border: 0px solid #ccc;">
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="optradio" id="optionsRadios1" value="ASC" checked>Ascendente
                                            </label>
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="optradio" id="optionsRadios2" value="DESC">Descendente
                                            </label>
                                        </div>
                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnGReporteGeneral">
                                            <i class="fa fa-file"></i> Generar Reporte
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>
                
                <!-- modal para timeline -->
                <div class="modal fade" id="modalHistorialTransferencia">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h5 class="modal-title">Timeline</h5>
                            </div>
                            <div class="modal-body">
                                <div id="content_dynamic_timeline">
                                    
                                    <div class="container">                      
                                        <div class="row text-center justify-content-center mb-5">
                                            <div class="col-xl-6 col-lg-8">
                                                <h2 class="font-weight-bold"></h2>
                                                <p class="text-muted"></p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                                                    <div class="timeline-step">
                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2003">
                                                            <div class="inner-circle"></div>
                                                            <p class="h6 mt-3 mb-1">2003</p>
                                                            <p class="h6 text-muted mb-0 mb-lg-0">Favland Founded</p>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-step">
                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2004">
                                                            <div class="inner-circle"></div>
                                                            <p class="h6 mt-3 mb-1">2004</p>
                                                            <p class="h6 text-muted mb-0 mb-lg-0">Launched Trello</p>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-step">
                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2005">
                                                            <div class="inner-circle"></div>
                                                            <p class="h6 mt-3 mb-1">2005</p>
                                                            <p class="h6 text-muted mb-0 mb-lg-0">Launched Messanger</p>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-step">
                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2010">
                                                            <div class="inner-circle"></div>
                                                            <p class="h6 mt-3 mb-1">2010</p>
                                                            <p class="h6 text-muted mb-0 mb-lg-0">Open New Branch</p>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-step mb-0">
                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                            <div class="inner-circle"></div>
                                                            <p class="h6 mt-3 mb-1">2020</p>
                                                            <p class="h6 text-muted mb-0 mb-lg-0">In Fortune 500</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <!-- Modal nuevo book -->
                <div class="modal fade" id="ModalNA" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Nuevo Addressbook</h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form1" class="FormAa" action="" method="" autocomplete="off">
                                    <div class="form-group col-md-6">
                                        <label>Nombres</label>
                                        <input type="text" class="form-control" name="nombre" id="nombre1" placeholder="Nombres">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Apellidos</label>
                                        <input type="text" class="form-control" name="nombre2" id="apellido1" placeholder="Apellidos">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Tipo</label>
                                        <select class="form-control select2" name="tipo" id="tipo1" style="width: 100%;height: 100%;">
                                            <option value="">Tipo</option>
                                            <option value="1">Home</option>
                                            <option value="2">Work</option>
                                            <option value="3">Cell</option>
                                            <option value="4">Fax</option>
                                            <option value="5">Pager</option>
                                            <option value="6">Mobile</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Oficina</label>
                                        <select class="form-control select2" name="oficina" id="oficina1" style="width: 100%;height: 100%;">
                                            <option value="">Oficina</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Cargo</label>
                                        <select class="form-control select2" name="cargo" id="cargo1" style="width: 100%;height: 100%;">
                                            <option value="">Cargo</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Telefono</label>
                                        <input type="text" class="form-control" name="tel" id="tel1" placeholder="Telefono">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Indice</label>
                                        <input type="text" class="form-control" name="indice" id="indice1" placeholder="Indice">
                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnG_book"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal Editar book -->
                <div class="modal fade" id="ModalEA" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Editar Addressbook</h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form1" class="FormAb" action="" method="" autocomplete="off">
                                    <div class="form-group col-md-6">
                                        <label>Nombres</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombres">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Apellidos</label>
                                        <input type="text" class="form-control" id="nombre2" name="nombre2" placeholder="Apellidos">
                                        <input type="hidden" name="id_phonebook" id="id_phonebook">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Tipo</label>
                                        <select class="form-control select2" name="tipo" style="width: 100%;height: 100%;">
                                            <option value="">Tipo</option>
                                            <option value="1">Home</option>
                                            <option value="2">Work</option>
                                            <option value="3">Cell</option>
                                            <option value="4">Fax</option>
                                            <option value="5">Pager</option>
                                            <option value="6">Mobile</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Oficina</label>
                                        <select class="form-control select2" name="oficina" id="oficina2" style="width: 100%;height: 100%;">
                                            <option value="">Oficina</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Cargo</label>
                                        <select class="form-control select2" name="cargo" id="cargo2" style="width: 100%;height: 100%;">
                                            <option value="">Cargo</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Telefono</label>
                                        <input type="text" class="form-control" id="tel" name="tel" placeholder="Telefono">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Indice</label>
                                        <input type="text" class="form-control" id="indice" name="indice" placeholder="Indice">
                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnA_book"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal lista oficinas -->
                <div class="modal fade" id="modalListaOficina" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Oficinas</h5>
                            </div>
                            <div class="">
                                <div class="col-md-12">
                                    <div class="btn-group pull-right" style="padding: 10px;">
                                        <button data-toggle="modal" data-target="#ModalNuevaOficina" type="button" class="btn btn-success">
                                            <i class="fa fa-plus"></i> Nueva oficina
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <table id="lista_oficina" class="table table-bordered table-striped" style="width: 100% !important;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th class="text-center">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal nueva oficina-->
                <div class="modal fade" id="ModalNuevaOficina" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Nueva Oficina</h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form1" class="FormNuevaOficina" action="" method="" autocomplete="off">
                                    <div class="form-group col-md-12">
                                        <label>Nombre Oficina</label>
                                        <input type="text" class="form-control" name="nombre" id="nombreOficina" placeholder="Nombre Oficina">
                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnG_oficina"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal Editar oficina -->
                <div class="modal fade" id="ModalEditarOficina" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Editar Oficina</h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form1" class="FormActializarOficina" action="" method="" autocomplete="off">
                                    <div class="form-group col-md-12">
                                        <label>Nombre Oficina</label>
                                        <input type="text" class="form-control" id="nombre_oficina_book" name="nombre" placeholder="Nombre Oficina">
                                        <input type="hidden" id="id_oficina_book" name="id_oficina">

                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnA_oficina"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal lista oficinas -->
                <div class="modal fade" id="modalListaCargo" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Cargos</h5>
                            </div>
                            <div class="">
                                <div class="col-md-12">
                                    <div class="btn-group pull-right" style="padding: 10px;">
                                        <button data-toggle="modal" data-target="#ModalNuevoCargo" type="button" class="btn btn-success">
                                            <i class="fa fa-plus"></i> Nuevo cargo
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <table id="lista_cargo" class="table table-bordered table-striped" style="width: 100% !important;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th class="text-center">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal nuevo cargo -->
                <div class="modal fade" id="ModalNuevoCargo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Nuevo Cargo</h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form1" class="FormNuevoCargo" action="" method="" autocomplete="off">
                                    <div class="form-group col-md-12">
                                        <label>Nombre Cargo</label>
                                        <input type="text" class="form-control" name="nombre" id="nombreCargo" placeholder="Nombre Cargo">
                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnG_cargo"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal Editar cargo -->
                <div class="modal fade" id="ModalEditarCargo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Editar Cargo</h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form1" class="FormEditarCargo" action="" method="" autocomplete="off">
                                    <div class="form-group col-md-12">
                                        <label>Nombre Cargo</label>
                                        <input type="text" class="form-control" id="nombre_cargo_book" name="nombre" placeholder="Nombre Cargo">
                                        <input type="hidden" id="id_cargo_book" name="id_cargo">

                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnA_cargo"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal reporte operador-->
                <div class="modal fade" id="modalReporteLlamadas" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"></h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form3" class="FormReporteLlamada" action="" method="" autocomplete="off">

                                    <div class="form-group col-md-12">
                                        <label>Direccion</label>
                                            <select class="form-control select2" style="width: 100%" name="direccion" id="direccion">
                                                <option value="Saliente">Saliente</option>
                                                <option value="Entrante">Entrante</option>
                                            </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Operador</label>
                                            <select class="form-control select2" style="width: 100%" name="operador" id="operador_llamada">
                                                <option value="">Operador</option>
                                                <?php
                                                    $queryExtension = mysqli_query($link,"SELECT * FROM costo");
                                                    while ($rowExtension = mysqli_fetch_array($queryExtension)) {
                                                        echo '<option value="'.$rowExtension['operador'].'">
                                                                '.$rowExtension['operador'].'
                                                              </option>';
                                                    }
                                                ?>
                                            </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Extension</label>
                                            <select class="form-control select2" style="width: 100%" name="extension" id="extension_llamada">
                                                <option value="">Extension</option>
                                                <?php
                                                    $queryExtension = mysqli_query($link,"SELECT DISTINCT origen FROM cdr_espejo");
                                                    while ($rowExtension = mysqli_fetch_array($queryExtension)) {
                                                        echo '<option value="'.$rowExtension['origen'].'">
                                                                '.$rowExtension['origen'].'
                                                              </option>';
                                                    }
                                                ?>
                                            </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Rango Fecha</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" name="rangoFechaLlamada" class="form-control pull-right" id="reservationLlamada">
                                        </div>
                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnGReporteLlamada">
                                            <i class="fa fa-file"></i> Generar Reporte
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- /.content-wrapper
            style="padding: 0px;border-top: 0px solid #d2d6de;background: #ecf0f5;" -->
            <!-- <footer class="main-footer" style="background-image: url('img/footer.png'); padding: 60px;border-top: 0px solid #d2d6de;background-color: #ecf0f5;">
                <div class="pull-right hidden-xs" style="margin-top: 20px;">
                    <strong>
                        <a style="color: #ffffff;" target="_blank" href="https://www.netsoluciones.com">
                        <h5 style="margin-top: 2px;">Diseñado por Multistore-s.com</h5>
                        </a>
                    </strong>
                </div>
            </footer> -->

            <div class="shadow">
                <img class="logo" src="img/pie.png"/>
            </div>

        </div>
        <!-- ./wrapper -->
        <!-- jQuery 3 -->
        <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- DataTables -->
        <script src="assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <!-- date-range-picker -->
        <script src="assets/bower_components/moment/min/moment.min.js"></script>
        <script src="assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
        <!-- bootstrap datepicker -->
        <script src="assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <!-- SlimScroll -->
        <script src="assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="assets/bower_components/fastclick/lib/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="assets/dist/js/adminlte.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="assets/dist/js/demo.js"></script>
        <!-- charts -->
        <script src="js/d3.v5.min.js" charset="utf-8"></script>
        <script src="js/c3.js"></script>
        <!-- PACE -->
        <script src="assets/bower_components/PACE/pace.min.js"></script>
        <!-- ChartJS -->
        <script src="js/Chart.min.js"></script>
        <!-- Select2 -->
        <!-- <script src="assets/bower_components/select2/dist/js/select2.full.min.js"></script> -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- ChartJS -->
        <script src="assets/bower_components/chart.js/Chart.js"></script>
        <!-- jQuery Knob Chart -->
        <script src="assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
        <!-- Morris.js charts -->
        <script src="assets/bower_components/raphael/raphael.min.js"></script>
        <script src="assets/bower_components/morris.js/morris.min.js"></script>
        <!--  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
        <!--  -->
        <script src="js/highcharts.js"></script>
        <script src="js/exporting.js"></script>
        <script src="js/export-data.js"></script>

        <script>
            // Sobrescribir el método addEventListener para asegurarnos de que ciertos eventos sean pasivos
            (function() {
                var addEventListener = EventTarget.prototype.addEventListener;
                EventTarget.prototype.addEventListener = function(type, listener, options) {
                    // Verificar si el tipo de evento es 'touchstart' o 'touchmove'
                    if (type === 'touchstart' || type === 'touchmove') {
                        if (typeof options === 'boolean') {
                            options = {
                                capture: options,
                                passive: true
                            };
                        } else if (typeof options === 'object') {
                            options.passive = true;
                        } else {
                            options = { passive: true };
                        }
                    }
                    addEventListener.call(this, type, listener, options);
                };
            }());
        </script>

        <script type="text/javascript">
            $(document).ajaxStart(function () {
                 Pace.restart()
            });
        </script>

        <!-- cargar librerias -->
        <script>
            $(function () {
                $('.select2').select2();

                //Date range picker1
                $("#reservation").daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerIncrement: 1,
                    locale: {
                        format: 'MM/DD/YYYY H:mm'
                    }
                });

                //Date range picker1
                $("#reservationDestino").daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerIncrement: 1,
                    locale: {
                        format: 'MM/DD/YYYY H:mm'
                    }
                });

                $("#reservationOperador").daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerIncrement: 1,
                    locale: {
                        format: 'MM/DD/YYYY H:mm'
                    }
                });

                $("#reservationLlamada").daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerIncrement: 1,
                    locale: {
                        format: 'MM/DD/YYYY H:mm'
                    }
                });

                //Date range 2
                $('#reservation2').daterangepicker();

                //
                $('#reservationGrafica').daterangepicker({},
                    function(start, end, label) {
                        //alert("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                        var fecha1 = start.format('YYYY-MM-DD h:mm:ss');
                        var fecha2 = end.format('YYYY-MM-DD h:mm:ss');
                        $('#container5').html('');  
                        var parametro = {
                            "fecha1" : fecha1,
                            "fecha2" : fecha2
                        }
                        $.ajax({
                            url:  'controller/new_grafica.php', 
                            type: 'POST',
                            data: parametro,
                            dataType: 'html'
                        })
                        .done(function(data){  
                            $('#container5').html('');    
                            $('#container5').html(data); // mostrar la data
                        })
                        .fail(function(){
                            $('#container5').html('');
                        });
                    });

                //rango de fecha del top de duracion
                $('#rangoFechaUno').daterangepicker({},
                    function(start, end, label) {
                        //alert("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                        var fecha1 = start.format('YYYY-MM-DD h:mm:ss');
                        var fecha2 = end.format('YYYY-MM-DD h:mm:ss');
                        var parametro = {
                            "fecha1" : fecha1,
                            "fecha2" : fecha2
                        }
                        $.ajax({
                            url:  'controller/new_grafica_top.php', 
                            type: 'POST',
                            data: parametro,
                            dataType: 'html'
                        })
                        .done(function(data){  
                            $('#container2').html('');    
                            $('#container2').html(data); // mostrar la data
                        })
                        .fail(function(){
                            $('#container2').html('');
                        });
                });
            
                //
                $('#reservationCards').daterangepicker({},
                    function(start, end, label) {
                        //alert("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                        var fecha1 = start.format('YYYY-MM-DD h:mm:ss');
                        var fecha2 = end.format('YYYY-MM-DD h:mm:ss');
                        
                        // 1. Mostrar spinners
                        setLoading(true);
                        
                        var parametro = {
                            "fecha1" : fecha1,
                            "fecha2" : fecha2
                        }
                        $.ajax({
                            url:  'controller/datos_cards.php', 
                            type: 'POST',
                            data: parametro,
                            dataType: 'json'
                        })
                        .done(function(data){
                            console.log(data);
                            $('#total_llamadas').text(data.total_llamadas);
                            $('#total_contestadas').text(data.total_contestadas);
                            $('#total_duracion').text(data.hora_texto);

                            // Si quieres mostrar “salientes / entrantes”
                            $('#total_salientes').text(`${data.salientes} / ${data.entrantes}`);
                        })
                        .fail(function(){
                            $('#total_llamadas').text('0');
                            $('#total_contestadas').text('0');
                            $('#total_duracion').text('00:00:00');  
                            $('#total_salientes').text('0 / 0');
                        });

                        // grafica
                        $('#revenue-chart').html('');  
                        $.ajax({
                            url:  'controller/new_grafica_principal.php', 
                            type: 'POST',
                            data: parametro,
                            dataType: 'html'
                        })
                        .done(function(data){  
                            $('#revenue-chart').html('');    
                            $('#revenue-chart').html(data); // mostrar la data
                        })
                        .fail(function(){
                            $('#revenue-chart').html('');
                        });
                    });

            });

            // Muestra un spinner en cada campo y guarda su HTML anterior
            function setLoading(isLoading = true) {
                const spinner = '<i class="fa fa-spinner fa-spin" style="font-size: 1em;"></i>'; // icono girando

                // Lista de IDs que quieres actualizar
                const fields = ['#total_llamadas', '#total_contestadas', '#total_duracion', '#total_salientes'];

                fields.forEach(id => {
                    const $el = $(id);

                    if (isLoading) {
                        // Guarda el contenido actual por si lo necesitas
                        $el.data('prev', $el.html());
                        $el.html(spinner);
                    } else {
                        // Recupera el contenido anterior (o pon “0” por defecto)
                        const prev = $el.data('prev') ?? '0';
                        $el.html(prev);
                    }
                });
            }
        </script>

        <!-- Grafica de compañias -->
        <script type="text/javascript">
            $(document).ready(function(){
                /* Morris.js Charts */
                // Sales chart
                var area = new Morris.Area({
                    element   : 'revenue-chart',
                    resize    : true,
                    data      : [
                    <?php
                        $consult = mysqli_query($link,"SELECT * FROM grafica_principal ORDER BY fecha_llamada ASC");
                            
                        while($rows = mysqli_fetch_array($consult))
                        {
                            $fecha_llamada = $rows['fecha_llamada'];
                            $total_claro = $rows['total_claro'];
                            $total_tigo = $rows['total_tigo'];
                            $total_cootel = $rows['total_cootel'];
                            $total_convencional = $rows['total_convencional'];

                            echo '{ y: "'.$fecha_llamada.'", item1: '.$total_claro.', item2: '.$total_tigo.',  item3: '.$total_cootel.', item4: '.$total_convencional.'},';
                        }
                    ?>
                    ],
                    xkey      : 'y',
                    ykeys     : ['item1', 'item2', 'item3', 'item4'],
                    labels    : ['Claro', 'Tigo', 'Cootel', 'Convencional'],
                    lineColors: ['#e73043', '#033c78', '#f9c644', '#a0d0e0'],
                    hideHover : 'auto'
                });
            });
        </script>

        <!-- grafico de todas las llamadas por periodo de fechas-->
        <script type="text/javascript">
            $(document).ready(function(){
                setTimeout(function() {
                    $(".chart_tari1").attr("id","chart_tari1");
                    $(".chart_tari2").attr("id","chart_tari2");
                    $(".chart_tari3").attr("id","chart_tari3");

                    //Hoy
                    var chart_tari1 = c3.generate({
                        data: {
                            columns: [
                            <?php
                                //optener rango de fechas por dia
                                $actual = date("Y-m-d H:i:s");
                                $pasadoAux = date("Y-m-d");
                                $pasado = $pasadoAux.' 00:00:00';

                                $consult = mysqli_query($link,"SELECT * FROM costo");
                                $total = 0;
                                while($rows = mysqli_fetch_array($consult))
                                {
                                    $operador = $rows['operador'];

                                    //
                                    $query_total = mysqli_query($link,"SELECT *
                                                                FROM cdr_espejo 
                                                                WHERE cdr_espejo.operador = '$operador'
                                                                AND CONCAT(cdr_espejo.fecha_llamada,' ',cdr_espejo.hora_llamada) BETWEEN '$pasado' AND '$actual'");
                                    $total = mysqli_num_rows($query_total);
                                    
                                    if ($total > 0) {
                                        echo '["'.$operador.'", '.$total.' ],';
                                    }
                                }
                                ?>
                            ],
                            type : 'donut',
                            onclick: function (d, i) {
                                // setTimeout("location.href = 'detalle_operador_rango.php?tipo!="+d.id+"&rango!=HOY'",100);
                                window.location.href = 'detalle_operador_rango.php?tipo!='+d.id+'&rango!=HOY';
                            },
                            colors: {
                                Claro: '#e73043',
                                Tigo: '#033c78',
                                Convencional: '#23aced',
                                Cootel: '#f09548',
                            },
                            color: function (color, d) {
                                // d will be 'id' when called for legends
                                return d.id && d.id === 'Cootel' ? d3.rgb(color).darker(d.value / 150) : color;
                            }
                        },
                        donut: {
                            title: "HOY",
                            label: {
                                format: function (value, ratio, id) {
                                    return d3.format(',')(value);
                                }
                            },
                        },
                        tooltip: {
                            grouped: false,
                            format: {
                                value: function (value, ratio, id) {
                                    return d3.format(',')(value);
                                }
                            }
                        },
                        legend: {
                            show: true
                        },
                    });
                    $("#chart_tari1").html(chart_tari1.element);

                    //Semana
                    var chart_tari2 = c3.generate({
                        data: {
                            columns: [
                            <?php
                                $hoy = date("Y-m-d H:i:s");
                                $semana = date("Y-m-d H:i:s",strtotime($hoy."- 1 week"));
                                
                                $consult = mysqli_query($link,"SELECT * FROM costo");
                                $total = 0;
                                while($rows = mysqli_fetch_array($consult))
                                {
                                    $operador = $rows['operador'];

                                    //
                                    $query_total = mysqli_query($link,"SELECT *
                                                                FROM cdr_espejo 
                                                                WHERE cdr_espejo.operador = '$operador'
                                                                AND CONCAT(cdr_espejo.fecha_llamada,' ',cdr_espejo.hora_llamada) BETWEEN '$semana' AND '$hoy'");
                                    $total = mysqli_num_rows($query_total);
                                    
                                    if ($total > 0) {
                                        echo '["'.$operador.'", '.$total.' ],';
                                    }
                                }
                                ?>
                            ],
                            type : 'donut',
                            onclick: function (d, i) {
                                // setTimeout("location.href = 'detalle_operador_rango.php?tipo!="+d.id+"&rango!=SEMANA'",100);
                                window.location.href = 'detalle_operador_rango.php?tipo!='+d.id+'&rango!=SEMANA';
                            },
                            colors: {
                                Claro: '#e73043',
                                Tigo: '#033c78',
                                Convencional: '#23aced',
                                Cootel: '#f09548',
                            },
                            color: function (color, d) {
                                // d will be 'id' when called for legends
                                return d.id && d.id === 'Cootel' ? d3.rgb(color).darker(d.value / 150) : color;
                            }
                        },
                        donut: {
                            title: "Ultima Semana",
                            label: {
                                format: function (value, ratio, id) {
                                    return d3.format(',')(value);
                                }
                            },
                        },
                        tooltip: {
                            grouped: false,
                            format: {
                                value: function (value, ratio, id) {
                                    return d3.format(',')(value);
                                }
                            }
                        },
                        legend: {
                            show: true
                        },
                    });
                    $("#chart_tari2").html(chart_tari2.element);

                    //Mes
                    var chart_tari3 = c3.generate({
                        data: {
                            columns: [
                            <?php
                                $meshoy = date("Y-m-d H:i:s");
                                $mes = date("Y-m-d H:i:s",strtotime($hoy."- 1 month"));
                                
                                $consult = mysqli_query($link,"SELECT * FROM costo");
                                $total = 0;
                                while($rows = mysqli_fetch_array($consult))
                                {
                                    $operador = $rows['operador'];

                                    //
                                    $query_total = mysqli_query($link,"SELECT *
                                                                FROM cdr_espejo 
                                                                WHERE cdr_espejo.operador = '$operador'
                                                                AND CONCAT(cdr_espejo.fecha_llamada,' ',cdr_espejo.hora_llamada) BETWEEN '$mes' AND '$meshoy'");
                                    $total = mysqli_num_rows($query_total);
                                    
                                    if ($total > 0) {
                                        echo '["'.$operador.'", '.$total.' ],';
                                    }
                                }
                                ?>
                            ],
                            type : 'donut',
                            onclick: function (d, i) {
                                // setTimeout("location.href = 'detalle_operador_rango.php?tipo!="+d.id+"&rango!=MES'",100);
                                window.location.href = 'detalle_operador_rango.php?tipo!='+d.id+'&rango!=MES';
                            },
                            colors: {
                                Claro: '#e73043',
                                Tigo: '#033c78',
                                Convencional: '#23aced',
                                Cootel: '#f09548',
                            },
                            color: function (color, d) {
                                // d will be 'id' when called for legends
                                return d.id && d.id === 'Cootel' ? d3.rgb(color).darker(d.value / 150) : color;
                            }
                        },
                        donut: {
                            title: "Ultimo Mes",
                            label: {
                                format: function (value, ratio, id) {
                                    return d3.format(',')(value);
                                }
                            },
                        },
                        tooltip: {
                            grouped: false,
                            format: {
                                value: function (value, ratio, id) {
                                    return d3.format(',')(value);
                                }
                            }
                        },
                        legend: {
                            show: true
                        },
                    });
                    $("#chart_tari3").html(chart_tari3.element);

                }, 200);
            
            });
        </script>

        <!-- script para tabla de llamadas -->
        <script>
            $(document).ready(function () {
                var columns = [
                    {
                        "targets": 0,
                        "render": function (data, type, row, meta) {
                            return '<input id="scales" class="editor-active" type="checkbox" name="scales" data-id="'+row[0]+'">';
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 1,
                        "render": function (data, type, row, meta) {
                            return row[1];
                        },
                    }, {
                        "targets": 2,
                        "render": function (data, type, row, meta) {
                            return '<a href="details.php?nombre!='+ row[2] +'" style="color: #495057;">'+ row[2] +'</a>';
                        },
                    }, {
                        "targets": 3,
                        "render": function (data, type, row, meta) {
                            return '<a href="details.php?origen!='+ row[3] +'" style="color: #495057;">'+ row[3] +'</a>';
                        },
                    }, {
                        "targets": 4,
                        "render": function (data, type, row, meta) {
                            return '<a href="details.php?destino!='+ row[4] +'" style="color: #495057;">'+ row[4] +'</a>';
                        },
                    }, {
                        "targets": 5,
                        "render": function (data, type, row, meta) {
                            return row[5];
                        },
                    }, {
                        "targets": 6,
                        "render": function (data, type, row, meta) {
                            return row[6];
                        },
                    }, {
                        "targets": 7,
                        "render": function (data, type, row, meta) {
                            var resultado = '';
                            if (row[7] == 'NO ANSWER') {
                                resultado = '<span style="color:red;">No Contestada</span>';
                            } else {
                                resultado = '<span style="color:green;">Contestada</span>';
                            }
                            return resultado;
                        },
                    }, {
                        "targets": 8,
                        "render": function (data, type, row, meta) {
                            return row[8];
                        },
                    }, {
                        "targets": 9,
                        "render": function (data, type, row, meta) {
                            var resultado = '';
                            if(row[9] == ''){
                                resultado = 'Interna';
                            } else {
                                resultado = row[9];
                            }
                            return resultado;
                        },
                    }, {
                        "targets": 10,
                        "render": function (data, type, row, meta) {
                            // return row[10];
                            var resultado = row[10];

                            if(resultado != ''){
                                var datos = JSON.parse(resultado);
                                var extension = datos.extension;
                                var linkedid = datos.linkedid;
                            }

                            var stringResultado = "";
                            if(resultado != ''){
                                stringResultado = '<a href="#0" data-toggle="modal" data-target="#modalHistorialTransferencia" data-linkedid="'+linkedid+'" id="btnViewData" style="color: #0095dd;">Transferido a '+ extension +'</a>';
                            } else {
                                stringResultado = "";
                            }
                            return stringResultado;
                        },
                    }, {
                        "targets": 11,
                        "render": function (data, type, row, meta) {
                            return row[11];
                        },
                    }, {
                        "targets": 12,
                        "render": function (data, type, row, meta) {
                            return row[12];
                        },
                    }
                    
                ];

                let idEspejo = [];
                $(document).on('change', '#scales', function(e){
                    e.preventDefault();
                    var id_cdr = $(this).data('id');
                    cb = $(this).prop('checked');
                    if(cb == true) {
                        if(idEspejo.includes(id_cdr) == false){
                            idEspejo.push(id_cdr);
                        }
                    } else {
                        let pos = idEspejo.indexOf(id_cdr);
                        idEspejo.splice(pos, 1);
                    }
                    //mostra boton eliminar
                    if(idEspejo.length > 0) {
                        $('.btnDelete').css("display", "block");
                    } else {
                        $('.btnDelete').css("display", "none");
                    }
                    //console.log(cb);
                    //console.log(id_cdr);
                    //console.log(idEspejo);
                });

                //
                $(document).on('click', '#btn_eliminar', function(e){
                    e.preventDefault();
                    var id_cdr = idEspejo;
                    var parametro = {
                        "id_cdr" : id_cdr
                    }
                    //alert(id_cdr);
                    $.ajax({
                        type: 'POST',
                        url: 'controller/e_cdr_individual.php',
                        data: parametro,
                        success: function(data) {
                            if (data == 'bien') {
                                //alert(data);
                                not4();
                                // setTimeout("location.href = 'home.php'",2000);
                                window.location.href = 'home.php';
                            }else {
                                //alert(data);
                                not5();
                            }
                        }
                    }); 
                });

                //
                $('#example1 thead tr').clone(true).appendTo( '#example1 thead' );
                $('#example1 thead tr:eq(1) th').each( function (i) {
                    var title = $(this).text();
                    $(this).html( '<input type="text" placeholder=" '+title+'" />' );
 
                    $( 'input', this ).on( 'keyup change', function () {
                        if ( table1.column(i).search() !== this.value ) {
                            table1
                            .column(i)
                            .search( this.value )
                            .draw();
                        }
                    });
                });

                var table1 = $('#example1').DataTable({
                     "scrollX": true,
                     "orderCellsTop": true,
                     "fixedHeader": true,
                     "columns": columns,
                     "processing": true,
                     "serverSide": true,
                     "ajax": "ajax_table/ajax_table_cdr.php",
                });

                //
                $('#example4 thead tr').clone(true).appendTo( '#example4 thead' );
                $('#example4 thead tr:eq(1) th').each( function (i) {
                    var title = $(this).text();
                    $(this).html( '<input type="text" placeholder=" '+title+'" />' );
 
                    $( 'input', this ).on( 'keyup change', function () {
                        if ( table4.column(i).search() !== this.value ) {
                            table4
                            .column(i)
                            .search( this.value )
                            .draw();
                        }
                    });
                });

                var table4 = $('#example4').DataTable({
                     "scrollX": true,
                     "orderCellsTop": true,
                     "fixedHeader": true,
                     "columns": columns,
                     "processing": true,
                     "serverSide": true,
                     "ajax": "ajax_table/ajax_table_cdr_no_contestadas.php"
                });

            });
        </script>

        <script type="text/javascript">
            $(document).ready(function(){
                //mostrar la info
                $(document).on('click', '#btnViewData', function(e){
                  e.preventDefault();
                  var id_llamada = $(this).data('linkedid');
                //   alert(id_llamada);
                  $('#content_dynamic_timeline').html('');
                  $.ajax({
                        url: 'controller/get_timeline_llamada.php',
                        type: 'POST',
                        data: 'id_llamada='+id_llamada,
                        dataType: 'html'
                    })
                    .done(function(data){  
                        $('#content_dynamic_timeline').html('');    
                        $('#content_dynamic_timeline').html(data); // mostrar la data
                        //$('#ModalEP').modal('show');
                    })
                    .fail(function(){
                        $('#content_dynamic_timeline').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                        //$('#ModalEP').modal('show');
                    });
                });
            });
        </script>

        <!-- grafica por rangos de fecha -->
        <script>
            $(document).on('click','#graficoLlamadas',function() {
                Highcharts.chart('container5', {
                      chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                      },
                      title: {
                        text: 'Grafico de llamadas'
                      },
                      tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                      },
                      plotOptions: {
                        pie: {
                          allowPointSelect: true,
                          cursor: 'pointer',
                          dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                          }
                        }
                      },
                      series: [{
                        name: '',
                        colorByPoint: true,
                        data: [
                        <?php
                            $consult = mysqli_query($link,"SELECT * FROM costo");
                            //optener el total de reglas
                            $total = mysqli_num_rows($consult);
                            $i = 1;
                            while($rows = mysqli_fetch_array($consult))
                            {
                                $total_llamada = 0;
                                $i++;
                                $operador = $rows['operador'];

                                //
                                $query_total = mysqli_query($link,"SELECT * FROM cdr_espejo 
                                                            WHERE operador = '$operador'");
                                $total_llamada = mysqli_num_rows($query_total);
                                
                                $color = "";
                                if($operador == 'Claro'){
                                    $color = '#e73043';
                                } else if($operador == 'Tigo'){
                                    $color = '#033c78';
                                } else if($operador == 'Cootel'){
                                    $color = '#f9c644';
                                } else if($operador == 'Convencional'){
                                    $color = '#a0d0e0';
                                }

                                if(!empty($color)){
                                    echo '{ name: "'.$rows['operador'].'", y:'.$total_llamada.', color: "'.$color.'" },';
                                } else {
                                    echo '{ name: "'.$rows['operador'].'", y:'.$total_llamada.' },';
                                }
                            }
                        ?>
                        ]
                      }]
                    });
            });
        </script>

        <!-- para poder generar el reporte por extension -->
        <script type="text/javascript">
            $('#btnGReporte').click(function () {
                var extension = $('#extension').val();
                var rangoFecha = $('#reservation').val();
                var telefono = $('#telefono').val();
                
                //alert(telefono);
                if (telefono != '') {
                    extension = telefono;
                }

                if (extension == '' || rangoFecha == '') {
                    not2();
                } else {
                    not8();
                    window.open('controller/reporteExtension.php?extension='+extension+'&rangoFecha='+rangoFecha+'&telefono='+telefono);
                }
            });

            // reporte destino
            $('#btnGReporteDestino').click(function () {
                var extension = $('#extensionDestino').val();
                var rangoFecha = $('#reservationDestino').val();
                var telefono = $('#telefonoDestino').val();
                
                //alert(telefono);
                if (telefono != '') {
                    extension = telefono;
                }

                if (extension == '' || rangoFecha == '') {
                    not2();
                } else {
                    not8();
                    window.open('controller/reporteExtensionDestino.php?extension='+extension+'&rangoFecha='+rangoFecha+'&telefono='+telefono);
                }
            });

            //pasar a verificar si escribe el numero de telefono
            $("#telefono").on("keyup", function() {
                var telefono = $('#telefono').val();
                if (telefono != '') {
                    $( "#extension" ).prop( "disabled", true );
                } else {
                    $( "#extension" ).prop( "disabled", false );
                }
            });
        </script>

        <!-- para poder generar el reporte por operador -->
        <script type="text/javascript">
            $('#btnGReporteOperador').click(function () {
                var extension = $('#extension_operador').val();
                var operador = $('#operador').val();
                var rangoFecha = $('#reservationOperador').val();

                if (operador == '' || rangoFecha == '') {
                    not2();
                } else {
                    not8();
                    window.open('controller/reporteOperador.php?extension='+extension+'&operador='+operador+'&rangoFecha='+rangoFecha+'');
                }
            });
        </script>

        <!-- para poder generar el reporte por direccion -->
        <script type="text/javascript">
            $('#btnGReporteLlamada').click(function () {
                var extension = $('#extension_llamada').val();
                var operador = $('#operador_llamada').val();
                var direccion = $('#direccion').val();
                var rangoFecha = $('#reservationLlamada').val();

                if (direccion == '' || rangoFecha == '') {
                    not2();
                } else {
                    not8();
                    window.open('controller/reporteLlamada.php?extension='+extension+'&operador='+operador+'&direccion='+direccion+'&rangoFecha='+rangoFecha+'');
                }
            });
        </script>

        <!-- para poder generar el reporte General -->
        <script type="text/javascript">
            $('#btnGReporteGeneral').click(function () {
                var rangoFecha = $('#reservation2').val();
                var tipo_reporte = $('#tipo_reporte').val();
                var tipo_ordenamiento = $('#tipo_ordenamiento').val();
                var optradio = $('input:radio[name=optradio]:checked').val();

                window.open('controller/reportePdf.php?rangoFechaGeneral='+rangoFecha+'&tipo_reporte='+tipo_reporte+'&tipo_ordenamiento='+tipo_ordenamiento+'&optradio='+optradio);
            });
        </script>

        <!-- para actualizar-->
        <script type="text/javascript">
            $('#update').click(function () {
                $.ajax({
                  url: 'controller/ajax_cdr_espejo.php', 
                    success: function (result) {
                        if (result == 'BIEN') {
                            //alert(result);
                            not6();
                            // setTimeout("location.href = 'home.php'",3000);
                            window.location.href = 'home.php';
                        } else {
                            //alert(result);
                            not5();
                        }
                    }
                })
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function () {
                $(document).on('change', '#tipo_reporte', function(event) {
                    var tipo_reporte = $('select[name=tipo_reporte]').val();
                    //alert(tipo_reporte);
                    if (tipo_reporte == 'rango') {
                        $( "#reservation2" ).prop( "disabled", false );
                    } else {
                        $( "#reservation2" ).prop( "disabled", true );
                    }
                });
            });
        </script>
        
        <script>
            $(document).ready(function () {
                var listaLlamadasIntervalId = null;
                var listaLlamadasRequestEnCurso = false;

                function cargarListaLlamadas() {
                    if (listaLlamadasRequestEnCurso) {
                        return;
                    }
                    listaLlamadasRequestEnCurso = true;
                    $.ajax({
                        url: 'ajax_table/ajax_table_llamadas.php',
                        type: 'POST',
                        dataType: 'html',
                        timeout: 8000
                    })
                    .done(function(data){
                        if(data != "[]"){
                            $('#content_dynamic_tabla_llamada').html('');
                            $('#content_dynamic_tabla_llamada').html(data);
                        } else {
                            $('#content_dynamic_tabla_llamada').html('<table id="example3" class="table table-bordered table-striped"><thead><tr><th>#</th><th>De</th><th>Para</th></tr></thead><tbody><tr><td  colspan="3" class="dataTables_empty text-center">No data available in table</td></tr></tbody></table>');
                        }
                    })
                    .always(function(){
                        listaLlamadasRequestEnCurso = false;
                    });
                }

                $(document).on('click','#listaLlamadas',function(e) {
                    if (listaLlamadasIntervalId !== null) {
                        return;
                    }
                    cargarListaLlamadas();
                    listaLlamadasIntervalId = setInterval(cargarListaLlamadas, 5000);
                });
            });
        </script>

        <!-- grafico de top 10-->
        <script type="text/javascript">
            $(document).on('click','#graficoDuracion',function() {
                Highcharts.chart('container2', {
                      chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'bar'
                      },
                      title: {
                        text: ''
                      },
                      tooltip: {
                        pointFormat: 'Duración : <b>{point.z}</b>'
                      },
                      plotOptions: {
                        pie: {
                          allowPointSelect: true,
                          cursor: 'pointer',
                          dataLabels: {
                            enabled: true,
                            format: '<b>Extension : {point.name}</b> ',
                            style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                          }
                        }
                      },
                      xAxis: {
                            categories: [
                            <?php
                                $consult = mysqli_query($link,"SELECT * FROM cdr
                                                            WHERE cdr.calldate <> '0000-00-00 00:00:00'
                                                            AND cdr.src <> ''
                                                            ORDER BY cdr.billsec DESC
                                                            LIMIT 10");
                                while($rows = mysqli_fetch_array($consult))
                                {
                                    $extension = $rows['src'];
                                    echo "'".$extension."',";
                                }
                            ?>
                            ],
                            title: {
                                text: null
                            },
                            gridLineWidth: 1,
                            lineWidth: 0
                        },
                      series: [{
                        colorByPoint: true,
                        point:{
                            events:{
                                click: function (event) {
                                    //alert(this.name);
                                    setTimeout("location.href = 'details.php?origen!="+this.name+"'",100);
                                    // window.location.href = "details.php?origen!="+this.name+"'";
                                    //http://localhost/tarificador2/details.php?origen!=82526899
                                }
                            }
                        }, 
                        data: [
                        <?php
                            $consult = mysqli_query($link,"SELECT * FROM cdr
                                                    WHERE cdr.calldate <> '0000-00-00 00:00:00'
                                                    AND cdr.src <> ''
                                                    ORDER BY cdr.billsec DESC
                                                    LIMIT 10");
                            $total = 0;
                            while($rows = mysqli_fetch_array($consult))
                            {
                                $id_cdr = $rows['id'];

                                $consultaDuracion = mysqli_query($link,"SELECT SEC_TO_TIME(cdr.billsec) AS duracion 
                                                                        FROM cdr 
                                                                        WHERE cdr.id = '$id_cdr' ");
                                $rowDuracion = mysqli_fetch_array($consultaDuracion);
                                
                                $extension = $rows['src'];
                                $duration = $rows['billsec'];
                                //$hora_texto = gmdate("H:i:s", $duration);
                                $hora_texto = $rowDuracion['duracion'];  

                                echo '{ name: "'.$extension.'", y:'.$duration.', z: "'.$hora_texto.'"},';
                            }
                        ?>
                        ]
                      }]
                    });
            });
        </script>

        <!-- opcion de eliminar filas -->
        <script type="text/javascript">
            $('#btnE').click(function () {
                $.ajax({
                  url: 'controller/e_cdr.php',
                  type: 'POST',
                  data: $('.FormB').serialize(),
                    success: function (result) {
                        not4();
                        // setTimeout("location.href = 'home.php'",3000);
                        window.location.href = 'home.php';
                    }
                })
            });
        </script>

       <!-- tabla de ataques -->
        <script type="text/javascript">
            // Helper: elimina tooltips sin importar la versión de Bootstrap (3 o 4+)
            function killTooltip($els){
                $($els).each(function(){
                    var $el = $(this);
                    try { $el.tooltip('dispose'); } 
                    catch(e1) { try { $el.tooltip('destroy'); } catch(e2){} }
                    $el.removeAttr('data-original-title').removeAttr('aria-describedby');
                });
                $('body .tooltip').remove();
            }

            $(document).ready(function(){
                var table = $('#example8').DataTable({
                    "order": [[1, 'desc']],
                    "processing": true,
                    "serverSide": true,
                    "ajax": "ajax_table/ajax_table.php",
                    "createdRow": function ( row, data, index ) {
                        //
                        if (data[1]) {
                            $('td', row).eq(1).append('<i class="fa fa-clock-o icono-relog"></i>');
                        }

                        //
                        if (data[2]) {
                            $('td', row).eq(2).append('<span class="f16"><i class="flag ac icono-bandera"></i></span>');
                            //optener la bandera del pais
                            var ip = data[2];
                            var parametro = {
                                "ip_bandera" : ip
                            }
                            $.ajax({
                                url:  'controller/return_badera.php', 
                                type: 'POST',
                                data: parametro,
                                dataType: 'html'
                            })
                            .done(function(result){
                                $('td', row).eq(2).html('');
                                $('td', row).eq(2).html(result+' '+data[2]);
                            })
                            .fail(function(){
                                $('td', row).eq(2).append('<span class="f16"><i class="flag ac icono-bandera"></i></span>');
                            });


                            // Extraer IP desde el texto HTML
                            let textoConHtml = data[2];  
                            let ip_busqueda = textoConHtml.match(/\b\d{1,3}(?:\.\d{1,3}){3}\b/);
                            ip_busqueda = ip_busqueda ? ip_busqueda[0] : "";

                            let cell = $('td', row).eq(2);

                            if(ip_busqueda){        
                                $.ajax({
                                    url: `https://stat.ripe.net/data/whois/data.json?resource=${ip_busqueda}`,
                                    method: 'GET',
                                    success: function(respuesta){
                                        let contenido = parseRipeData1(respuesta);

                                        // ADDED: limpiar tooltip previo en esa celda (compat BS3/BS4+)
                                        killTooltip(cell);

                                        // Luego seteas el contenido y vuelves a iniciar
                                        cell.attr('data-toggle', 'tooltip')
                                            .attr('data-html', 'true')
                                            .attr('title', contenido)
                                            .tooltip({ html:true, container:'body', boundary:'window' });
                                    },
                                    error: function(){
                                        // Manejo cuando la petición falla (Error 500 u otros)
                                        let contenido = '<ul><li>No se encontraron datos WHOIS para esta IP.</li></ul>';

                                        // ADDED: limpiar tooltip previo en esa celda (compat BS3/BS4+)
                                        killTooltip(cell);

                                        cell.attr('data-toggle', 'tooltip')
                                            .attr('data-html', 'true')
                                            .attr('title', contenido)
                                            .tooltip({html:true, container:'body'});
                                    }
                                });
                            } else {
                                // En caso que la IP no se pueda extraer
                                let contenido = '<ul><li>IP no válida o no disponible.</li></ul>';

                                // ADDED: limpiar tooltip previo en esa celda (compat BS3/BS4+)
                                killTooltip(cell);

                                cell.attr('data-toggle', 'tooltip')
                                    .attr('data-html', 'true')
                                    .attr('title', contenido)
                                    .tooltip({html:true, container:'body'});
                            }
                        }
                    }
                });

                // ADDED: limpiar tooltips antes de cada redraw (compat BS3/BS4+)
                table.on('preDraw.dt', function () {
                    killTooltip($('#example8 [data-toggle="tooltip"]'));
                });

                // ADDED: por si acaso, limpiar al paginar/ordenar/buscar
                table.on('page.dt order.dt search.dt', function () {
                    $('body .tooltip').remove();
                });

                // ADDED: ocultar tooltip al salir del td y mostrar limpio al entrar
                $('#example8 tbody')
                .on('mouseleave', 'td[data-toggle="tooltip"]', function () {
                    try { $(this).tooltip('hide'); } catch(e){}
                })
                .on('mouseenter', 'td[data-toggle="tooltip"]', function () {
                    try { $(this).tooltip('show'); } catch(e){}
                });

                $('#dropdown1').on('change', function(){
                    table.column(3).search(this.value || '').draw();
                    if ($(this).data('select2')) {
                        $(this).select2('close');
                    }
                });
            });

            function parseRipeData1(respuesta) {
                let html = '<ul style="padding-left:15px; text-align:left;">';

                if(respuesta.data && respuesta.data.records && respuesta.data.records.length > 0){
                    let records = respuesta.data.records[0];

                    if(records.length > 0){
                        records.forEach(function(record) {
                            html += `<li><strong>${record.key}:</strong> ${record.value}</li>`;
                        });
                    } else {
                        html += '<li>No se encontraron datos WHOIS para esta IP.</li>';
                    }

                } else {
                    html += '<li>No se encontraron datos WHOIS para esta IP.</li>';
                }

                html += '</ul>';
                return html;
            }
        </script>

        <!-- grafica de ip bloqueadas-->
        <script type="text/javascript">
            $(document).ready(function(){
                $(document).on('click', '#ataquesGrafica', function(e){
                    var area = new Morris.Area({
                        element   : 'revenue-chart2',
                        resize    : true,
                        data      : [
                        <?php
                            $consult = mysqli_query($link,"SELECT * FROM grafica_bloqueo ORDER BY fecha_bloqueo ASC");
                                
                            while($rows = mysqli_fetch_array($consult))
                            {
                                $fecha_bloqueo = $rows['fecha_bloqueo'];
                                $total_ssh = $rows['ssh'];
                                $asterisk = $rows['asterisk'];

                                echo '{ y: "'.$fecha_bloqueo.'", item1: '.$total_ssh.', item2: '.$asterisk.'},';
                            }
                        ?>
                        ],
                        xkey      : 'y',
                        ykeys     : ['item1', 'item2'],
                        labels    : ['ssh brute-force', 'Sip brute-force'],
                        lineColors: ['#e73043', '#033c78'],
                        hideHover : 'auto'
                    });
                });
            });
        </script>

        <!-- grafica de ataques por paises -->
        <script type="text/javascript">
            $(document).on('click','#graficoP',function() {
                Highcharts.chart('containerPais', {
                      chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                      },
                      title: {
                        text: ''
                      },
                      tooltip: {
                        pointFormat: 'Total : <b>{point.y:,.0f}</b>'
                      },
                      plotOptions: {
                        pie: {
                          allowPointSelect: true,
                          cursor: 'pointer',
                          dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                          }
                        }
                      },
                      series: [{
                        name: 'Paises',
                        colorByPoint: true,
                        point:{
                            events:{
                                click: function (event) {
                                    //alert(this.name);
                                    //setTimeout("location.href = 'bloqueo_pais.php?pais!="+this.name+"'",100);
                                }
                            }
                        }, 
                        data: [
                        <?php
                            $consult = mysqli_query($link,"SELECT * FROM bloqueo_pais
                                                    INNER JOIN paises
                                                    ON bloqueo_pais.id_pais = paises.id_pais");
                            
                            while($rows = mysqli_fetch_array($consult))
                            {
                                $nombre_pais = $rows['nombre'];
                                $total_bloqueos = $rows['total_bloqueo'];
                                $codigo_p = $rows['iso'];
                            
                                if ($total_bloqueos > 0) {
                                    echo '{ name: "'.$nombre_pais.'", y:'.$total_bloqueos.'},';
                                }
                            }
                        ?>
                        ]
                      }]
                });
            });
        </script>

        <!-- grafica de top de ip bloqueadas -->
        <script type="text/javascript">
            $(document).on('click','#graficoIP',function() {
                Highcharts.chart('containerTopIp', {
                      chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                      },
                      title: {
                        text: ''
                      },
                      tooltip: {
                        pointFormat: 'Total : <b>{point.y:,.0f}</b>'
                      },
                      plotOptions: {
                        pie: {
                          allowPointSelect: true,
                          cursor: 'pointer',
                          dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                          }
                        }
                      },
                      series: [{
                        name: 'Paises',
                        colorByPoint: true,
                        point:{
                            events:{
                                click: function (event) {
                                    //alert(this.name);
                                    //setTimeout("location.href = 'bloqueo_pais.php?pais!="+this.name+"'",100);
                                }
                            }
                        }, 
                        data: [
                        <?php
                            $consult = mysqli_query($link,"SELECT ip_bloqueo, count(*) as total 
                            FROM bloqueo_ataques GROUP BY ip_bloqueo ORDER BY total DESC LIMIT 5
                            ");
                            
                            while($rows = mysqli_fetch_array($consult))
                            {
                                $ip_bloqueo = trim($rows['ip_bloqueo']);
                                $total_bloqueos = $rows['total'];
                                if ($total_bloqueos > 0) {
                                    echo '{ name: "'.$ip_bloqueo.'", y:'.$total_bloqueos.'},';
                                }
                            }
                        ?>
                        ]
                      }]
                });
            });
        </script>

        <!-- rango de fecha top ip -->
        <script>
            $(function () {
                    //rango de fecha del top de duracion
                    $('#rangoFechaTopIp').daterangepicker({},
                    function(start, end, label) {
                        //alert("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                        var fecha1 = start.format('YYYY-MM-DD h:mm:ss');
                        var fecha2 = end.format('YYYY-MM-DD h:mm:ss');
                        var parametro = {
                            "fecha1" : fecha1,
                            "fecha2" : fecha2
                        }
                        $.ajax({
                            url:  'controller/new_grafica_top_ip.php', 
                            type: 'POST',
                            data: parametro,
                            dataType: 'html'
                        })
                        .done(function(data) {
                            $('#containerTopIp').html('');    
                            $('#containerTopIp').html(data); // mostrar la data
                        })
                        .fail(function(){
                            $('#containerTopIp').html('');
                        });
                    });
            });
        </script>

         <!-- rango de fecha pais -->
         <script>
            $(function () {
                    //rango de fecha del top de duracion
                    $('#rangoFechaPais').daterangepicker({},
                    function(start, end, label) {
                        //alert("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                        var fecha1 = start.format('YYYY-MM-DD h:mm:ss');
                        var fecha2 = end.format('YYYY-MM-DD h:mm:ss');
                        var parametro = {
                            "fecha1" : fecha1,
                            "fecha2" : fecha2
                        }
                        $.ajax({
                            url:  'controller/new_grafica_pais.php', 
                            type: 'POST',
                            data: parametro,
                            dataType: 'html'
                        })
                        .done(function(data) {
                            $('#containerPais').html('');    
                            $('#containerPais').html(data); // mostrar la data
                        })
                        .fail(function(){
                            $('#containerPais').html('');
                        });
                    });
            });
        </script>

        <!-- rango de fecha ataques -->
        <script>
            $(function () {
                    //rango de fecha del top de duracion
                    $('#rangoFechaAtaques').daterangepicker({},
                    function(start, end, label) {
                        //alert("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                        var fecha1 = start.format('YYYY-MM-DD h:mm:ss');
                        var fecha2 = end.format('YYYY-MM-DD h:mm:ss');
                        $('#revenue-chart2').html('');
                        var parametro = {
                            "fecha1" : fecha1,
                            "fecha2" : fecha2
                        }
                        $.ajax({
                            url:  'controller/new_grafica_ataques.php', 
                            type: 'POST',
                            data: parametro,
                            dataType: 'html'
                        })
                        .done(function(data) {
                            $('#revenue-chart2').html('');    
                            $('#revenue-chart2').html(data); // mostrar la data
                        })
                        .fail(function(){
                            $('#revenue-chart2').html('');
                        });
                    });
            });
        </script>

        <!-- llamada a WebSocket -->
        <script type="text/javascript">
            $(document).ready(function(){
                $(document).on('click','#listaExtension',function(e) {
                    e.preventDefault();

                    if (!$.fn.DataTable.isDataTable('#example5')) {
                        var table5 = $('#example5').DataTable({
                            "ajax": { "url": "controller/ajax_lista_extensiones.php", "timeout": 8000 },
                            "createdRow": function ( row, data, index ) {
                                // 
                                // if (data[1]) {
                                //     $('td', row).eq(1).html('<img src="img/telephone.png" width="30" alt="" style="'+data[2]+'" class="img-bordered img-circle"> '+data[1]);
                                // }

                                if (data[2]) {
                                    $('td', row).eq(2).css('display', 'none');                            
                                }
                            }
                        });

                        setInterval(function(){
                            table5.ajax.reload(null, false);  // false para que no cambie la página actual
                        }, 5000);
                    }
                });
            });
        </script>

        <!-- para guardar la addressbook -->
        <script type="text/javascript">
            function recargarListas() {
                const bust = Date.now(); // cache-buster

                // OFICINAS
                $.ajax({
                    url: 'controller/get_oficinas.php', // evita caché
                    cache: false,
                    success: function (data) {
                        const $of = $('#oficina2');
                        if ($of.data('select2')) {           // si ya está inicializado
                            $of.select2('destroy');            // destruye UI viejo
                        }
                        $of.empty().append(data);            // reemplaza opciones
                        $of.select2();                       // re-inicializa
                        $of.trigger('change');               // notifica cambio
                    }
                });

                // CARGOS
                $.ajax({
                    url: 'controller/get_cargos.php',
                    cache: false,
                    success: function (data) {
                        const $cg = $('#cargo2');
                        if ($cg.data('select2')) {
                            $cg.select2('destroy');
                        }
                        $cg.empty().append(data);
                        $cg.select2();
                        $cg.trigger('change');
                    }
                });
            }

            function recargarListasDos() {
                const bust = Date.now(); // cache-buster

                // OFICINAS
                $.ajax({
                    url: 'controller/get_oficinas.php',
                    cache: false,
                    success: function (data) {
                        const $of = $('#oficina1');
                        if ($of.data('select2')) {           // si ya está inicializado
                            $of.select2('destroy');            // destruye UI viejo
                        }
                        $of.empty().append(data);            // reemplaza opciones
                        $of.select2();                       // re-inicializa
                        $of.trigger('change');               // notifica cambio
                    }
                });

                // CARGOS
                $.ajax({
                    url: 'controller/get_cargos.php',
                    cache: false,
                    success: function (data) {
                        const $cg = $('#cargo1');
                        if ($cg.data('select2')) {
                            $cg.select2('destroy');
                        }
                        $cg.empty().append(data);
                        $cg.select2();
                        $cg.trigger('change');
                    }
                });
            }

            $(document).ready(function(){
                // cargar tabla de book
                var table3 = $('#tbla_book').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": "ajax_table/ajax_table_book.php",
                    "createdRow": function ( row, data, index ) {
                        if(data[7]){
                            html = `
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary">Opciones</button>
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="#0"
                                            data-toggle="modal"
                                            data-target="#ModalEA" 
                                            data-nombre=`+data[2]+`
                                            data-apellido=`+data[3]+`
                                            data-tel=`+data[4]+`
                                            data-indice=`+data[5]+`
                                            data-tipo=`+data[6]+`
                                            data-id=`+data[7]+` class="btn1_book">
                                            <i class="fa fa-pencil"></i> Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0" 
                                            data-id=`+data[7]+` class="btn2_book">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            `;
                            $('td', row).eq(7).addClass("text-center");
                            $('td', row).eq(7).html(html);
                        }
                    }
                });

                //guardar la addressbook
                $(document).on('click', '#btnG_book', function(e){
                    e.preventDefault();
                    $.ajax({
                    type: 'POST',
                    url: 'controller/g_directorio.php',
                    data: $('.FormAa').serialize(),
                    success: function(data) {
                        if (data == 'bien') {
                        not1();
                        $('#ModalNA').modal('hide');
                        table3.ajax.reload(null, false);
                        $('#nombre1').val('');
                        $('#apellido1').val('');
                        $('#tipo1').val('');
                        $('#oficina1').val('');
                        $('#cargo1').val('');
                        $('#tel1').val('');
                        $('#indice1').val('');
                        // setTimeout("location.href = 'home.php'",3000);
                        } else{
                        not2();
                        }
                    }
                    });      
                });

                //mostrar la info
                $(document).on('click', '.btn1_book', function(e){
                    e.preventDefault();
                    var id = $(this).data('id');
                    var nombre = $(this).data('nombre');
                    var apellido = $(this).data('apellido');
                    var tel = $(this).data('tel');
                    var indice = $(this).data('indice');
                    var tipo = $(this).data('tipo');

                    $('#id_phonebook').val(id);
                    $('#nombre').val(nombre);
                    $('#nombre2').val(apellido);
                    $('#tel').val(tel);
                    $('#indice').val(indice);
                    $('#tipo').val(tipo);

                    //
                    recargarListas();
                });

                //editar
                $(document).on('click', '#btnA_book', function(e){
                    e.preventDefault();
                    $.ajax({
                    type: 'POST',
                    url: 'controller/a_directorio.php',
                    data: $('.FormAb').serialize(),
                    success: function(data) {
                        if (data == 'bien') {
                        not3();
                        $('#ModalEA').modal('hide');
                        table3.ajax.reload(null, false);
                        // setTimeout("location.href = 'home.php'",3000);
                        }else{
                        not2();
                        }
                    }
                    });      
                });

                //Eliminar 
                $(document).on('click', '.btn2_book', function(e){
                    e.preventDefault();
                    var id_phonebook = $(this).data('id');
                    var parametro = {
                    "id_phonebook" : id_phonebook
                    }
                    $.ajax({
                    type: 'POST',
                    url: 'controller/e_directorio.php',
                    data: parametro,
                    success: function(data) {
                        if (data == 'bien') {
                            not4();
                            table3.ajax.reload(null, false);
                            // setTimeout("location.href = 'home.php'",2000);
                        } else{
                        not5();
                        }
                    }
                    });      
                });

                //generar  1
                $(document).on('click', '#btnXml', function(e){
                    e.preventDefault();
                    $.ajax({
                    url: 'controller/generarXml.php',
                    success: function(data) {
                        if (data == 'bien') {
                            not9();
                            // setTimeout("location.href = 'home.php'",2000);
                        } else{
                            not5();
                        }
                    }
                    });      
                });

                //generar cisco
                $(document).on('click', '#btnXmlCisco', function(e){
                    e.preventDefault();
                    $.ajax({
                    url: 'controller/generarXmlCisco.php',
                    success: function(data) {
                        if (data == 'bien') {
                            not9();
                            // setTimeout("location.href = 'home.php'",2000);
                        } else{
                            not5();
                        }
                    }
                    });      
                });

                //generar snom
                $(document).on('click', '#btnXmlSnom', function(e){
                    e.preventDefault();
                    $.ajax({
                    url: 'controller/generarXmlSnom.php',
                    success: function(data) {
                        if (data == 'bien') {
                            not9();
                            // setTimeout("location.href = 'home.php'",2000);
                        } else{
                            not5();
                        }
                    }
                    });      
                });

                //para cargar las oficinas y cargos
                $(document).on('click', '#btnModalBook', function(e){
                    e.preventDefault();
                    recargarListasDos();
                });
            });
        </script>

        <!-- para guardar la oficina -->
        <script type="text/javascript">
        $(document).ready(function(){
            // cargar tabla de oficinas
            var tabla2 = $('#lista_oficina').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": "ajax_table/ajax_table_oficina.php",
                    "createdRow": function ( row, data, index ) {
                        // boton opciones
                        if (data[2]) {
                            var nombreOficina = data[1];
                            html =
                                `<div class="btn-group">
                                    <button type="button" class="btn btn-primary">Opciones</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="#0"
                                                    data-toggle="modal"
                                                    data-target="#ModalEditarOficina" 
                                                    data-nombre="${nombreOficina}"
                                                    data-id=`+data[0]+` class="btn1_oficina">
                                                    <i class="fa fa-pencil"></i> Editar
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#0" 
                                                    data-id=`+data[0]+` class="btn2_oficina">
                                                    <i class="fa fa-trash"></i> Eliminar
                                                </a>
                                            </li>
                                        </ul>
                                </div>`;
                            $('td', row).eq(2).addClass("text-center");
                            $('td', row).eq(2).html(html);
                        }
                    }
                });

            //guardar la oficina
            $(document).on('click', '#btnG_oficina', function(e){
                e.preventDefault();
                $.ajax({
                type: 'POST',
                url: 'controller/g_oficina.php',
                data: $('.FormNuevaOficina').serialize(),
                success: function(data) {
                    if (data == 'bien') {
                        not1();
                        $('#ModalNuevaOficina').modal('hide');
                        tabla2.ajax.reload(null, false);
                        $('#nombreOficina').val('');
                        // setTimeout("location.href = 'home.php'",3000);
                    } else {
                        not2();
                    }
                }
                });      
            });

            //mostrar la info
            $(document).on('click', '.btn1_oficina', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                var nombre = $(this).data('nombre');

                $('#id_oficina_book').val(id);
                $('#nombre_oficina_book').val(nombre);
            });

            //editar
            $(document).on('click', '#btnA_oficina', function(e){
                e.preventDefault();
                $.ajax({
                type: 'POST',
                url: 'controller/a_oficina.php',
                data: $('.FormActializarOficina').serialize(),
                success: function(data) {
                    if (data == 'bien') {
                        not3();
                        $('#ModalEditarOficina').modal('hide');
                        tabla2.ajax.reload(null, false);
                        // setTimeout("location.href = 'home.php'",3000);
                    } else {
                        not2();
                    }
                }
                });      
            });

            //Eliminar 
            $(document).on('click', '.btn2_oficina', function(e){
                e.preventDefault();
                var id_oficina = $(this).data('id');
                var parametro = {
                    "id_oficina" : id_oficina
                }
                $.ajax({
                type: 'POST',
                url: 'controller/e_oficina.php',
                data: parametro,
                success: function(data) {
                    if (data == 'bien') {
                        not4();
                        tabla2.ajax.reload(null, false);
                        // setTimeout("location.href = 'home.php'",2000);
                    } else {
                        not5();
                    }
                }
                });      
            });

        });
        </script>

        <!-- para guardar el cargo -->
        <script type="text/javascript">
            $(document).ready(function(){
                // cargar tabla de cargos
                var tabla1 = $('#lista_cargo').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": "ajax_table/ajax_table_cargo.php",
                    "createdRow": function ( row, data, index ) {
                        // boton opciones
                        if (data[2]) {
                            var nombreCargo = data[1];
                            html =
                                `<div class="btn-group">
                                    <button type="button" class="btn btn-primary">Opciones</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="#0"
                                                    data-toggle="modal"
                                                    data-target="#ModalEditarCargo" 
                                                    data-nombre="${nombreCargo}"
                                                    data-id=`+data[0]+` class="btn1_cargo">
                                                    <i class="fa fa-pencil"></i> Editar
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#0" 
                                                    data-id=`+data[0]+` class="btn2_cargo">
                                                    <i class="fa fa-trash"></i> Eliminar
                                                </a>
                                            </li>
                                        </ul>
                                </div>`;
                            $('td', row).eq(2).addClass("text-center");
                            $('td', row).eq(2).html(html);
                        }
                    }
                });

                //guardar la addressbook
                $(document).on('click', '#btnG_cargo', function(e){
                    e.preventDefault();
                    $.ajax({
                    type: 'POST',
                    url: 'controller/g_cargo.php',
                    data: $('.FormNuevoCargo').serialize(),
                    success: function(data) {
                        if (data == 'bien') {
                            not1();
                            $('#ModalNuevoCargo').modal('hide');
                            tabla1.ajax.reload(null, false);
                            $('#nombreCargo').val('');
                            // setTimeout("location.href = 'home.php'",3000);
                        } else{
                            not2();
                        }
                    }
                    });      
                });

                //mostrar la info
                $(document).on('click', '.btn1_cargo', function(e){
                    e.preventDefault();
                    var id = $(this).data('id');
                    var nombre = $(this).data('nombre');

                    $('#id_cargo_book').val(id);
                    $('#nombre_cargo_book').val(nombre);
                });

                //editar
                $(document).on('click', '#btnA_cargo', function(e){
                    e.preventDefault();
                    $.ajax({
                    type: 'POST',
                    url: 'controller/a_cargo.php',
                    data: $('.FormEditarCargo').serialize(),
                    success: function(data) {
                        if (data == 'bien') {
                            not3();
                            $('#ModalEditarCargo').modal('hide');
                            tabla1.ajax.reload(null, false);
                            // setTimeout("location.href = 'home.php'",3000);
                        }else{
                            not2();
                        }
                    }
                    });      
                });

                //Eliminar 
                $(document).on('click', '.btn2_cargo', function(e){
                    e.preventDefault();
                    var id_cargo = $(this).data('id');
                    var parametro = {
                    "id_cargo" : id_cargo
                    }
                    $.ajax({
                    type: 'POST',
                    url: 'controller/e_cargo.php',
                    data: parametro,
                    success: function(data) {
                        if (data == 'bien') {
                            not4();
                            tabla1.ajax.reload(null, false);
                            // setTimeout("location.href = 'home.php'",2000);
                        } else{
                            not5();
                        }
                    }
                    });      
                });

            });
        </script>

        <!-- para guardar el cargo -->
        <script type="text/javascript">
            $(document).ready(function(){

                //guardar la addressbook
                $(document).on('click', '#btnGLicencia', function(e){
                    e.preventDefault();
                    $.ajax({
                    type: 'POST',
                    url: 'controller/g_licencia.php',
                    data: $('.FormLicencia').serialize(),
                    success: function(data) {
                        if (data == 'bien') {
                            not1();
                            setTimeout("location.href = 'home.php'",3000);
                        } else{
                            not2();
                        }
                    }
                    });      
                });
            });
        </script>


        <!-- table de extensiones lista -->
        <script type="text/javascript">
            $(document).ready(function(){
                var tableExtensiones = null;
                var editarRequest = null;
                var selectedExtensiones = {};

                function selectedCountExtensiones() {
                    return Object.keys(selectedExtensiones).length;
                }

                function updateEliminarExtensionesButton() {
                    var totalSeleccionadas = selectedCountExtensiones();
                    if (totalSeleccionadas > 0) {
                        $('#btnEliminarExtensiones').show().html('<i class="fa fa-trash"></i> Eliminar (' + totalSeleccionadas + ')');
                    } else {
                        $('#btnEliminarExtensiones').hide().html('<i class="fa fa-trash"></i> Eliminar');
                    }

                    var $checks = $('#example6 tbody .ext-row-check');
                    if (!$checks.length) {
                        $('#ext_select_all').prop('checked', false);
                        return;
                    }

                    var checkedOnPage = $('#example6 tbody .ext-row-check:checked').length;
                    $('#ext_select_all').prop('checked', checkedOnPage === $checks.length);
                }

                function clearSelectedExtensiones() {
                    selectedExtensiones = {};
                    $('#ext_select_all').prop('checked', false);
                    updateEliminarExtensionesButton();
                }

                $(document).on('click', '#listaExtension2', function(e){
                    if ($.fn.DataTable.isDataTable('#example6')) {
                        tableExtensiones = $('#example6').DataTable();
                        tableExtensiones.ajax.reload(null, false);
                        return;
                    }

                    tableExtensiones = $('#example6').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": "ajax_table/ajax_table_extensiones_nuevas.php",
                        "columnDefs": [
                            { "orderable": false, "targets": [0, 5] }
                        ],
                        "createdRow": function ( row, data, index ) {
                            if (data[0]) {
                                $('td', row).eq(0).addClass("text-center");
                            }
                            if (data[1]) {
                                $('td', row).eq(1).addClass("text-center");
                            }
                            //centrar extension
                            if (data[2]) {
                                $('td', row).eq(2).addClass("text-center");
                            }
                            //centrar estado
                            if (data[4]) {
                                $('td', row).eq(4).addClass("text-center");
                                $('td', row).eq(4).html('<span class="label label-success" style="font-size: inherit; background-color: #3bb67f !important;">'+data[4]+'</span>');
                            }

                            //boton editar
                            if (data[5]) {
                                var result = data[5];
                                $('td', row).eq(5).addClass("text-center");
                                $('td', row).eq(5).html('<button class="btn btn-primary"><a href="#0" data-idExtension="'+result+'" style="color: #FFFFFF;" id="btnEditarExtension"><i class="fa fa-edit"></i> Editar</a></btutton>');
                            }
                        },
                        "drawCallback": function () {
                            $('#example6 tbody .ext-row-check').each(function(){
                                var id = String($(this).data('id'));
                                $(this).prop('checked', !!selectedExtensiones[id]);
                            });
                            updateEliminarExtensionesButton();
                        }
                    });
                });

                window.refreshTablaExtensiones = function(){
                    if ($.fn.DataTable.isDataTable('#example6')) {
                        $('#example6').DataTable().ajax.reload(null, false);
                    }
                };

                $(document).on('change', '.ext-row-check', function(){
                    var idExtension = String($(this).data('id'));
                    if (!idExtension) {
                        return;
                    }

                    if ($(this).is(':checked')) {
                        selectedExtensiones[idExtension] = true;
                    } else {
                        delete selectedExtensiones[idExtension];
                    }

                    updateEliminarExtensionesButton();
                });

                $(document).on('change', '#ext_select_all', function(){
                    var check = $(this).is(':checked');
                    $('#example6 tbody .ext-row-check').each(function(){
                        $(this).prop('checked', check).trigger('change');
                    });
                });

                $(document).on('click', '#btnEliminarExtensiones', function(e){
                    e.preventDefault();

                    var ids = Object.keys(selectedExtensiones);
                    if (!ids.length) {
                        updateEliminarExtensionesButton();
                        return;
                    }

                    if (!confirm('¿Desea eliminar ' + ids.length + ' extension(es) seleccionada(s)?')) {
                        return;
                    }

                    $('#btnEliminarExtensiones').prop('disabled', true);

                    $.ajax({
                        url: 'controller/ajax_eliminar_extensiones_nuevo.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_extension: ids
                        }
                    })
                    .done(function(response){
                        if (response && response.ok) {
                            notif({
                                msg: response.message ? response.message : "Extensiones eliminadas correctamente.",
                                type: "success",
                                position: "center"
                            });
                            clearSelectedExtensiones();
                            window.refreshTablaExtensiones();
                        } else {
                            notif({
                                msg: response && response.message ? response.message : "Error al eliminar extensiones.",
                                type: "error",
                                position: "center"
                            });
                        }
                    })
                    .fail(function(){
                        notif({
                            msg: "Error al eliminar extensiones.",
                            type: "error",
                            position: "center"
                        });
                    })
                    .always(function(){
                        $('#btnEliminarExtensiones').prop('disabled', false);
                    });
                });

                $(document).on('click', '#btnEditarExtension', function(e){
                    e.preventDefault();
                    var idExtension = $(this).data('idextension');
                    if (!idExtension) {
                        notif({
                            msg: "No se pudo obtener el ID de la extensión.",
                            type: "error",
                            position: "center"
                        });
                        return;
                    }

                    if (editarRequest && editarRequest.readyState !== 4) {
                        editarRequest.abort();
                    }

                    editarRequest = $.ajax({
                        url: 'controller/ajax_get_extension.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_extension: idExtension
                        }
                    })
                    .done(function(response){
                        if (!response || !response.ok || !response.data) {
                            notif({
                                msg: response && response.message ? response.message : "No se pudo cargar la extensión.",
                                type: "error",
                                position: "center"
                            });
                            return;
                        }

                        var d = response.data;
                        $('#edit_id_extension').val(d.id_extension);
                        $('#edit_conf_text').val(response.conf_text || '');

                        $('#modalEditarExtension').modal('show');
                    })
                    .fail(function(){
                        notif({
                            msg: "Error al consultar la extensión.",
                            type: "error",
                            position: "center"
                        });
                    });
                });

                $(document).on('click', '#btnActualizarExtension', function(e){
                    e.preventDefault();

                    var confText = $.trim($('#edit_conf_text').val());
                    if (confText === '') {
                        notif({
                            msg: "El contenido de tel_endpoints.conf no puede estar vacío.",
                            type: "error",
                            position: "center"
                        });
                        return;
                    }

                    $.ajax({
                        url: 'controller/ajax_update_extension.php',
                        type: 'POST',
                        dataType: 'json',
                        data: $('#formEditarExtension').serialize()
                    })
                    .done(function(response){
                        if (response && response.ok) {
                            notif({
                                msg: "Extensión actualizada correctamente.",
                                type: "success",
                                position: "center"
                            });
                            $('#modalEditarExtension').modal('hide');
                            window.refreshTablaExtensiones();
                        } else {
                            notif({
                                msg: response && response.message ? response.message : "Error al actualizar la extensión.",
                                type: "error",
                                position: "center"
                            });
                        }
                    })
                    .fail(function(){
                        notif({
                            msg: "Error al actualizar la extensión.",
                            type: "error",
                            position: "center"
                        });
                    });
                });
            });
        </script>

        <!-- wizard para crear extensiones -->
        <script type="text/javascript">
            $(function(){
                var $wizard = $('#wizardExtensiones');
                if (!$wizard.length) {
                    return;
                }

                var totalSteps = 4;
                var currentStep = 1;

                function clearAllErrors() {
                    $wizard.find('.wizard-error').text('');
                    $wizard.find('.form-group').removeClass('has-error');
                }

                function setError(key, msg) {
                    var $error = $wizard.find('.wizard-error[data-error-for="' + key + '"]');
                    if (!$error.length) {
                        return;
                    }
                    $error.text(msg);
                    $error.closest('.form-group').addClass('has-error');
                }

                function validateStep(step) {
                    clearAllErrors();

                    if (step === 1) {
                        var numeracion = $.trim($('#wizard_numeracion').val());
                        if (!numeracion) {
                            setError('numeracion', 'La numeración es obligatoria.');
                            return false;
                        }
                    }

                    if (step === 2) {
                        var cantidad = parseInt($('#wizard_cantidad').val(), 10);
                        if (!cantidad || cantidad <= 0) {
                            setError('cantidad', 'La cantidad debe ser mayor a cero.');
                            return false;
                        }
                    }

                    if (step === 3) {
                        if (!$('input[name="wizard_transporte"]:checked').length) {
                            setError('transporte', 'Selecciona un transporte.');
                            return false;
                        }
                    }

                    if (step === 4) {
                        if (!$('input[name="wizard_plan"]:checked').length) {
                            setError('plan', 'Selecciona un plan.');
                            return false;
                        }
                    }

                    return true;
                }

                function validateAll() {
                    for (var step = 1; step <= totalSteps; step++) {
                        if (!validateStep(step)) {
                            showStep(step);
                            return false;
                        }
                    }
                    return true;
                }

                function showStep(step) {
                    if (step < 1 || step > totalSteps) {
                        return;
                    }
                    currentStep = step;
                    $wizard.find('.wizard-panel').removeClass('active').hide();
                    $wizard.find('.wizard-panel[data-step="' + step + '"]').addClass('active').show();

                    $wizard.find('.wizard-step').each(function(){
                        var stepNumber = parseInt($(this).data('step'), 10);
                        $(this).toggleClass('active', stepNumber === step);
                        $(this).toggleClass('completed', stepNumber < step);
                    });

                    $wizard.find('#wizardPrev').toggle(step > 1);
                    $wizard.find('#wizardNext').toggle(step < totalSteps);
                    $wizard.find('#wizardSubmit').toggle(step === totalSteps);
                }

                function setLoading(isLoading) {
                    $wizard.toggleClass('is-loading', isLoading);
                    $wizard.find('input, button').prop('disabled', isLoading);
                }

                function resetWizard() {
                    clearAllErrors();
                    $('#wizard_numeracion').val('');
                    $('#wizard_cantidad').val('');
                    $('input[name="wizard_transporte"]').prop('checked', false);
                    $('input[name="wizard_plan"]').prop('checked', false);
                    showStep(1);
                }

                function getPayload() {
                    return {
                        numeracion: $.trim($('#wizard_numeracion').val()),
                        cantidad: $('#wizard_cantidad').val(),
                        transporte: $('input[name="wizard_transporte"]:checked').val(),
                        plan: $('input[name="wizard_plan"]:checked').val()
                    };
                }

                $wizard.on('click', '#wizardPrev', function(){
                    showStep(currentStep - 1);
                });

                $wizard.on('click', '#wizardNext', function(){
                    if (!validateStep(currentStep)) {
                        return;
                    }
                    showStep(currentStep + 1);
                });

                $wizard.on('click', '#wizardSubmit', function(){
                    if (!validateAll()) {
                        return;
                    }

                    setLoading(true);
                    $.ajax({
                        url: 'controller/ajax_crear_extensiones_nuevo.php',
                        type: 'POST',
                        dataType: 'json',
                        data: getPayload()
                    })
                    .done(function(response){
                        if (response && response.ok) {
                            notif({
                                msg: "Extensiones creadas correctamente.",
                                type: "success",
                                position: "center"
                            });
                            resetWizard();
                            if (typeof window.refreshTablaExtensiones === 'function') {
                                window.refreshTablaExtensiones();
                            }
                        } else {
                            notif({
                                msg: response && response.message ? response.message : "Error al crear extensiones.",
                                type: "error",
                                position: "center"
                            });
                        }
                    })
                    .fail(function(){
                        notif({
                            msg: "Error al crear extensiones.",
                            type: "error",
                            position: "center"
                        });
                    })
                    .always(function(){
                        setLoading(false);
                    });
                });

                $(document).on('shown.bs.tab', 'a[href="#tap_crear"]', function(){
                    showStep(1);
                });

                showStep(1);
            });
        </script>

        <!-- llamar comando -->
        <script type="text/javascript">
            $(document).ready(function(){
                $(document).on('click', '#btnCambios', function(e){
                    e.preventDefault();
                    $.ajax({
                        url: 'controller/procesar_cambios.php',
                        type: 'POST',
                        dataType: 'json',
                    })
                    .done(function(response){
                        if (response && response.ok) {
                            notif({
                                msg: "Cambios procesados correctamente.",
                                type: "success",
                                position: "center"
                            });
                        } else {
                            notif({
                                msg: response && response.message ? response.message : "Error al procesar cambios.",
                                type: "error",
                                position: "center"
                            });
                        }
                    })
                    .fail(function(){
                        notif({
                            msg: "Error al procesar cambios.",
                            type: "error",
                            position: "center"
                        });
                    })
                });
            });
            // Lógica para editar endpoints conf
            $('#btnEditEndpointsConf').on('click', function(){
                $('#editEndpointsAlert').hide();
                $('#txtEndpointsConfContent').val('Cargando...');
                
                $.ajax({
                    url: 'controller/ajax_get_endpoints_file.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response){
                        if(response.ok){
                            $('#txtEndpointsConfContent').val(response.data);
                            $('#modalEditEndpointsConf').modal('show');
                        }else{
                            notif({msg: "Error al cargar archivo: " + response.message, type: "error", position: "center"});
                        }
                    },
                    error: function(){
                        notif({msg: "Error de red al cargar el archivo.", type: "error", position: "center"});
                    }
                });
            });

            $('#btnSaveEndpointsConf').on('click', function(){
                var content = $('#txtEndpointsConfContent').val();
                var btn = $(this);
                btn.prop('disabled', true).text('Guardando...');
                $('#editEndpointsAlert').hide();

                $.ajax({
                    url: 'controller/ajax_save_endpoints_file.php',
                    type: 'POST',
                    data: { content: content, csrf_token: CSRF_TOKEN },
                    dataType: 'json',
                    success: function(response){
                        btn.prop('disabled', false).text('Guardar y Sincronizar');
                        if(response.ok){
                            $('#modalEditEndpointsConf').modal('hide');
                            notif({msg: response.message, type: "success", position: "center"});
                            if(typeof tableExtensiones !== 'undefined' && tableExtensiones) {
                                tableExtensiones.ajax.reload(null, false);
                            }
                        }else{
                            $('#editEndpointsAlert').removeClass('alert-success').addClass('alert-danger').html('<strong>Error:</strong> ' + response.message).show();
                        }
                    },
                    error: function(xhr, status, error){
                        btn.prop('disabled', false).text('Guardar y Sincronizar');
                        $('#editEndpointsAlert').removeClass('alert-success').addClass('alert-danger').html('<strong>Error de servidor:</strong> ' + error).show();
                    }
                });
            });
        </script>

        <?php include_once 'includes/footer_license.php'; ?>
    </body>
</html>
