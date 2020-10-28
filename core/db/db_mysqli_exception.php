<?php
/*
 *  Класс исключение
 */

class DBMySQLIException extends Exception
{
    //  файл настроек не найден
    const EX_1 = 100001;
    const EX_MES_1 = 'settings file not found';

    //подготовка запроса неудачна
    const EX_2 = 100002;
    const EX_MES_2 = 'not prepared query';

    //подготовка запроса неудачна
    const EX_3 = 100003;
    const EX_MES_3 = 'not executed query';

    // ошибка при выполнении store_result
    const EX_4 = 100004;

    //ошибка при выполнении result_metadata
    const EX_5 = 100005;
    const EX_MES_5 = 'not return result metadata';

    //ошибка при выполнении result_metadata
    const EX_6 = 100006;
    const EX_MES_6 = 'not fetched result data';

    private $_message = null; //сообщение об ошибки
    private $_code = null; //код ошибки

    public function __construct($message, $code = 0)
    {
        $this->_message = $message;
        $this->_code = $code;
        parent::__construct($message, $code);
    }

    public function showError()
    {
        $msg = 'Error: ' . $this->_message . ' code: ' . $this->_code . ' in: ' . $this->getTraceAsString();
        return $msg;
    }
}