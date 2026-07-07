<?php
// tabla cdr
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'db_cdra');
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD,DB_DATABASE) or die(mysqli_error($link));
@mysqli_query($link,"SET NAMES 'utf8'");     

// tabla asteriskcel
define('DB_SERVER2', 'localhost');
define('DB_USERNAME2', 'root');
define('DB_PASSWORD2', '');
define('DB_DATABASE2', 'asteriskcel');
$linkAsteriskcel = mysqli_connect(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2,DB_DATABASE2) or die(mysqli_error($linkAsteriskcel));
@mysqli_query($linkAsteriskcel,"SET NAMES 'utf8'");  

// datos para tablas
$sql_details = array(
    'user' => 'root',
    'pass' => '',
    'db'   => 'db_cdra',
    'host' => 'localhost'
);

// valores para api ari
$usuario_ari = 'kewinj';
$password_ari = 'zasld1wRA';
$ip_servidor_ari = '5.161.104.71';

//
$look_view_audio = true;

//activar licencia
$activar_licencia = true;
$devices = "90/100";

//activar modulos
$activar_sistema = true;

// tabla ejabberd
define('DB_DATABASE_EJABBERD', 'db_ejabberd');
define('DB_SERVER_EJABBERD', 'localhost');
define('DB_USERNAME_EJABBERD', 'root');
define('DB_PASSWORD_EJABBERD', '');

$linkEjabberd = mysqli_connect(DB_SERVER_EJABBERD, DB_USERNAME_EJABBERD, DB_PASSWORD_EJABBERD, DB_DATABASE_EJABBERD) or die(mysqli_error($linkEjabberd));
@mysqli_query($linkEjabberd,"SET NAMES 'utf8mb4'");

$sql_details_ejabberd = array(
    'user' => DB_USERNAME_EJABBERD,
    'pass' => DB_PASSWORD_EJABBERD,
    'db'   => DB_DATABASE_EJABBERD,
    'host' => DB_SERVER_EJABBERD
);

?>