<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['product_id'], $data['name'], $data['price'])) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$productId = $data['product_id'];
$found = false;

foreach ($_SESSION['cart'] as &$item) {
    if ($item['product_id'] == $productId) {
        $item['quantity']++;
        $found = true;
        break;
    }
}

if (!$found) {
    $_SESSION['cart'][] = [
        'product_id' => $productId,
        'name' => $data['name'],
        'price' => $data['price'],
        'quantity' => 1,
        'unit' => $data['unit'] ?? 'шт'
    ];
}

echo json_encode([
    'success' => true,
    'count' => count($_SESSION['cart'])
]);
?>