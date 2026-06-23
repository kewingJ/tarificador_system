<?php
 	include_once '../includes/config.php';
    include_once '../includes/security.php';
    include_once '../geoIp/geoiploc.php';

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    //guardar en grafica bloqueo
    // $query = mysqli_query($link,"TRUNCATE grafica_bloqueo") or die(mysqli_error($link));

    $valores = array("ssh brute-force", "Sip brute-force");

    // Recorrer el array con foreach
    foreach ($valores as $valor) {
        $tipoAtaque = $valor;

        // consultar la tabla de account para saber desde que fecha arrancar
        $consultGrafica = mysqli_query($link,"SELECT fecha_bloqueo FROM grafica_bloqueo ORDER BY fecha_bloqueo DESC LIMIT 1");
        $rowGrafica = mysqli_fetch_array($consultGrafica);
        $ultima_fecha = $rowGrafica['fecha_bloqueo'];

        if(!empty($ultima_fecha)){
            $consultBloqueo = mysqli_query($link,"SELECT DISTINCT CAST(bloqueo_ataques.fecha_bloqueo AS DATE) AS fecha FROM bloqueo_ataques WHERE CAST(bloqueo_ataques.fecha_bloqueo AS DATE) > '$ultima_fecha'");
        } else {
            $consultBloqueo = mysqli_query($link,"SELECT DISTINCT CAST(bloqueo_ataques.fecha_bloqueo AS DATE) AS fecha FROM bloqueo_ataques");
        }
        // $consultBloqueo = mysqli_query($link,"SELECT DISTINCT CAST(bloqueo_ataques.fecha_bloqueo AS DATE) AS fecha FROM bloqueo_ataques");
        while($rowsBloqueo = mysqli_fetch_array($consultBloqueo))
        {
            $fecha_bloqueo = $rowsBloqueo['fecha'];
            $consultTotalBloqueo = mysqli_query($link,"SELECT * FROM bloqueo_ataques
                                                    WHERE CAST(bloqueo_ataques.fecha_bloqueo AS DATE) = '$fecha_bloqueo' AND tipo_bloqueo = '$tipoAtaque'");
            $totalPorFechaBloqueo = mysqli_num_rows($consultTotalBloqueo);

            if($tipoAtaque == 'ssh brute-force'){
                $guardar = mysqli_query($link,"INSERT INTO grafica_bloqueo VALUES (0,'$fecha_bloqueo','$totalPorFechaBloqueo', 0)");
            } else if($tipoAtaque == 'Sip brute-force'){
                $consultTablaPrincipal = mysqli_query($link,"SELECT * FROM grafica_bloqueo WHERE fecha_bloqueo = '$fecha_bloqueo'");
                $totalTablaPrincipal = mysqli_num_rows($consultTablaPrincipal);

                if($totalTablaPrincipal > 0){
                    //actualizar valor del total
                    $queryUpdate = mysqli_query($link,"UPDATE grafica_bloqueo SET asterisk = '$totalPorFechaBloqueo' WHERE fecha_bloqueo = '$fecha_bloqueo'");
                } else {
                    //insertar el nuevo valor
                    mysqli_query($link,"INSERT INTO grafica_bloqueo VALUES (0,'$fecha_bloqueo',0, '$totalPorFechaBloqueo')");
                }
            }
        }
    }
?>