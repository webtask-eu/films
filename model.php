<?php
require_once 'db.php';

class Model {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function registerUser($email, $password) {
        // Проверяем, что email не занят другим пользователем
        $query = 'SELECT id FROM users WHERE email = ?';
        $params = array($email);
        $result = $this->db->query($query, $params);
        if ($result !== false && count($result) > 0) {
            return 'Email is already taken';
        }

        // Хэшируем пароль и добавляем пользователя в базу данных
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = 'INSERT INTO users (email, password) VALUES (?, ?)';
        $params = array($email, $hashed_password);
        $result = $this->db->query($query, $params);
        if ($result === false) {
            return 'Registration failed';
        }

        // Авторизуем пользователя
        $user_id = $this->db->getLastInsertId();
        $_SESSION['user_id'] = $user_id;

        return true;
    }

    public function loginUser($email, $password) {
        // Получаем информацию о пользователе по email
        $query = 'SELECT id, password FROM users WHERE email = ?';
        $params = array($email);
        $result = $this->db->query($query, $params);
        if ($result === false || count($result) === 0) {
            return 'Invalid email or password';
        }

        // Проверяем, что пароль верен
        $hashed_password = $result[0]['password'];
        if (!password_verify($password, $hashed_password)) {
            return 'Invalid email or password';
        }

        // Авторизуем пользователя
        $user_id = $result[0]['id'];
        $_SESSION['user_id'] = $user_id;

        return true;
    }

    public function logoutUser() {
        unset($_SESSION['user_id']);
    }

    public function getUserCollections($user_id) {
        $query = 'SELECT id, title, description FROM collections WHERE user_id = ?';
        $params = array($user_id);
        $result = $this->db->query($query, $params);
        return $result;
    }

    public function getCollectionMovies($collection_id) {
        $query = 'SELECT id, title, description, image_url, likes_count FROM movies WHERE collection_id = ?';
        $params = array($collection_id);
        $result = $this->db->query($query, $params);
        return $result;
    }

    public function createCollection($user_id, $title, $description) {
        $query = 'INSERT INTO collections (user_id, title, description) VALUES (?, ?, ?)';
        $params = array($user_id, $title, $description);
        $result = $this->db->query($query, $params);
        if ($result === false) {
            return 'Failed to create collection';
        }
        return $this->db->getLastInsertId();
    }

    public function addMovieToCollection($collection_id, $title, $description, $image_url) {
        $query = 'INSERT INTO movies (collection_id, title, description, image_url) VALUES (?, ?,?, ?, ?)';
        $params = array($collection_id, $title, $description, $image_url);
        $result = $this->db->query($query, $params);
        if ($result === false) {
            return 'Failed to add movie to collection';
        }
        return true;
    }

    public function likeMovie($movie_id) {
        // Увеличиваем количество лайков фильма на 1
        $query = 'UPDATE movies SET likes_count = likes_count + 1 WHERE id = ?';
        $params = array($movie_id);
        $result = $this->db->query($query, $params);
        if ($result === false) {
            return 'Failed to like movie';
        }
        return true;
    }
}

