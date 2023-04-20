<?php
session_start();
require_once 'controller.php';
$controller = new Controller();

// Если пользователь авторизован, перенаправляем его на страницу профиля
if ($controller->isLoggedIn()) {
    $email = $_POST['email'];
    $password = $_POST['password'];
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

<?php 

// Определение текущего языка (по умолчанию латышский)
if (isset($_GET['lang'])) {
  $lang = $_GET['lang'];
} else {

    // Получаем язык браузера
  $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  echo $browser_lang;
  // Массив поддерживаемых языков
  $supported_langs = ['en', 'ru', 'lv'];

  // Проверяем, поддерживается ли язык браузера
  if (in_array($browser_lang, $supported_langs)) {
    // Используем язык браузера
    $lang = $browser_lang;
  } else {
    // Используем язык по умолчанию
    $lang = 'en';
  }
}




/*
// Загрузка файла локализации
$lang_file = 'lang/' . $lang . '.php';
if (file_exists($lang_file)) {
$lang_strings = include $lang_file;
} else {
$lang_strings = include 'lang/lv.php';
}
*/
//$lang = 'en'; // текущий язык
$localization = include('lang/' . $lang . '.php');

foreach ($localization as $key => $value) {
  $$key = $value;
}

$langs = array(
  'lv' => 'LV',
  'ru' => 'RU',
  'en' => 'EN'
);   
?>


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
          <a class="nav-link" href="#"><?php echo $menu_create_collection; ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo $menu_my_collections; ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo $menu_favorites; ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo $menu_about; ?></a>
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
          <a class="nav-link" href="#"> <?php echo $menu_login; ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/register.php"> <?php echo $menu_register; ?></a>
        </li>
      </ul>
    </nav>

  </div>
</div>

<div class="container mt-3">
  <h1 class="mt-3 mb-3"><?php echo $welcome_message; ?></h1>
  <p><?php echo $website_description; ?></p>
  <h2 class="mt-3"><?php echo $collections_title; ?></h2>
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
      echo '<p>' . $no_collections_message . '</p>';
    }
  ?>
</div>

<script src="script.js"></script>
</body>
</html>





