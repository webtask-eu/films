<?php

// Подключаем файл с настройками для базы данных
require_once 'config.php';

/**
 * Получает список подборок фильмов, отсортированных по рейтингу.
 *
 * @return array Список подборок фильмов.
 */
function getMovieLists()
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на получение списка подборок фильмов
    $statement = $pdo->query('SELECT * FROM movie_lists ORDER BY rating DESC');

    // Возвращаем результат запроса в виде массива
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Получает данные пользователя по ID.
 *
 * @param int $userId ID пользователя.
 *
 * @return array|null Данные пользователя или null, если пользователь не найден.
 */
function getUserById($userId)
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на получение данных пользователя
    $statement = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $statement->bindValue(':id', $userId, PDO::PARAM_INT);
    $statement->execute();

    // Возвращаем результат запроса в виде массива или null, если пользователь не найден
    return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Получает данные пользователя по номеру телефона.
 *
 * @param string $phone Номер телефона пользователя.
 *
 * @return array|null Данные пользователя или null, если пользователь не найден.
 */
function getUserByPhone($phone)
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на получение данных пользователя
    $statement = $pdo->prepare('SELECT * FROM users WHERE phone = :phone');
    $statement->bindValue(':phone', $phone, PDO::PARAM_STR);
    $statement->execute();

    // Возвращаем результат запроса в виде массива или null, если пользователь не найден
    return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Создает нового пользователя в базе данных.
 *
 * @param string $phone Номер телефона пользователя.
 * @param string $password Хэш пароля пользователя.
 *
 * @return int ID созданного пользователя.
 */
function createUser($phone, $password)
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на создание нового пользователя
    $statement = $pdo->prepare('INSERT INTO users (phone, password) VALUES (:phone, :password)');
    $statement->bindValue(':phone', $phone, PDO::PARAM_STR);
    $statement->bindValue(':password', $password, PDO::PARAM_STR);
    $statement->execute();

    // Возвращаем ID созданного пользователя
    return $pdo->lastInsertId();
}

/**
 * Получает список подборок фильмов, созданных пользователем с указанным ID.
 *
 * @param int $userId ID пользователя.
 *
 * @return array Список подборок фильмов.
 */
function getMovieListsByUser($userId)
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на получение списка подборок фильмов, созданных пользователем с указанным ID
    $statement = $pdo->prepare('SELECT * FROM movie_lists WHERE user_id = :user_id ORDER BY rating DESC');
    $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $statement->execute();

    // Возвращаем результат запроса в виде массива
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Получает подборку фильмов по ID.
 *
 * @param int $movieListId ID подборки фильмов.
 *
 * @return array|null Данные подборки фильмов или null, если подборка не найдена.
 */
function getMovieListById($movieListId)
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на получение данных подборки фильмов
    $statement = $pdo->prepare('SELECT * FROM movie_lists WHERE id = :id');
    $statement->bindValue(':id', $movieListId, PDO::PARAM_INT);
    $statement->execute();

    // Возвращаем результат запроса в виде массива или null, если подборка не найдена
    return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Создает новую подборку фильмов в базе данных.
 *
 * @param int $userId ID пользователя, создающего подборку фильмов.
 * @param string $name Название подборки фильмов.
 * @param string $description Описание подборки фильмов.
 * @param string $imageFilename Имя файла изображения для подборки фильмов.
 *
 * @return int ID созданной подборки фильмов.
 */
function createMovieList($userId, $name, $description, $imageFilename)
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на создание новой подборки фильмов
    $statement = $pdo->prepare('INSERT INTO movie_lists (user_id, name, description, image_filename) VALUES (:user_id, :name, :description, :image_filename)');
    $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $statement->bindValue(':name', $name, PDO::PARAM_STR);
    $statement->bindValue(':description', $description, PDO::PARAM_STR);
    $statement->bindValue(':image_filename', $imageFilename, PDO::PARAM_STR);
    $statement->execute();

    // Возвращаем ID созданной подборки фильмов
    return $pdo->lastInsertId();
}

/**
 * Обновляет данные подборки фильмов в базе данных.
 *
 * @param int $movieListId ID подборки фильмов.
 * @param string $name Название подборки фильмов.
 * @param string $description Описание подборки фильмов.
 * @param string $imageFilename Имя файла изображения для подборки фильмов.
 *
 * @return bool Результат обновления данных подборки фильмов.
 */
function updateMovieList($movieListId, $name, $description, $imageFilename)
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на обновление данных подборки фильмов
    $statement = $pdo->prepare('UPDATE movie_lists SET name = :name, description = :description, image_filename = :image_filename WHERE id = :id');
    $statement->bindValue(':id', $movieListId, PDO::PARAM_INT);
    $statement->bindValue(':name', $name, PDO::PARAM_STR);
    $statement->bindValue(':description', $description, PDO::PARAM_STR);
    $statement->bindValue(':image_filename', $imageFilename, PDO::PARAM_STR);
    return $statement->execute();
}

/**
 * Удаляет подборку фильмов из базы данных.
 *
 * @param int $movieListId ID подборки фильмов.
 *
 * @return bool Результат удаления подборки фильмов.
 */
function deleteMovieList($movieListId)
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на удаление подборки фильмов
    $statement = $pdo->prepare('DELETE FROM movie_lists WHERE id = :id');
    $statement->bindValue(':id', $movieListId, PDO::PARAM_INT);
    return $statement->execute();
}

/**
 * Получает список фильмов в подборке по ID.
 *
 * @param int $movieListId ID подборки фильмов.
 *
 * @return array Список фильмов в подборке.
 */
function getMoviesByMovieList($movieListId)
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на получение списка фильмов в подборке
    $statement = $pdo->prepare('SELECT * FROM movies WHERE movie_list_id = :movie_list_id');
    $statement->bindValue(':movie_list_id', $movieListId, PDO::PARAM_INT);
    $statement->execute();

    // Возвращаем результат запроса в виде массива
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Получает фильм по ID.
 *
 * @param int $movieId ID фильма.
 *
 * @return array|null Данные фильма или null, если фильм не найден.
 */
function getMovieById($movieId)
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на получение данных фильма
    $statement = $pdo->prepare('SELECT * FROM movies WHERE id = :id');
    $statement->bindValue(':id', $movieId, PDO::PARAM_INT);
    $statement->execute();

    // Возвращаем результат запроса в виде массива или null, если фильм не найден
    return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Создает новый фильм в подборке в базе данных.
 *
 * @param int $movieListId ID подборки фильмов.
 * @param string $name Название фильма.
 * @param string $description Описание фильма.
 * @param string $imageFilename Имя файла изображения для фильма.
 *
 * @return int ID созданного фильма.
 */
function createMovie($movieListId, $name, $description, $imageFilename)
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на создание нового фильма
    $statement = $pdo->prepare('INSERT INTO movies (movie_list_id, name, description, image_filename) VALUES (:movie_list_id, :name, :description, :image_filename)');
    $statement->bindValue(':movie_list_id', $movieListId, PDO::PARAM_INT);
    $statement->bindValue(':name', $name, PDO::PARAM_STR);
    $statement->bindValue(':description', $description, PDO::PARAM_STR);
    $statement->bindValue(':image_filename', $imageFilename, PDO::PARAM_STR);
    $statement->execute();

    // Возвращаем ID созданного фильма
    return $pdo->lastInsertId();
}

/**
 * Обновляет данные фильма в базе данных.
 *
 * @param int $movieId ID фильма.
 * @param string $name Название фильма.
 * @param string $description Описание фильма.
 * @param string $imageFilename Имя файла изображения для фильма.
 *
 * @return bool Результат обновления данных фильма.
 */
function updateMovie($movieId, $name, $description, $imageFilename)
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на обновление данных фильма
    $statement = $pdo->prepare('UPDATE movies SET name = :name, description = :description, image_filename = :image_filename WHERE id = :id');
    $statement->bindValue(':id', $movieId, PDO::PARAM_INT);
    $statement->bindValue(':name', $name, PDO::PARAM_STR);
    $statement->bindValue(':description', $description, PDO::PARAM_STR);
    $statement->bindValue(':image_filename', $imageFilename, PDO::PARAM_STR);
    return $statement->execute();
}

/**
 * Удаляет фильм из базы данных.
 *
 * @param int $movieId ID фильма.
 *
 * @return bool Результат удаления фильма.
 */
function deleteMovie($movieId)
{
    // Подключаемся к базе данных
    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);

    // Выполняем запрос на удаление фильма
    $statement = $pdo->prepare('DELETE FROM movies WHERE id = :id');
    $statement->bindValue(':id', $movieId, PDO::PARAM_INT);
    return $statement->execute();
}


