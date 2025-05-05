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

    $update = $conn->query("UPDATE product SET 
        name='$name', 
        price=$price, 
        quantity=$quantity, 
        size='$size', 
        color='$color', 
        available=$available 
        WHERE flower_id=$flower_id
    ");

    if ($update) {
        $success = "Flower updated successfully!";
    } else {
        $error = "Failed to update flower.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Flower</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #4CAF50;
            --primary-hover: #45a049;
            --secondary: #007bff;
            --secondary-hover: #0056b3;
            --error-color: #dc3545;
            --background: #f4f6f9;
            --card-bg: #ffffff;
            --text-color: #333;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--background);
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: var(--card-bg);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: var(--primary);
            margin-bottom: 30px;
            font-size: 26px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: var(--text-color);
        }

        input[type="text"],
        input[type="number"] {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        button {
            padding: 12px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--primary-hover);
        }

        .btn-back {
            margin-top: 20px;
            text-align: center;
        }

        .btn-back a {
            color: var(--secondary);
            text-decoration: none;
            font-size: 14px;
        }

        .btn-back a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>✏️ Edit Flower</h2>

        <form method="POST">
            <div>
                <label>Flower Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($flower['name']) ?>" required>
            </div>
            <div>
                <label>Price (₱):</label>
                <input type="number" name="price" value="<?= $flower['price'] ?>" step="0.01" required>
            </div>
            <div>
                <label>Quantity:</label>
                <input type="number" name="quantity" value="<?= $flower['quantity'] ?>" required>
            </div>
            <div>
                <label>Size:</label>
                <input type="text" name="size" value="<?= htmlspecialchars($flower['size']) ?>" required>
            </div>
            <div>
                <label>Color:</label>
                <input type="text" name="color" value="<?= htmlspecialchars($flower['color']) ?>" required>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" name="available" <?= $flower['available'] ? 'checked' : '' ?>>
                <label>Available</label>
            </div>
            <button type="submit">💾 Update Flower</button>
        </form>

        <div class="btn-back">
            <a href="inventory.php">🔙 Back to Inventory</a>
        </div>
    </div>

    <?php if ($success): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $success ?>',
                confirmButtonColor: '#4CAF50'
            }).then(function() {
                window.location.href = 'inventory.php';
            });
        </script>
    <?php elseif ($error): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= $error ?>',
                confirmButtonColor: '#dc3545'
            });
        </script>
    <?php endif; ?>

</body>

</html>