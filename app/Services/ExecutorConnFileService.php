<?php

namespace App\Services;

// **Отслеживает подключенных исполнителей*/
class ExecutorConnFileService
{
    private static string $EXECUTORS_FILEPATH = '/storage/executors';

    /***записывает подключение исполнителя в файл***/
    public static function write_connection($login, $id)
    {
        $file_path = self::executors_filepath();
        $file_content = file_get_contents($file_path);

        $executors_dict = $file_content == '' ? (object)[] : json_decode($file_content);
        $executors_dict->$id = $login;
        $file_content = json_encode($executors_dict);

        file_put_contents($file_path, $file_content);
    }

    /***удаляет подключение исполнителя из файла***/
    public static function remove_connection($login, $id)
    {
        $file_path = self::executors_filepath();
        $file_content = file_get_contents($file_path);

        $executors_dict = json_decode($file_content);
        unset($executors_dict->$id);

        $file_content = json_encode($executors_dict);
        file_put_contents($file_path, $file_content);
    }

    /**путь к файлу исполнителей*/
    private static function executors_filepath()
    {
        return dirname(__FILE__, 3).self::$EXECUTORS_FILEPATH;
    }

    /**получить список исполнителей */
    public static function get_executors_array()
    {
        $file_content = file_get_contents(self::executors_filepath());
        $executors_dict = $file_content == '' ? (object)[] : json_decode($file_content);

        return $executors_dict;
    }
}
