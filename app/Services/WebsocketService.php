<?php

namespace App\Services;

use function Ratchet\Client\connect;

// серверный вебсокет
class WebsocketService
{
    public static function getWebsockerAddr()
    {
        return 'ws://'.env('WEBSOCKET_IP').':'.env('WEBSOCKET_PORT');
    }

    /** отправка данных в вебсокет */
    public static function send($data)
    {
        $websocket_addr = self::getWebsockerAddr();
        connect($websocket_addr)->then(function ($conn) use ($data) {
            $conn->send(json_encode($data));
            $conn->close();
        });
    }
}
