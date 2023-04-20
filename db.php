<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class DB {
    private $connection;
    private $last_insert_id;

    public function __construct() {
        // Указываем параметры подключения к базе данных
        $host = 'localhost';
        $username = 'films';
        $password = '_Uf5k2y91';
        $database = 'films';

        // Подключаемся к базе данных
        $this->connection = new mysqli($host, $username, $password, $database);

        // Проверяем, что подключение установлено успешно
        if ($this->connection->connect_error) {
            die('Connection failed: ' . $this->connection->connect_error);
        }
    }

    public function __destruct() {
        // Закрываем соединение с базой данных при уничтожении объекта
        $this->connection->close();
    }

    public function query($query, $params = array()) {
        // Подготавливаем запрос с помощью подготовленных выражений
        $statement = $this->connection->prepare($query);
        if (!$statement) {
            return false;
        }
    
        // Привязываем параметры к запросу
        if (count($params) > 0) {
            $types = str_repeat('s', count($params));
            $statement->bind_param($types, ...$params);
        }
    
        // Выполняем запрос и сохраняем последний вставленный ID
        $result = $statement->execute();
        $this->last_insert_id = $this->connection->insert_id;
    
        if ($result !== false) {
            // Если запрос выполнился успешно, получаем количество затронутых строк
            $affected_rows = $statement->affected_rows;
            if ($affected_rows >= 0) {
                return $affected_rows;
            } else {
                // Если количество затронутых строк неизвестно, возвращаем true
                return true;
            }
        } else {
            // Если запрос не выполнился, выводим сообщение об ошибке
            echo 'Query failed: ' . $this->connection->error;
            return false;
        }
    }
    
    

    public function getLastInsertId() {
        return $this->last_insert_id;
    }
}
