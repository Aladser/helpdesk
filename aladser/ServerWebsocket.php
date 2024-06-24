<?php

namespace Aladser;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/** Cерверная часть вебсокета */
class ServerWebsocket implements MessageComponentInterface
{
    // массив подключенных пользователей
    private $joined_users_id_arr = [];
    // массив исполнителей
    private $joined_executors_conn_arr = [];
    // массив постановищиков
    private $joined_authors_conn_arr = [];

    public function onOpen(ConnectionInterface $conn)
    {
        // запрос имени пользователя
        $message = json_encode(['type' => 'onconnection', 'resourceId' => $conn->resourceId]);
        $conn->send($message);
    }

    public function onClose(ConnectionInterface $conn)
    {
        $user_login = array_search($conn->resourceId, $this->joined_users_id_arr);
        if ($this->joined_executors_conn_arr[$user_login]) {
            unset($this->joined_executors_conn_arr[$user_login]);
            $this->log($conn->resourceId, "отключен исполнитель $user_login");
        } elseif ($this->joined_authors_conn_arr[$user_login]) {
            unset($this->joined_authors_conn_arr[$user_login]);
            $this->log($conn->resourceId, "отключен постановщик $user_login");
        }
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        $request_data = json_decode($message);

        switch ($request_data->type) {
            case 'onconnection':
                // новое подключение
                $this->joined_users_id_arr[$request_data->user_login] = $request_data->resourceId;
                if ($request_data->user_role == 'executor') {
                    $this->joined_executors_conn_arr[$request_data->user_login] = $from;
                    $this->log($from->resourceId, "подключен исполнитель {$request_data->user_login}");
                } elseif ($request_data->user_role == 'author') {
                    $this->joined_authors_conn_arr[$request_data->user_login] = $from;
                    $this->log($from->resourceId, "подключен постановщик {$request_data->user_login}");
                } else {
                    return;
                }
                break;
            case 'new-task':
                // новая задача
                foreach ($this->joined_executors_conn_arr as $executor) {
                    $executor->send($message);
                }
                break;
            default:
                var_dump($request_data);
        }
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
