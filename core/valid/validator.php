<?php
/*
 *  Класс производит валидацию POST и GET массивов, а так же изображений по размерам
 */

require_once 'validator_exception.php';

class Validator
{

    /**
     * метод производит валидацию POST массива
     *
     * @param array $params - массив POST
     * @return array
     */
    public function validatePost($params)
    {
        $result = array();
        foreach ($params as $key => $post) {
            $result[$key] = filter_input(INPUT_POST, $key);
        }

        return $result;
    }

    /**
     * метод производит валидацию GET массива
     *
     * @param array $params - массив GET
     * @return array
     */
    public function validateGet($params)
    {
        $result = array();
        foreach ($params as $key => $get) {
            $result[$key] = filter_input(INPUT_GET, $key);
        }

        return $result;
    }
}