<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['product_id'] ?? null;
$quantity = $data['quantity'] ?? 1;

if (!$productId || !isset($_SESSION['cart'])) {
    echo json_encode(['success' => false]);
    exit;
}

foreach ($_SESSION['cart'] as $key => &$item) {
    if ($item['product_id'] == $productId) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$key]);
        } else {
            $item['quantity'] = $quantity;
        }
        break;
    }
}

$_SESSION['cart'] = array_values($_SESSION['cart']);

echo json_encode(['success' => true]);
?>