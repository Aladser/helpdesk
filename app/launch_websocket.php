<?php

namespace App;

require dirname(__DIR__, 1).'/vendor/autoload.php';

use Aladser\ServerWebsocket;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ServerWebsocket()
        )
    ),
    env('WEBSOCKET_PORT')
);
$server->run();
