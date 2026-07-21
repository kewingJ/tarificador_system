<?php
session_start();
require_once '../includes/auth_check.php';
require_ajax_auth();
include_once '../includes/config.php';

// DB table to use
$table = 'archive';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
$columns = array(
    array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'username', 'dt' => 1 ),
    array( 'db' => 'bare_peer', 'dt' => 2 ),
    array( 'db' => 'txt',  'dt' => 3 ),
    array( 'db' => 'created_at', 'dt' => 4 )
);

require( 'ssp.class.php' );

echo json_encode(
    SSP::simple( $_GET, $sql_details_ejabberd, $table, $primaryKey, $columns )
);
