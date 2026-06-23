<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Extenciones;
    require dirname(__DIR__) . '/vendor/autoload.php';
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Extenciones()
            )
        ),
        8083
    );
    $server->run();