<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$errors = [];

// --- СЕРВЕРНАЯ ВАЛИДАЦИЯ ---

// 1. Имя
$name = isset($data['name']) ? trim($data['name']) : '';
if (empty($name)) {
    $errors['name'] = 'Имя обязательно';
} elseif (!preg_match('/^[а-яёa-z\s-]{2,50}$/iu', $name)) {
    $errors['name'] = 'Имя должно содержать от 2 до 50 букв';
}

// 2. Телефон (оставляем только цифры)
$phone = isset($data['phone']) ? preg_replace('/\D/', '', $data['phone']) : '';
if (empty($phone)) {
    $errors['phone'] = 'Телефон обязателен';
} elseif (strlen($phone) !== 11 || ($phone[0] !== '7' && $phone[0] !== '8')) {
    $errors['phone'] = 'Некорректный номер телефона';
} else {
    $phone = '+7' . substr($phone, 1);
}

// 3. Email
$email = isset($data['email']) ? trim($data['email']) : '';
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Некорректный email';
}

// 4. Адрес
$address = isset($data['address']) ? trim($data['address']) : '';
if (empty($address)) {
    $errors['address'] = 'Введите адрес';
} elseif (mb_strlen($address) < 5) {
    $errors['address'] = 'Адрес слишком короткий';
}

// 5. Комментарий и Корзина
$comment = isset($data['comment']) ? trim($data['comment']) : '';
$cart = isset($data['cart']) && is_array($data['cart']) ? $data['cart'] : [];
if (empty($cart)) {
    $errors['cart'] = 'Корзина пуста';
}

// Если есть ошибки - сразу возвращаем их на фронтенд
if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// --- ОБРАБОТКА ЗАКАЗА ---
try {
    $pdo = getDBConnection();
    $pdo->beginTransaction();

    $totalAmount = 0;
    $validatedCart = [];
    
    // ИСПРАВЛЕНИЕ УЯЗВИМОСТИ: Берем цену из базы данных, а не от пользователя!
    foreach ($cart as $item) {
        $stmtPrice = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmtPrice->execute([$item['product_id']]);
        $realPrice = $stmtPrice->fetchColumn();
        
        if (!$realPrice) {
            throw new Exception("Товар с ID {$item['product_id']} не найден");
        }
        
        $totalAmount += $realPrice * $item['quantity'];
        $validatedCart[] = [
            'product_id' => $item['product_id'],
            'name' => $item['name'],
            'quantity' => $item['quantity'],
            'price' => $realPrice
        ];
    }

    $stmt = $pdo->prepare("
        INSERT INTO orders (customer_name, customer_phone, customer_email, delivery_address, total_amount, comment) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$name, $phone, $email ?: null, $address, $totalAmount, $comment ?: null]);
    $orderId = $pdo->lastInsertId();

    $stmt = $pdo->prepare("
        INSERT INTO order_items (order_id, product_id, product_name, quantity, price) 
        VALUES (?, ?, ?, ?, ?)
    ");
    foreach ($validatedCart as $item) {
        $stmt->execute([$orderId, $item['product_id'], $item['name'], $item['quantity'], $item['price']]);
    }

    $pdo->commit();

    $cleanData = [
        'name' => $name, 
        'phone' => $phone, 
        'email' => $email, 
        'address' => $address, 
        'comment' => $comment
    ];

    sendOrderEmail($orderId, $cleanData, $validatedCart, $totalAmount);
    sendTelegramNotification($orderId, $cleanData, $totalAmount);

    unset($_SESSION['cart']);
    echo json_encode(['success' => true, 'order_id' => $orderId]);

} catch (Exception $e) {
    if (isset($pdo)) $pdo->rollBack();
    error_log("Order submit error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Ошибка сервера. Попробуйте позже.']);
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

    error_log("ORDER EMAIL:\n" . $message);
}

function sendTelegramNotification($orderId, $data, $totalAmount) {
    // Здесь можно добавить отправку в Telegram через бота
}
?>
