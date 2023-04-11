<?php
session_start();

require_once 'auth.php';

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Получение данных из формы
  $phone_number = $_POST['phone_number'];
  $password = $_POST['password'];

  // Регистрация пользователя
  register_user($phone_number, $password);

  // Перенаправление на страницу подтверждения кода подтверждения
  header('Location: confirm.php?phone_number=' . urlencode($phone_number));
  exit();
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Регистрация на сайте</title>
</head>
<body>
  <h1>Регистрация на сайте</h1>

  <form method="POST">
    <label>Номер телефона:</label><br>
    <input type="text" name="phone_number"><br><br>

    <label>Пароль:</label><br>
    <input type="password" name="password"><br><br>

    <input type="submit" value="Зарегистрироваться">
  </form>
</body>
</html>
