<?php
session_start();
require_once '../includes/auth_check.php';
require_ajax_auth();
include_once '../includes/config.php';
 
// DB table to use
$table = 'tbla_extensiones';
 
// Table's primary key
$primaryKey = 'id_extension';

$columns = array(
    array(
        'db' => 'id_extension',
        'dt' => 0,
        'formatter' => function( $d, $row ) {
            $id = (int) $d;
            return '<input type="checkbox" class="ext-row-check" data-id="' . $id . '">';
        }
    ),
    array( 'db' => 'id_extension', 'dt' => 1 ),
    array( 'db' => 'aors', 'dt' => 2 ),
    array( 'db' => 'callerid', 'dt' => 3 ),
    array( 'db' => 'aors', 'dt' => 4 ),
    array( 'db' => 'id_extension', 'dt' => 5 )
);

require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);
