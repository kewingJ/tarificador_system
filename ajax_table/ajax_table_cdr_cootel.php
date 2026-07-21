<?php
session_start();
require_once '../includes/auth_check.php';
require_ajax_auth();
include_once '../includes/config.php';

$table = "cdr_espejo";
 
// Table's primary key
$primaryKey = 'id_cdr_espejo';
 

$columns = array(
    array( 'db' => 'id_cdr_espejo', 'dt'    => 0 ),
    array( 'db' => 'nombre',        'dt'    => 1 ),
    array( 'db' => 'origen',        'dt'    => 2 ),
    array( 'db' => 'destino',       'dt'    => 3 ),
    array( 'db' => 'fecha_llamada', 'dt'    => 4 ),
    array( 'db' => 'hora_llamada',  'dt'    => 5 ),
    array( 'db' => 'estado',        'dt'    => 6 ),
    array( 'db' => 'duracion',      'dt'    => 7 ),
    array( 'db' => 'operador',      'dt'    => 8 ),
    array( 'db' => 'id_cdr',        'dt'    => 9 )
);
 
require( 'ssp.class.php' );

$operador = 'cootel';
 
echo json_encode(
    SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "operador = '$operador' " )
);
?>