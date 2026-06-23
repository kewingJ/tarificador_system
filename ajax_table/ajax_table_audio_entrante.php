<?php
include_once '../includes/config.php';
 
// DB table to use
$tipo_audio = 'Entrante';
$table = <<<EOT
 (
    SELECT * FROM audios_llamadas
    WHERE audios_llamadas.tipo_audio = '$tipo_audio' AND peso_audio <> '44 B'
 ) temp
EOT;

// Table's primary key
$primaryKey = 'id_audio';
 
$columns = array(
    array( 'db' => 'id_audio',      'dt'    => 0 ),
    array( 'db' => 'fecha_audio',   'dt'    => 1 ),
    array( 'db' => 'origen',        'dt'    => 2 ),
    array( 'db' => 'destino',       'dt'    => 3 ),
    array( 'db' => 'url_audio',     'dt'    => 4 ),
    array( 'db' => 'peso_audio',    'dt'    => 5 ),
    array( 'db' => 'operador_audio','dt'    => 6 )
);
 
require( 'ssp.class.php' );

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);
?>