<?php
require_once 'db.php';

// Если форма была отправлена, то обрабатываем данные
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Создаем объект класса DB и регистрируем пользователя
    $db = new DB();
    $result = $db->query('INSERT INTO users (email, password) VALUES (?, ?)', array($email, $password));

    // Если регистрация прошла успешно, то авторизуем пользователя
    if ($result) {
        session_start();
        $_SESSION['user_id'] = $db->getLastInsertId();
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
</head>
<body>
<h1>Registration</h1>
<form method="post">
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br>
    <br>
    <button type="submit">Register</button>
</form>
</body>
</html>
