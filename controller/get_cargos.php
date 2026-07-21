<?php
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	include_once '../includes/config.php';
    include_once '../includes/security.php';

    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    
    echo '<option value="">Cargo</option>';
    $query = mysqli_query($link, "SELECT * FROM tbla_book_cargo
                                            WHERE activo_cargo = 1");
    $i = 1;
    while($row = mysqli_fetch_array($query)){
        echo '<option value="'.(int) $row['id_cargo'].'">'.htmlspecialchars(utf8_encode($row['nombre_cargo']), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'</option>';
    }