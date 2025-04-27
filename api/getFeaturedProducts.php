<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database configuration
$host = 'localhost';
$dbname = 'inventory';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Query to get 4 random featured products
    $query = "SELECT 
                flower_id as id, 
                name, 
                description, 
                price, 
                image_url,
                color
              FROM product 
              WHERE available = 1
              ORDER BY RAND()
              LIMIT 4";
    
    $stmt = $conn->query($query);
    $products = $stmt->fetchAll();
    
    // Process image URLs
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    $uploadPath = 'dashboard/uploads/';
    
    foreach ($products as &$product) {
        // Format price
        $product['price'] = number_format((float)$product['price'], 2, '.', '');
        
        // Handle image URL
        if (!empty($product['image_url'])) {
            $product['image_url'] = $baseUrl . $uploadPath . $product['image_url'];
        } else {
            $product['image_url'] = $baseUrl . $uploadPath . 'default-product.jpg';
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $products
    ]);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>