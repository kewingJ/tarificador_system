<?php
include_once '../includes/config.php';

// DB table to use
$table = <<<EOT
 (
    SELECT * FROM tbla_book_cargo
    WHERE activo_cargo = 1
 ) temp
EOT;
 
// Table's primary key
$primaryKey = 'id_cargo';

$columns = array(
    array( 'db' => 'id_cargo',        'dt'    => 0 ),
    array( 'db' => 'nombre_cargo',    'dt' => 1,
        'formatter' => function( $d, $row ) {
                return htmlspecialchars(utf8_encode($d), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        } 
    ),
    array( 'db' => 'id_cargo',        'dt'    => 2 )
);
 
require( 'ssp.class.php' );

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);
?>