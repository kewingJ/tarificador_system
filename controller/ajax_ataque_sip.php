<?php
 	include_once '../includes/config.php';
    include_once '../includes/security.php';
    include_once '../geoIp/geoiploc.php';
    
    $fp = fopen("../sipban.log", "r");
    while(!feof($fp)) 
	{
		$linea = fgets($fp);
        if(!empty($linea))
        {
            //buscar en cada liena y separar os parametros
            $data = explode(' ', $linea);
            if (@$data[2] === 'BLOCKED' || @$data[2] === 'BLOCK') {
                //fecha de bloqueo
                $fecha_bloqueo = @$data[0].' '.@$data[1];
                $caracteres = array("[", "]");
                $fecha_bloqueo = str_replace($caracteres, "", $fecha_bloqueo);

                //trabajar con ataques que tengan un rango de 4 dias
                $fechaFin = date("Y-m-d H:i:s");
                $fechaInicio = date("Y-m-d H:i:s",strtotime($fechaFin."- 40 minutes"));

                if($fecha_bloqueo >= $fechaInicio && $fecha_bloqueo <= $fechaFin) 
                {
                    //ip de bloqueo
                    $ip_bloqueo = @$data[4];
                    $tipo_bloqueo = 'Sip brute-force';

                    //guardamos en base de datos
                    $fecha_r = date('y-m-d');
                    $guardar = mysqli_query($link,"INSERT INTO bloqueo_ataques VALUES (0,'$fecha_bloqueo','$ip_bloqueo','$tipo_bloqueo','$fecha_r')");
                }
            }
        }
    }
    fclose($fp);
?>