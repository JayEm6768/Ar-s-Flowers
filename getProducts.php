
<?php

//Backe End php code to fetch from the data base if there is one, still cannot use


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database configuration
$host = 'localhost';
$dbname = 'inventory';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get filter parameters from request
    $category = $_GET['category'] ?? null;
    $minPrice = $_GET['min_price'] ?? null;
    $maxPrice = $_GET['max_price'] ?? null;
    $colors = isset($_GET['colors']) ? explode(',', $_GET['colors']) : [];
    $sizes = isset($_GET['sizes']) ? explode(',', $_GET['sizes']) : [];
    
    // Base query
    $query = "SELECT * FROM product WHERE available = 1";
    $params = [];
    
    // Add filters to query
    if ($category && $category !== 'all') {
        $query .= " AND category = :category";
        $params[':category'] = $category;
    }
    
    if ($minPrice !== null && $maxPrice !== null) {
        $query .= " AND price BETWEEN :min_price AND :max_price";
        $params[':min_price'] = $minPrice;
        $params[':max_price'] = $maxPrice;
    }
    
    if (!empty($colors)) {
        $placeholders = implode(',', array_fill(0, count($colors), '?'));
        $query .= " AND color IN ($placeholders)";
        $params = array_merge($params, $colors);
    }
    
    if (!empty($sizes)) {
        $placeholders = implode(',', array_fill(0, count($sizes), '?'));
        $query .= " AND size IN ($placeholders)";
        $params = array_merge($params, $sizes);
    }
    
    // Prepare and execute query
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    
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