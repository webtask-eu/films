<?php
session_start();
require_once 'auth.php';

//$playlists = get_playlists(); // Функция, возвращающая список подборок из базы данных

?>

<!DOCTYPE html>
<html>
<head>
  <title>Мой сайт</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Мой сайт</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">Главная</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">О нас</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Контакты</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-3">
  <h1>Добро пожаловать на мой сайт</h1>
  <p>Здесь вы можете создавать свои подборки любимых фильмов и делиться ими по специальной ссылке.</p>
  
  <?php if (is_authenticated()): ?>
    <a href="logout.php" class="btn btn-primary">Выйти</a>
    <button type="button" class="btn btn-primary ml-3" data-toggle="modal" data-target="#createPlaylistModal">Создать подборку</button>
  <?php else: ?>
    <a href="login.php" class="btn btn-primary">Войти</a>
  <?php endif; ?>

  <hr>

  <!--div class="row">
    <?php foreach ($playlists as $playlist): ?>
      <div class="col-md-4 mb-4">
        <div class="card">
          <img src="<?php echo $playlist['image']; ?>" class="card-img-top" alt="<?php echo $playlist['name']; ?>">
          <div class="card-body">
            <h5 class="card-title"><?php echo $playlist['name']; ?></h>
           </div>
      <div class="card-footer">
        <p class="card-text"><small class="text-muted"><?php echo $playlist['likes']; ?> лайков</small></p>
        <a href="playlist.php?id=<?php echo $playlist['id']; ?>" class="btn btn-primary">Открыть подборку</a>
        <?php if (is_authenticated()): ?>
          <button type="button" class="btn btn-link" data-toggle="modal" data-target="#likePlaylistModal" data-playlist-id="<?php echo $playlist['id']; ?>">Поставить лайк</button>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div-->
</div>
<!-- Модальное окно для создания подборки -->
<div class="modal fade" id="createPlaylistModal" tabindex="-1" role="dialog" aria-labelledby="createPlaylistModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createPlaylistModalLabel">Создать подборку фильмов</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="create_playlist.php" method="post">
          <div class="form-group">
            <label for="name">Название</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="form-group">
            <label for="description">Описание</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label for="image">Изображение</label>
            <input type="text" class="form-control" id="image" name="image" required>
          </div>
          <button type="submit" class="btn btn-primary">Создать</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Модальное окно для постановки лайка -->
<div class="modal fade" id="likePlaylistModal" tabindex="-1" role="dialog" aria-labelledby="likePlaylistModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="likePlaylistModalLabel">Поставить лайк для подборки</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="like_playlist.php" method="post">
          <div class="form-group">
            <input type="hidden" class="form-control" id="likePlaylistId" name="playlist_id" required>
          </div>
          <button type="submit" class="btn btn-primary">Поставить лайк</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).on('click', '[data-target="#likePlaylistModal"]', function() {
    var playlistId = $(this).data('playlist-id');
    $('#likePlaylistId').val(playlistId);
  });
</script>
</body>
</html>