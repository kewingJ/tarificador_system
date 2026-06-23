<?php
    include_once '../includes/config.php';
    include_once '../includes/security.php';

    //limpiar
    $query = mysqli_query($link,"TRUNCATE grafica_principal") or die(mysqli_error($link));

    //optener operador
    $consultOperador = mysqli_query($link,"SELECT * FROM costo");
    while($rowsOperador = mysqli_fetch_array($consultOperador))
    {
        $operador = $rowsOperador['operador'];
        $consultLlamada = mysqli_query($link,"SELECT DISTINCT fecha_llamada AS fecha FROM cdr_espejo WHERE operador = '$operador'");
        while($rowsLlamada = mysqli_fetch_array($consultLlamada))
        {
            $fecha_llamada = $rowsLlamada['fecha'];

            $consultTotalLlamada = mysqli_query($link,"SELECT * FROM cdr_espejo
                                                   WHERE fecha_llamada = '$fecha_llamada'
                                                   AND operador = '$operador'");
            $totalPorFechaLlamada = mysqli_num_rows($consultTotalLlamada);
            // echo $operador.' - '.$fecha_llamada.' - '.$totalPorFechaLlamada.'<br>';

            // 
            if($operador == 'Claro'){
                $guardarTablaPrincipal = mysqli_query($link,"INSERT INTO grafica_principal VALUES (0,'$fecha_llamada', '$totalPorFechaLlamada', 0, 0, 0)");
            } else if($operador == 'Tigo'){
                // 
                $consultTablaPrincipal = mysqli_query($link,"SELECT * FROM grafica_principal WHERE fecha_llamada = '$fecha_llamada'");
                $totalTablaPrincipal = mysqli_num_rows($consultTablaPrincipal);

                if($totalTablaPrincipal > 0){
                    //actualizar valor del total
                    $queryUpdate = mysqli_query($link,"UPDATE grafica_principal SET total_tigo = '$totalPorFechaLlamada' WHERE fecha_llamada = '$fecha_llamada'");
                } else {
                    //insertar el nuevo valor
                    $guardarTablaPrincipal = mysqli_query($link,"INSERT INTO grafica_principal VALUES (0,'$fecha_llamada', 0, '$totalPorFechaLlamada', 0, 0)");
                }
            } else if($operador == 'Cootel'){
                // 
                $consultTablaPrincipal = mysqli_query($link,"SELECT * FROM grafica_principal WHERE fecha_llamada = '$fecha_llamada'");
                $totalTablaPrincipal = mysqli_num_rows($consultTablaPrincipal);

                if($totalTablaPrincipal > 0){
                    //actualizar valor del total
                    $queryUpdate = mysqli_query($link,"UPDATE grafica_principal SET total_cootel = '$totalPorFechaLlamada' WHERE fecha_llamada = '$fecha_llamada'");
                } else {
                    //insertar el nuevo valor
                    $guardarTablaPrincipal = mysqli_query($link,"INSERT INTO grafica_principal VALUES (0,'$fecha_llamada', 0, 0, '$totalPorFechaLlamada', 0)");
                }
            } else {
                // 
                $consultTablaPrincipal = mysqli_query($link,"SELECT * FROM grafica_principal WHERE fecha_llamada = '$fecha_llamada'");
                $totalTablaPrincipal = mysqli_num_rows($consultTablaPrincipal);

                if($totalTablaPrincipal > 0){
                    //actualizar valor del total
                    $queryUpdate = mysqli_query($link,"UPDATE grafica_principal SET total_convencional = '$totalPorFechaLlamada' WHERE fecha_llamada = '$fecha_llamada'");
                } else {
                    //insertar el nuevo valor
                    $guardarTablaPrincipal = mysqli_query($link,"INSERT INTO grafica_principal VALUES (0,'$fecha_llamada', 0, 0, 0, '$totalPorFechaLlamada')");
                }
            }
        }
    }
?>