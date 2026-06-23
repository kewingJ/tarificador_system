<?php
include_once '../includes/config.php';
 
// DB table to use
$nombre = $_POST['busqueda'];
switch ($_POST['bandera']) {
    case 1:
        $table = <<<EOT
 (
    SELECT * FROM cdr_espejo
    WHERE nombre = '$nombre'
 ) temp
EOT;
    break;

    case 2:
        $table = <<<EOT
 (
    SELECT * FROM cdr_espejo
    WHERE origen = '$nombre'
 ) temp
EOT;
    break;

    case 3:
        $table = <<<EOT
 (
    SELECT * FROM cdr_espejo
    WHERE destino = '$nombre'
 ) temp
EOT;
    break;
}
 
// Table's primary key
$primaryKey = 'id_cdr_espejo';

$columns = array(
    array( 'db' => 'id_cdr_espejo', 'dt' => 0 ),
    array( 'db' => 'nombre', 'dt' => 1 ),
    array( 'db' => 'origen', 'dt' => 2 ),
    array( 'db' => 'destino',  'dt' => 3 ),
    array( 'db' => 'fecha_llamada', 'dt' => 4 ),
    array( 'db' => 'hora_llamada', 'dt' => 5 ),
    array( 'db' => 'estado', 'dt' => 6 ),
    array( 'db' => 'duracion', 'dt' => 7 )
);
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);