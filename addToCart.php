<?php
// Set headers for JSON response and CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Get and validate input data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON input'
    ]);
    exit;
}

$productId = $data['product_id'] ?? null;
$quantity = isset($data['quantity']) ? (int)$data['quantity'] : 1;

// Validate input
if (!$productId || !is_numeric($productId)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid product ID'
    ]);
    exit;
}

if ($quantity < 1) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Quantity must be at least 1'
    ]);
    exit;
}

// Database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=inventory", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Start transaction
    $pdo->beginTransaction();

    // Fetch product with all details
    $stmt = $pdo->prepare("SELECT 
                            flower_id, 
                            name, 
                            description,
                            price, 
                            image_url,
                            quantity as stock_quantity,
                            available,
                            size,
                            color
                          FROM product 
                          WHERE flower_id = ? FOR UPDATE");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if (!$product) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Product not found'
        ]);
        exit;
    }

    // Check product availability
    if (!$product['available']) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Product is not available'
        ]);
        exit;
    }

    // Check stock quantity
    if ($product['stock_quantity'] < $quantity) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Not enough stock available. Only ' . $product['stock_quantity'] . ' remaining.'
        ]);
        exit;
    }

    // Generate full image URL
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $baseUrl = $protocol . "://$_SERVER[HTTP_HOST]";
    $uploadPath = '/dashboard/uploads';

    $product['image_url'] = !empty($product['image_url'])
        ? $baseUrl . $uploadPath . $product['image_url']
        : $baseUrl . $uploadPath . 'default-product.jpg';

    // Format price consistently
    $product['price'] = number_format((float)$product['price'], 2, '.', '');

    // Update stock quantity 
    $updateStmt = $pdo->prepare("UPDATE product SET quantity = quantity - ? WHERE flower_id = ?");
    $updateStmt->execute([$quantity, $productId]);


    // Commit transaction
    $pdo->commit();

    // Return success response with complete product data
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'product' => $product,
        'quantity' => $quantity,
        'message' => 'Product added to cart successfully',
        'cart_total_items' => $quantity // You might want to calculate actual cart total
    ]);
} catch (PDOException $e) {
    // Roll back transaction if there was an error
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'code' => $e->getCode(),
        'trace' => $e->getTrace() // Remove in production
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
        'code' => $e->getCode()
    ]);
}
