<?php
    include_once '../includes/config.php';
    include_once '../includes/security.php';

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    $address = 'localhost';
    $port = 8080;

    // Create WebSocket.
    $server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
    socket_bind($server, $address, $port);
    socket_listen($server);
    $client = socket_accept($server);

    // Send WebSocket handshake headers.
    $request = socket_read($client, 5000);
    preg_match('#Sec-WebSocket-Key: (.*)\r\n#', $request, $matches);
    $key = base64_encode(pack(
        'H*',
        sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')
    ));
    $headers = "HTTP/1.1 101 Switching Protocols\r\n";
    $headers .= "Upgrade: websocket\r\n";
    $headers .= "Connection: Upgrade\r\n";
    $headers .= "Sec-WebSocket-Version: 13\r\n";
    $headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";
    socket_write($client, $headers, strlen($headers));

    // Send messages into WebSocket in a loop.
    while (true) {
        sleep(1);
        $content = 'Now: ' . time();
        $response = chr(129) . chr(strlen($content)) . $content;
        socket_write($client, $response);
    }

    function consultarBaseDeDatos($query, $link) {
        // $command = "curl -s -X GET -u $usuario_ari:$password_ari $ip_servidor_ari:8088/ari/endpoints";
        // $output = shell_exec($command);
        
        $output = '
        [
            {
            "technology": "PJSIP",
            "resource": "2000",
            "state": "offline",
            "channel_ids": []
            },
            {
            "technology": "PJSIP",
            "resource": "2001",
            "state": "online",
            "channel_ids": []
            },
            {
            "technology": "PJSIP",
            "resource": "2002",
            "state": "online",
            "channel_ids": []
            }
        ]';

        $datos = json_decode($output, true);

        foreach ($datos as $elemento) {
            $tecnologia = $elemento['technology'];
            $recurso = $elemento['resource'];
            $estado = $elemento['state'];

            //obtener el nombre
            $nombre = "";
            $consult = mysqli_query($link,"SELECT * FROM cdr_espejo WHERE origen = '$recurso' LIMIT 1");
            $row = mysqli_fetch_array($consult);
            if($row){
                $nombre = $row['nombre'];
            } else {
                $nombre = $recurso;
            }

            $color = '';
            if($estado == 'offline'){
                $color = 'borderRed';
            } else {
                $color = 'borderGreen';
            }

            echo '
            <div class="col-md-3" style="margin: 5px 0px;">
                <div class="profile-box content-box">
                    <div class="content-box-header clearfix contenedorContacto">
                        <img src="assets/dist/img/avatar5.png" width="52" alt="" class="img-bordered '.$color.' img-circle">
                        <div class="user-details">
                            '.$nombre.'
                            <span>'.$recurso.'</span>
                        </div>
                    </div>
                </div>
            </div>
            ';
        }
    }