<?php
 	session_start();
 	require_once '../includes/auth_check.php';
 	require_ajax_auth_or_cli();
 	include_once '../includes/config.php';
    include_once '../includes/security.php';
    include_once '../geoIp/geoiploc.php';

    date_default_timezone_set('America/Managua');

    $thefolder = "../audio/salientes";
    if ($handler = opendir($thefolder)) {
        while (false !== ($file = readdir($handler))) {
            //
            $data = explode('.', $file);
		    if (@$data[1] === 'wav') {
                $nombreArchivo = @$data[0].'.'.@$data[1];
                $urlAudio = 'audio/salientes/'.$nombreArchivo;
                //echo $nombreArchivo.'<br>';
                
                //fecha
                $dataLlamada = explode('X', @$nombreArchivo);
                $fecha = @$dataLlamada[1];
                $fechaFormateada = DateTime::createFromFormat('YmdHis', $fecha)->format('Y-m-d H:i:s');
                // echo $fecha.'<br>';

                //optener datos del .log con rango de hora
                $fechaFin = date("Y-m-d H:i:s");
                $fechaInicio = date("Y-m-d H:i:s",strtotime($fechaFin."- 32 minutes"));

                // echo "inicio ".$fechaInicio.'<br>';
                // echo $fechaFin.'<br>';

                if(!empty($fechaFormateada) && $fechaFormateada >= $fechaInicio && $fechaFormateada <= $fechaFin) 
                {

                    //origen
                    $origen = @$dataLlamada[2];
                    //echo $origen.'<br>';

                    //destino
                    $destino = @$dataLlamada[3];
                    //echo $destino.'<br>';

                    //esto es para poder calcular el costo por operadora
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
                    
                    $operador = "";
                    //aqui verificamos a que operador pertenece
                    $query2 = mysqli_query($link,"SELECT prefijos.operador FROM prefijos 
                                                WHERE prefijos.prefijo = '$prefijo1' 
                                                AND prefijos.activo_p = 1");
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

                    //peso del audio
                    $path = $thefolder.'/'.$nombreArchivo;
                    $peso = filesize($path);

                    $units 	= array('B', 'KB', 'MB', 'GB', 'TB'); 
                    $bytes 	= max($peso, 0); 
                    $pow 	= floor(($bytes ? log($bytes) : 0) / log(1024)); 
                    $pow 	= min($pow, count($units) - 1); 
                    $bytes /= pow(1024, $pow);
                    $pesoAudio = round($bytes, 2) . ' ' . $units[$pow]; 

                    //verificar si el audio ya esta guardado
                    $consult = mysqli_query($link,"SELECT * FROM audios_llamadas 
                    WHERE audios_llamadas.nombre_audio = '$nombreArchivo'");
                    $total_audio = mysqli_num_rows($consult);
                    if ($total_audio == 0) 
                    {
                        $fecha_r = date('y-m-d');
                        $guardar = mysqli_query($link,"INSERT INTO audios_llamadas VALUES (0,'$urlAudio','$nombreArchivo','$fecha','$origen','$destino','$fecha_r','Saliente','$pesoAudio','$operador')");
                    }
                }
            }
        }
        closedir($handler);
    }

    $thefolder = "../audio/entrantes";
    if ($handler = opendir($thefolder)) {
        while (false !== ($file = readdir($handler))) {
            //
            $data = explode('.', $file);
		    if (@$data[1] === 'wav') {
                $nombreArchivo = @$data[0].'.'.@$data[1];
                $urlAudio = 'audio/entrantes/'.$nombreArchivo;
                //echo $nombreArchivo.'<br>';

                //fecha
                $dataLlamada = explode('-', @$nombreArchivo);
                $fechaAux = @$dataLlamada[0];
                $dia = substr($fechaAux, 0, 2);
                $mes = substr($fechaAux, 2, 2);
                $year = substr($fechaAux, 4, 5);
                $fecha = $year.''.$mes.''.$dia.''.@$dataLlamada[1];
                //echo $dia.'/'.$mes.'/'.$year.'<br>';
                $fechaFormateada = DateTime::createFromFormat('YmdHis', $fecha)->format('Y-m-d H:i:s');

                // echo $fechaFormateada.'<br>';


                //optener datos del .log con rango de hora
                $fechaFin = date("Y-m-d H:i:s");
                $fechaInicio = date("Y-m-d H:i:s",strtotime($fechaFin."- 32 minutes"));

                //echo $fechaInicio.'<br>';

                if(!empty($fechaFormateada) && $fechaFormateada >= $fechaInicio && $fechaFormateada <= $fechaFin) 
                {

                    //origen
                    $origen = @$dataLlamada[2];
                    //echo $origen.'<br>';

                    //destino
                    $dataDestino = explode('.', @$dataLlamada[3]);
                    $destino = @$dataDestino[0];
                    //echo $destino.'<br>';

                    //esto es para poder calcular el costo por operadora
                    $prefijo1 = '';
                    $numeroAux = $origen;
                    if (!empty($numeroAux)) {
                        $aux = $numeroAux[0];
                    }

                    //verificamos si el numero inicia con 9
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
                    //echo $prefijo1.'<br>';
                    $operador = "";
                    //aqui verificamos a que operador pertenece
                    $query2 = mysqli_query($link,"SELECT prefijos.operador FROM prefijos 
                                                WHERE prefijos.prefijo = '$prefijo1' 
                                                AND prefijos.activo_p = 1");
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

                    //peso del audio
                    $path = $thefolder.'/'.$nombreArchivo;
                    $peso = filesize($path);

                    $units 	= array('B', 'KB', 'MB', 'GB', 'TB'); 
                    $bytes 	= max($peso, 0); 
                    $pow 	= floor(($bytes ? log($bytes) : 0) / log(1024)); 
                    $pow 	= min($pow, count($units) - 1); 
                    $bytes /= pow(1024, $pow);
                    $pesoAudio = round($bytes, 2) . ' ' . $units[$pow]; 

                    //verificar si el audio ya esta guardado
                    $consult = mysqli_query($link,"SELECT * FROM audios_llamadas 
                    WHERE audios_llamadas.nombre_audio = '$nombreArchivo'");
                    $total_audio = mysqli_num_rows($consult);
                    if ($total_audio == 0) 
                    {
                        $fecha_r = date('y-m-d');
                        $guardar = mysqli_query($link,"INSERT INTO audios_llamadas VALUES (0,'$urlAudio','$nombreArchivo','$fecha','$origen','$destino','$fecha_r','Entrante','$pesoAudio','$operador')");
                    }
                }
            }
        }
        closedir($handler);
    }

    $thefolder = "../audio/internas";
    if ($handler = opendir($thefolder)) {
        while (false !== ($file = readdir($handler))) {
            //
            $data = explode('.', $file);
		    if (@$data[1] === 'wav') {
                $nombreArchivo = @$data[0].'.'.@$data[1];
                $urlAudio = 'audio/internas/'.$nombreArchivo;
                //echo $nombreArchivo.'<br>';
                
                //fecha
                $dataLlamada = explode('X', @$nombreArchivo);
                $fecha = @$dataLlamada[1];
                $fechaFormateada = DateTime::createFromFormat('YmdHis', $fecha)->format('Y-m-d H:i:s');
                //echo $fecha.'<br>';

                //optener datos del .log con rango de hora
                $fechaFin = date("Y-m-d H:i:s");
                $fechaInicio = date("Y-m-d H:i:s",strtotime($fechaFin."- 32 minutes"));

                //echo $fechaInicio.'<br>';

                if(!empty($fechaFormateada) && $fechaFormateada >= $fechaInicio && $fechaFormateada <= $fechaFin) 
                {

                    //origen
                    $origen = @$dataLlamada[2];
                    //echo $origen.'<br>';

                    //destino
                    $destino = @$dataLlamada[3];
                    //echo $destino.'<br>';

                    //esto es para poder calcular el costo por operadora
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
                    
                    $operador = "";
                    //aqui verificamos a que operador pertenece
                    $query2 = mysqli_query($link,"SELECT prefijos.operador FROM prefijos 
                                                WHERE prefijos.prefijo = '$prefijo1' 
                                                AND prefijos.activo_p = 1");
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

                    //peso del audio
                    $path = $thefolder.'/'.$nombreArchivo;
                    $peso = filesize($path);

                    $units 	= array('B', 'KB', 'MB', 'GB', 'TB'); 
                    $bytes 	= max($peso, 0); 
                    $pow 	= floor(($bytes ? log($bytes) : 0) / log(1024)); 
                    $pow 	= min($pow, count($units) - 1); 
                    $bytes /= pow(1024, $pow);
                    $pesoAudio = round($bytes, 2) . ' ' . $units[$pow]; 

                    //verificar si el audio ya esta guardado
                    $consult = mysqli_query($link,"SELECT * FROM audios_llamadas 
                    WHERE audios_llamadas.nombre_audio = '$nombreArchivo'");
                    $total_audio = mysqli_num_rows($consult);
                    if ($total_audio == 0) 
                    {
                        $fecha_r = date('y-m-d');
                        $guardar = mysqli_query($link,"INSERT INTO audios_llamadas VALUES (0,'$urlAudio','$nombreArchivo','$fecha','$origen','$destino','$fecha_r','Interna','$pesoAudio','$operador')");
                    }
                }
            }
        }
        closedir($handler);
        echo 'bien';
    }
?>