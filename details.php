<?php
    include_once 'includes/config.php';
    include_once 'includes/security.php';

    date_default_timezone_set('America/Managua');
    
    session_start();
    $id = $_SESSION['id_u'];
    $nombre = $_SESSION['nombre'];
    $apellido = $_SESSION['apellido'];
    $activo = $_SESSION['activo'];
    $cargo = $_SESSION['tipo_usuario'];
    
    /*optener solo el primer nombre y el primer apellido del profesor*/
    $nombre = explode(' ', $nombre);
    @$nombre = $nombre[0];
    
    $apellido = explode(' ', $apellido);
    @$apellido = $apellido[0];
    
    $consult = mysqli_query($link,"SELECT * FROM usuario WHERE id_usuario = '$id'");
    $row = mysqli_fetch_array($consult);
    
    if (empty($id) || empty($activo) || $cargo != 1) {
        header("Location: index.php");
    }

    // if (empty($id) || empty($activo) || $tipo != 1) {
    //     header("Location: index.php");
    // } else {
    //     $fechaGuardada = $_SESSION["ultimoAcceso"];
    //     $ahora = date("Y-n-j H:i:s");
    //     $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));
            
    //     //echo $tiempo_transcurrido;
    //     //comparamos el tiempo transcurrido
    //     if($tiempo_transcurrido >= 1200) {
    //         //si pasaron 20 minutos o más
    //         session_destroy(); // destruyo la sesión
    //         session_start();
    //         $_SESSION['nombre_usuario']     = $nombre;
    //         $_SESSION['apellido_usuario']   = $apellido;
    //         $_SESSION['correo_usuario']     = $row['email_u'];
    
    //         header("Location: lockscreen.php"); //envío al usuario a la pag. de autenticación
    //         //sino, actualizo la fecha de la sesión
    //     } else {
    //         $_SESSION["ultimoAcceso"] = $ahora;
    //     }
    // }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> Detalle de llamadas </title>
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

        <style type="text/css">
            .skin-blue .main-header .navbar{
                background-image: url('img/header.png');
            }
        </style>

        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/php_file_tree_jquery.js" type="text/javascript"></script>

        <style type="text/css">
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
    </head>
    
    <body class="hold-transition skin-blue layout-top-nav">
        <!-- Site wrapper -->
        <div class="wrapper">
            <div class="main-header bg-header wow fadeInDown animated animated" style="visibility: visible;">
                <div class="container">
                    <a href="home.php" class="header-logo" style="width: 40px;"></a>
                    
                    <ul class="header-nav collapse">
                        <li class="active">
                            <a href="home.php" title="">
                                <i class="fa fa-home"></i>
                                Inicio
                            </a>
                        </li>

                        <li class="">
                            <a href="user.php" title="">
                                <!-- <i class="fa fa-users"></i> -->
                                <img src="img/user.png" width="20%">
                                Usuarios
                            </a>
                        </li>

                        <li class="">
                            <a href="prefijos.php" title="">
                                <!-- <i class="fa fa-list"></i> -->
                                <img src="img/prefijo.png" width="20%">
                                Prefijos
                            </a>
                        </li>

                        <?php
                            if($look_view_audio) {
                                echo '
                                    <li class="">
                                        <a href="listAudio.php" title="">
                                            <!-- <i class="fa fa-file-audio-o"></i> -->
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

                        <li class="">
                            <a href="salir.php" title="">
                                <!-- <i class="fa fa-power-off"></i> -->
                                <img src="img/salir.png" width="20%">
                                Salir
                            </a>
                        </li>
                    </ul><!-- .header-nav -->
                </div><!-- .container -->
            </div>
            <!-- =============================================== -->
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Detalle
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="home.php"><i class="fa fa-home"></i> Inicio</a></li>
                    </ol>
                </section>
                <!-- Main content -->
                <section class="content">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">

                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">
                                <i class="fa fa-asterisk"></i> Todas las llamadas
                                </a>
                            </li>

                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_1">
                                <label>Lista de todas las llamadas</label>
                                <table id="example1" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Origen</th>
                                            <th>Destino</th>
                                            <th>Fecha de llamada</th>
                                            <th>Hora de llamada</th>
                                            <th>Estado</th>
                                            <th>Duración</th>                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                </section>
                <!-- /.content -->

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

        <script>
            $(document).ready(function () {
              $('.sidebar-menu').tree()
            });
        </script>
        
        <!-- para las tablas -->
        <script>
            $(document).ready(function () {
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
                <?php
                    //verificar por medio de que parametro buscare
                    if (!empty($_GET['nombre!']) && empty($_GET['origen!']) && empty($_GET['destino!'])) {

                        $id_busqueda = $_GET['nombre!'];
                        $bandera = 1;

                    } else if(empty($_GET['nombre!']) && !empty($_GET['origen!']) && empty($_GET['destino!'])) {

                        $id_busqueda = $_GET['origen!'];
                        $bandera = 2;

                    } else if(empty($_GET['nombre!']) && empty($_GET['origen!']) && !empty($_GET['destino!'])) {

                        $id_busqueda = $_GET['destino!'];
                        $bandera = 3;
                    }
                ?>
                var buildSearchData = {
                    'busqueda' : <?php echo "'$id_busqueda'"; ?>,
                    'bandera' : <?php echo $bandera; ?>
                };

                //alert(buildSearchData);

                var table1 = $('#example1').DataTable({
                     "scrollX": true,
                     "orderCellsTop": true,
                     "fixedHeader": true,
                     "ajax": {
                        "url" : "ajax_table/ajax_table_cdr_details.php",
                        "type": 'POST',
                        "data": buildSearchData,
                    },
                     "createdRow": function ( row, data, index ) {
                            //
                            if (data[6]) {
                                //$('td', row).eq(5).append('<i class="fa fa-clock-o icono-relog"></i>');
                                var resultado = '';
                                $('td', row).eq(6).html('');
                                if (data[6] == 'NO ANSWER') {
                                     $('td', row).eq(6).html('<span style="color:red;">No Contestada</span>');
                                } else {
                                     $('td', row).eq(6).html('<span style="color:green;">Contestada</span>');
                                }
                            }
                        }
                });
            });
        </script> 

    </body>
</html>