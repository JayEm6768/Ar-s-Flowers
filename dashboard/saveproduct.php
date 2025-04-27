<?php
require 'db.php'; // Ensure $conn is correctly set up here

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect form data with safe fallbacks
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);
    $size = trim($_POST['size'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $available = intval($_POST['available'] ?? 0);
    $image_url = ''; // Initialize empty, will be set below

    // Basic validation
    if (
        empty($name) || empty($price) || $price < 0 ||
        $quantity < 0 || empty($size) || empty($color) || !is_numeric($available)
    ) {
        $error_message = urlencode("Invalid input. Please check all required fields.");
        header("Location: add_product.php?status=error&message=$error_message");
        exit;
    }

    // File upload handling
    if(isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        
        // Create uploads directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Validate file type and size
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedType = finfo_file($fileInfo, $_FILES['image_upload']['tmp_name']);
        finfo_close($fileInfo);

        if (!in_array($detectedType, $allowedTypes)) {
            $error_message = urlencode("Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.");
            header("Location: add_product.php?status=error&message=$error_message");
            exit;
        }

        if ($_FILES['image_upload']['size'] > $maxFileSize) {
            $error_message = urlencode("File too large. Maximum size is 2MB.");
            header("Location: add_product.php?status=error&message=$error_message");
            exit;
        }

        // Generate unique filename to prevent overwrites
        $extension = pathinfo($_FILES['image_upload']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $uploadFile = $uploadDir . $filename;
        
        // Move the uploaded file
        if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $uploadFile)) {
            $image_url = $filename; // Store only the filename in database
        } else {
            $error_message = urlencode("Failed to upload image. Please try again.");
            header("Location: add_product.php?status=error&message=$error_message");
            exit;
        }
    } else {
        // No file was uploaded or there was an error
        if ($_FILES['image_upload']['error'] !== UPLOAD_ERR_NO_FILE) {
            $error_message = urlencode("Image upload error: " . $_FILES['image_upload']['error']);
            header("Location: add_product.php?status=error&message=$error_message");
            exit;
        }
        
        // If no file was uploaded, check if there's a fallback URL
        $image_url = trim($_POST['image_url'] ?? '');
    }

    // Prepare SQL statement (flower_id auto-increments)
    $stmt = $conn->prepare("INSERT INTO product (name, description, price, quantity, size, color, available, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssdissis", $name, $description, $price, $quantity, $size, $color, $available, $image_url);

        if ($stmt->execute()) {
            $success_message = urlencode("Product '$name' added successfully!");
            header("Location: add_product.php?status=success&message=$success_message");
        } else {
            $error_message = urlencode("Failed to add product. Please try again.");
            header("Location: add_product.php?status=error&message=$error_message");
        }

        $stmt->close();
    } else {
        $error_message = urlencode("Database error. Please contact support.");
        header("Location: add_product.php?status=error&message=$error_message");
    }

    $conn->close();
} else {
    header("Location: add_product.php");
    exit;
}