<?php
require_once __DIR__ . '/functions.php';
$pdo = getDBConnection();
$cartCount = getCartCount();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ООО "Марка" - Железобетонные изделия</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<!-- Верхняя панель -->
<div class="top-bar">
    <div class="container">
        <div class="top-bar-content">
            <div class="logo">
                <img src="/images/logo.png" alt="ООО Марка">
                <div class="logo-text">
                    <h1>ООО "МАРКА"</h1>
                    <span>Производство ЖБИ</span>
                </div>
            </div>

            <div class="search-box">
                <form action="/catalog.php" method="GET">
                    <input type="text" name="search" placeholder="Поиск товаров..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="contacts-top">
                <button class="btn-contacts" onclick="showContactsModal()">
                    <i class="fas fa-phone"></i>
                    <span>Контакты</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Навигация -->
<nav class="main-nav">
    <div class="container">
        <ul class="nav-menu">
            <li><a href="/">Главная</a></li>
            <li><a href="/catalog.php">Каталог</a></li>
            <li><a href="/catalog.php#concrete">Товарный бетон</a></li>
            <li><a href="/catalog.php#services">Услуги</a></li>
            <li><a href="/contacts.php">Контакты</a></li>
        </ul>

        <div class="cart-button">
            <a href="/cart.php">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count"><?php echo $cartCount; ?></span>
                <span class="cart-text">Корзина</span>
            </a>
        </div>
    </div>
</nav>

<!-- Модальное окно контактов -->
<div id="contactsModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeContactsModal()">&times;</span>
        <h2>Контактная информация</h2>
        <div class="contacts-info">
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <div>
                    <h4>Телефон</h4>
                    <p><?php echo ADMIN_PHONE; ?></p>
                </div>
            </div>
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <h4>Email</h4>
                    <p><?php echo ADMIN_EMAIL; ?></p>
                </div>
            </div>
            <div class="contact-item">
                <i class="fas fa-clock"></i>
                <div>
                    <h4>Режим работы</h4>
                    <p>Пн-Пт: 8:00 - 18:00</p>
                    <p>Сб: 9:00 - 14:00</p>
                    <p>Вс: выходной</p>
                </div>
            </div>
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h4>Адрес</h4>
                    <p>г. Москва, ул. Строителей, 25</p>
                </div>
            </div>
        </div>
    </div>
</div>