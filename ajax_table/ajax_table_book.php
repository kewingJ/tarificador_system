<?php
session_start();
require_once '../includes/auth_check.php';
require_ajax_auth();
include_once '../includes/config.php';

// DB table to use
$table = <<<EOT
 (
    SELECT * FROM tbla_book_phonebook
    INNER JOIN tbla_book_tipo_extencion
    ON tbla_book_phonebook.type = tbla_book_tipo_extencion.id_tipo_ex
    WHERE activo_p = 1 ORDER BY tbla_book_phonebook.id_phonebook DESC
 ) temp
EOT;
 
// Table's primary key
$primaryKey = 'id_oficina';

$columns = array(
    array( 'db' => 'id_phonebook',      'dt'    => 0 ),
    array( 'db' => 'genero',            'dt' => 1,
        'formatter' => function( $d, $row ) {
            $imagen = "";
            switch ($d) {
                case 'male':
                    $imagen = "img/avatar2.png";
                    break;
                case 'female':
                    $imagen = "img/avatar1.png";
                    break;
                default:
                    $imagen = "img/avatar2.png";
                    break;
            }
            return '<img src="'.$imagen.'" style="width: 40px; height: 40px; object-fit: cover;" class="img-circle" alt="user image">';
        } 
    ),
    array( 'db' => 'first_name',        'dt' => 2,
        'formatter' => function( $d, $row ) {
                return htmlspecialchars(utf8_encode($d), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        } 
    ),
    array( 'db' => 'last_name',        'dt' => 3,
        'formatter' => function( $d, $row ) {
                return htmlspecialchars(utf8_encode($d), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        } 
    ),
    array( 'db' => 'phone_number',      'dt' => 4,
        'formatter' => function( $d, $row ) {
                return htmlspecialchars(utf8_encode($d), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
    ),
    array( 'db' => 'account_index',     'dt' => 5,
        'formatter' => function( $d, $row ) {
                return htmlspecialchars(utf8_encode($d), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
    ),
    array( 'db' => 'nombre_ex',         'dt'    => 6 ),
    array( 'db' => 'id_phonebook',      'dt'    => 7 )
);
 
require( 'ssp.class.php' );

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);
?>