<?php
    include_once 'includes/config.php';
    include_once 'includes/security.php';   
    
    date_default_timezone_set('America/Managua');

    session_start();
    require_once 'includes/auth_check.php';
    require_web_auth(1);

    $id = $_SESSION['id_u'];
    $nombre = $_SESSION['nombre'];
    $apellido = $_SESSION['apellido'];
    $activo = $_SESSION['activo'];
    $tipo = $_SESSION['tipo_usuario'];

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
        <title> Usuarios</title>
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

        <script type="text/javascript">
        $(window).load(function(){
            setTimeout(function() {
                $('#loading').fadeOut( 400, "linear" );
            }, 300);
        });
        </script>

        <style type="text/css">
            .skin-blue .main-header .navbar{
                background-image: url('img/header.png');
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

        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/php_file_tree_jquery.js" type="text/javascript"></script>
    </head>
    
    <body class="hold-transition skin-blue layout-top-nav" onload="loadServer()">
        <!-- Site wrapper -->
        <div class="wrapper">
            <?php include 'includes/navbar.php'; ?>
            <!-- =============================================== -->
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Usuarios
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="user.php"><i class="fa fa-users"></i> Usuarios</a></li>
                    </ol>
                </section>
                <!-- Main content -->
                <section class="content">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">

                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">
                                <i class="fa fa-users"></i> Usuarios
                                </a>
                            </li>
                            
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_1">
                                <div style="padding: 10px 0px;">
                                    <label>Lista de usuarios</label>
                                    <button data-toggle="modal" data-target="#ModalNU" type="button" class="btn btn-success pull-right">
                                        <i class="fa fa-plus"></i> Nuevo Usuario
                                    </button>
                                </div>
                                <table id="example1" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Nombres y Apellidos</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Teléfono</th>
                                            <th class="text-center">Opciones</th>                              
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $query = mysqli_query($link,"SELECT * FROM usuario WHERE usuario.activo_u = 1 ORDER BY usuario.id_usuario DESC");
                                            $i = 1;
                                            while($row = mysqli_fetch_array($query))
                                            {
                                            echo '
                                            <tr>
                                                <td class="text-center">'.$i.'</td>
                                                <td class="text-center">'.$row['nombre_u'].' '.$row['apellido_u'].'</td>
                                                <td class="text-center">'.$row['email_u'].'</td>
                                                <td class="text-center">'.$row['telefono'].'</td>
                                                <td class="text-center">
                                                    <div class="btn-group dropup">
                                                        <button type="button" class="btn btn-primary">Opciones</button>
                                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                            <span class="caret"></span>
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <ul class="dropdown-menu pull-right" role="menu">
                                                            <li>
                                                                <a href="#0" data-toggle="modal" data-target="#ModalEU" data-id="'.$row['id_usuario'].'" data-nombre="'.$row['nombre_u'].'" data-apellido="'.$row['apellido_u'].'" data-tel="'.$row['telefono'].'" data-email="'.$row['email_u'].'" data-tipo="'.$row['tipo_u'].'" class="btnA"> <i class="fa fa-edit"></i> Editar</a>
                                                            </li>';
                                                            // <li>
                                                            //     <a href="#0" data-toggle="modal" data-target="#ModalME" data-id="'.$row['id_usuario'].'"  data-email="'.$row['email_u'].'" class="btnM"> <i class="fa fa-envelope-o"></i> Mensaje</a>
                                                            // </li>
                                                            echo '
                                                            <li>
                                                                <a href="#0" data-id="'.$row['id_usuario'].'" class="btnE"><i class="fa fa-trash"></i> Eliminar</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>';
                                            $i++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                </section>
                <!-- /.content -->

                <!-- nuevo usuario -->
                <div class="modal fade" id="ModalNU">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h5 class="modal-title">Nuevo usuario</h5>
                            </div>
                            <div class="modal-body">
                                <form id="FormU" class="FormUa" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

                                    <div class="form-group col-md-6">
                                        <label>Nombres del usuario</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input type="text" name="nombre" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Apellidos del usuario</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input type="text" name="apellido" class="form-control">
                                        </div>
                                    </div>

                                    <!-- <div class="form-group col-md-12">
                                        <label>Cargo</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-list"></i>
                                            </div>
                                            <select class="form-control" name="id_cargo">
                                                <option value="">Cargos</option>
                                                <option value="1">Administrador</option>
                                                <option value="2">Usuario normal</option>
                                            </select>
                                        </div>
                                    </div> -->

                                    <div class="form-group col-md-4">
                                        <label>Teléfono</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <input type="text" name="tel" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>E-mail</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-at"></i>
                                            </div>
                                            <input type="email" name="email" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Contraseña</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-key"></i>
                                            </div>
                                            <input type="password" name="pass" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="submitG"><i class="fa fa-save"></i> Guardar</button>
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
                <!-- /.modal -->


                <!-- editar usuario -->
                <div class="modal fade" id="ModalEU">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h5 class="modal-title">Editar usuario</h5>
                            </div>
                            <div class="modal-body">
                                <form id="FormU" class="FormUb" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

                                    <div class="form-group col-md-6">
                                        <label>Nombres del usuario</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input type="text" name="nombre" id="nombre" class="form-control">
                                            <input type="hidden" name="id_usuario" id="id_usuario">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Apellidos del usuario</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input type="text" name="apellido" id="apellido" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Teléfono</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <input type="text" name="tel" id="tel" class="form-control">
                                            <input type="hidden" name="id_cargo2" id="id_cargo">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>E-mail</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-at"></i>
                                            </div>
                                            <input type="email" name="email" id="email" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Contraseña</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-key"></i>
                                            </div>
                                            <input type="password" name="pass" id="pass" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="submitA"><i class="fa fa-save"></i> Actualizar</button>
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
                <!-- /.modal -->


            </div>
            <!-- /.content-wrapper
            style="padding: 0px;border-top: 0px solid #d2d6de;background: #ecf0f5;" -->
            <!-- <footer class="main-footer" style="background-image: url('img/footer.png'); padding: 60px;border-top: 0px solid #d2d6de;background-color: #ecf0f5;">
                <div class="pull-right hidden-xs" style="margin-top: 20px;">
                    <strong>
                        <a style="color: #ffffff;" target="_blank" href="https://www.netsoluciones.com">
                        <h5 style="margin-top: 2px;">Diseñado por NetSoluciones</h5>
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

        <!-- Bootstrap WYSIHTML5 -->
        <script src="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        
        <script type="text/javascript">
            $(document).ajaxStart(function () {
                 Pace.restart()
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('#example1').DataTable();
            });
        </script>

        <script>
            $(document).ready(function () {
              $('.sidebar-menu').tree()
            });
        </script>

        <script type="text/javascript">
            var CSRF_TOKEN = <?php echo json_encode(csrf_token()); ?>;

            $(document).ready(function(){

                //guardar la usuario
                $(document).on('click', '#submitG', function(e){
                    e.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: 'controller/g_user.php',
                        data: $('.FormUa').serialize(),
                        success: function(data) {
                            if (data == 'bien') {
                                not1();
                                setTimeout("location.href = 'user.php'",3000);
                            }else{
                                not2();
                            }
                        }
                    });      
                });

                //Mostrar informacion del usuario
                $(document).on('click', '.btnA', function(e){
                    e.preventDefault();
                    var id_usuario = $(this).data('id');
                    var nombre = $(this).data('nombre');
                    var apellido = $(this).data('apellido');
                    var tel = $(this).data('tel');
                    var direcc = $(this).data('direcc');
                    var email = $(this).data('email');
                    var cargo = $(this).data('cargo');
                    $('#id_usuario').val(id_usuario);
                    $('#nombre').val(nombre);
                    $('#apellido').val(apellido);
                    $('#tel').val(tel);
                    $('#direcc').val(direcc);
                    $('#email').val(email);
                    $('#id_cargo').val(cargo);
                });

                //actualizar usuario
                $(document).on('click', '#submitA', function(e){
                    e.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: 'controller/a_user.php',
                        data: $('.FormUb').serialize(),
                        success: function(data) {
                            if (data == 'bien') {
                                not3();
                                setTimeout("location.href = 'user.php'",3000);
                            }else{
                                not2();
                            }
                        }
                    });      
                });

                //Eliminar usuario
                $(document).on('click', '.btnE', function(e){
                    e.preventDefault();
                    var id_usuario = $(this).data('id');
                    var parametro = {
                        "id_usuario" : id_usuario,
                        "csrf_token" : CSRF_TOKEN
                    }
                    $.ajax({
                        type: 'POST',
                        url: 'controller/e_user.php',
                        data: parametro,
                        success: function(data) {
                            if (data == 'bien') {
                                not4();
                                setTimeout("location.href = 'user.php'",2000);
                            }else{
                                not5();
                            }
                        }
                    });      
                });

            });
        </script>

    <?php include 'includes/footer_license.php'; ?>
    </body>
</html>