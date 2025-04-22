<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$productId = $data['product_id'] ?? null;
$quantity = $data['quantity'] ?? 1;

try {
    $pdo = new PDO("mysql:host=localhost;dbname=inventory", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the product
    $stmt = $pdo->prepare("SELECT flower_id, name, price FROM product WHERE flower_id = ? AND available = 1");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        echo json_encode([
            'success' => true,
            'product' => $product
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Product not found or unavailable'
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>