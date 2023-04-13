<?php
require_once 'model.php';

class Controller {
    private $model;

    public function __construct() {
        $this->model = new Model();
    }

    public function isLoggedIn() {
        return $this->model->isLoggedIn();
    }

    public function registerUser($email, $password, $confirm_password) {
        // Проверяем, что пароли совпадают
        if ($password !== $confirm_password) {
            return 'Passwords do not match';
        }

        // Регистрируем пользователя
        $result = $this->model->registerUser($email, $password);
        if ($result !== true) {
            return $result;
        }

        // Авторизуем пользователя
        $result = $this->model->loginUser($email, $password);
        if ($result !== true) {
            return $result;
        }

        return true;
    }

    public function loginUser($email, $password) {
        return $this->model->loginUser($email, $password);
    }

    public function logoutUser() {
        $this->model->logoutUser();
        header('Location: index.php');
        exit();
    }

    public function getUserCollections($user_id) {
        return $this->model->getUserCollections($user_id);
    }

    public function getCollectionMovies($collection_id) {
        return $this->model->getCollectionMovies($collection_id);
    }

    public function createCollection($user_id, $title, $description) {
        return $this->model->createCollection($user_id, $title, $description);
    }

    public function addMovieToCollection($collection_id, $title, $description, $image_url) {
        return $this->model->addMovieToCollection($collection_id, $title, $description, $image_url);
    }

    public function likeMovie($movie_id) {
        return $this->model->likeMovie($movie_id);
    }
}
