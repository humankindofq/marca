<?php
require_once __DIR__ . '/includes/header.php';

$cart = $_SESSION['cart'] ?? [];
?>

    <div class="cart-page">
        <div class="container">
            <h2 class="section-title">Корзина</h2>

            <?php if (empty($cart)): ?>
                <div style="text-align: center; padding: 60px; background: var(--white); border-radius: 10px;">
                    <i class="fas fa-shopping-cart" style="font-size: 80px; color: var(--primary-gold); margin-bottom: 20px;"></i>
                    <h3 style="font-size: 24px; margin-bottom: 20px;">Ваша корзина пуста</h3>
                    <a href="/catalog.php" class="btn-primary">Перейти в каталог</a>
                </div>
            <?php else: ?>
                <div class="cart-items">
                    <h3 style="margin-bottom: 20px;">Товары:</h3>

                    <?php
                    $total = 0;
                    foreach ($cart as $item):
                        $itemTotal = $item['price'] * $item['quantity'];
                        $total += $itemTotal;
                        ?>
                        <div class="cart-item">
                            <div class="cart-item-info">
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                <div class="cart-item-price"><?php echo number_format($item['price'], 0, '.', ' '); ?> ₽/<?php echo htmlspecialchars($item['unit'] ?? 'шт'); ?></div>
                            </div>

                            <div class="quantity-control">
                                <button onclick="updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] - 1; ?>)">-</button>
                                <input type="number" value="<?php echo $item['quantity']; ?>" readonly>
                                <button onclick="updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] + 1; ?>)">+</button>
                            </div>

                            <div style="font-weight: bold; font-size: 18px;">
                                <?php echo number_format($itemTotal, 0, '.', ' '); ?> ₽
                            </div>

                            <button class="btn-remove" onclick="removeFromCart(<?php echo $item['product_id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-total">
                    <div class="total-amount">
                        Итого: <?php echo number_format($total, 0, '.', ' '); ?> ₽
                    </div>

                    <div class="order-form">
                        <h3 style="margin-bottom: 20px;">Оформление заказа</h3>

                        <form id="orderForm" onsubmit="handleOrderSubmit(event)">
                            <div class="form-group">
                                <label for="name">Ваше имя *</label>
                                <input type="text" id="name" name="name" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Телефон *</label>
                                <input type="tel" id="phone" name="phone" required placeholder="+7 (___) ___-__-__">
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email">
                            </div>

                            <div class="form-group">
                                <label for="address">Адрес доставки</label>
                                <textarea id="address" name="address" placeholder="Укажите адрес доставки"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="comment">Комментарий к заказу</label>
                                <textarea id="comment" name="comment" placeholder="Дополнительная информация"></textarea>
                            </div>

                            <button type="submit" class="btn-submit">
                                <i class="fas fa-paper-plane"></i> Оформить заказ
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function handleOrderSubmit(e) {
            e.preventDefault();

            const formData = {
                name: document.getElementById('name').value,
                phone: document.getElementById('phone').value,
                email: document.getElementById('email').value,
                address: document.getElementById('address').value,
                comment: document.getElementById('comment').value,
                cart: <?php echo json_encode($cart); ?>
            };

            submitOrder(formData);
        }
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>