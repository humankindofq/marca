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
              <div class="cart-item-price"><?php echo number_format($item['price'], 0, '.', ''); ?> ₽/<?php echo htmlspecialchars($item['unit'] ?? 'шт'); ?></div>
            </div>
            <div class="quantity-control">
              <button onclick="updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] - 1; ?>)">-</button>
              <input type="number" value="<?php echo $item['quantity']; ?>" readonly>
              <button onclick="updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] + 1; ?>)">+</button>
            </div>
            <div style="font-weight: bold; font-size: 18px;">
              <?php echo number_format($itemTotal, 0, '.', ''); ?> ₽
            </div>
            <button class="btn-remove" onclick="removeFromCart(<?php echo $item['product_id']; ?>)">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="cart-total">
        <div class="total-amount">
          Итого: <?php echo number_format($total, 0, '.', ''); ?> ₽
        </div>

        <div class="order-form">
          <h3 style="margin-bottom: 20px;">Оформление заказа</h3>
          <form id="orderForm" onsubmit="handleOrderSubmit(event)" novalidate>
            
            <div class="form-group">
              <label for="name">Ваше имя *</label>
              <input type="text" id="name" name="name" placeholder="Иван Иванов" maxlength="50">
              <span class="error-text" id="error-name"></span>
            </div>

            <div class="form-group">
              <label for="phone">Телефон *</label>
              <input type="tel" id="phone" name="phone" placeholder="+7 (___) ___-__-__" maxlength="18">
              <span class="error-text" id="error-phone"></span>
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" placeholder="example@mail.ru" maxlength="50">
              <span class="error-text" id="error-email"></span>
            </div>

            <div class="form-group">
              <label for="address">Адрес доставки *</label>
              <textarea id="address" name="address" placeholder="Город, улица, дом, квартира" maxlength="200"></textarea>
              <span class="error-text" id="error-address"></span>
            </div>

            <div class="form-group">
              <label for="comment">Комментарий к заказу</label>
              <textarea id="comment" name="comment" placeholder="Дополнительная информация" maxlength="300"></textarea>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
              <i class="fas fa-paper-plane"></i> Оформить заказ
            </button>
          </form>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
// 1. Маска для телефона
function formatPhone(value) {
    let digits = value.replace(/\D/g, '');
    if (digits.startsWith('8')) digits = '7' + digits.slice(1);
    if (!digits.startsWith('7')) digits = '7' + digits;
    
    let formatted = '+7';
    if (digits.length > 1) formatted += ' (' + digits.slice(1, 4);
    if (digits.length >= 4) formatted += ') ' + digits.slice(4, 7);
    if (digits.length >= 7) formatted += '-' + digits.slice(7, 9);
    if (digits.length >= 9) formatted += '-' + digits.slice(9, 11);
    return formatted;
}

document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            e.target.value = formatPhone(e.target.value);
        });
    }
});

// 2. Функция показа ошибок
function showError(fieldId, message) {
    const input = document.getElementById(fieldId);
    const errorEl = document.getElementById('error-' + fieldId);
    if(input) input.classList.add('input-error');
    if(errorEl) errorEl.textContent = message;
}

// 3. Строгая валидация перед отправкой
function validateForm() {
    let isValid = true;
    document.querySelectorAll('.error-text').forEach(el => el.textContent = '');
    document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

    // Имя: только буквы (кириллица/латиница), от 2 до 50 символов
    const name = document.getElementById('name').value.trim();
    if (!name) { showError('name', 'Введите имя'); isValid = false; }
    else if (!/^[а-яёa-z\s-]{2,50}$/i.test(name)) { showError('name', 'Только буквы, от 2 до 50 символов'); isValid = false; }

    // Телефон: проверяем, что введено ровно 11 цифр
    const phoneDigits = document.getElementById('phone').value.replace(/\D/g, '');
    if (!phoneDigits) { showError('phone', 'Введите телефон'); isValid = false; }
    else if (phoneDigits.length !== 11) { showError('phone', 'Введите полный номер телефона'); isValid = false; }

    // Email: если введен, проверяем формат
    const email = document.getElementById('email').value.trim();
    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showError('email', 'Некорректный формат email'); isValid = false; }

    // Адрес: обязателен, минимум 5 символов
    const address = document.getElementById('address').value.trim();
    if (!address) { showError('address', 'Введите адрес доставки'); isValid = false; }
    else if (address.length < 5) { showError('address', 'Адрес слишком короткий'); isValid = false; }

    return isValid;
}

// 4. Обработчик отправки
function handleOrderSubmit(e) {
    e.preventDefault();
    if (!validateForm()) {
        const firstError = document.querySelector('.input-error');
        if(firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return; 
    }

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Оформление...';

    const formData = {
        name: document.getElementById('name').value.trim(),
        phone: document.getElementById('phone').value.trim(),
        email: document.getElementById('email').value.trim(),
        address: document.getElementById('address').value.trim(),
        comment: document.getElementById('comment').value.trim(),
        cart: <?php echo json_encode($cart); ?>
    };
    
    submitOrder(formData, submitBtn);
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
