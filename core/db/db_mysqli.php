<?php
/**
 * Класс для работы с БД через MySqlI. Данный класс является синглтоном, поэтому в программе может существовать только один объект этого класса
 */
require_once 'db_mysqli_exception.php';

class DBMySQLI
{

    protected static $_config = null; //статическое свойство, в котором хранится конфиг подключения к БД
    protected static $_db = null; //статическое свойство в котором храниться объект подключения к БД
    protected static $_dataBase = null; //статическое свойство в котором храниться объект класса DBMySqlI
    protected $_data = array(); //свойство в котором хранятся данные которые были выбраны из БД
    protected $_stmt = null; //свойство в котором хранится подготовленный запрос к БД
    protected $_insertID = 0; //свойство в котором хранится информация о последнем вставленном ID в таблицу

    /**
     * метод для получения экземпляра класса
     *
     * @return DBMySQLI
     */
    public static function getInstance()
    {

        if (!static::$_config) {

            require_once __DIR__ . '/../../settings/settings.mysql.php';
            if (!isset($dbConf)) {
                throw new DBMySQLIException(DBMySQLIException::EX_MES_1, DBMySQLIException::EX_1);
            }
            static::$_config = $dbConf;
        }

        if (!static::$_dataBase) {
            static::$_dataBase = new DBMySQLI;
            static::$_dataBase->_init($dbConf);
        }

        return static::$_dataBase;
    }

    /**
     * запрещаем создание объектов, клонирование и десериализацию
     */
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public function __sleep()
    {
    }

    /**
     * метод инициализирует подключение к БД
     *
     * @param array $dbConf - массив содержащий параметры подключения. Массив является именованным. Список ключей для данного массива следующий: host, user, password, db, port
     */
    private function _init($dbConf)
    {
        static::$_db = new mysqli($dbConf['host'], $dbConf['user'], $dbConf['password'], $dbConf['db'], $dbConf['port']);
        if (static::$_db->connect_errno != 0) {
            throw new DBMySQLIException(static::$_db->connect_error, static::$_db->connect_errno);
        }

        static::$_db->query('set names utf8');

    }

    /**
     * метод привязывает переменные к подготовленному запросу для размещения результата
     *
     * @return object
     */
    protected function _bindResult()
    {
        $var = array();
        $this->_stmt->store_result();
        if ($this->_stmt->error != '') {
            throw new DBMySQLIException($this->_stmt->error, DBMySQLIException::EX_4);
        }
        $meta = $this->_stmt->result_metadata();
        if (!$meta) {
            throw new DBMySQLIException(DBMySQLIException::EX_MES_5, DBMySQLIException::EX_5);
        }
        while ($field = $meta->fetch_field()) {
            $var[] = &$this->_data[$field->name];
        }
        return call_user_func_array(array($this->_stmt, 'bind_result'), $var);
    }

    /**
     * метод подготавливает запрос для выполнения
     *
     * @param array $arguments
     * @return string
     */
    private function _prepareQuery($arguments)
    {
        if (count($arguments) == 1) {
            return $arguments[0];
        }
        $query = '';
        $queryArgument = array_shift($arguments);
        $arrayQuery = explode('?', $queryArgument);
        foreach ($arrayQuery as $arg) {
            $argument = array_shift($arguments);
            $query .= $arg . static::$_db->real_escape_string($argument);
        }

        return $query;
    }

    /**
     * метод  формирует ассоциативный массив на основе результатов из БД.
     * Ключи массива поля выбора.
     * При вызове метода обрабатывается одна строка выбора
     *
     * @return boolean
     */
    public function fetchAssoc()
    {
        $result = $this->_stmt->fetch();
        if ($result) {
            $data = array();
            foreach ($this->_data as $key => $value) {
                $data[$key] = $value;
            }
            return $data;
        } elseif ($result === false) {
            throw new DBMySQLIException(DBMySQLIException::EX_MES_6, DBMySQLIException::EX_6);
        } else {
            return null;
        }
    }

    /**
     * метод возвращает первое поле результата выбора из БД
     *
     * @return string / int
     */
    public function fetchFirstField()
    {
        $result = $this->fetchAssoc();
        if ($result) {
            $result = array_values($result);
            return $result[0];
        } else {
            return null;
        }
    }

    /**
     *  метод возвращает двумерный массив, первый ключ которого номер строки, а второй ключ название поля результата выбора из БД
     *
     * @return array
     */
    public function fetchAll()
    {
        $data = array();
        while ($row = $this->fetchAssoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * метод возвращает не ассоциативный массив, в котором находится первая колонка результата выбора из БД
     *
     * @return array
     */
    public function fetchFirstColumn()
    {
        $data = array();
        while ($row = $this->fetchAssoc()) {
            $result = array_values($row);
            $data[] = $result[0];
        }

        return $data;
    }

    /**
     * метод возвращает ассоциативный массив, в котором находится первая строка результата выбора из БД
     *
     * @return array
     */
    public function fetchRow()
    {
        $result = $this->fetchAll();
        return isset($result[0]) ? $result[0] : false;
    }

    /**
     * метод который выполняет запрос у БД
     *
     * @return boolean
     */
    public function query()
    {
        $arguments = func_get_args();
        $query = $this->_prepareQuery($arguments);
        $this->_stmt = static::$_db->prepare($query);
        if (!$this->_stmt) {
            throw new DBMySQLIException(DBMySQLIException::EX_MES_2, DBMySQLIException::EX_2);
        }
        $this->_data = array();
        $result = $this->_stmt->execute();
        if (!$result) {
            throw new DBMySQLIException(DBMySQLIException::EX_MES_3, DBMySQLIException::EX_3);
        }
        $this->_insertID = static::$_db->insert_id;
        if ($this->_stmt->affected_rows === -1) {
            $this->_bindResult($this->_stmt);
        }
        return true;

    }

    /**
     * метод производит вставку записи в БД
     *
     * @param string $table - название таблицы в которую будет произведена вставка
     * @param array $data - ассоциативный массив в котором ключи массива названия полей, а значения массива, значения которые нужно записать в БД
     * @return object
     */
    public function insert($table, $data)
    {
        $query = 'INSERT INTO ' . $table . ' SET ';

        $params = array();
        foreach ($data as $paramField => $paramValue) {
            $typeFileld = explode(':', $paramField);
            $type = $typeFileld[0];
            $field = $typeFileld[1];
            switch ($type) {
                case 'i':
                    $params[] = '`' . $field . '`=' . (int)$paramValue;
                    break;

                case 'f':
                    $params[] = '`' . $field . '`=' . (float)$paramValue;
                    break;

                case 's':
                    $params[] = '`' . $field . '`= "' . static::$_db->real_escape_string($paramValue) . '"';
                    break;
            }
        }

        $query .= implode(', ', $params);
        $result = $this->query($query);

        return $result;
    }

    /**
     * метод производит удаление записи из таблицы
     *
     * @param string $table - название таблицы в которую будет произведено удаление
     * @param array $condition - ассоциативный двумерный массив в котором ключи массива названия полей, а следующий индекс  массива содержит в себе следующие элементы: значения которые нужно сравнить, условие по которому происходит сравнение
     * @return object
     */
    public function delete($table, $condition)
    {
        $query = 'DELETE FROM ' . $table . ' WHERE';

        $params = array();

        foreach ($condition as $paramField => $paramValue) {
            $typeFileld = explode(':', $paramField);
            $type = $typeFileld[0];
            $field = $typeFileld[1];
            switch ($type) {
                case 'i':
                    $params[] = '`' . $field . '`' . $paramValue[1] . (int)$paramValue[0];
                    break;

                case 'f':
                    $params[] = '`' . $field . '`' . $paramValue[1] . (float)$paramValue[0];
                    break;

                case 's':
                    $params[] = '`' . $field . '`' . $paramValue[1] . '\'' . $paramValue[0] . '\'';
                    break;
            }
        }

        $query .= implode(' AND ', $params);
        return $this->query($query);
    }

    /**
     * метод производит изменения записи в таблице. Стоит учитывать что условия для изменения связаны всегда AND
     *
     * @param string $table - название таблицы в которую будет произведено изменение
     * @param array $data - ассоциативный массив в котором ключи массива названия полей, а значения массива, значения на которые нужно изменить  записи в БД
     * @param array $condition - ассоциативный двумерный массив в котором ключи массива названия полей, а следующий индекс  массива содержит в себе следующие элементы: значения которые нужно сравнить, условие по которому происходит сравнение
     * @return object
     */
    public function update($table, $data, $condition)
    {
        $query = 'UPDATE ' . $table . ' SET ';

        $params = array();

        foreach ($data as $paramField => $paramValue) {
            $typeFileld = explode(':', $paramField);
            $type = $typeFileld[0];
            $field = $typeFileld[1];
            switch ($type) {
                case 'i':
                    $params[] = '`' . $field . '`=' . (int)$paramValue;
                    break;

                case 'f':
                    $params[] = '`' . $field . '`=' . (float)$paramValue;
                    break;

                case 's':
                    $params[] = '`' . $field . '`= "' . static::$_db->real_escape_string($paramValue) . '"';
                    break;
            }
        }
        $query .= implode(', ', $params);
        $query .= ' WHERE ';
        $params = array();
        foreach ($condition as $paramField => $paramValue) {
            $typeFileld = explode(':', $paramField);
            $type = $typeFileld[0];
            $field = $typeFileld[1];
            switch ($type) {
                case 'i':
                    $params[] = '`' . $field . '`' . $paramValue[1] . (int)$paramValue[0];
                    break;

                case 'f':
                    $params[] = '`' . $field . '`' . $paramValue[1] . (float)$paramValue[0];
                    break;

                case 's':
                    $params[] = '`' . $field . '`' . $paramValue[1] . '\'' . $paramValue[0] . '\'';
                    break;
            }
        }
        $query .= implode(' AND ', $params);
        return $this->query($query);
    }

    /**
     * метод возвращает ID последней вставленной записи
     *
     * @return int
     */
    public function getInsertID()
    {
        return $this->_insertID;
    }
}