<?php
session_start();

// Подключаем файл с функциями для работы с базой данных
require_once 'db.php';

// Если пользователь уже авторизован, перенаправляем его на главную страницу
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Если пользователь отправил форму регистрации
if (isset($_POST['email']) && isset($_POST['password'])) {
    // Получаем данные из формы
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Создаем объект для работы с базой данных
    $db = new DB();

    // Добавляем нового пользователя в базу данных
    $result = $db->query('INSERT INTO users (email, password) VALUES (?, ?)', array($email, password_hash($password, PASSWORD_DEFAULT)));

    // Если запрос выполнен успешно, то авторизуем пользователя
    if ($result) {
        // Получаем ID нового пользователя
        $user_id = $db->getLastInsertId();

        // Записываем ID пользователя в сессию
        $_SESSION['user_id'] = $user_id;

        // Перенаправляем пользователя на главную страницу
        header('Location: index.php');
        exit();
    } else {
        // Если запрос не выполнен, выводим сообщение об ошибке
        echo 'Registration failed. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container mt-3">
    <h1 class="mt-3 mb-3">Registration</h1>
    <form method="post">
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
</body>
</html>
