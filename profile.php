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

// Замените YOUR_API_KEY на свой API-ключ для The Movie Database API
$api_key = 'fca80a35e9a4bccbf9a300c8e938e3e0';

// Подключаем библиотеку для отправки HTTP-запросов
require_once 'vendor/autoload.php';
use GuzzleHttp\Client;

// Получаем запрос на поиск фильмов
if (isset($_GET['movie-search-input'])) {
    $movie_name = $_GET['movie-search-input'];

    // Создаем объект для отправки запросов
    $client = new Client(['base_uri' => 'https://api.themoviedb.org/3/']);

    // Отправляем запрос к The Movie Database API
    $response = $client->request('GET', 'search/movie', [
        'query' => [
            'api_key' => $api_key,
            'query' => $movie_name,
        ],
    ]);

    // Получаем результаты поиска
    $movies = json_decode($response->getBody(), true);

    // Выводим результаты на страницу
    $html = '<ul>';
    foreach ($movies['results'] as $movie) {
        $html .= '<li><a href="movie.php?id=' . $movie['id'] . '">' . $movie['title'] . '</a></li>';
    }
    $html .= '</ul>';
    echo $html;
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search = $_POST['search'];
    $url = 'https://api.themoviedb.org/3/search/movie?api_key=YOUR_API_KEY&query=' . urlencode($search);
    $response = file_get_contents($url);
    $movies = json_decode($response, true)['results'];
  
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
  } else {
    echo 'Invalid request.';
  }
  


echo '<form method="post">';
echo 'Old password: <input type="password" name="oldPassword"><br>';
echo 'New password: <input type="password" name="newPassword"><br>';
echo '<input type="submit" value="Change password">';
echo '</form>';

echo '<a href="logout.php">Logout</a>';
?>


<form action="add-movies.php" method="post">
  <input type="text" name="search" placeholder="Search movies...">
  <button type="submit">Search</button>
</form>
