<?php
require_once __DIR__ . '/includes/header.php';

$searchQuery = $_GET['search'] ?? null;
$categoryId = $_GET['category'] ?? null;

if ($searchQuery) {
    $products = searchProducts($pdo, $searchQuery);
} else {
    $products = getProducts($pdo, $categoryId);
}

$categories = getCategories($pdo);
?>

    <div class="catalog">
        <div class="container">
            <h2 class="section-title"><?php echo $searchQuery ? 'Результаты поиска' : 'Каталог продукции'; ?></h2>

            <?php if ($searchQuery): ?>
                <p style="text-align: center; margin-bottom: 30px; font-size: 18px;">
                    Найдено товаров: <?php echo count($products); ?>
                </p>
            <?php endif; ?>

            <div class="products-grid">
                <?php if (empty($products)): ?>
                    <p style="text-align: center; grid-column: 1/-1; font-size: 18px; color: #7f8c8d;">
                        Товары не найдены
                    </p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <i class="fas fa-cube"></i>
                            </div>
                            <div class="product-info">
                                <div class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></div>
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
                <?php endif; ?>
            </div>

            <!-- Услуги -->
            <div id="services" style="margin-top: 80px;">
                <h2 class="section-title">Дополнительные услуги</h2>

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
        </div>
    </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>