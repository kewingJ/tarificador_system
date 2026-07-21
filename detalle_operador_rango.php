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

    //
    $operador = $_GET['tipo!'];
    $rango = $_GET['rango!'];

    $consult = mysqli_query($link,"SELECT * FROM usuario WHERE id_usuario = '$id'");
    $row = mysqli_fetch_array($consult);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> Detalle Operador </title>
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

        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/php_file_tree_jquery.js" type="text/javascript"></script>

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
            <?php include 'includes/navbar.php'; ?>
            <!-- =============================================== -->
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Detalle Operador
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
                                <i class="fa fa-asterisk"></i> Detalle
                                </a>
                            </li>

                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_1">
                                <label></label>

                                <div class="text-center">
                                    <div class="btn-group">

                                        <a href="#0" id="btn_eliminar" class="btn btn-danger btnDelete" style="display: none;">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </a>
                                                            
                                    </div>
                                </div>
                                <table id="example1" class="table table-bordered table-striped" width="100%">
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
                                            <th>Duración</th>
                                            <th>Operador</th>
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
                            return row[9];
                        },
                    },
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
                                setTimeout("location.href = 'home.php'",2000);
                            }else {
                                //alert(data);
                                not5();
                            }
                        }
                    }); 
                });

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
                var buildSearchData = {
                    'operador' : <?php echo "'$operador'"; ?>,
                    'rango' : <?php echo "'$rango'"; ?>
                };

                //alert(buildSearchData);

                var table1 = $('#example1').DataTable({
                     "orderCellsTop": true,
                     "fixedHeader": true,
                     "columns": columns,
                     "scrollX": true,
                     "ajax": {
                        "url" : "ajax_table/ajax_detalle_operador_rango.php",
                        "type": 'POST',
                        "data": buildSearchData
                    }
                });
            });
        </script> 

    <?php include 'includes/footer_license.php'; ?>
    </body>
</html>