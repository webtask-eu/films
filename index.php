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
    <div class="container">
        <h1 class="mt-3 mb-3">Welcome to Movies Collection!</h1>
        <p>On this website, you can create your own collections of favorite movies and share them with others.</p>
        <div class="row">
            <div class="col-md-6">
                <h2>Register</h2>
                <p>If you don't have an account yet, please register to start creating your own collections of favorite movies:</p>
                <p><a href="register.php" class="btn btn-primary">Register</a></p>
            </div>
            <div class="col-md-6">
                <h2>Login</h2>
                <p>If you already have an account, please login:</p>
                <p><a href="login.php" class="btn btn-primary">Login</a></p>
            </div>
        </div>
        <h2 class="mt-3">Collections</h2>
        <?php
        require_once 'controller.php';

        $controller = new Controller();

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



