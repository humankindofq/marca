<?php require_once __DIR__ . '/includes/header.php'; ?>

    <!-- Герой секция -->
    <section class="hero">
        <div class="container">
            <h2>Железобетонные изделия от производителя</h2>
            <p>Высокое качество. Доступные цены. Доставка по всей России</p>
            <a href="/catalog.php" class="btn-primary">Смотреть каталог</a>
        </div>
    </section>

    <!-- Каталог продукции -->
    <section class="catalog">
        <div class="container">
            <h2 class="section-title">Каталог продукции</h2>

            <?php
            $categories = getCategories($pdo);
            foreach ($categories as $category):
                $products = getProducts($pdo, $category['id']);
                if (empty($products)) continue;
                ?>
                <div id="<?php echo $category['slug']; ?>" style="margin-bottom: 60px;">
                    <h3 style="font-size: 28px; margin-bottom: 30px; color: var(--primary-blue);">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </h3>

                    <div class="products-grid">
                        <?php foreach (array_slice($products, 0, 6) as $product): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <i class="fas fa-cube"></i>
                                </div>
                                <div class="product-info">
                                    <div class="product-category"><?php echo htmlspecialchars($category['name']); ?></div>
                                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>

                                    <?php if ($product['specifications']): ?>
                                        <div class="product-specs">
                                            <?php
                                            $specs = json_decode($product['specifications'], true);
                                            foreach ($specs as $key => $value): ?>
                                                <div><strong><?php echo htmlspecialchars($key); ?>:</strong> <?php echo htmlspecialchars($value); ?></div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="product-price">
                                        <?php echo number_format($product['price'], 0, '.', ' '); ?> ₽/<?php echo htmlspecialchars($product['unit']); ?>
                                    </div>

                                    <button class="btn-add-cart" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>)">
                                        <i class="fas fa-cart-plus"></i> В корзину
                                    </button>

                                    <a href="/product.php?slug=<?php echo $product['slug']; ?>" style="display: block; text-align: center; margin-top: 10px; color: var(--primary-blue); text-decoration: none;">
                                        Подробнее
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div style="text-align: center; margin-top: 40px;">
                <a href="/catalog.php" class="btn-primary">Весь каталог</a>
            </div>
        </div>
    </section>

    <!-- Услуги -->
    <section class="catalog" style="background: var(--light);">
        <div class="container">
            <h2 class="section-title">Наши услуги</h2>

            <div class="services-grid">
                <?php
                $services = getServices($pdo);
                foreach ($services as $service):
                    ?>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-<?php echo htmlspecialchars($service['icon']); ?>"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                        <?php if ($service['price']): ?>
                            <div class="service-price">от <?php echo number_format($service['price'], 0, '.', ' '); ?> ₽</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Преимущества -->
    <section class="catalog">
        <div class="container">
            <h2 class="section-title">Почему выбирают нас</h2>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3>Гарантия качества</h3>
                    <p>Вся продукция сертифицирована и соответствует ГОСТ</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Быстрая доставка</h3>
                    <p>Собственный автопарк. Доставка в срок от 1 дня</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-ruble-sign"></i>
                    </div>
                    <h3>Лучшие цены</h3>
                    <p>Работаем без посредников. Гибкая система скидок</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h3>Собственное производство</h3>
                    <p>Современное оборудование. Опыт работы более 10 лет</p>
                </div>
            </div>
        </div>
    </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>