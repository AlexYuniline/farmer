<?php
require_once 'controller.php';
require_once 'models/objects.php';

class IndexController extends Controller
{
    private $_twig = null;

    public function __construct($postParams)
    {
        parent::__construct($postParams);
        $this->_twig = $this->twig();
    }

    /**
     *Вывод главной страницы
     */
    public function index()
    {
        $search = (isset($this->_get['search']) ? $this->_get['search'] : null);

        $objects = new Objects();

        $getObjects = $objects->getObjects($search);

        $data = array(
            'objects' => $getObjects,
            'search' => $search
        );

        $template = $this->_twig->loadTemplate('main.tpl');
        echo $template->render($data);
        exit();
    }

    /**
     *Страница добавления
     */
    public function show()
    {
        $data = array();
        $template = $this->_twig->loadTemplate('create.tpl');
        echo $template->render($data);
        exit();
    }

    /**
     *Добавление
     */
    public function create()
    {
        $objects = new Objects();
        $objects->createObject($this->_post);
        header('Location: /');
        exit();
    }

    /**
     *Страница редактирования
     */
    public function edit($params)
    {
        $objects = new Objects();

        $getObject = $objects->getObjectsById($params['id']);

        $data = array(
            'object' => $getObject
        );

        $template = $this->_twig->loadTemplate('edit.tpl');
        echo $template->render($data);
        exit();
    }

    /**
     *Редактирование
     */
    public function update()
    {
        $objects = new Objects();
        $objects->updateObject($this->_post);
        header('Location: /');
        exit();
    }

    /**
     *Удаление
     */
    public function delete($params)
    {
        $objects = new Objects();
        $objects->deleteObjectById($params['id']);
        header('Location: /');
        exit();
    }
}