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

    // Определение текущего языка (по умолчанию латышский)
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'lv';

// Загрузка файла локализации
$lang_file = 'lang/' . $lang . '.php';
if (file_exists($lang_file)) {
  $lang_strings = include $lang_file;
} else {
  $lang_strings = include 'lang/lv.php';
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
<div class="page-header">
  <div class="container">
    <div class="logo">
      <a href="/"><img src="logo.png" alt="Movies Collection"></a>
    </div>
    <nav class="navbar">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="#">Создать коллекцию</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Мои коллекции</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Избранное</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">О проекте</a>
    </li>
  </ul>
  <ul class="navbar-nav ml-auto">
    <?php foreach ($langs as $code => $name) {
      if ($code !== $lang) { ?>
        <li class="nav-item">
          <a href="?lang=<?= $code ?>" class="nav-link"><?= $name ?></a>
        </li>
      <?php }
    } ?>
    <li class="nav-item">
      <a class="nav-link" href="#">Вход</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Регистрация</a>
    </li>
  </ul>
</nav>

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





