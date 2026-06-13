<?php
require_once __DIR__ . '/includes/header.php';

$slug = $_GET['slug'] ?? '';
$product = getProduct($pdo, $slug);

if (!$product) {
    header('Location: /catalog.php');
    exit;
}
?>

    <div class="catalog">
        <div class="container">
            <div style="background: var(--white); border-radius: 10px; padding: 40px; margin-top: 40px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                    <div>
                        <div style="height: 400px; background: linear-gradient(135deg, var(--primary-gold) 0%, var(--primary-brown) 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 120px; color: var(--white);">
                            <i class="fas fa-cube"></i>
                        </div>
                    </div>

                    <div>
                        <div style="color: var(--primary-blue); margin-bottom: 10px;">
                            <?php echo htmlspecialchars($product['category_name']); ?>
                        </div>

                        <h1 style="font-size: 36px; margin-bottom: 20px; color: var(--dark);">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h1>

                        <p style="font-size: 18px; color: #7f8c8d; margin-bottom: 30px;">
                            <?php echo htmlspecialchars($product['description']); ?>
                        </p>

                        <div style="font-size: 42px; color: var(--primary-red); font-weight: bold; margin-bottom: 30px;">
                            <?php echo number_format($product['price'], 0, '.', ' '); ?> ₽/<?php echo htmlspecialchars($product['unit']); ?>
                        </div>

                        <?php if ($product['specifications']): ?>
                            <div style="background: var(--light); padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                                <h3 style="margin-bottom: 15px;">Характеристики:</h3>
                                <?php
                                $specs = json_decode($product['specifications'], true);
                                foreach ($specs as $key => $value): ?>
                                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #ddd;">
                                        <span style="color: #7f8c8d;"><?php echo htmlspecialchars($key); ?></span>
                                        <span style="font-weight: 500;"><?php echo htmlspecialchars($value); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <button class="btn-add-cart" style="padding: 20px; font-size: 20px;"
                                onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>)">
                            <i class="fas fa-cart-plus"></i> Добавить в корзину
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>