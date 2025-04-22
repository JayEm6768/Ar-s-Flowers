<?php
//not yet implemented


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database configuration
$host = 'localhost';
$dbname = 'inventory';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=localhost;dbname=inventory", 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // Query to get 3 random featured products (or you could order by popularity, etc.)
    $query = "SELECT id, name, price, image_url 
              FROM product 
              WHERE available = 1 AND featured = 1
              ORDER BY RAND() 
              LIMIT 3";
    
    // Prepare and execute query
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    // Fetch results
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return JSON response
    echo json_encode($products);
    
} catch(PDOException $e) {
    // Return error response
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>