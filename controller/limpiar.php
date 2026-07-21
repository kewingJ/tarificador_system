<?php
    session_start();
    require_once '../includes/auth_check.php';
    require_ajax_auth();
    include_once '../includes/config.php';
    include_once '../includes/security.php';
    include_once '../geoIp/geoiploc.php';

    //limpiar bloqueo
    $query1 = mysqli_query($link,"TRUNCATE bloqueo_ataques") or die(mysqli_error($link));
    //limpiar grafica
    $query2 = mysqli_query($link,"TRUNCATE grafica_bloqueo") or die(mysqli_error($link));
    //limpiar paises
    $query3 = mysqli_query($link,"UPDATE bloqueo_pais SET total_bloqueo = 0 ,iso3 = '' WHERE 1") or die(mysqli_error($link));
    //limpiar audios
    $query4 = mysqli_query($link,"TRUNCATE audios_llamadas") or die(mysqli_error($link));
?>