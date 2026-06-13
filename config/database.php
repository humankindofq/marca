<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'marca_db');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8mb4');

// Настройки почты
define('ADMIN_EMAIL', 'info@marca.ru');
define('ADMIN_PHONE', '+7 (XXX) XXX-XX-XX');

// Социальные сети
define('VKONTAKTE_URL', 'https://vk.com/marca');
define('TELEGRAM_URL', 'https://t.me/marca');

function getDBConnection() {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }
}

session_start();
?>