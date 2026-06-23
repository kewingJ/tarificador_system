<?php
    include_once 'includes/config.php';
    include_once 'includes/security.php';    

    date_default_timezone_set('America/Managua');

    session_start();
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
    
    if (empty($id) || empty($activo) || $tipo != 1) {
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
        <title>Audios grabados</title>
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

        <script>
            function not6(){
                notif({
                    msg: "Datos actualizados!!",
                    type: "success",
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

            table.dataTable thead tr {
                background-color: #337ab7;
            }

            .table>thead:first-child>tr:first-child>th {
                color: #FFF;
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
                        Audios
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="listAudio.php"><i class="fa fa-file-audio-o"></i> Audios</a></li>
                    </ol>
                </section>
                <!-- Main content -->
                <section class="content">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">

                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">
                                <i class="fa fa-file-audio-o"></i> Audios Entrantes
                                </a>
                            </li>

                            <li>
                                <a href="#tab_2" data-toggle="tab" id="tabListaDos">
                                <i class="fa fa-file-audio-o"></i> Audios Salientes
                                </a>
                            </li>

                            <li>
                                <a href="#tab_3" data-toggle="tab" id="tabListaTres">
                                <i class="fa fa-file-audio-o"></i> Audios Internos
                                </a>
                            </li>

                            <li class="dropdown pull-right">
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
                                            <small> <i class="fa fa-trash"></i> Eliminar audios</small>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_1">
                                <div class="text-center">
                                    <div class="btn-group">
                                        <a href="#0" id="btn_eliminar" class="btn btn-danger btnDelete" style="display: none;">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </a>
                                        <a href="#0" id="btn_eliminar_entrante" class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Eliminar todos
                                        </a>
                                    </div>
                                </div>
                                <table id="example1" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                <input id="scales_check" class="editor-active" type="checkbox" name="scales_check" data-id="1">
                                            </th>
                                            <th class="text-center">Fecha y hora</th>
                                            <th class="text-center">Origen</th>
                                            <th class="text-center">Destino</th>
                                            <th class="text-center">Audio</th>
                                            <th class="text-center">Peso Audio</th>
                                            <th class="text-center">Operador</th>               
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane" id="tab_2">
                                <div class="text-center">
                                    <div class="btn-group">
                                        <a href="#0" id="btn_eliminar" class="btn btn-danger btnDelete" style="display: none;">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </a>
                                        <a href="#0" id="btn_eliminar_saliente" class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Eliminar todos
                                        </a>
                                    </div>
                                </div>
                                <table id="example2" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                <input id="scales_check2" class="editor-active" type="checkbox" name="scales_check" data-id="1">
                                            </th>
                                            <th class="text-center">Fecha y hora</th>
                                            <th class="text-center">Origen</th>
                                            <th class="text-center">Destino</th>
                                            <th class="text-center">Audio</th>
                                            <th class="text-center">Peso Audio</th>
                                            <th class="text-center">Operador</th>                 
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane" id="tab_3">
                                <div class="text-center">
                                    <div class="btn-group">
                                        <a href="#0" id="btn_eliminar" class="btn btn-danger btnDelete" style="display: none;">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </a>
                                        <a href="#0" id="btn_eliminar_interna" class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Eliminar todos
                                        </a>
                                    </div>
                                </div>
                                <table id="example3" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                <input id="scales_check3" class="editor-active" type="checkbox" name="scales_check" data-id="1">
                                            </th>
                                            <th class="text-center">Fecha y hora</th>
                                            <th class="text-center">Origen</th>
                                            <th class="text-center">Destino</th>
                                            <th class="text-center">Audio</th>
                                            <th class="text-center">Peso Audio</th>
                                            <th class="text-center">Operador</th>                    
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

                <div class="modal fade" id="modalE">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h5 class="modal-title">Eliminar</h5>
                            </div>
                            <div class="modal-body">
                                <form id="Form1" class="FormB" action="" method="" autocomplete="off">
                                    <h4 class="text-center">Eliminar audios despues de 30 dias</h4>
                                    <div class="form-group col-md-12">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-list"></i>
                                            </div>
                                            <select class="form-control" name="opc">
                                                <option value="si">Si</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" type="reset"><i class="fa fa-close"></i> Cancelar</button>
                                        <button class="btn btn-primary" type="button" id="btnE"><i class="fa fa-trash"></i> Guardar</button>
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

        <!-- para tabla uno -->
        <script type="text/javascript">
            $(document).ready(function () {
                var contador = 0;
                var columns = [
                    {
                        "targets": 0,
                        "render": function (data, type, row, meta) {
                            return '<input id="scales" class="editor-active scales-'+ contador++ +'" type="checkbox" name="scales" data-id="'+row[0]+'">';
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 1,
                        "render": function (data, type, row, meta) {
                            return row[1];
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 2,
                        "render": function (data, type, row, meta) {
                            return row[2];
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 3,
                        "render": function (data, type, row, meta) {
                            return row[3];
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 4,
                        "render": function (data, type, row, meta) {
                            return '<audio controls style="width: 300px;height: 30px;"><source src="'+row[4]+'" type="audio/wav">Your browser does not support the audio element.</audio>';
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 5,
                        "render": function (data, type, row, meta) {
                            return row[5];
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 6,
                        "render": function (data, type, row, meta) {
                            return row[6];
                        },
                        className: "dt-body-center text-center"
                    },
                ];

                let idEspejo = [];
                //check las primeras 10 filas
                $(document).on('change', '#scales_check', function(e){
                    e.preventDefault();

                    checked = $(this).prop('checked');

                    var cols = table1.column(0).nodes(),
                        state = this.checked;

                    if(checked == true) {

                        for (var i = 0; i < cols.length; i ++) {
                            cols[i].querySelector("#scales").checked = state;
                            var id_cdr = $('.scales-'+i).data('id');
                            
                            if(idEspejo.includes(id_cdr) == false){
                                idEspejo.push(id_cdr);
                            }
                        }

                    } else {
                        for (var i = 0; i < cols.length; i ++) {
                            cols[i].querySelector("#scales").checked = false;
                            var id_cdr = $('.scales-'+i).data('id');
                            
                            let pos = idEspejo.indexOf(id_cdr);
                            idEspejo.splice(pos, 1);
                        }
                    }

                    //mostra boton eliminar
                    if(idEspejo.length > 0) {
                        $('.btnDelete').css("display", "block");
                    } else {
                        $('.btnDelete').css("display", "none");
                    }
                    //console.log(id_cdr);
                    console.log(idEspejo);
                });

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
                    // console.log(cb);
                    // console.log(id_cdr);
                    // console.log(idEspejo);
                });

                //
                $(document).on('click', '#btn_eliminar', function(e){
                    e.preventDefault();
                    var id_cdr = idEspejo;
                    var parametro = {
                        "id_audio" : id_cdr
                    }
                    //alert(id_cdr);
                    $.ajax({
                        type: 'POST',
                        url: 'controller/e_audio.php',
                        data: parametro,
                        success: function(data) {
                            if (data == 'bien') {
                                //alert(data);
                                not4();
                                setTimeout("location.href = 'listAudio.php'",2000);
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
                     "fixedHeader": true,
                     "searching": true,
                     "columns": columns,
                     "processing": true,
                     "serverSide": true,
                     "scrollX": true,
                     "ajax": "ajax_table/ajax_table_audio_entrante.php",
                });
            });
        </script>

        <!-- para tabla dos -->
        <script type="text/javascript">
            $(document).on('click','#tabListaDos',function() {
                var contador = 0;
                var columns = [
                    {
                        "render": function (data, type, row, meta) {
                            return '<input id="scales2" class="editor-active scales2-'+ contador++ +'" type="checkbox" name="scales" data-id="'+row[0]+'">';
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 1,
                        "render": function (data, type, row, meta) {
                            return row[1];
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 2,
                        "render": function (data, type, row, meta) {
                            return row[2];
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 3,
                        "render": function (data, type, row, meta) {
                            return row[3];
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 4,
                        "render": function (data, type, row, meta) {
                            return '<audio controls style="width: 300px;height: 30px;"><source src="'+row[4]+'" type="audio/wav">Your browser does not support the audio element.</audio>';
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 5,
                        "render": function (data, type, row, meta) {
                            return row[5];
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 6,
                        "render": function (data, type, row, meta) {
                            return row[6];
                        },
                        className: "dt-body-center text-center"
                    },
                ];

                let idEspejo = [];
                //check las primeras 10 filas
                $(document).on('change', '#scales_check2', function(e){
                    e.preventDefault();

                    checked = $(this).prop('checked');

                    var cols = table2.column(0).nodes(),
                        state = this.checked;

                    if(checked == true) {

                        for (var i = 0; i < cols.length; i ++) {
                            cols[i].querySelector("#scales2").checked = state;
                            var id_cdr = $('.scales2-'+i).data('id');
                            
                            if(idEspejo.includes(id_cdr) == false){
                                idEspejo.push(id_cdr);
                            }
                        }

                    } else {
                        for (var i = 0; i < cols.length; i ++) {
                            cols[i].querySelector("#scales2").checked = false;
                            var id_cdr = $('.scales2-'+i).data('id');
                            
                            let pos = idEspejo.indexOf(id_cdr);
                            idEspejo.splice(pos, 1);
                        }
                    }

                    //mostra boton eliminar
                    if(idEspejo.length > 0) {
                        $('.btnDelete').css("display", "block");
                    } else {
                        $('.btnDelete').css("display", "none");
                    }
                    //console.log(id_cdr);
                    console.log(idEspejo);
                });
                
                $(document).on('change', '#scales2', function(e){
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
                        "id_audio" : id_cdr
                    }
                    //alert(id_cdr);
                    $.ajax({
                        type: 'POST',
                        url: 'controller/e_audio.php',
                        data: parametro,
                        success: function(data) {
                            if (data == 'bien') {
                                //alert(data);
                                not4();
                                setTimeout("location.href = 'listAudio.php'",2000);
                            }else {
                                //alert(data);
                                not5();
                            }
                        }
                    }); 
                });

                //
                $('#example2 thead tr').clone(true).appendTo( '#example2 thead' );
                $('#example2 thead tr:eq(1) th').each( function (i) {
                    var title = $(this).text();
                    $(this).html( '<input type="text" placeholder=" '+title+'" />' );
 
                    $( 'input', this ).on( 'keyup change', function () {
                        if ( table2.column(i).search() !== this.value ) {
                            table2
                            .column(i)
                            .search( this.value )
                            .draw();
                        }
                    });
                });

                var table2 = $('#example2').DataTable({
                     "fixedHeader": true,
                     "columns": columns,
                     "processing": true,
                     "serverSide": true,
                     "scrollX": true,
                     "bDestroy": true,
                     "ajax": "ajax_table/ajax_table_audio_saliente.php",
                });
            });
        </script>

        <!-- para tabla tres -->
        <script type="text/javascript">
            $(document).on('click','#tabListaTres',function() {
                var contador = 0;
                var columns = [
                    {
                        "render": function (data, type, row, meta) {
                            return '<input id="scales3" class="editor-active scales3-'+ contador++ +'" type="checkbox" name="scales" data-id="'+row[0]+'">';
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 1,
                        "render": function (data, type, row, meta) {
                            return row[1];
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 2,
                        "render": function (data, type, row, meta) {
                            return row[2];
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 3,
                        "render": function (data, type, row, meta) {
                            return row[3];
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 4,
                        "render": function (data, type, row, meta) {
                            return '<audio controls style="width: 300px;height: 30px;"><source src="'+row[4]+'" type="audio/wav">Your browser does not support the audio element.</audio>';
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 5,
                        "render": function (data, type, row, meta) {
                            return row[5];
                        },
                        className: "dt-body-center text-center"
                    }, {
                        "targets": 6,
                        "render": function (data, type, row, meta) {
                            return row[6];
                        },
                        className: "dt-body-center text-center"
                    },
                ];

                let idEspejo = [];
                //check las primeras 10 filas
                $(document).on('change', '#scales_check3', function(e){
                    e.preventDefault();

                    checked = $(this).prop('checked');

                    var cols = table3.column(0).nodes(),
                        state = this.checked;

                    if(checked == true) {

                        for (var i = 0; i < cols.length; i ++) {
                            cols[i].querySelector("#scales3").checked = state;
                            var id_cdr = $('.scales3-'+i).data('id');
                            
                            if(idEspejo.includes(id_cdr) == false){
                                idEspejo.push(id_cdr);
                            }
                        }

                    } else {
                        for (var i = 0; i < cols.length; i ++) {
                            cols[i].querySelector("#scales3").checked = false;
                            var id_cdr = $('.scales3-'+i).data('id');
                            
                            let pos = idEspejo.indexOf(id_cdr);
                            idEspejo.splice(pos, 1);
                        }
                    }

                    //mostra boton eliminar
                    if(idEspejo.length > 0) {
                        $('.btnDelete').css("display", "block");
                    } else {
                        $('.btnDelete').css("display", "none");
                    }
                    //console.log(id_cdr);
                    console.log(idEspejo);
                });

                $(document).on('change', '#scales3', function(e){
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
                        "id_audio" : id_cdr
                    }
                    //alert(id_cdr);
                    $.ajax({
                        type: 'POST',
                        url: 'controller/e_audio.php',
                        data: parametro,
                        success: function(data) {
                            if (data == 'bien') {
                                //alert(data);
                                not4();
                                setTimeout("location.href = 'listAudio.php'",2000);
                            }else {
                                //alert(data);
                                not5();
                            }
                        }
                    }); 
                });

                //
                $('#example3 thead tr').clone(true).appendTo( '#example3 thead' );
                $('#example3 thead tr:eq(1) th').each( function (i) {
                    var title = $(this).text();
                    $(this).html( '<input type="text" placeholder=" '+title+'" />' );
 
                    $( 'input', this ).on( 'keyup change', function () {
                        if ( table3.column(i).search() !== this.value ) {
                            table3
                            .column(i)
                            .search( this.value )
                            .draw();
                        }
                    });
                });

                var table3 = $('#example3').DataTable({
                     "fixedHeader": true,
                     "columns": columns,
                     "processing": true,
                     "serverSide": true,
                     "scrollX": true,
                     "bDestroy": true,
                     "ajax": "ajax_table/ajax_table_audio_interno.php",
                });
            });
        </script>

        <script>
            $(document).ready(function () {
              $('.sidebar-menu').tree()
            });
        </script>

        <!-- para actualizar-->
        <script type="text/javascript">
            $(document).ready(function(){
                $(document).on('click', '#update', function(e){
                    e.preventDefault();
                    $.ajax({
                    url: 'controller/ajax_audio.php', 
                        success: function (result) {
                            if (result == 'bien') {
                                //alert(result);
                                not6();
                                setTimeout("location.href = 'listAudio.php'", 3000);
                            } else {
                                //alert(result);
                                not5();
                            }
                        }
                    })
                });
            });
        </script>

        <!-- para eliminar audios entrantes-->
        <script type="text/javascript">
            $(document).ready(function(){
                $(document).on('click', '#btn_eliminar_entrante', function(e){
                    e.preventDefault();
                    var tipo_audio = 'Entrante';
                    var parametro = {
                        "tipo_audio" : tipo_audio
                    }
                    //alert(id_cdr);
                    $.ajax({
                        type: 'POST',
                        url: 'controller/e_audio_entrante.php',
                        data: parametro,
                        success: function(data) {
                            if (data == 'bien') {
                                //alert(data);
                                not4();
                                setTimeout("location.href = 'listAudio.php'",2000);
                            }else {
                                //alert(data);
                                not5();
                            }
                        }
                    }); 
                });
            });
        </script>

        <!-- para eliminar audios salientes-->
        <script type="text/javascript">
            $(document).ready(function(){
                $(document).on('click', '#btn_eliminar_saliente', function(e){
                    e.preventDefault();
                    var tipo_audio = 'Saliente';
                    var parametro = {
                        "tipo_audio" : tipo_audio
                    }
                    //alert(id_cdr);
                    $.ajax({
                        type: 'POST',
                        url: 'controller/e_audio_saliente.php',
                        data: parametro,
                        success: function(data) {
                            if (data == 'bien') {
                                //alert(data);
                                not4();
                                setTimeout("location.href = 'listAudio.php'",2000);
                            }else {
                                //alert(data);
                                not5();
                            }
                        }
                    }); 
                });
            });
        </script>

        <!-- para eliminar audios internos-->
        <script type="text/javascript">
            $(document).ready(function(){
                $(document).on('click', '#btn_eliminar_interna', function(e){
                    e.preventDefault();
                    var tipo_audio = 'Interna';
                    var parametro = {
                        "tipo_audio" : tipo_audio
                    }
                    //alert(id_cdr);
                    $.ajax({
                        type: 'POST',
                        url: 'controller/e_audio_interno.php',
                        data: parametro,
                        success: function(data) {
                            if (data == 'bien') {
                                //alert(data);
                                not4();
                                setTimeout("location.href = 'listAudio.php'",2000);
                            }else {
                                //alert(data);
                                not5();
                            }
                        }
                    }); 
                });
            });
        </script>

        <!-- opcion eliminar audios que ya no esten en archivos -->
        <script type="text/javascript">
            $('#btnE').click(function () {
                $.ajax({
                  url: 'controller/g_opcion_audio.php',
                  type: 'POST',
                  data: $('.FormB').serialize(),
                    success: function (result) {
                        not3();
                        setTimeout("location.href = 'listAudio.php'",3000);
                    }
                })
            });
        </script>

    </body>
</html>