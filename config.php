<?php

// Параметры подключения к базе данных
define('DB_HOST', 'localhost');
define('DB_NAME', 'films');
define('DB_USER', 'films');
define('DB_PASSWORD', '_Uf5k2y91');

// Параметры для отправки SMS через сервис SMS Gateway
define('SMS_GATEWAY_URL', 'http://api.sms-gateway.com/v1/send');
define('SMS_GATEWAY_TOKEN', 'your_api_token_here');
define('SMS_GATEWAY_SENDER', 'YourApp');

// Язык по умолчанию
define('DEFAULT_LANGUAGE', 'en');

// Список поддерживаемых языков
$SUPPORTED_LANGUAGES = array(
    'en' => 'English',
    'ru' => 'Русский'
);
