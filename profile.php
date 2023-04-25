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

<?php

require_once 'controller.php';
$controller = new Controller();

// Обрабатываем POST-запрос при нажатии на кнопку "Добавить"
if (isset($_POST['add_movie'])) {
    // Получаем данные фильма из POST-запроса
    $movie_id = $_POST['movie_id'];
    $title = $_POST['title'];
    $overview = $_POST['overview'];
    $poster_path = $_POST['poster_path'];
    $release_date = $_POST['release_date'];

    // Добавляем фильм в профиль пользователя
    $result = $controller->addMovieToCollection($_SESSION['user_id'], $movie_id, $title, $overview, $poster_path, $release_date);

    // Выводим сообщение об успешном добавлении фильма или об ошибке
    if ($result) {
        $success_message = 'Фильм успешно добавлен в коллекцию!';
    } else {
        $error_message = 'Произошла ошибка при добавлении фильма в коллекцию. Попробуйте ещё раз.';
    }
}

// Выводим найденные фильмы
if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Получаем список найденных фильмов с помощью The Movie Database API
    $url = 'https://api.themoviedb.org/3/search/movie?api_key=YOUR_API_KEY&language=ru-RU&query=' . urlencode($query);
    $response = file_get_contents($url);
    $result = json_decode($response, true);

    if ($result['results']) {
        echo '<h2>Результаты поиска</h2>';
        foreach ($result['results'] as $movie) {
            echo '<div class="card mb-3">';
            echo '<div class="row no-gutters">';
            echo '<div class="col-md-2">';
            if ($movie['poster_path']) {
                $poster_url = 'https://image.tmdb.org/t/p/w185' . $movie['poster_path'];
                echo '<img src="' . $poster_url . '" class="card-img" alt="' . htmlspecialchars($movie['title']) . '">';
            } else {
                echo '<div class="no-poster">Нет постера</div>';
            }
            echo '</div>';
            echo '<div class="col-md-10">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($movie['title']) . '</h5>';
            echo '<p class="card-text">' . htmlspecialchars($movie['overview']) . '</p>';
            echo '<p class="card-text"><small class="text-muted">Выход: ' . htmlspecialchars($movie['release_date']) . '</small></p>';
            echo '<form method="POST">';
            echo '<input type="hidden" name="movie_id" value="' . htmlspecialchars($movie['id']) . '">';
            echo '<input type="hidden" name="title" value="' . htmlspecialchars($movie['title']) . '">';
            echo '<input type="hidden" name="overview" value="' . htmlspecialchars($movie['overview']) . '">';
            echo '<input type="hidden" name="poster_path" value="' . htmlspecialchars($movie['poster_path']) . '">';
          
?>

<div class="search-results">
            <ul>
              <?php foreach ($movies as $movie) { ?>
                <li>
                  <h4><?php echo htmlspecialchars($movie['title']); ?></h4>
                  <p><?php echo htmlspecialchars($movie['overview']); ?></p>
                  <?php if ($movie['poster_path']) { ?>
                    <img src="https://image.tmdb.org/t/p/w500/<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="">
                  <?php } ?>
                  <form action="add-movie.php" method="post">
                    <input type="hidden" name="tmdb_id" value="<?php echo $movie['id']; ?>">
                    <input type="hidden" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>">
                    <input type="hidden" name="overview" value="<?php echo htmlspecialchars($movie['overview']); ?>">
                    <input type="hidden" name="poster_path" value="<?php echo htmlspecialchars($movie['poster_path']); ?>">
                    <button type="submit" name="add_movie">Добавить</button>
                  </form>
                </li>
              <?php } ?>
            </ul>
          </div>