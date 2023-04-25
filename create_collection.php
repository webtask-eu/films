<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $title = $_POST['title'];
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id'];

    // Подключаемся к базе данных
    $db = new DB();

    // Добавляем новую коллекцию в базу данных
    $result = $db->query('INSERT INTO collections (title, description, user_id) VALUES (?, ?, ?)', array($title, $description, $user_id));

    // Проверяем, что запрос выполнен успешно
    if ($result !== false) {
        // Если все ок, перенаправляем пользователя на страницу со списком коллекций
        header('Location: collections.php');
        exit();
    } else {
        // Если запрос не выполнен, выводим сообщение об ошибке
        echo 'Collection creation failed. Please try again.';
    }
}
?>
