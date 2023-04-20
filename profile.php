<?php
require_once 'db.php';

session_start();

require_once 'controller.php';
$controller = new Controller();

// Проверяем, авторизован ли пользователь
if (!$controller->isLoggedIn()) {
  header('Location: index.php');
  exit();
}

// Получаем информацию о пользователе
$db = new DB();
$user = $db->query('SELECT * FROM users WHERE id = ?', array($_SESSION['user_id']));
if (!$user) {
  die('Failed to get user info');
}

print_r($user);

echo $_SESSION['user_id'];
//$user = $user[0];

// Выводим информацию о пользователе
echo 'Welcome, ' . $user['id'] . '!<br>';
echo 'Your email: ' . $user['email'] . '<br>';
echo 'Your password: ' . $user['password'] . '<br>';

// Форма для изменения пароля
echo '<h2>Change password</h2>';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Обработка формы
  $oldPassword = $_POST['oldPassword'];
  $newPassword = $_POST['newPassword'];

  // Проверяем, что старый пароль введен верно
  if (password_verify($oldPassword, $user['password'])) {
    // Обновляем пароль в базе данных
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $result = $db->query('UPDATE users SET password = ? WHERE id = ?', array($newPasswordHash, $_SESSION['user_id']));
    if ($result) {
      echo 'Password changed successfully!';
    } else {
      echo 'Failed to change password. Please try again.';
    }
  } else {
    echo 'Incorrect old password. Please try again.';
  }
}

echo '<form method="post">';
echo 'Old password: <input type="password" name="oldPassword"><br>';
echo 'New password: <input type="password" name="newPassword"><br>';
echo '<input type="submit" value="Change password">';
echo '</form>';

echo '<a href="logout.php">Logout</a>';