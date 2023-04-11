<?php

// Параметры подключения к базе данных
$host = 'localhost';
$dbname = 'movie_lists';
$username = 'username';
$password = 'password';

// Подключение к базе данных
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Функция для выполнения запроса и получения одной строки данных
function queryOne($sql, $params = []) {
    global $db;

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Функция для выполнения запроса и получения нескольких строк данных
function queryAll($sql, $params = []) {
    global $db;

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Функция для выполнения запроса и получения количества строк, которые были затронуты запросом
function query($sql, $params = []) {
    global $db;

    $stmt = $db->prepare($sql);
    return $stmt->execute($params);
}
