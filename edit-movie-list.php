<?php
// Подключаем файлы с функциями работы с базой данных
require_once 'db.php';
require_once 'functions.php';

// Получаем ID подборки фильмов из GET-параметра
$movieListId = $_GET['id'];

// Получаем информацию о выбранной подборке фильмов
$movieList = getMovieListById($movieListId);

// Если была отправлена форма редактирования подборки фильмов
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-movie-list'])) {
    // Получаем данные из формы
    $name = $_POST['name'];
    $description = $_POST['description'];
    $imageFilename = null;

    // Если было загружено изображение, сохраняем его и получаем имя файла
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFilename = uploadImage($_FILES['image']);

        // Удаляем старое изображение, если оно было
        if ($movieList['image_filename']) {
            unlink('uploads/' . $movieList['image_filename']);
        }
    } else {
        // Иначе сохраняем старое изображение
        $imageFilename = $movieList['image_filename'];
    }

    // Обновляем информацию о подборке фильмов в базе данных
    updateMovieList($movieListId, $name, $description, $imageFilename);

    // Перенаправляем на страницу редактирования подборки фильмов
    header("Location: edit-movie-list.php?id=$movieListId");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Movie List</title>
</head>
<body>
    <h1>Edit Movie List</h1>

    <form method="post" enctype="multipart/form-data">
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= $movieList['name'] ?>" required>
        </div>
        <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?= $movieList['description'] ?></textarea>
        </div>
        <div>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image">
            <?php if ($movieList['image_filename']): ?>
                <img src="uploads/<?= $movieList['image_filename'] ?>" alt="<?= $movieList['name'] ?>">
            <?php endif; ?>
        </div>
        <button type="submit" name="update-movie-list">Save</button>
    </form>
</body>
</html>
