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

$user = $user[0];

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
?>


<?php 

// Замените YOUR_API_KEY на свой API-ключ для The Movie Database API
$api_key = 'fca80a35e9a4bccbf9a300c8e938e3e0';

require_once 'vendor/autoload.php';
use GuzzleHttp\Client;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $search = $_POST['search'];
  
  // Создаем клиент GuzzleHttp для отправки запросов к API The Movie Database
  $client = new Client([
    'base_uri' => 'https://api.themoviedb.org/3/',
  ]);
  
  // Формируем URL-адрес для запроса поиска фильмов
  $url = 'search/movie?api_key=fca80a35e9a4bccbf9a300c8e938e3e0&query=' . urlencode($search);
  
  // Отправляем запрос к API и получаем ответ
  $response = $client->request('GET', $url);
  $data = json_decode($response->getBody()->getContents(), true);
  
  // Получаем список найденных фильмов
  $movies = $data['results'];
  
  // Выводим список найденных фильмов
  if (!empty($movies)) {
    echo '<ul>';
    foreach ($movies as $movie) {
      echo '<li>';
      echo '<input type="checkbox" name="movies[]" value="' . $movie['id'] . '"> ';
      echo $movie['title'];
      echo '</li>';
    }
    echo '</ul>';
    echo '<button type="submit">Add selected movies</button>';
  } else {
    echo 'No movies found.';
  }
}

?>