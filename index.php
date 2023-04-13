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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Movies Collection</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .card {
            margin-bottom: 20px;
        }
        .card-header {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register or Login</div>
                    <div class="card-body">
                        <a href="register.php" class="btn btn-primary">Register</a>
                        <a href="login.php" class="btn btn-secondary">Login</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Users with collections</div>
                    <div class="card-body">
                        <?php
                        require_once 'controller.php';

                        $controller = new Controller();

                        $collections = $controller->getUserCollectionsList();
                        if (count($collections) > 0) {
                            echo '<ul class="list-group">';
                            foreach ($collections as $collection) {
                                echo '<li class="list-group-item">';
                                echo '<a href="collection.php?id=' . $collection['id'] . '">' . $collection['title'] . '</a>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        } else {
                            echo '<p>No collections found</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


