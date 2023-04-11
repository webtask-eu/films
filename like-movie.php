<?php
// Подключаем файлы с функциями работы с базой данных
require_once 'db.php';

// Получаем ID фильма из GET-параметра
$movieId = $_GET['id'];

// Увеличиваем количество лайков для фильма в базе данных
likeMovie($movieId);

// Перенаправляем на предыдущую страницу
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
