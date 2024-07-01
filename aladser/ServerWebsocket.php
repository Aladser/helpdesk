<?php

namespace Aladser;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/** Cерверная часть вебсокета */
class ServerWebsocket implements MessageComponentInterface
{
    // массив подключений
    private array $joined_users_arr = [];
    // БД коннектор
    private DBQuery $db_connector;

    public function __construct()
    {
        $this->db_connector = new DBQuery('pgsql', env('DB_HOST'), env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'));
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // запрос имени пользователя
        $message = json_encode(['type' => 'onconnection', 'resourceId' => $conn->resourceId]);
        $conn->send($message);
    }

    public function onClose(ConnectionInterface $conn)
    {
        if ($this->joined_users_arr['executor'][$conn->resourceId]) {
            // отключение исполнителя
            $executor_conn = $this->joined_users_arr['executor'][$conn->resourceId];

            $sql = 'delete from connections where conn_id = :conn_id';
            $this->db_connector->queryPrepared($sql, ['conn_id' => $conn->resourceId]);

            $this->log($conn->resourceId, "отключен исполнитель {$executor_conn['login']}");
            unset($this->joined_users_arr['executor'][$conn->resourceId]);
        } elseif ($this->joined_users_arr['author'][$conn->resourceId]) {
            // отключение постановщика
            $this->log($conn->resourceId, "отключен постановщик {$this->joined_users_arr['author'][$conn->resourceId]['login']}");
            unset($this->joined_users_arr['author'][$conn->resourceId]);
        }
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        try {
            $request_data = json_decode($message);

            switch ($request_data->type) {
                case 'onconnection':
                    // новое подключение
                    $this->joined_users_arr[$request_data->user_role][$request_data->resourceId] = [
                        'login' => $request_data->user_login,
                        'conn' => $from,
                    ];

                    if ($request_data->user_role == 'executor') {
                        $this->log($from->resourceId, "подключен исполнитель {$request_data->user_login}");
                    } elseif ($request_data->user_role == 'author') {
                        $this->log($from->resourceId, "подключен постановщик {$request_data->user_login}");
                    } else {
                        return;
                    }

                    break;
                case 'user-status':
                    // установка статуса пользователя
                    $sql = 'select count(*) as count from connections where conn_id = :conn_id';
                    $is_existed = $this->db_connector->queryPrepared($sql, ['conn_id' => $from->resourceId], false)[0]['count'] > 0;
                    if ($is_existed) {
                        $sql = 'update connections set is_active = :is_active where conn_id = :conn_id';
                        $this->db_connector->queryPrepared($sql, ['conn_id' => $from->resourceId, 'is_active' => $request_data->status]);
                    } else {
                        $id = $this->db_connector->queryPrepared('select id from users where login = :login', ['login' => $request_data->login])['id'];
                        if ($id) {
                            $sql = 'insert into connections (conn_id, user_id, is_active) values (:conn_id, :user_id, :is_active)';
                            $this->db_connector->queryPrepared($sql, ['conn_id' => $from->resourceId, 'user_id' => $id, 'is_active' => $request_data->status]);
                        }
                    }

                    break;
                case 'task-new':
                    // новая задача
                    $this->log($from->resourceId, "новая задача = $message");
                    break;
                case 'take-task':
                    // принята задача
                    $this->sendMessageToAuthor($request_data->author_login, $message);
                    $this->log($from->resourceId, "задача взята в работу = $message");
                    break;
                case 'complete-task':
                    // выполнена задача
                    $this->sendMessageToAuthor($request_data->author_login, $message);
                    $this->log($from->resourceId, "завершена задача = $message");
                    break;
                case 'comment-new':
                    // отправлен комментарий
                    $executor_conn = $this->joined_users_arr['executor'][$request_data->executor_login];
                    if ($executor_conn) {
                        $executor_conn->send($message);
                    }
                    $this->sendMessageToAuthor($request_data->author_login, $message);
                    $this->log($from->resourceId, "добавлен комментарий = $message");

                    break;
                default:
                    var_dump($request_data);
            }
            if (in_array($request_data->type, ['task-new', 'take-task', 'complete-task'])) {
                foreach ($this->joined_users_arr['executor'] as $login => $user_conn) {
                    if ($user_conn['conn'] != $from) {
                        $user_conn['conn']->send($message);
                    }
                }
            }
        } catch (\Exception $e) {
            var_dump($e);
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
        $this->log($conn->resourceId, $e->getMessage());
    }

    private function log(int $id, string $text)
    {
        echo date('d-m-Y h:i').": resourceId $id - $text\n";
    }

    // оправляет сообщение постановщику
    private function sendMessageToAuthor($author_login, $message)
    {
        foreach ($this->joined_users_arr['author'] as $key => $userConn) {
            if ($userConn['login'] == $author_login) {
                $userConn['conn']->send($message);
                break;
            }
        }
    }
}
