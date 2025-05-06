<?php
include 'db.php';

$success = '';
$error = '';

// Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $sale_date = $_POST['sale_date'];

    if ($quantity <= 0) {
        $error = "Quantity must be greater than 0.";
    } else {
        // Fetch current stock
        $result = $conn->query("SELECT quantity, name FROM product WHERE flower_id = $product_id");
        if ($result->num_rows === 0) {
            $error = "Product ID does not exist.";
        } else {
            $product = $result->fetch_assoc();
            $current_stock = $product['quantity'];
            $product_name = htmlspecialchars($product['name']);

            if ($current_stock < $quantity) {
                $error = "Not enough stock. Available: $current_stock.";
            } else {
                // Insert sale and update stock
                $stmt = $conn->prepare("INSERT INTO sales (product_id, quantity, sale_date) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $product_id, $quantity, $sale_date);
                $stmt->execute();

                $conn->query("UPDATE product SET quantity = quantity - $quantity WHERE flower_id = $product_id");

                $remaining = $current_stock - $quantity;
                $success = "Sale recorded for <strong>$product_name</strong>. Remaining stock: <strong>$remaining</strong>";
            }
        }
    }
}

// Fetch product list
$products = $conn->query("SELECT flower_id, name, quantity FROM product");

// Fetch sales history
$sales_history = $conn->query("
    SELECT s.id, p.name AS product_name, s.quantity, s.sale_date 
    FROM sales s 
    JOIN product p ON s.product_id = p.flower_id 
    ORDER BY s.sale_date DESC, s.id DESC 
    LIMIT 20
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Sale - Ar's Flowers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8e44ad;
            --primary-light: #9b59b6;
            --primary-dark: #7d3c98;
            --secondary: #3498db;
            --secondary-light: #5dade2;
            --error: #e74c3c;
            --success: #2ecc71;
            --warning: #f39c12;
            --dark: #2c3e50;
            --light: #ecf0f1;
            --gray: #95a5a6;
            --light-gray: #bdc3c7;
            --white: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            padding: 0;
        }

        .header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 25px 30px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .header i {
            font-size: 26px;
        }

        .form-container {
            padding: 30px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .alert-success {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        .alert-error {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--error);
            border-left: 4px solid var(--error);
        }

        .alert i {
            font-size: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 20px;
            }
        }

        .form-group {
            flex: 1;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
            background-color: var(--white);
        }

        .form-control:focus {
            border-color: var(--primary-light);
            outline: none;
            box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.2);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 15px;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--white);
        }

        .btn-secondary:hover {
            background-color: var(--secondary-light);
            transform: translateY(-2px);
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .section-title {
            margin: 2.5rem 0 1rem;
            font-size: 1.3rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--light-gray);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        table th,
        table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
        }

        table th {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        table tr:nth-child(even) {
            background-color: rgba(142, 68, 173, 0.05);
        }

        table tr:hover {
            background-color: rgba(142, 68, 173, 0.1);
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--light-gray);
        }

        .stock-info {
            font-size: 13px;
            color: var(--gray);
            margin-top: 5px;
            font-weight: 400;
        }

        /* Floating label animation */
        .floating-label-group {
            position: relative;
            margin-bottom: 20px;
        }

        .floating-label {
            position: absolute;
            pointer-events: none;
            left: 15px;
            top: 12px;
            transition: var(--transition);
            background: var(--white);
            padding: 0 5px;
            color: var(--gray);
            font-size: 14px;
        }

        .floating-input:focus~.floating-label,
        .floating-input:not(:placeholder-shown)~.floating-label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: var(--primary);
            background: var(--white);
        }

        .floating-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
            background-color: var(--white);
        }

        .floating-input:focus {
            border-color: var(--primary-light);
            outline: none;
            box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.2);
        }

        @media (max-width: 768px) {
            .container {
                margin: 15px auto;
            }

            .form-container {
                padding: 20px;
            }

            .header h1 {
                font-size: 20px;
            }

            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
    <script>
        function confirmSale() {
            const productSelect = document.getElementById('product_id');
            const selectedProduct = productSelect.options[productSelect.selectedIndex].text;
            const quantity = document.getElementById('quantity').value;
            return confirm(`Are you sure you want to sell ${quantity} unit(s) of "${selectedProduct}"?`);
        }

        // Set default date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('sale_date').value = today;

            // Update stock info when product selection changes
            document.getElementById('product_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    const stockInfo = selectedOption.text.match(/\(Stock: (\d+)\)/);
                    if (stockInfo) {
                        document.getElementById('stock-display').textContent = `Current stock: ${stockInfo[1]}`;
                    }
                }
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-cash-register"></i> Record Sale</h1>
        </div>

        <div class="form-container">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div><?= $success ?></div>
                </div>
            <?php elseif ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div><?= $error ?></div>
                </div>
            <?php endif; ?>

            <form method="post" onsubmit="return confirmSale()">
                <div class="form-row">
                    <div class="form-group floating-label-group">
                        <select name="product_id" id="product_id" class="floating-input" required>
                            <option value="" disabled selected></option>
                            <?php while ($row = $products->fetch_assoc()): ?>
                                <option value="<?= $row['flower_id'] ?>">
                                    <?= htmlspecialchars($row['name']) ?> (Stock: <?= $row['quantity'] ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <label class="floating-label">Select Product</label>
                        <div id="stock-display" class="stock-info">Select a product to view stock</div>
                    </div>

                    <div class="form-group floating-label-group">
                        <input type="number" name="quantity" id="quantity" class="floating-input" placeholder=" " min="1" required>
                        <label class="floating-label">Quantity</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group floating-label-group">
                        <input type="date" name="sale_date" id="sale_date" class="floating-input" placeholder=" " required>
                        <label class="floating-label">Sale Date</label>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Record Sale
                        </button>
                    </div>
                </div>
            </form>

            <h3 class="section-title"><i class="fas fa-history"></i> Recent Sales History</h3>
            <table>
                <thead>
                    <tr>
                        <th>Sale ID</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($sale = $sales_history->fetch_assoc()): ?>
                        <tr>
                            <td><?= $sale['id'] ?></td>
                            <td><?= htmlspecialchars($sale['product_name']) ?></td>
                            <td><?= $sale['quantity'] ?></td>
                            <td><?= date('M j, Y', strtotime($sale['sale_date'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="footer">
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</body>

</html>