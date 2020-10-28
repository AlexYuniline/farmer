<?php

/**
 * Карта
 */
class Autoload
{
    private $_coreArray = array(
        'DBMySQLI' => 'db/db_mysqli.php',
        'Validator' => 'valid/validator.php',
        'Data' => 'data/data_general.php',
    );

    public function __construct()
    {
        spl_autoload_register(array($this, 'core'));
    }

    public function core($class)
    {
        if (isset($this->_coreArray[$class])) {
            require_once $this->_coreArray[$class];
        }
    }
}

$autoload = new Autoload(); 
