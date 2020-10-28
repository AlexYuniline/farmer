<?php
/*
 *  Класс в котором находятся методы для подготовки данных (ответы от сервера)
 */

require_once 'data_exception.php';

class Data
{

    const SUCCESS = '{"success": true}'; // Константа для сообщения об успешном действии

    public static function createDataSuccess($result)
    {
        $data = array(
            'success' => true,
            'result' => $result
        );

        $data = json_encode($data);
        if (!$data) {
            throw new DataException(DataException::EX_MES_1, DataException::EX_1);
        }

        return $data;
    }

    public static function createDataFailed($result)
    {
        $data = array(
            'success' => false,
            'result' => $result
        );

        $data = json_encode($data);
        if (!$data) {
            throw new DataException(DataException::EX_MES_1, DataException::EX_1);
        }

        return $data;
    }
}