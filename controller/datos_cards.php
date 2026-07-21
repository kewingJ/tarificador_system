<?php
    session_start();
    require_once('../includes/auth_check.php');
    require_ajax_auth();

    require("../includes/config.php");
    require("../includes/security.php");

    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');

    $fecha1 = mysqli_real_escape_string($link, $_POST['fecha1']);
    $fecha2 = mysqli_real_escape_string($link, $_POST['fecha2']);

    $total_llamadas = 0;
    $entrantes = 0;
    $salientes = 0;
    $cont1 = 0;
    $cont2 = 0;
    $cont3 = 0;
    $cont4 = 0;
    $contE = 0;
    $contS = 0;
    // Total de llamadas
    $consulta = mysqli_query($link,"SELECT * FROM cdr WHERE calldate BETWEEN '$fecha1' AND '$fecha2'");
    while ($row = mysqli_fetch_array($consulta)) {
            //esto es para poder calcular el costo por operadora
            //origen de llamada
            $origen = $row['src'];
            
            //destino de llamada
            $destino = $row['dst'];
            
            $prefijo1 = '';
            // 1)Paso uno verificar si es llamada entrante
            if(strlen($origen) > strlen($destino)) {
                $prefijo1 = '';
                $numeroAux = $origen;
                if (!empty($numeroAux)) {
                    $aux = $numeroAux[0];
                }
                //verifcamos si el numero inicia con 9
                if ($aux == 9) {
                    $numero = substr($origen, 1, strlen($origen));
                } else {
                    $numero = $origen;
                }
                //Escenario si es (+505)
                if(!empty(strstr($numero, '+505'))) {
                    $prefijo1 = substr($numero, 4, 4);
                }
                //Escenario si es (505) o numero sin entrada nica
                if($prefijo1 == '') {
                    if(substr($numero, 0, 3) == '505') {
                        $prefijo1 = substr($numero, 3, 4);
                    } else {
                        $prefijo1 = substr($numero, 0, 4);
                    }
                }
                if($prefijo1 != ''){
                    $contE++;
                }
            }

            // 1)Paso uno verificar si es llamada saliente
            if(strlen($origen) < strlen($destino)) {
                $prefijo1 = '';
                $numeroAux = $destino;
                if (!empty($numeroAux)) {
                    $aux = $numeroAux[0];
                }
                //verifcamos si el numero inicia con 9
                if ($aux == 9) {
                    $numero = substr($destino, 1, strlen($destino));
                } else {
                    $numero = $destino;
                }
                //Escenario si es (+505)
                if(!empty(strstr($numero, '+505'))) {
                    $prefijo1 = substr($numero, 4, 4);
                }
                //Escenario si es (505) o numero sin entrada nica
                if($prefijo1 == '') {
                    if(substr($numero, 0, 3) == '505') {
                        $prefijo1 = substr($numero, 3, 4);
                    } else {
                        $prefijo1 = substr($numero, 0, 4);
                    }
                }
                if($prefijo1 != ''){
                    $contS++;
                }
            }

            $operador = "";
            //aqui verificamos a que operador pertenece
            $query2 = mysqli_query($link,"SELECT prefijos.operador FROM prefijos 
                                        WHERE prefijos.prefijo = '$prefijo1' AND prefijos.activo_p = 1");
            $row2 = mysqli_fetch_array($query2);
            if(!empty($row2['operador']))
            {
                $operador = $row2['operador'];
            }

            //origen de llamada
            $origen = $row['src'];
            
            //destino de llamada
            $destino = $row['dst'];

            if ($operador === 'Claro') {
                $cont1++;
            } else if ($operador === 'Tigo') {
                $cont2++;
            } else if ($operador === 'Cootel') {
                $cont3++;
            } else if ($operador === 'Convencional'){ 
                $cont4++;
            }
    }

    // $total_llamadas = $cont1 + $cont2 + $cont3 + $cont4 + $contE + $contS;
    $consultTotal = mysqli_query($link,"SELECT count(*) as total FROM cdr_espejo WHERE CONCAT(cdr_espejo.fecha_llamada,' ',cdr_espejo.hora_llamada) BETWEEN '$fecha1' AND '$fecha2'");
    $rowTotal = mysqli_fetch_array($consultTotal);
    $total_llamadas = $rowTotal['total'];
    // 
    $entrantes = $contE;
    $salientes = $contS;

    // Total de llamadas contestadas
    $total_contestadas = 0;
    $consult = mysqli_query($link,"SELECT count(*) as total FROM cdr_espejo WHERE estado = 'ANSWERED' AND CONCAT(cdr_espejo.fecha_llamada,' ',cdr_espejo.hora_llamada) BETWEEN '$fecha1' AND '$fecha2'");
    $row = mysqli_fetch_array($consult);
    $total_contestadas = $row['total'];
    // echo $total_contestadas;

    // Total de duracion
    $hora_texto = "00:00:00";
    $billsec = 0;
    $consultaDuracion = mysqli_query($link,"SELECT billsec FROM cdr WHERE calldate BETWEEN '$fecha1' AND '$fecha2'");
    while($rowDuracion = mysqli_fetch_array($consultaDuracion)){
        $billsec += $rowDuracion['billsec'];
    }
    $hora_texto = gmdate("H:i:s", $billsec);
    // echo $hora_texto;

    $datos = [
        "total_llamadas" => $total_llamadas,
        "entrantes"   => $entrantes,
        "salientes"  => $salientes,
        "total_contestadas"  => $total_contestadas,
        "hora_texto"  => $hora_texto,
    ];

    echo json_encode($datos);
?>
