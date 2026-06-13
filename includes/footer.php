<!-- Подвал -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>ООО "МАРКА"</h3>
                <p>Производство и продажа железобетонных изделий с 2010 года</p>
            </div>
            <div class="footer-section">
                <h4>Каталог</h4>
                <ul>
                    <li><a href="/catalog.php#fundament">Фундаментные блоки</a></li>
                    <li><a href="/catalog.php#plates">Плиты перекрытия</a></li>
                    <li><a href="/catalog.php#rings">Кольца колодезные</a></li>
                    <li><a href="/catalog.php#concrete">Товарный бетон</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Услуги</h4>
                <ul>
                    <li><a href="/catalog.php#delivery">Доставка</a></li>
                    <li><a href="/catalog.php#loading">Погрузка/разгрузка</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Контакты</h4>
                <p><i class="fas fa-phone"></i> <?php echo ADMIN_PHONE; ?></p>
                <p><i class="fas fa-envelope"></i> <?php echo ADMIN_EMAIL; ?></p>
                <div class="social-links">
                    <a href="<?php echo VKONTAKTE_URL; ?>" target="_blank"><i class="fab fa-vk"></i></a>
                    <a href="<?php echo TELEGRAM_URL; ?>" target="_blank"><i class="fab fa-telegram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> ООО "Марка". Все права защищены.</p>
        </div>
    </div>
</footer>

<script src="/js/main.js"></script>
</body>
</html>