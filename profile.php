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
$api_key = 'YOUR_API_KEY';

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

    // добавление фильма в профиль
if ($_POST['action'] == 'add_movie') {
  $movieId = $_POST['movie_id'];
  $title = $_POST['title'];
  $description = $_POST['description'];
  $userId = $_SESSION['user_id'];

// Проверяем, есть ли уже такой фильм в базе данных
$result = $db->query("SELECT * FROM user_movies WHERE user_id = ? AND movie_id = ?", array($userId, $movieId));
if ($result !== false && count($result) > 0) {
  echo "Movie already added";
} else {
  // Добавляем фильм в базу данных
  $db->query("INSERT INTO user_movies (user_id, movie_id) VALUES (?, ?)", array($userId, $movieId));
  echo "Movie added successfully";
}

}


echo '<form method="post">';
echo 'Old password: <input type="password" name="oldPassword"><br>';
echo 'New password: <input type="password" name="newPassword"><br>';
echo '<input type="submit" value="Change password">';
echo '</form>';

echo '<a href="logout.php">Logout</a>';
?>

<!-- скрипт для отправки запроса на добавление фильма в профиль -->
<script>
  $(document).ready(function() {
    // обработчик нажатия кнопки "Add to My Collection"
    $('#search-results').on('click', '.add-movie-btn', function(event) {
      event.preventDefault();
      var movieId = $(this).attr('data-movie-id');
      var title = $(this).attr('data-movie-title');
      var description = $(this).attr('data-movie-description');
      $('#add-movie-id').val(movieId);
      $('#add-movie-title').val(title);
      $('#add-movie-description').val(description);
      $('#add-movie-form').submit();
    });

    // обработчик отправки формы для добавления фильма в профиль
    $('#add-movie-form').on('submit', function(event) {
      event.preventDefault();
      $.ajax({
        url: 'profile.php',
        type: 'post',
        data: $('#add-movie-form').serialize(),
        success: function(response) {
          alert('Movie added to your collection!');
        },
        error: function(response) {
          alert('Error adding movie to your collection. Please try again later.');
        }
      });
    });
  });
</script>

<div class="movie-search">
    <form id="movie-search-form">
        <label for="movie-search-input">Search for a movie:</label>
        <input type="text" id="movie-search-input" name="movie-search-input">
        <button type="submit">Search</button>
    </form>
    <div id="movie-search-results"></div>
</div>

<!-- добавляем форму для добавления фильма в профиль -->
<form id="add-movie-form" method="post">
  <input type="hidden" name="action" value="add_movie">
  <input type="hidden" name="movie_id" id="add-movie-id">
  <div class="form-group">
    <label for="add-movie-title">Title:</label>
    <input type="text" class="form-control" id="add-movie-title" name="title" readonly>
  </div>
  <div class="form-group">
    <label for="add-movie-description">Description:</label>
    <textarea class="form-control" id="add-movie-description" name="description" readonly></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Add to My Collection</button>
</form>

<!-- вставляем результаты поиска в таблицу -->
<table class="table">
  <thead>
    <tr>
      <th>Title</th>
      <th>Description</th>
      <th>Add to Collection</th>
    </tr>
  </thead>
  <tbody id="search-results">
  </tbody>
</table>

