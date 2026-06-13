<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name'], $data['phone'], $data['cart']) || empty($data['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

try {
    $pdo = getDBConnection();
    $pdo->beginTransaction();

    // Рассчитываем общую сумму
    $totalAmount = 0;
    foreach ($data['cart'] as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }

    // Создаем заказ
    $stmt = $pdo->prepare("
        INSERT INTO orders (customer_name, customer_phone, customer_email, delivery_address, total_amount, comment)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data['name'],
        $data['phone'],
        $data['email'] ?? null,
        $data['address'] ?? null,
        $totalAmount,
        $data['comment'] ?? null
    ]);

    $orderId = $pdo->lastInsertId();

    // Добавляем товары заказа
    $stmt = $pdo->prepare("
        INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($data['cart'] as $item) {
        $stmt->execute([
            $orderId,
            $item['product_id'],
            $item['name'],
            $item['quantity'],
            $item['price']
        ]);
    }

    $pdo->commit();

    // Отправляем email
    sendOrderEmail($orderId, $data, $data['cart'], $totalAmount);

    // Отправляем уведомление в Telegram (если настроено)
    sendTelegramNotification($orderId, $data, $totalAmount);

    // Очищаем корзину
    unset($_SESSION['cart']);

    echo json_encode(['success' => true, 'order_id' => $orderId]);

} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function sendOrderEmail($orderId, $data, $cart, $totalAmount) {
    $subject = "Новый заказ #" . $orderId . " от ООО Марка";

    $message = "Новый заказ с сайта!\n\n";
    $message .= "Заказ #" . $orderId . "\n";
    $message .= "Дата: " . date('d.m.Y H:i') . "\n\n";
    $message .= "Клиент:\n";
    $message .= "Имя: " . $data['name'] . "\n";
    $message .= "Телефон: " . $data['phone'] . "\n";
    if (!empty($data['email'])) {
        $message .= "Email: " . $data['email'] . "\n";
    }
    if (!empty($data['address'])) {
        $message .= "Адрес доставки: " . $data['address'] . "\n";
    }
    if (!empty($data['comment'])) {
        $message .= "Комментарий: " . $data['comment'] . "\n";
    }

    $message .= "\nТовары:\n";
    foreach ($cart as $item) {
        $message .= "- " . $item['name'] . " x" . $item['quantity'] . " = " . number_format($item['price'] * $item['quantity'], 0, '.', ' ') . " ₽\n";
    }

    $message .= "\nИтого: " . number_format($totalAmount, 0, '.', ' ') . " ₽";

    // Для локальной разработки просто логируем
    error_log("ORDER EMAIL:\n" . $message);

    // В продакшене раскомментируйте:
    // mail(ADMIN_EMAIL, $subject, $message);
}

function sendTelegramNotification($orderId, $data, $totalAmount) {
    // Здесь можно добавить отправку в Telegram через бота
    // Пример:
    /*
    $token = "YOUR_BOT_TOKEN";
    $chatId = "YOUR_CHAT_ID";

    $message = "🛒 Новый заказ #" . $orderId . "\n";
    $message .= "👤 " . $data['name'] . "\n";
    $message .= "📞 " . $data['phone'] . "\n";
    $message .= "💰 " . number_format($totalAmount, 0, '.', ' ') . " ₽";

    $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
    $params = [
        'chat_id' => $chatId,
        'text' => $message
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
    */
}
?>