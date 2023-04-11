<?php
// Начинаем сессию
session_start();

// Подключаем файлы с функциями
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

// Получаем список подборок фильмов из базы данных
$movieLists = getMovieLists();

// Получаем список языков из файлов локализации
$languages = [
    'en' => t('english'),
    'ru' => t('russian'),
];

// Если была отправлена форма выбора языка
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['language'])) {
    // Получаем выбранный язык
    $language = $_POST['language'];

    // Проверяем, что выбранный язык поддерживается
    if (isset($languages[$language])) {
        // Сохраняем язык в сессии
        $_SESSION['language'] = $language;
    }
}

// Получаем текущий язык из сессии
$language = $_SESSION['language'] ?? 'en';
?>

<!doctype html>
<html lang="<?= $language ?>">
<head>
    <meta charset="UTF-8">
    <title><?= t('movie_lists') ?></title>
</head>
<body>
    <h1><?= t('movie_lists') ?></h1>

    <p><?= t('choose_language') ?>:</p>
    <form method="post">
        <?php foreach ($languages as $code => $name): ?>
            <label>
                <input type="radio" name="language" value="<?= $code ?>"<?= $language === $code ? ' checked' : '' ?>>
                <?= $name ?>
            </label><br>
        <?php endforeach; ?>
        <button type="submit"><?= t('save') ?></button>
    </form>

    <?php if (count($movieLists) === 0): ?>
        <p><?= t('no_movie_lists') ?></p>
    <?php else: ?>
        <ul>
            <?php foreach ($movieLists as $movieList): ?>
                <li>
                    <a href="movie_list.php?id=<?= $movieList['id'] ?>"><?= $movieList['name'] ?></a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="edit_movie_list.php?id=<?= $movieList['id'] ?>"><?= t('edit') ?></a>
                        <a href="delete_movie_list.php?id=<?= $movieList['id'] ?>" onclick="return confirm('<?= t('delete_confirmation') ?>')"><?= t('delete') ?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p><a href="create_movie_list.php"><?= t('create_movie_list') ?></a></p>
        <p><a href="logout.php"><?= t('logout') ?></a></p>
    <?php else: ?>
        <p><a href="login.php"><?= t('login') ?></a></p>
    <?php endif; ?>
</body>
</html>
