<?php
// Подключаем файлы с функциями работы с базой данных
require_once 'db.php';
require_once 'functions.php';

// Получаем ID подборки фильмов из GET-параметра
$movieListId = $_GET['id'];

// Получаем информацию о выбранной подборке фильмов
$movieList = getMovieListById($movieListId);

// Если была отправлена форма добавления нового фильма в подборку
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-movie'])) {
    // Получаем данные из формы
    $name = $_POST['name'];
    $description = $_POST['description'];
    $imageFilename = null;

    // Если было загружено изображение, сохраняем его и получаем имя файла
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFilename = uploadImage($_FILES['image']);
    }

    // Создаем новый фильм в подборке в базе данных
    createMovie($movieListId, $name, $description, $imageFilename);

    // Перезагружаем страницу
    header("Location: movie-list.php?id=$movieListId");
    exit;
}

// Получаем список фильмов в выбранной подборке
$movies = getMoviesByMovieList($movieListId);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $movieList['name'] ?></title>
</head>
<body>
    <h1><?= $movieList['name'] ?></h1>
    <p><?= $movieList['description'] ?></p>
    <?php if ($movies): ?>
        <ul>
            <?php foreach ($movies as $movie): ?>
                <li>
                    <h2><?= $movie['name'] ?></h2>
                    <p><?= $movie['description'] ?></p>
                    <img src="uploads/<?= $movie['image_filename'] ?>" alt="<?= $movie['name'] ?>">
                    <p>Likes: <?= $movie['likes'] ?></p>
                    <form method="post" enctype="multipart/form-data">
                        <div>
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div>
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" required></textarea>
                        </div>
                        <div>
                            <label for="image">Image:</label>
                            <input type="file" id="image" name="image">
                        </div>
                        <button type="submit" name="add-movie">Add Movie</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No movies yet</p>
    <?php endif; ?>
</body>
</html>
