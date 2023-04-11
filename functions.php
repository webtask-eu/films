<?php
/**
 * Загружает изображение на сервер и возвращает имя файла.
 *
 * @param array $file Массив с данными о загружаемом файле.
 *
 * @return string Имя сохраненного файла.
 *
 * @throws Exception Если произошла ошибка при загрузке файла.
 */
function uploadImage($file)
{
    // Получаем расширение файла
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

    // Генерируем уникальное имя для файла
    $filename = uniqid() . '.' . $extension;

    // Сохраняем файл на сервере
    if (!move_uploaded_file($file['tmp_name'], 'uploads/' . $filename)) {
        throw new Exception('Failed to upload image');
    }

    // Возвращаем имя сохраненного файла
    return $filename;
}

// Функция для получения перевода фразы на текущий язык
function t($key)
{
    // Получаем текущий язык из сессии
    $language = $_SESSION['language'] ?? 'en';

    // Подключаем файл с переводом
    $translations = require "lang/$language.php";

    // Возвращаем перевод фразы, если он найден, или ключ фразы
    return $translations[$key] ?? $key;
}
