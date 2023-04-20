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
            // Обработка ошибки подготовки запроса
            echo 'Prepare failed: ' . $this->connection->error;
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
    
        // Если запрос выполнился успешно, получаем результат
        if ($result !== false) {
            $result = $statement->get_result();
            if ($result !== false) {
                $rows = array();
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                return $rows;
            } else {
                // Обработка ошибки получения результата
                echo 'Get result failed: ' . $this->connection->error;
                return false;
            }
        } else {
            // Обработка ошибки выполнения запроса
            echo 'Query failed: ' . $this->connection->error;
            return false;
        }
    }
    
    
    

    public function getLastInsertId() {
        return $this->last_insert_id;
    }
}
