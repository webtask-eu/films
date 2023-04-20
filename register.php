<?php
require_once 'db.php';

// Проверяем, была ли отправлена форма регистрации
if (isset($_POST['register'])) {

    // Получаем данные из формы регистрации
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Хешируем пароль
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Подготавливаем SQL-запрос на проверку наличия пользователя с таким же email
    $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Если пользователь с таким email уже есть, выводим ошибку
    if ($user) {
        echo "Пользователь с таким email уже зарегистрирован.";
        exit();
    }

    // Если пользователь с таким email не найден, добавляем его в базу данных
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email, 'password' => $password_hashed]);

    // Автоматически логиним пользователя после регистрации
    $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['user_id'] = $user['id'];
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Регистрация нового пользователя</title>
</head>
<body>
    <h1>Регистрация нового пользователя</h1>
    <form method="POST">
        <label>E-mail:</label>
        <input type="email" name="email" required><br>
        <label>Пароль:</label>
        <input type="password" name="password" required><br>
        <input type="submit" name="register" value="Зарегистрироваться">
    </form>
</body>
</html>
