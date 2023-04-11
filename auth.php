<?php

// Подключение к базе данных
$servername = "localhost";
$username = "films";
$password = "_Uf5k2y91";
$dbname = "films";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Регистрация пользователя
function register_user($phone_number, $password) {
  global $conn;

  // Генерация случайного кода для подтверждения
  $code = rand(1000, 9999);

  // Сохранение данных пользователя в базу данных
  $sql = "INSERT INTO users (phone_number, password, code) VALUES ('$phone_number', '$password', '$code')";
  mysqli_query($conn, $sql);

  // Отправка SMS с подтверждением
  send_sms($phone_number, "Код подтверждения: $code");
}

// Подтверждение кода
function confirm_code($phone_number, $code) {
  global $conn;

  // Проверка соответствия кода в базе данных
  $sql = "SELECT id FROM users WHERE phone_number='$phone_number' AND code='$code'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    // Обновление статуса подтверждения и удаление кода из базы данных
    $sql = "UPDATE users SET confirmed=1, code=NULL WHERE phone_number='$phone_number'";
    mysqli_query($conn, $sql);
    return true;
  } else {
    return false;
  }
}

// Авторизация пользователя по номеру телефона и паролю
function login_user($phone_number, $password) {
  global $conn;

  // Проверка соответствия номера телефона и пароля в базе данных
  $sql = "SELECT id FROM users WHERE phone_number='$phone_number' AND password='$password' AND confirmed=1";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    // Создание сессии пользователя
    $_SESSION['user_id'] = mysqli_fetch_assoc($result)['id'];
    return true;
  } else {
    return false;
  }
}

// Авторизация пользователя через Google
function login_user_google($google_user_id) {
  global $conn;

  // Проверка соответствия ID пользователя Google в базе данных
  $sql = "SELECT id FROM users WHERE google_user_id='$google_user_id' AND confirmed=1";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    // Создание сессии пользователя
    $_SESSION['user_id'] = mysqli_fetch_assoc($result)['id'];
    return true;
  } else {
    return false;
  }
}

// Авторизация пользователя через Facebook
function login_user_facebook($facebook_user_id) {
  global $conn;

  // Проверка соотствия ID пользователя Facebook в базе данных
$sql = "SELECT id FROM users WHERE facebook_user_id='$facebook_user_id' AND confirmed=1";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
// Создание сессии пользователя
$_SESSION['user_id'] = mysqli_fetch_assoc($result)['id'];
return true;
} else {
return false;
}
}

// Отправка SMS
function send_sms($to, $message) {
  // Ваши учетные данные Twilio
  $account_sid = 'AC8a1e257f19c0c0220422ffaf4410190a';
  $auth_token = '5e9d8baca1be76c36be79df1d717df79';

  // Создание клиента Twilio
  $client = new Twilio\Rest\Client($account_sid, $auth_token);

  // Отправка SMS-сообщения
  $message = $client->messages->create(
    $to, // Номер телефона получателя
    array(
      'from' => 'YOUR_TWILIO_NUMBER', // Номер телефона Twilio, с которого отправляется SMS
      'body' => $message // Текст SMS-сообщения
    )
  );

  // Возврат идентификатора SMS-сообщения
  return $message->sid;
}

?>
