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
        // добавление клиента
        $this->clients->attach($conn);
        
        // запрос имени пользователя 
        $message = json_encode(['type'=>'onconnection', 'resourceId' => $conn->resourceId]);
        $conn->send($message);

        $date = date('d-m-Y h:i');
        echo "$date: {$conn->resourceId} - cоединение установлено\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        // удаление клиента
        $this->clients->detach($conn);
        
        $date = date('d-m-Y h:i');
        echo "$date: {$conn->resourceId} - cоединение закрыто\n";
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        echo "$message\n";
        $request_data = json_decode($message);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
        $date = date('d-m-Y h:i');
        echo "$date: ошибка - {$e->getMessage()}\n";
    }
}
