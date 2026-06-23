<?php
    include_once '../includes/config.php';
    include_once '../includes/security.php';
    include_once '../geoIp/geoiploc.php';
    
    $consult = mysqli_query($link,"SELECT * FROM paises");

    while($rows = mysqli_fetch_array($consult))
    {
        $codigo_pais = $rows['iso'];
        $id_pais = $rows['id_pais'];
        $total_ataques = 0;
        $codigo_ip3 = '';
        
        //optener total de ataque por pais
        $query = mysqli_query($link,"SELECT * FROM bloqueo_ataques");
        while($bloqueo = mysqli_fetch_array($query))
        {
            $ip_ataque = $bloqueo["ip_bloqueo"];
            $codigo_ip = getCountryFromIP($ip_ataque, "code");
            if ($codigo_pais === $codigo_ip) {
                $total_ataques++;
                $codigo_ip3 = getCountryFromIP($ip_ataque, "AbBr");
            }
        }

        if ($total_ataques > 0) {
            $query = mysqli_query($link,"UPDATE bloqueo_pais SET total_bloqueo = '$total_ataques',
                iso3 = '$codigo_ip3'
                WHERE id_pais = '$id_pais'");
        }
    }
?>