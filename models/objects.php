<?php

class Objects
{
    private $_db = null;

    function __construct()
    {
        $this->_db = DBMySQLI::getInstance();
    }

    /*Выбор  объектов*/
    public function getObjects($search = null)
    {
        if ($search && $search != '') {
            $this->_db->query('SELECT * FROM `objects` WHERE `user_id` = ?', $search);
        } else {
            $this->_db->query('SELECT * FROM `objects`');
        }
        $data = $this->_db->fetchAll();
        return $data;
    }

    /*Выбор объекта по id*/
    public function getObjectsById($id)
    {
        $this->_db->query('SELECT * FROM `objects` WHERE `id` = ?', $id);
        $data = $this->_db->fetchRow();
        return $data;
    }

    /*Удаление объекта по id*/
    public function deleteObjectById($id)
    {
        $data['i:id'] = array(
            0 => $id,
            1 => '='
        );
        $result = $this->_db->delete('objects', $data);
        return $result;
    }

    /*Добавление объекта*/
    public function createObject($post)
    {
        $data = array();
        $data['s:user_id'] = $post['user_id'];
        $data['s:name'] = $post['name'];
        $data['i:main_lat'] = $post['main_lat'];
        $data['i:main_lat'] = $post['main_lat'];
        $data['i:main_lon'] = $post['main_lon'];
        $data['i:address'] = $post['address'];
        $data['i:circuit'] = $post['circuit'];
        $data['i:extends'] = $post['extends'];
        try {
            $this->_db->insert('objects', $data);
        } catch (DBMySQLIException $ex) {
            var_dump($ex->showError());
            exit();
        }
        echo Data::SUCCESS;
    }

    /*Редактирование объекта*/
    public function updateObject($post)
    {
        $data = array();
        $data['s:user_id'] = $post['user_id'];
        $data['s:name'] = $post['name'];
        $data['i:main_lat'] = $post['main_lat'];
        $data['i:main_lat'] = $post['main_lat'];
        $data['i:main_lon'] = $post['main_lon'];
        $data['i:address'] = $post['address'];
        $data['i:circuit'] = $post['circuit'];
        $data['i:extends'] = $post['extends'];
        $where['i:id'] = array(
            0 => $post['id'],
            1 => '='
        );
        try {
            $result = $this->_db->update('objects', $data, $where);
        } catch (DBMySQLIException $ex) {
            var_dump($ex->showError());
            exit();
        }
        echo Data::SUCCESS;
    }
}