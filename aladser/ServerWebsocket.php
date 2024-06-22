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
        echo "cоединение установлено\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        // удаление клиента
        $this->clients->detach($conn);
        echo "cоединение закрыто\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
        echo "Получено сообщение: $msg\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
        echo "Ошибка: {$e->getMessage()}\n";
    }
}
