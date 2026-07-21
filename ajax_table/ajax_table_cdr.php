<?php
    session_start();
    require_once '../includes/auth_check.php';
    require_ajax_auth();
    include_once '../includes/config.php';
    include_once '../controller/return_information.php';
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'cdr_espejo';
 
// Table's primary key
$primaryKey = 'id_cdr_espejo';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'id_cdr', 'dt'           => 0 ),
    array( 'db' => 'id_cdr_espejo', 'dt'    => 1 ),
    array( 'db' => 'nombre', 'dt'           => 2 ),
    array( 'db' => 'origen', 'dt'           => 3 ),
    array( 'db' => 'destino',       'dt'    => 4,
        'formatter' => function( $d, $row ) {
            global $link;
            $resultado = "";
            $firstId = $row[0];
            $respuesta = procesarInfo($firstId, $link);
            if(empty($respuesta)) {
                $resultado = $d;
            } else {
                $resultado = $respuesta;
            }
            return $resultado;
        } 
    ),
    array( 'db' => 'fecha_llamada', 'dt'    => 5 ),
    array( 'db' => 'hora_llamada', 'dt'     => 6 ),
    array( 'db' => 'estado', 'dt'           => 7 ),
    array( 'db' => 'duracion', 'dt'         => 8 ),
    array( 'db' => 'operador', 'dt'         => 9 ),
    array( 'db' => 'transferencia', 'dt'    => 10 ),
    array( 'db' => 'pais_origen', 'dt'     => 11 ),
    array( 'db' => 'pais_destino', 'dt'    => 12 )
);
 
// SQL server connection information
// $sql_details = array(
//     'user' => 'root',
//     'pass' => '',
//     'db'   => 'tarificador',
//     'host' => 'localhost'
// );
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);