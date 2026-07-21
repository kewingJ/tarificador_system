<?php
    session_start();
    require_once '../includes/auth_check.php';
    require_ajax_auth();
    include_once '../includes/config.php';
    include_once '../includes/security.php';

    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');

    $command = "curl -s --connect-timeout 3 --max-time 5 -X GET -u $usuario_ari:$password_ari $ip_servidor_ari:8088/ari/channels";
    $output = shell_exec($command);

    $json_data = $output;

    $resultadoString = "";
    $data = json_decode((string) $json_data, true);
    if(!empty($data) && is_array($data))
    {
        $contador = 1;

        $resultadoString .= '
        <table id="example3" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>De</th>
                    <th>Para</th>
                </tr>
            </thead>
            <tbody>
        ';
        // Recorrer cada elemento del arreglo
        foreach ($data as $item) {
            // Obtener los datos del caller y connected para cada elemento
            $caller_name = $item['caller']['name'];
            $caller_number = $item['caller']['number'];
            
            $connected_name = $item['connected']['name'];
            $connected_number = $item['connected']['number'];
            
            $color = "";
            if($contador % 2 != 0){
                $color = "background: #F5B7B1;";
            }

            $resultadoString .= '
            <tr style="'.$color.'">
                <td>'.$contador.'</td>
                <td><img src="../img/telephone_14946196.png" style="width: 3%;" class="" alt="user image"> '.$caller_name.'('.$caller_number.')</td>
                <td><img src="../img/telephone_14946196.png" style="width: 3%;" class="" alt="user image"> '.$connected_name.'('.$connected_number.')</td>
            </tr>';
            $contador++;
        }
        $resultadoString .= '</tbody>
                        </table>';
        echo $resultadoString;
    } else {
        echo "[]";
    }
?>
