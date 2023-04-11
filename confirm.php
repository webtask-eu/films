<?php
session_start();

require_once 'auth.php';

// Получение номера телефона из URL-параметра
$phone_number = $_GET['phone_number'];

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Получение данных из формы
  $code = $_POST['code'];

  // Подтверждение кода подтверждения
  if (confirm_code($phone_number, $code)) {
    // Если код подтверждения был успешно подтвержден, авторизация пользователя и перенаправление на главную страницу
    if (login_user($phone_number, $password)) {
      header('Location: index.php');
      exit();
    }
  } else {
    // Если код подтверждения был неправильным, показать ошибку
    $error = 'Неправильный код подтверждения';
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Подтверждение номера телефона</title>
</head>
<body>
  <h1>Подтверждение номера телефона</h1>

  <?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php endif; ?>

  <p>На номер телефона <?php echo $phone_number; ?> был отправлен код подтверждения. Введите код ниже:</p>

  <form method="POST">
    <label>Код подтверждения:</label><br>
    <input type="text" name="code"><br><br>

    <input type="submit" value="Подтвердить">
  </form>
</body>
</html>
