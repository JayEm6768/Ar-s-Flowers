<?php
include 'db.php';

// Initialize variables
$success = '';
$error = '';

// Check if id is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Error: No flower ID specified.');
}

$flower_id = intval($_GET['id']);

// Fetch flower data
$result = $conn->query("SELECT * FROM product WHERE flower_id = $flower_id");
if ($result->num_rows !== 1) {
    die('Error: Flower not found.');
}
$flower = $result->fetch_assoc();

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $size = $conn->real_escape_string($_POST['size']);
    $color = $conn->real_escape_string($_POST['color']);
    $available = isset($_POST['available']) ? 1 : 0;
    $description = $conn->real_escape_string($_POST['description']);

    // Handle image upload
    $image_url = $flower['image_url']; // Keep existing image by default

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/products/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generate unique filename
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'product_' . $flower_id . '_' . time() . '.' . $file_extension;
        $target_file = $upload_dir . $filename;

        // Check if image file is valid
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (
            in_array(strtolower($file_extension), $allowed_types) &&
            $_FILES['image']['size'] <= $max_size
        ) {

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Delete old image if it exists and isn't the default
                if (!empty($flower['image_url']) && strpos($flower['image_url'], 'default') === false) {
                    @unlink($flower['image_url']);
                }
                $image_url = $target_file;
            }
        }
    }

    $update = $conn->query("UPDATE product SET 
        name='$name', 
        price=$price, 
        quantity=$quantity, 
        size='$size', 
        color='$color', 
        available=$available,
        description='$description',
        image_url='$image_url'
        WHERE flower_id=$flower_id
    ");

    if ($update) {
        $success = "Product updated successfully!";
        // Refresh flower data
        $result = $conn->query("SELECT * FROM product WHERE flower_id = $flower_id");
        $flower = $result->fetch_assoc();
    } else {
        $error = "Failed to update product: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Product | Floral Inventory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #6c5ce7;
            --primary-hover: #5649c0;
            --secondary: #00b894;
            --secondary-hover: #00a884;
            --danger: #d63031;
            --danger-hover: #b52b2b;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --dark-gray: #495057;
            --text-color: #2d3436;
            --border-radius: 8px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--medium-gray);
        }

        .header h2 {
            color: var(--primary);
            font-size: 24px;
            font-weight: 600;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 15px;
            background-color: var(--medium-gray);
            color: var(--dark-gray);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background-color: #dee2e6;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-gray);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--medium-gray);
            border-radius: var(--border-radius);
            font-size: 15px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
        }

        .checkbox-container input {
            margin-right: 10px;
        }

        .image-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .image-preview {
            width: 200px;
            height: 200px;
            border-radius: var(--border-radius);
            overflow: hidden;
            border: 1px solid var(--medium-gray);
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .upload-btn {
            position: relative;
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--primary);
            color: white;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .upload-btn:hover {
            background-color: var(--primary-hover);
        }

        .upload-btn input {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            grid-column: span 2;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: var(--danger-hover);
        }

        .file-info {
            font-size: 13px;
            color: var(--dark-gray);
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-edit"></i> Edit Product</h2>
            <a href="inventory.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Inventory
            </a>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <!-- Left Column -->
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="<?= htmlspecialchars($flower['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="price">Price (₱)</label>
                    <input type="number" id="price" name="price" class="form-control"
                        value="<?= $flower['price'] ?>" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label for="quantity">Stock Quantity</label>
                    <input type="number" id="quantity" name="quantity" class="form-control"
                        value="<?= $flower['quantity'] ?>" min="0" required>
                </div>

                <div class="form-group">
                    <label for="size">Size</label>
                    <input type="text" id="size" name="size" class="form-control"
                        value="<?= htmlspecialchars($flower['size']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="color">Color</label>
                    <input type="text" id="color" name="color" class="form-control"
                        value="<?= htmlspecialchars($flower['color']) ?>" required>
                </div>

                <div class="form-group">
                    <div class="checkbox-container">
                        <input type="checkbox" id="available" name="available"
                            <?= $flower['available'] ? 'checked' : '' ?>>
                        <label for="available">Available for purchase</label>
                    </div>
                </div>

                <!-- Right Column - Image Upload -->
                <div class="form-group image-upload">
                    <label>Product Image</label>
                    <div class="image-preview">
                        <?php if (!empty($flower['image_url'])): ?>
                            <img src="<?= $flower['image_url'] ?>" alt="Current product image">
                        <?php else: ?>
                            <i class="fas fa-image" style="font-size: 48px; color: #ccc;"></i>
                        <?php endif; ?>
                    </div>
                    <label class="upload-btn">
                        <i class="fas fa-upload"></i> Choose New Image
                        <input type="file" name="image" accept="image/*">
                    </label>
                    <div class="file-info">
                        Max size: 5MB (JPEG, PNG, GIF, WEBP)
                    </div>
                </div>

                <!-- Full Width Fields -->
                <div class="form-group full-width">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control"><?= htmlspecialchars($flower['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Product
                </button>
                <a href="inventory.php" class="btn btn-danger">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>

    <?php if ($success): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $success ?>',
                confirmButtonColor: 'var(--primary)',
                willClose: () => {
                    window.location.href = 'inventory.php';
                }
            });
        </script>
    <?php elseif ($error): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= $error ?>',
                confirmButtonColor: 'var(--danger)'
            });
        </script>
    <?php endif; ?>

    <script>
        // Preview image before upload
        document.querySelector('input[name="image"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.querySelector('.image-preview').innerHTML =
                        `<img src="${event.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>