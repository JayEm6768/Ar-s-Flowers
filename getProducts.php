<?php
// Enable detailed error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$host = 'localhost';
$dbname = 'inventory';
$username = 'root';
$password = '';

try {
    // Create PDO connection with enhanced settings
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Test connection
    $conn->query("SELECT 1");

    // Get filter parameters from request
    $category = $_GET['category'] ?? 'all';
    $minPrice = $_GET['min_price'] ?? null;
    $maxPrice = $_GET['max_price'] ?? null;
    $colors = isset($_GET['colors']) ? explode(',', $_GET['colors']) : [];
    
    // Base query - selecting all fields including description
    $query = "SELECT 
                flower_id, 
                name, 
                description, 
                price, 
                quantity, 
                size, 
                color, 
                available,
                image_url
              FROM product 
              WHERE available = 1";
    $params = [];
    
    // Add filters to query
    if ($category !== 'all') {
        $query .= " AND color = :category"; // Using color as category since your table doesn't have category
        $params[':category'] = $category;
    }
    
    if ($minPrice !== null && $maxPrice !== null) {
        $query .= " AND price BETWEEN :min_price AND :max_price";
        $params[':min_price'] = $minPrice;
        $params[':max_price'] = $maxPrice;
    }
    
    if (!empty($colors)) {
        $colorPlaceholders = [];
        foreach ($colors as $i => $color) {
            $param = ":color_$i";
            $colorPlaceholders[] = $param;
            $params[$param] = $color;
        }
        $query .= " AND color IN (" . implode(',', $colorPlaceholders) . ")";
    }
    
    // Prepare and execute query
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    
    // Fetch results
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Process products data
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    $uploadPath = '/dashboard/uploads/';
    $defaultImage = 'default-product.jpg';
    
    foreach ($products as &$product) {
        // Format price with 2 decimal places
        $product['price'] = number_format((float)$product['price'], 2, '.', '');
        
        // Ensure description is not null
        $product['description'] = $product['description'] ?? 'No description available';
        
        // Handle image URL - use default if empty or invalid
        $imageFile = !empty($product['image_url']) ? $product['image_url'] : $defaultImage;
        $product['image_url'] = $baseUrl . $uploadPath . $imageFile;
        
        // Add category (using color as category in this case)
        $product['category'] = strtolower($product['color']);
        
        // Add stock status
        $product['is_low_stock'] = $product['quantity'] < 10;
    }
    
    // Return JSON response
    echo json_encode([
        'success' => true,
        'data' => $products,
        'count' => count($products),
        'message' => 'Products loaded successfully'
    ]);
    
} catch(PDOException $e) {
    // Log error for debugging
    error_log('PDO Error: ' . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred',
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
}
?>