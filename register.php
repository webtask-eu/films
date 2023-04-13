<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // Проверка, что пароль и его подтверждение совпадают
  if ($password != $confirm_password) {
    die("Passwords do not match");
  }

  // Проверка, что адрес электронной почты был введен
  if (!filter_var($email, FILTER_VALIDATE,$email)) {
    die("Invalid email address");
    }
    
    // Проверка, что пользователь с таким адресом электронной почты еще не зарегистрирован
    $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
    die("User already registered");
    }
    
    // Хеширование пароля
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Добавление нового пользователя в базу данных
    $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $hashed_password]);
    
    // Успешная регистрация
    $_SESSION['email'] = $email;
    header("Location: confirm.php");
    exit();
    }

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Registration</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-HcJ0D1pjRsWt8207j7mJjkPfof/ZMY6it+XfM2Pcwo55jRGz0ksdQRVvoxMfooAo"
        crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <h1>Registration</h1>
    <form method="post" action="register.php">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Register</button>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
          integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
          crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
          integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI9MdX2Qcz8VI6zIcz0"
          crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
          integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
          crossorigin="anonymous"></script>
</body>
</html>
