<?php

namespace Aladser;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/** Cерверная часть вебсокета */
class ServerWebsocket implements MessageComponentInterface
{
    // хранение всех подключенных пользователей
    private $join_users_arr = [];
    private $join_users_conn_arr = [];

    public function onOpen(ConnectionInterface $conn)
    {
        // запрос имени пользователя
        $message = json_encode(['type' => 'onconnection', 'resourceId' => $conn->resourceId]);
        $conn->send($message);

        $this->log($conn->resourceId, 'cоединение установлено');
    }

    public function onClose(ConnectionInterface $conn)
    {
        $user_login = array_search($conn->resourceId, $this->join_users_arr);
        unset($this->join_users_arr[$user_login]);
        unset($this->join_users_conn_arr[$user_login]);

        $this->log($conn->resourceId, 'cоединение закрыто');
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        $request_data = json_decode($message);

        switch ($request_data->type) {
            case 'onconnection':
                $this->join_users_arr[$request_data->user_login] = $request_data->resourceId;
                $this->join_users_conn_arr[$request_data->user_login] = [
                    'conn' => $from,
                    'role' => $request_data->user_role,
                ];
                break;
        }

        $this->log($from->resourceId, $message);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
        $this->log($conn->resourceId, $e->getMessage());
    }

    private function log(int $id, string $text)
    {
        $date = date('d-m-Y h:i');
        echo "$date: resourceId $id - $text\n";
    }
}
