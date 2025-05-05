<?php
session_start();
header('Content-Type: application/json');  // Ensure the response is JSON

// Get the cart
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['cart'])) {
        echo json_encode(['cart' => $_SESSION['cart']]);
    } else {
        echo json_encode(['cart' => []]);  // Empty cart if none exists
    }
}

// Update Cart - Add or Remove items
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $cartData = json_decode(file_get_contents('php://input'), true);  // Get JSON body

    // Example to add/update item in cart
    if ($cartData && isset($cartData['id'])) {
        // Retrieve current cart
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        // Check if item exists
        $itemExists = false;
        foreach ($cart as &$item) {
            if ($item['id'] === $cartData['id']) {
                $item['quantity'] = $cartData['quantity'];  // Update quantity
                $itemExists = true;
                break;
            }
        }

        // If item does not exist, add it to cart
        if (!$itemExists) {
            $cart[] = $cartData;
        }

        $_SESSION['cart'] = $cart;  // Save the updated cart

        echo json_encode(['success' => true, 'message' => 'Cart updated']);
    } else {
        echo json_encode(['error' => 'Invalid item data']);
    }
}
// Remove item from cart
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $cartData = json_decode(file_get_contents('php://input'), true);  // Get JSON body

    if (isset($cartData['id'])) {
        // Retrieve current cart
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        // Filter out item by id
        $cart = array_filter($cart, function ($item) use ($cartData) {
            return $item['id'] !== $cartData['id'];
        });

        $_SESSION['cart'] = array_values($cart);  // Reindex array and save the cart
        echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
    } else {
        echo json_encode(['error' => 'Invalid item ID']);
    }
}
