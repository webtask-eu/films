<?php
session_start();

require_once 'auth.php';

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Получение данных из формы
  $phone_number = $_POST['phone_number'];
  $password = $_POST['password'];

  // Авторизация пользователя
  if (login_user($phone_number, $password)) {
    // Если авторизация прошла успешно, перенаправление на главную страницу
    header('Location: index.php');
    exit();
  } else {
    // Если авторизация не удалась, показать ошибку
    $error = 'Неправильный номер телефона или пароль';
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Вход на сайт</title>
</head>
<body>
  <h1>Вход на сайт</h1>
  
  <?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php endif; ?>

  <form method="POST">
    <label>Номер телефона:</label><br>
    <input type="text" name="phone_number"><br><br>

    <label>Пароль:</label><br>
    <input type="password" name="password"><br><br>

    <input type="submit" value="Войти">
  </form>
</body>
</html>
