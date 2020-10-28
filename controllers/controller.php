<?php
require_once 'libs/Twig/Autoloader.php';

/* 
 * Контроллер родительский, 
 * для подключения твига
 */

class Controller
{

    private $_twig;
    protected $_validator = null;
    protected $_post = null;
    protected $_get = null;

    public function __construct()
    {
        $this->_validator = new Validator();
        $this->_post = $this->_validator->validatePost($_POST);
        $this->_get = $this->_validator->validateGet($_GET);
    }

    public function twig()
    {
        if (null === $this->_twig) {
            Twig_Autoloader::register();
            $loader = new Twig_Loader_Filesystem('tpl');
            $this->_twig = new Twig_Environment($loader, array(
                'charset' => 'utf8'
            ));
        }
        return $this->_twig;
    }
}
