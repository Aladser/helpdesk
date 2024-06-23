<?php

namespace Aladser;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/** Cерверная часть вебсокета */
class ServerWebsocket implements MessageComponentInterface
{
    // хранение всех подключенных пользователей
    private \SplObjectStorage $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        
        // запрос имени пользователя 
        $message = json_encode(['onconnection' => $conn->resourceId]);
        $conn->send($message);
        
        echo "{$conn->resourceId} - cоединение установлено\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        // удаление клиента
        $this->clients->detach($conn);
        echo "{$conn->resourceId} - cоединение закрыто\n";
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        $data = json_decode($message);
        var_dump($message);
        var_dump($data);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
        echo "Ошибка: {$e->getMessage()}\n";
    }
}
