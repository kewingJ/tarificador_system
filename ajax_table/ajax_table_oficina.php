<?php
include_once '../includes/config.php';

// DB table to use
$table = <<<EOT
 (
    SELECT * FROM tbla_book_oficina
    WHERE activo_oficina = 1
 ) temp
EOT;
 
// Table's primary key
$primaryKey = 'id_oficina';

$columns = array(
    array( 'db' => 'id_oficina',        'dt'    => 0 ),
    array( 'db' => 'nombre_oficina',    'dt' => 1,
        'formatter' => function( $d, $row ) {
                return htmlspecialchars(utf8_encode($d), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        } 
    ),
    array( 'db' => 'id_oficina',        'dt'    => 2 )
);
 
require( 'ssp.class.php' );

header('Content-Type: application/json; charset=utf-8');
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);
?>