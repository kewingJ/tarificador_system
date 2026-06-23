<?php 
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
        echo '<option value="'.$row['id_cargo'].'">'.$row['nombre_cargo'].'</option>';
    }