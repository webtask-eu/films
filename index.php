<?php
session_start();
require_once 'controller.php';
$controller = new Controller();

// Если пользователь авторизован, перенаправляем его на страницу профиля
if ($controller->isLoggedIn()) {
    header('Location: profile.php');
    exit();
}

// Обработка регистрации
if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $result = $controller->registerUser($email, $password, $confirm_password);
    if ($result === true) {
        // Если регистрация прошла успешно, перенаправляем пользователя на страницу профиля
        header('Location: profile.php');
        exit();
    } else {
        // Если при регистрации возникла ошибка, выводим ее на экран
        $error = $result;
    }
}

// Обработка авторизации
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $result = $controller->loginUser($email, $password);
    if ($result === true) {
        // Если авторизация прошла успешно, перенаправляем пользователя на страницу профиля
        header('Location: profile.php');
        exit();
    } else {
        // Если при авторизации возникла ошибка, выводим ее на экран
        $error = $result;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Movies Collection - Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Movies Collection</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php
                // Показываем кнопки входа и регистрации, если пользователь не авторизован
                if (!isset($_SESSION['user_id'])) {
                    echo '<li class="nav-item"><a class="nav-link" href="login.php" style="background-color: green; color: white;">Login</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>';
                }
                // Показываем кнопку выхода, если пользователь авторизован
                else {
                    echo '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
                }
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-3">
        <h1 class="mt-3 mb-3">Welcome to Movies Collection!</h1>
        <p>On this website, you can create your own collections of favorite movies and share them with others.</p>
        <h2 class="mt-3">Collections</h2>
        <?php

        // Получаем список коллекций
        $collections = $controller->getUserCollections($_SESSION['user_id']);

        if ($collections !== false && count($collections) > 0) {
            // Выводим список коллекций
            echo '<ul>';
            foreach ($collections as $collection) {
                echo '<li><a href="collection.php?id=' . $collection['id'] . '">' . htmlspecialchars($collection['title']) . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>You have no collections yet.</p>';
        }
        ?>
    </div>
</body>
</html>




