<?php
// Получаем строку поиска из запроса
$query = $_GET['q'];

// Формируем URL для запроса к API The Movie DB
$url = 'https://api.themoviedb.org/3/search/movie?api_key=fca80a35e9a4bccbf9a300c8e938e3e0&query=' . urlencode($query);

// Отправляем запрос и получаем ответ в формате JSON
$response = file_get_contents($url);

// Преобразуем ответ в массив PHP
$movies = json_decode($response, true)['results'];

// Отправляем результаты поиска в формате JSON
header('Content-Type: application/json');
echo json_encode($movies);
?>
