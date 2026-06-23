<?php
    include_once '../includes/config.php';
    include_once '../includes/security.php';

    //tabla original
    $sql1 = mysqli_query($link,"SELECT count(id) AS total FROM cdr");
    $conteo1 = mysqli_fetch_array($sql1);
    $id_actual_cdr = $conteo1['total'];

    $contE = 0;
    $contS = 0;
    $cont1 = 0;
    $cont2 = 0;
    $cont3 = 0;
    $cont4 = 0;

    //tabla espejo
    $sql2 = mysqli_query($link,"SELECT count(id_cdr) AS total FROM cdr_espejo");
    $conteo2 = mysqli_fetch_array($sql2);
    $id_actual_cdr_espejo = $conteo2['total'];

    //si los valores no coinsiden es porque hay un cambio en la base de datos
    if ($id_actual_cdr != $id_actual_cdr_espejo) {
        //pamos a limpiar la tabla espejo para el cambio
        $query_truncate = mysqli_query($link,"TRUNCATE cdr_espejo") or die(mysqli_error($link));

        //pasamos a obtener la nueva data y almacenarla
        $consulta = mysqli_query($link,"SELECT * FROM cdr 
                                        WHERE cdr.calldate <> '0000-00-00 00:00:00'
                                        ORDER BY cdr.calldate DESC");
        while ($row = mysqli_fetch_array($consulta)) 
        {
            //id del cdr
            $id = $row['id'];

            //modificar el formato de clid que es el nombre del cliente
            $nombre = $row['clid'];
            $nombre = explode('"', $nombre);
            @$nombre = $nombre[1];
            
            //origen de llamada
            $origen = $row['src'];
            
            //destino de llamada
            $destino = $row['dst'];
            
            //estado de la llamada
            $estado = $row['disposition'];

            //modificamos el formato de la fecha
            $date = $row['calldate'];
            $fecha = date('Y/m/d',strtotime($date));

            //obtener la hora de llamada
            $hora = date('H:i:s',strtotime($date));
            
            //calculamos el total de duracion de la llamada.
            $tiempo_en_segundos = $row['billsec']; //este es el total de segundos obtenidos desde la base de datos
            
            //formato de hora 1:20:2s
            $consultaDuracion = mysqli_query($link,"SELECT SEC_TO_TIME(cdr.billsec) AS duracion 
                                            FROM cdr 
                                            WHERE cdr.id = '$id' ");
            $rowDuracion = mysqli_fetch_array($consultaDuracion);

            $hora_texto = $rowDuracion['duracion'];  
            //$hora_texto = gmdate("H:i:s", $tiempo_en_segundos);

            //Esto es para saber el operador del numero
            $prefijo1 = '';
            $numero = '';
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
            } else {
                if(strlen($numero) >= 12) {
                    //verificar si el numero es internacional
                    for($i = 0; $i <= 4; $i++) {
                        $queryInter = mysqli_query($link,"SELECT * FROM codigo_prefijo 
                                                        ORDER BY numero_prefijoUno ASC");
                        while($rowInter = mysqli_fetch_array($queryInter)) {
                            $cadena = substr($prefijo1, 0, $i);
                            if($cadena == $rowInter['numero_prefijoUno']) {
                                $operador = $rowInter['pais_prefijo'];
                                //echo $operador.'<br>';
                            }
                        }
                    }
                }
            }
            
            $precio = "";
            if(!empty($operador))
            {
                //optener el costo por operadora
                $query3 = mysqli_query($link,"SELECT * FROM costo WHERE operador = '$operador'");
                $row3 = mysqli_fetch_array($query3);
                if(!empty($row3['costo']))
                {
                    $precio = $row3['costo'];
                }
            }

            if ($operador === 'Claro') {
                $cont1++;
            } else if ($operador === 'Tigo') {
                $cont2++;
            } else if ($operador === 'Cootel') {
                $cont3++;
            } else if ($operador === 'Convencional'){ 
                $cont4++;
            }

            if (!empty($origen)) {
                $auxOrigen = $origen[0];
            }

            if (!empty($destino)) {
                $auxDestino = $destino[0];
            }

            if ($auxOrigen == 9) {
                $origenG = substr($origen, 1, strlen($origen));
            } else {
                $origenG = $origen;
            }

            if ($auxDestino == 9) {
                $destinoG = substr($destino, 1, strlen($destino));
            } else {
                $destinoG = $destino;
            }

            $pais_origen = obtenerPaisNumero($origenG, $link);
            $pais_destino = obtenerPaisNumero($destinoG, $link);

            // nuevo campo transferencia
            $uniqueid = $row['uniqueid'];
            $queryTransferencia = mysqli_query($linkAsteriskcel,"SELECT * FROM cel WHERE uniqueid = '$uniqueid' AND eventtype = 'BLINDTRANSFER'");
            $rowTransferencia = mysqli_fetch_array($queryTransferencia);
            if($rowTransferencia > 0){
                $jsonDatos = $rowTransferencia['extra'];
                $datos = json_decode($jsonDatos, true);
                $extension = $datos['extension'];
                $linkedid = $rowTransferencia['linkedid'];

                $transferencia = '{"extension":"'.$extension.'","linkedid":"'.$linkedid.'"}';
            } else {
                $transferencia = "";
            }
            
            $query_guardar = mysqli_query($link,"INSERT INTO cdr_espejo (id_cdr_espejo,id_cdr,nombre,origen,destino,estado,fecha_llamada,hora_llamada,costo,duracion,operador,transferencia,pais_origen,pais_destino) VALUES (0,'$id',
                                                                                                                                                                                   '$nombre',
                                                                                                                                                                                   '$origenG',
                                                                                                                                                                                   '$destinoG',
                                                                                                                                                                                   '$estado',
                                                                                                                                                                                   '$fecha',
                                                                                                                                                                                   '$hora',
                                                                                                                                                                                   '$precio',
                                                                                                                                                                                   '$hora_texto',
                                                                                                                                                                                   '$operador',
                                                                                                                                                                                   '$transferencia',
                                                                                                                                                                                   '$pais_origen',
                                                                                                                                                                                   '$pais_destino')") or die(mysqli_error($link));
        }

        $query = mysqli_query($link,"UPDATE estadistica_llamadas SET total_claro = '$cont1',
                                                                     total_movistar = '$cont2',
                                                                     total_cootel = '$cont3',
                                                                     total_convencional = '$cont4',
                                                                     total_entrante = '$contE',
                                                                     total_saliente = '$contS' 
                                                                     WHERE id_estadistica = 1") or die(mysqli_error($link));


        echo 'BIEN';
    } 
    else {
        //para actualizar la data de la estadisticas
        $consulta = mysqli_query($link,"SELECT * FROM cdr 
                                        WHERE cdr.calldate <> '0000-00-00 00:00:00'
                                        ORDER BY cdr.calldate DESC");
        while ($row = mysqli_fetch_array($consulta)) 
        {
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

            $query = mysqli_query($link,"UPDATE estadistica_llamadas SET total_claro = '$cont1',
                                                                     total_movistar = '$cont2',
                                                                     total_cootel = '$cont3',
                                                                     total_convencional = '$cont4',
                                                                     total_entrante = '$contE',
                                                                     total_saliente = '$contS' 
                                                                     WHERE id_estadistica = 1") or die(mysqli_error($link));
        }
        echo 'BIEN';
    }

    function obtenerPaisNumero($numero, $link) {
        static $prefijosPais = null;
        static $extensionesInternas = null;

        if ($prefijosPais === null) {
            $prefijosPais = array();
            $queryPrefijos = mysqli_query($link, "SELECT numero_prefijoUno, pais_prefijo FROM codigo_prefijo ORDER BY CHAR_LENGTH(numero_prefijoUno) DESC, numero_prefijoUno DESC");
            while ($rowPrefijo = mysqli_fetch_array($queryPrefijos)) {
                $prefijo = (string)$rowPrefijo['numero_prefijoUno'];
                if (!isset($prefijosPais[$prefijo])) {
                    $prefijosPais[$prefijo] = $rowPrefijo['pais_prefijo'];
                }
            }
        }

        if ($extensionesInternas === null) {
            $extensionesInternas = array();
            $queryExtensiones = mysqli_query($link, "SELECT extension FROM tbla_extensiones");
            while ($rowExtension = mysqli_fetch_array($queryExtensiones)) {
                $extension = preg_replace('/\D/', '', (string)$rowExtension['extension']);
                if ($extension !== '') {
                    $extensionesInternas[$extension] = true;
                }
            }
        }

        $numero = trim((string)$numero);
        if ($numero === '') {
            return '';
        }

        $soloDigitos = preg_replace('/\D/', '', $numero);
        if ($soloDigitos === '') {
            return '';
        }

        if (isset($extensionesInternas[$soloDigitos]) || strlen($soloDigitos) <= 5) {
            return 'Interno';
        }

        if (strlen($soloDigitos) == 8) {
            return 'Nicaragua';
        }

        if (substr($soloDigitos, 0, 2) === '00') {
            $soloDigitos = substr($soloDigitos, 2);
        }

        foreach ($prefijosPais as $prefijo => $pais) {
            if (strncmp($soloDigitos, $prefijo, strlen($prefijo)) === 0) {
                return $pais;
            }
        }

        if (substr($soloDigitos, 0, 3) === '505') {
            return 'Nicaragua';
        }

        return 'Desconocido';
    }
?>
