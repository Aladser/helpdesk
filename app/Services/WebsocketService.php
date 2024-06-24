<?php

namespace App\Services;

use function Ratchet\Client\connect;

// серверный вебсокет
class WebsocketService
{
    /** отправка данных в вебсокет */
    public static function send($data)
    {
        connect(env('WEBSOCKET_ADDR'))->then(function ($conn) use ($data) {
            $conn->send(json_encode($data));
            $conn->close();
        });
    }
}
