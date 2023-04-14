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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Movies Collection</a>
            <div class="navbar-links">
                <?php
                // Показываем кнопки входа и регистрации, если пользователь не авторизован
                if (!isset($_SESSION['user_id'])) {
                    echo '<a href="login.php" class="btn btn-primary">Login</a>';
                    echo '<a href="register.php" class="btn btn-secondary">Register</a>';
                }
                // Показываем кнопку выхода, если пользователь авторизован
                else {
                    echo '<a href="logout.php" class="btn btn-danger">Logout</a>';
                }
                ?>
                <a href="about.php">About</a>
            </div>
            <button class="navbar-toggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>
    <div class="container mt-3">
        <h1 class="mt-3 mb-3">Welcome to Movies Collection!</h1>
        <p>On this website, you can create your own collections of favorite movies and share them with others.</p>
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
    <script src="script.js"></script>
</body>
</html>





