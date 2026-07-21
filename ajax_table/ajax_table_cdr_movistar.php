<?php
session_start();
require_once '../includes/auth_check.php';
require_ajax_auth();
include_once '../includes/config.php';

// DB table to use
$operador = 'tigo';
$table = <<<EOT
 (
    SELECT * FROM cdr_espejo
    WHERE cdr_espejo.operador = '$operador'
 ) temp
EOT;
 
// Table's primary key
$primaryKey = 'id_cdr_espejo';

$columns = array(
    array( 'db' => 'id_cdr',        'dt'    => 0 ),
    array( 'db' => 'id_cdr_espejo', 'dt'    => 1 ),
    array( 'db' => 'nombre',        'dt'    => 2 ),
    array( 'db' => 'origen',        'dt'    => 3 ),
    array( 'db' => 'destino',       'dt'    => 4 ),
    array( 'db' => 'fecha_llamada', 'dt'    => 5 ),
    array( 'db' => 'hora_llamada',  'dt'    => 6 ),
    array( 'db' => 'estado',        'dt'    => 7 ),
    array( 'db' => 'duracion',      'dt'    => 8 ),
    array( 'db' => 'operador',      'dt'    => 9 )
);
 
require( 'ssp.class.php' );

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);
?>