<?php

namespace App\Services;

// **Отслеживает подключенных исполнителей*/
class ExecutorConnFileService
{
    private static string $EXECUTORS_FILEPATH = '/storage/executors';

    /***записывает подключение исполнителя в файл***/
    public static function write_connection($login)
    {
        $file_content = file_get_contents(self::executors_filepath());
        $file_content .= "$login\n";
        file_put_contents(self::executors_filepath(), $file_content);
    }

    /***удаляет подключение исполнителя из файла***/
    public static function remove_connection($login)
    {
        $file_content = file_get_contents(self::executors_filepath());
        $file_content_arr = explode("\n", $file_content);

        foreach ($file_content_arr as $key => $value) {
            if ($value == $login) {
                unset($file_content_arr[$key]);
            } elseif ($value == '') {
                unset($file_content_arr[$key]);
            }
        }

        $file_content = implode("\n", $file_content_arr);
        file_put_contents(self::executors_filepath(), $file_content);
    }

    /**путь к файлу исполнителей*/
    private static function executors_filepath()
    {
        return dirname(__FILE__, 3).self::$EXECUTORS_FILEPATH;
    }

    /**получить список исполнителей */
    public static function get_executors_array(): array
    {
        $file_content = file_get_contents(self::executors_filepath());

        return explode("\n", $file_content);
    }
}
