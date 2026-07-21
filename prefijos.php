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
        <title>Prefijos</title>
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
                        Prefijos
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="prefijos.php"><i class="fa fa-phone-square"></i> Prefijos</a></li>
                    </ol>
                </section>
                <!-- Main content -->
                <section class="content">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">

                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">
                                <i class="fa fa-phone-square"></i> Prefijos
                                </a>
                            </li>

                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_1">
                                <div style="padding: 10px 0px;">
                                    <label>Lista Prefijos</label>
                                    <button data-toggle="modal" data-target="#ModalNP" type="button" class="btn btn-success pull-right">
                                    <i class="fa fa-plus"></i> Nuevo Prefijo
                                    </button>

                                    <button data-toggle="modal" data-target="#ModalListaOperadora" type="button" class="btn btn-success pull-right">
                                    <i class="fa fa-list"></i> Operadoras
                                    </button>
                                </div>
                                <table id="example1" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="">#</th>
                                            <th class="">Prefijo</th>
                                            <th class="">Operador</th>
                                            <th class="">Costo compra</th>
                                            <th class="">Costo venta</th>  
                                            <th class="">Eliminar</th>                               
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

                <div class="modal fade" id="ModalNP">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h5 class="modal-title">Nuevo prefijo</h5>
                            </div>
                            <div class="modal-body">
                                <form role="form" class="FormP" id="FormP" action="" method="POST" autocomplete="off">
                                    <div class="form-group col-md-12">
                                        <label>Prefijo</label>
                                        <input class="form-control" type="text" name="prefijo" placeholder="Prefijo">
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
                                    
                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnGP"><i class="fa fa-save"></i> Guardar</button>
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

                <!-- Modal lista operadoras -->
                <div class="modal fade" id="ModalListaOperadora" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Operadoras</h5>
                            </div>
                            <div class="">
                                <div class="col-md-12">
                                    <div class="btn-group pull-right" style="padding: 10px;">
                                        <button data-toggle="modal" data-target="#ModalNuevaOperadora" type="button" class="btn btn-success">
                                            <i class="fa fa-plus"></i> Nuevo operadora
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <table id="lista_operadora" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th>Costo compra</th>
                                                <th>Costo venta</th>
                                                <th class="text-center">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            //optener todos los usuarios
                                                $query = mysqli_query($link, "SELECT * FROM costo");
                                                $i = 1;
                                                while($row = mysqli_fetch_array($query)){
                                                    echo '
                                                    <tr>
                                                    <td>'.$i.'</td>
                                                    <td>'.$row['operador'].'</td>
                                                    <td>'.$row['costo'].' $USD</td>
                                                    <td>'.$row['costo_venta'].' $USD</td>
                                                    <td class="text-center">
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
                                                                data-target="#ModalEditarOperador" 
                                                                data-nombre="'.$row['operador'].'"
                                                                data-costo1="'.$row['costo'].'"
                                                                data-costo2="'.$row['costo_venta'].'"
                                                                data-id="'.$row['id_costo'].'" class="btn1_operador">
                                                                <i class="fa fa-pencil"></i> Editar
                                                            </a>
                                                            </li>
                                                            <li>
                                                            <a href="#0" 
                                                                data-id="'.$row['id_costo'].'" class="btn2_operador">
                                                                <i class="fa fa-trash"></i> Eliminar
                                                            </a>
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
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal nuevo operador -->
                <div class="modal fade" id="ModalNuevaOperadora" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Nuevo operador</h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form1" class="FormNuevoOperador" action="" method="" autocomplete="off">
                                    <div class="form-group col-md-12">
                                        <label>Nombre operador</label>
                                        <input type="text" class="form-control" name="nombre" placeholder="Nombre operador">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Costo compra</label>
                                        <input type="text" class="form-control" name="costo1" placeholder="">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Costo venta</label>
                                        <input type="text" class="form-control" name="costo2" placeholder="">
                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnG_operador"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal Editar operador -->
                <div class="modal fade" id="ModalEditarOperador" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Editar operador</h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form1" class="FormEditarOperador" action="" method="" autocomplete="off">
                                    <div class="form-group col-md-12">
                                        <label>Nombre operador</label>
                                        <input type="text" class="form-control" name="nombre" id="nombre_operador" placeholder="Nombre operador">
                                        <input type="hidden" id="id_operador" name="id_operador">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Costo compra</label>
                                        <input type="text" class="form-control" name="costo1" id="costo_compra" placeholder="">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Costo venta</label>
                                        <input type="text" class="form-control" name="costo2" id="costo_venta" placeholder="">
                                    </div>

                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnA_operador"><i class="fa fa-save"></i> Guardar</button>
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
        
        <!-- page script -->
        <script>
            $(document).ready(function () {
                var columns = [
                    {
                        "targets": 0,
                        "render": function (data, type, row, meta) {
                            return row[0];
                        },
                    }, {
                        "targets": 1,
                        "render": function (data, type, row, meta) {
                            return row[1];
                        },
                    }, {
                        "targets": 2,
                        "render": function (data, type, row, meta) {
                            return row[2];
                        },
                    }, {
                        "targets": 3,
                        "render": function (data, type, row, meta) {
                            return row[3]+' $USD';
                        },
                    },
                    {
                        "targets": 4,
                        "render": function (data, type, row, meta) {
                            return row[4]+' $USD';
                        },
                    }, {
                        "targets": 5,
                        "render": function (data, type, row, meta) {
                            return '<a href="0#" style="color: #C71C22;" data-id="'+ row[0] +'" class="btnE"> <i class="fa fa-trash"></i> Eliminar</a>';
                        },
                    },
                ];

                $('#example1').DataTable({
                    "columns": columns,
                    "processing": true,
                    "serverSide": true,
                    "ajax": "ajax_table/ajax_table_prefijos.php"
                });
            });
        </script> 

        <script type="text/javascript">
            $(document).ready(function(){
                //guardar la usuario
                $(document).on('click', '#btnGP', function(e){
                  e.preventDefault();
                  $.ajax({
                    type: 'POST',
                    url: 'controller/g_prefijo.php',
                    data: $('.FormP').serialize(),
                    success: function(data) {
                      if (data == 'bien') {
                        not1();
                        setTimeout("location.href = 'prefijos.php'",3000);
                      } else{
                        not2();
                      }
                    }
                  });      
                });
              });
        </script>

        <script type="text/javascript">
            $(document).ready(function(){

                //Eliminar
                $(document).on('click', '.btnE', function(e){
                    e.preventDefault();
                    var id_prefijo = $(this).data('id');
                    var parametro = {
                        "id_prefijo" : id_prefijo
                    }
                    $.ajax({
                        type: 'POST',
                        url: 'controller/e_prefijo.php',
                        data: parametro,
                        success: function(data) {
                            if (data == 'bien') {
                                not4();
                                setTimeout("location.href = 'prefijos.php'",2000);
                            }else{
                                not5();
                            }
                        }
                    });      
                });
            });
        </script>

        <!-- tabla para operadora -->
        <script>
            $(function () {
                $('#lista_operadora').DataTable();
            });
        </script>

        <!-- para guardar el cargo -->
        <script type="text/javascript">
            $(document).ready(function(){

                //guardar la addressbook
                $(document).on('click', '#btnG_operador', function(e){
                    e.preventDefault();
                    $.ajax({
                    type: 'POST',
                    url: 'controller/g_operador.php',
                    data: $('.FormNuevoOperador').serialize(),
                    success: function(data) {
                        if (data == 'bien') {
                            not1();
                            setTimeout("location.href = 'prefijos.php'",3000);
                        } else{
                            not2();
                        }
                    }
                    });      
                });

                //mostrar la info
                $(document).on('click', '.btn1_operador', function(e){
                    e.preventDefault();
                    var id = $(this).data('id');
                    var nombre = $(this).data('nombre');
                    var costo1 = $(this).data('costo1');
                    var costo2 = $(this).data('costo2');

                    $('#id_operador').val(id);
                    $('#costo_compra').val(costo1);
                    $('#costo_venta').val(costo2);
                    $('#nombre_operador').val(nombre);
                });

                //editar
                $(document).on('click', '#btnA_operador', function(e){
                    e.preventDefault();
                    $.ajax({
                    type: 'POST',
                    url: 'controller/a_operador.php',
                    data: $('.FormEditarOperador').serialize(),
                    success: function(data) {
                        if (data == 'bien') {
                            not3();
                            setTimeout("location.href = 'prefijos.php'",3000);
                        }else{
                            not2();
                        }
                    }
                    });      
                });

                //Eliminar 
                $(document).on('click', '.btn2_operador', function(e){
                    e.preventDefault();
                    var id_operador = $(this).data('id');
                    var parametro = {
                    "id_operador" : id_operador
                    }
                    $.ajax({
                    type: 'POST',
                    url: 'controller/e_operador.php',
                    data: parametro,
                    success: function(data) {
                        if (data == 'bien') {
                            not4();
                            setTimeout("location.href = 'prefijos.php'",2000);
                        } else{
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