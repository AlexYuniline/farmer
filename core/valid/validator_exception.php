<?php
/*
 *  Класс исключение
 */

class ValidatorException extends Exception
{
    //  файл настроек не найден
    const EX_1 = 104001;
    const EX_MES_1 = 'not get params image size';

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