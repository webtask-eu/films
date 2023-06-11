<?php
session_start();

require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $db = new DB();

    $user = $db->query('SELECT * FROM users WHERE email = ?', array($email));

    // Добавим проверку, чтобы увидеть, что запрос возвращает
    if ($user === false) {
        echo 'SQL query failed. Please check your database connection and SQL statement.';
        exit();
    } elseif (empty($user)) {
        echo 'No user found with the provided email.';
        exit();
    }

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];

        header('Location: index.php');
        exit();
    } else {
        echo 'Password verification failed. Please check your password.';
        exit();
    }
} else {
    echo 'Login failed. Please check your email and password.';
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container mt-3">
    <h1 class="mt-3 mb-3">Login</h1>
    <form method="post">
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
</body>
</html>