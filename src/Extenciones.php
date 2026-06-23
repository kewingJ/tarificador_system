<?php
include_once '../includes/config.php';
include_once '../includes/security.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

$ruta_archivo = '../src/datos.json';
$contenido_json = file_get_contents($ruta_archivo);
$output = $contenido_json; 

// $command = "curl -s -X GET -u $usuario_ari:$password_ari $ip_servidor_ari:8088/ari/endpoints";
// $output = shell_exec($command);

$datos = json_decode($output, true);

// verificar si el json es el mismo
$consultJson = mysqli_query($link,"SELECT * FROM lista_extencion");
$rowJson = mysqli_fetch_array($consultJson);
$data_json = $rowJson['data_json'];
$array1 = json_decode($data_json, true);
$stringLista = "";

if ($array1 != $datos) {
    
    if($datos != "[]" ){

        // actualizar el json
        $query = mysqli_query($link,"UPDATE lista_extencion SET data_json = '$output' WHERE id_extencion = 1") or die(mysqli_error($link));
        
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

            $stringLista .= '<div class="item col-md-2" style="margin: 5px 0px;">
                                <div class="profile-box content-box">
                                    <div class="content-box-header clearfix contenedorContacto" style="padding: 5px !important;">
                                        <img src="img/telephone.png" width="45" alt="" style="'.$color.'" class="img-bordered img-circle">
                                        <div class="user-details">
                                            '.$recurso.'<span>'.$nombre.'</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
        }
        echo $stringLista;
    } else {
        $query = mysqli_query($link,"UPDATE lista_extencion SET data_json = '$output' WHERE id_extencion = 1") or die(mysqli_error($link));
        echo "vacio";
    }
} else {
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

        $stringLista .= '<div class="item col-md-2" style="margin: 5px 0px;">
                            <div class="profile-box content-box">
                                <div class="content-box-header clearfix contenedorContacto" style="padding: 5px !important;">
                                    <img src="img/telephone.png" width="45" alt="" style="'.$color.'" class="img-bordered img-circle">
                                    <div class="user-details">
                                    '.$recurso.'<span>'.$nombre.'</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
    }
    echo $stringLista;
}
?>
