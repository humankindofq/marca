<?php require_once __DIR__ . '/includes/header.php'; ?>

    <div class="catalog">
        <div class="container">
            <h2 class="section-title">Контакты</h2>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 40px;">
                <div style="background: var(--white); padding: 40px; border-radius: 10px;">
                    <h3 style="margin-bottom: 30px; color: var(--primary-blue);">Контактная информация</h3>

                    <div class="contact-item" style="margin-bottom: 25px;">
                        <i class="fas fa-map-marker-alt" style="font-size: 24px; color: var(--primary-red); margin-right: 15px;"></i>
                        <div>
                            <h4 style="margin-bottom: 5px;">Адрес</h4>
                            <p>г. Москва, ул. Строителей, 25</p>
                        </div>
                    </div>

                    <div class="contact-item" style="margin-bottom: 25px;">
                        <i class="fas fa-phone" style="font-size: 24px; color: var(--primary-red); margin-right: 15px;"></i>
                        <div>
                            <h4 style="margin-bottom: 5px;">Телефон</h4>
                            <p><?php echo ADMIN_PHONE; ?></p>
                        </div>
                    </div>

                    <div class="contact-item" style="margin-bottom: 25px;">
                        <i class="fas fa-envelope" style="font-size: 24px; color: var(--primary-red); margin-right: 15px;"></i>
                        <div>
                            <h4 style="margin-bottom: 5px;">Email</h4>
                            <p><?php echo ADMIN_EMAIL; ?></p>
                        </div>
                    </div>

                    <div class="contact-item" style="margin-bottom: 25px;">
                        <i class="fas fa-clock" style="font-size: 24px; color: var(--primary-red); margin-right: 15px;"></i>
                        <div>
                            <h4 style="margin-bottom: 5px;">Режим работы</h4>
                            <p>Пн-Пт: 8:00 - 18:00</p>
                            <p>Сб: 9:00 - 14:00</p>
                            <p>Вс: выходной</p>
                        </div>
                    </div>

                    <div style="margin-top: 30px;">
                        <h4 style="margin-bottom: 15px;">Мы в социальных сетях:</h4>
                        <div class="social-links" style="margin-top: 15px;">
                            <a href="<?php echo VKONTAKTE_URL; ?>" target="_blank" style="width: 50px; height: 50px; font-size: 24px;">
                                <i class="fab fa-vk"></i>
                            </a>
                            <a href="<?php echo TELEGRAM_URL; ?>" target="_blank" style="width: 50px; height: 50px; font-size: 24px;">
                                <i class="fab fa-telegram"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div style="background: var(--white); padding: 40px; border-radius: 10px;">
                    <h3 style="margin-bottom: 30px; color: var(--primary-blue);">Напишите нам</h3>

                    <form style="display: flex; flex-direction: column; gap: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Ваше имя</label>
                            <input type="text" style="width: 100%; padding: 12px; border: 2px solid var(--primary-gold); border-radius: 5px;">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Телефон</label>
                            <input type="tel" style="width: 100%; padding: 12px; border: 2px solid var(--primary-gold); border-radius: 5px;">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Сообщение</label>
                            <textarea style="width: 100%; padding: 12px; border: 2px solid var(--primary-gold); border-radius: 5px; min-height: 150px;"></textarea>
                        </div>

                        <button type="submit" class="btn-submit" style="align-self: flex-start;">
                            <i class="fas fa-paper-plane"></i> Отправить
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>