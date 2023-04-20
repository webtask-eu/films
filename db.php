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
            // В случае ошибки выводим сообщение
            echo "Error preparing query: " . $this->connection->error;
            return false;
        }
    
        // Привязываем параметры к запросу
        if (count($params) > 0) {
            $types = str_repeat('s', count($params));
            $statement->bind_param($types, ...$params);
        }
    
        // Выполняем запрос
        $result = $statement->execute();
    
        // Если запрос SELECT выполнился успешно, получаем результат
        if (strpos(strtoupper(trim($query)), 'SELECT') === 0) {
            if ($result) {
                $result = $statement->get_result();
                $rows = array();
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                return $rows;
            } else {
                // В случае ошибки выводим сообщение
                echo "Error executing query: " . $this->connection->error;
                return false;
            }
        }
    
        // Если запрос INSERT выполнился успешно, возвращаем last_insert_id
        if (strpos(strtoupper(trim($query)), 'INSERT') === 0) {
            if ($result) {
                $this->last_insert_id = $this->connection->insert_id;
                return $this->last_insert_id;
            } else {
                // В случае ошибки выводим сообщение
                echo "Error executing query: " . $this->connection->error;
                return false;
            }
        }
    
        // В случае, если запрос не является ни SELECT, ни INSERT, возвращаем результат выполнения запроса
        return $result;
    }
    
    
    
    

    public function getLastInsertId() {
        return $this->last_insert_id;
    }
}
