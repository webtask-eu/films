<?php
// Подключаем файлы с функциями работы с базой данных
require_once 'db.php';
require_once 'functions.php';

// Подключаем библиотеку Hybridauth
require_once 'vendor/autoload.php';

// Получаем конфигурацию для Hybridauth из файла config.php
$config = require 'config.php';

// Создаем экземпляр класса Hybridauth
$hybridauth = new \Hybridauth\Hybridauth($config);

// Если пользователь уже авторизован, перенаправляем его на главную страницу
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Если была отправлена форма авторизации
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Получаем данные из формы
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Получаем пользователя с указанным телефоном из базы данных
    $user = getUserByPhone($phone);

    // Если пользователь не найден или пароль неверный, выводим сообщение об ошибке
    if (!$user || !password_verify($password, $user['password_hash'])) {
        $error = 'Invalid phone or password';
    } else {
        // Иначе авторизуем пользователя
        $_SESSION['user_id'] = $user['id'];

        // Перенаправляем на главную страницу
        header('Location: index.php');
        exit;
    }
}

// Если была нажата кнопка авторизации через Google
if (isset($_GET['provider']) && $_GET['provider'] === 'google') {
    // Создаем экземпляр адаптера Google
    $adapter = $hybridauth->getAdapter('Google');

    // Авторизуем пользователя через Google
    $adapter->authenticate();

    // Получаем информацию о пользователе
    $profile = $adapter->getUserProfile();

    // Получаем пользователя с указанным email из базы данных
    $user = getUserByEmail($profile->email);

    // Если пользователь не найден, создаем нового пользователя
    if (!$user) {
        $userId = createUser($profile->email, null, $profile->firstName, $profile->lastName);
    } else {
        $userId = $user['id'];
    }

    // Авторизуем пользователя
    $_SESSION['user_id'] = $userId;

    // Перенаправляем на главную страницу
    header('Location: index.php');
    exit;
}

// Если была нажата кнопка авторизации через Facebook
if (isset($_GET['provider']) && $_GET['provider'] === 'facebook') {
    // Создаем экземпляр адаптера Facebook
    $adapter = $hybridauth->getAdapter('Facebook');

    // Авторизуем пользователя через Facebook
    $adapter->authenticate();

    // Получаем информацию о пользователе
    $profile = $adapter->getUserProfile();

    // Получаем пользователя с указанным email из базы данных
    $user = getUserByEmail($profile->email);

    // Если пользователь не найден, создаем нового пользователя
    if (!$user) {
        $userId = createUser($profile->email, null, $profile->firstName, $profile->lastName);
    } else {
        $userId = $user['id'];
    }

    // Авторизуем пользователя
    $_SESSION['user_id'] = $userId;

    // Перенаправляем на главную страницу
    header('Location: index.php');
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php if (isset($error)): ?>
        <p><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <div>
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="login">Login</button>
    </form>

    <p>Or login with:</p>
    <ul>
        <li><a href="?provider=google">Google</a></li>
        <li><a href="?provider=facebook">Facebook</a></li>
    </ul>
</body>
</html>

