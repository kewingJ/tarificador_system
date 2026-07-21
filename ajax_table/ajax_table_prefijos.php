<?php
    session_start();
    require_once '../includes/auth_check.php';
    require_ajax_auth();
    include_once '../includes/config.php';

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
//$table = 'prefijos';
$table = <<<EOT
 (
    SELECT
      a.id_prefijo,
      a.prefijo,
      a.operador,
      b.costo,
      b.costo_venta
    FROM prefijos as a
    INNER JOIN costo as b 
    ON a.operador = b.operador
    WHERE a.activo_p = 1
 ) temp
EOT;
 
// Table's primary key
$primaryKey = 'id_prefijo';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'id_prefijo', 'dt' => 0 ),
    array( 'db' => 'prefijo', 'dt' => 1 ),
    array( 'db' => 'operador', 'dt' => 2 ),
    array( 'db' => 'costo',  'dt' => 3 ),
    array( 'db' => 'costo_venta',  'dt' => 4 )
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