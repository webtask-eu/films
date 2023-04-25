<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $movies = $_POST['movies'];
  $user_id = $_SESSION['user_id'];
  $db = new DB();

  foreach ($movies as $movie_id) {
    $db->query('INSERT INTO user_movies (user_id, movie_id) VALUES (?, ?)', array($user_id, $movie_id));
  }

  header('Location: profile.php');
  exit();
}
?>
