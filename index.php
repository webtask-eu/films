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
<div class="navbar">
  <a href="/" class="navbar-brand">
    <img src="https://i.ss.com/img/p.gif" alt="Movies Collection Logo">
    <h1>Movies Collection</h1>
  </a>
  <div class="navbar-links">
    <a href="/work/are-required/new/" class="menu_main a_menu_active">Create Collection</a>
    <a href="/login/" class="menu_main a_menu">My Collections</a>
    <a href="/work/are-required/autohouse-painter/search/" class="menu_main a_menu">Search</a>
    <a href="/favorites/" class="menu_main a_menu">Bookmarks</a>
  </div>
  <div class="navbar-lang">
    <a href="/lv/login/?uri=/ru/work/are-required/autohouse-painter/new-step-1/&mode=login" class="a_menu">LV</a>
    <a href="/en/login/?uri=/ru/work/are-required/autohouse-painter/new-step-1/&mode=login" class="a_menu">EN</a>
  </div>
</div>

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





