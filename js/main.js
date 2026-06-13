// Модальное окно контактов
function showContactsModal() {
    document.getElementById('contactsModal').style.display = 'block';
}

function closeContactsModal() {
    document.getElementById('contactsModal').style.display = 'none';
}

// Закрытие модалки при клике вне её
window.onclick = function(event) {
    const modal = document.getElementById('contactsModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Добавление в корзину
function addToCart(productId, productName, price) {
    fetch('/api/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            name: productName,
            price: price,
            quantity: 1
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount(data.count);
                showNotification('Товар добавлен в корзину');
            } else {
                alert('Ошибка при добавлении товара');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка');
        });
}

// Обновление счетчика корзины
function updateCartCount(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count;
    }
}

// Уведомление
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #27ae60;
        color: white;
        padding: 15px 25px;
        border-radius: 5px;
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Обновление количества в корзине
function updateQuantity(productId, quantity) {
    fetch('/api/update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
}

// Удаление из корзины
function removeFromCart(productId) {
    fetch('/api/remove_from_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
}

// Оформление заказа
function submitOrder(formData, submitBtn) {
    fetch('/api/submit_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Заказ успешно оформлен! Мы свяжемся с вами в ближайшее время.');
            window.location.href = '/';
        } else {
            // Если бэкенд вернул конкретные ошибки полей
            if (data.errors) {
                for (const [field, message] of Object.entries(data.errors)) {
                    showError(field, message);
                }
            } else {
                alert('Ошибка: ' + (data.message || 'Неизвестная ошибка'));
            }
            // Возвращаем кнопку
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Оформить заказ';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка сети. Попробуйте еще раз.');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Оформить заказ';
        }
    });
}

// Плавная прокрутка
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
