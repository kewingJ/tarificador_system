<?php
    include_once '../includes/config.php';
    include_once '../includes/security.php';

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    // $ruta_archivo = '../src/datos.json';
    // $contenido_json = file_get_contents($ruta_archivo);
    // $output = $contenido_json; 

    $command = "curl -s -X GET -u $usuario_ari:$password_ari $ip_servidor_ari:8088/ari/endpoints";
    $output = shell_exec($command);

    $datos = json_decode($output, true);

    $json1 = $output;
    $json2 = '{"draw":0,"recordsTotal":0,"recordsFiltered":0,"data":[]}';

    $array1 = json_decode($json1, true);
    $array2 = json_decode($json2, true);

    foreach ($datos as $elemento) {
        $recurso = $elemento['resource'];
        $estado = $elemento['state'];

        $nombre = "";
        $consult = mysqli_query($link,"SELECT * FROM cdr_espejo WHERE origen = '$recurso' ORDER BY id_cdr DESC LIMIT 1");
        $row = mysqli_fetch_array($consult);
        if($row){
            $nombre = $row['nombre'];
        } else {
            $nombre = $recurso;
        }

        $color = '';
        if($estado == 'offline'){
            $color = 'border: 2px solid #808080';
        } else {
            $color = 'border: 2px solid #2ecc71';
        }

        $ruta_archivo = 'hints.json';
        $contenido_json = file_get_contents($ruta_archivo);
        $jsonString = $contenido_json; 
        $dataJson = json_decode($jsonString, true);

        // Inicializar un array para almacenar los resultados
        $state = "";
        $claveABuscar = $recurso;

        if (array_key_exists($claveABuscar, $dataJson)) {
            $state = $dataJson[$claveABuscar]['estado'];
        }

        if($state == 'Ringing'){
            $color = 'border: 2px solid #ee6715;';
        } else if($state == 'InUse'){
            $color = 'border: 2px solid #af0627;';
        }

        $img = '<img src="img/phone.png" width="50" alt="" style="'.$color.'" class="img-bordered img-circle">';

        // crear json
        $array2['data'][] = [count($array2['data']) + 1, $img.' '.$nombre."(".$recurso.")", $color];

    }
    $array2['recordsTotal'] = count($array2['data']);
    $array2['recordsFiltered'] = count($array2['data']);

    $jsonResult = json_encode($array2);

    echo $jsonResult;
?>
