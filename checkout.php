<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$id = $_SESSION['user_id'];
$query = "SELECT `address` FROM `users` WHERE `users`.`user_id` = $id;";
$conn = mysqli_connect("localhost:3306","root", "", "inventory");
$select = mysqli_query($conn, $query);

// Get cart data from POST
$cart = [];
if (isset($_POST['cart_data'])) {
    $cart = json_decode($_POST['cart_data'], true);
} elseif (isset($_SESSION['checkout_cart'])) {
    $cart = $_SESSION['checkout_cart'];
}

// If cart is empty, redirect back
if (empty($cart)) {
    header("Location: cart.php");
    exit();
}

// Calculate totals
$subtotal = 0;
$taxRate = 0.05; // 5% tax
$deliveryFee = 30;

foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$tax = $subtotal * $taxRate;
$total = $subtotal + $tax + $deliveryFee;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    require_once 'connect.php';
    
    try {
        $pdo->beginTransaction();
        
        // 1. Create order record
        $stmt = $pdo->prepare("INSERT INTO ordertable (customer_id, order_date, total_amount, shipping_date) 
                              VALUES (?, NOW(), ?, ?)");
        $shippingDate = date('Y-m-d', strtotime($_POST['delivery_date']));
        $stmt->execute([
            $_SESSION['user_id'],
            $total,
            $shippingDate
        ]);
        $orderId = $pdo->lastInsertId();
        
        // 2. Add order items
        foreach ($cart as $item) {
            $stmt = $pdo->prepare("INSERT INTO orderitem (order_id, product_id, quantity, price) 
                                  VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $orderId,
                $item['id'],
                $item['quantity'],
                $item['price']
            ]);
            
            // Update product quantity
            $stmt = $pdo->prepare("UPDATE product SET quantity = quantity - ? WHERE flower_id = ?");
            $stmt->execute([$item['quantity'], $item['id']]);
        }
        
        // 3. Create order summary
        $stmt = $pdo->prepare("INSERT INTO ordersummary (order_id, total_items, total_cost) 
                              VALUES (?, ?, ?)");
        $totalItems = array_sum(array_column($cart, 'quantity'));
        $stmt->execute([$orderId, $totalItems, $total]);
        
        $pdo->commit();
        
        // Clear the cart
        unset($_SESSION['checkout_cart']);
        
        // Redirect to confirmation page
        header("Location: order_confirmation.php?order_id=$orderId");
        
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Error processing your order: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout - ARS Flowershop Davao</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #FFF9F9;
      margin: 0;
      padding: 0;
    }

    .checkout-container {
      max-width: 1000px;
      margin: 120px auto 50px;
      padding: 30px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    }

    .checkout-title {
      color: #b10e73;
      margin-bottom: 30px;
      text-align: center;
      font-size: 2rem;
      border-bottom: 2px solid #ffb6c1;
      padding-bottom: 15px;
    }

    .checkout-section {
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 1px solid #f0f0f0;
    }

    .section-title {
      color: #122349;
      margin-bottom: 20px;
      font-size: 1.3rem;
    }

    .checkout-columns {
      display: flex;
      gap: 30px;
    }

    .checkout-items {
      flex: 1;
    }

    .checkout-form {
      flex: 1;
    }

    .cart-item {
      display: flex;
      margin-bottom: 20px;
      padding-bottom: 20px;
      border-bottom: 1px solid #f0f0f0;
    }

    .cart-item img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      margin-right: 15px;
    }

    .item-details {
      flex: 1;
    }

    .item-name {
      font-weight: bold;
      margin-bottom: 5px;
      color: #122349;
      font-size: 1.1rem;
    }

    .item-price {
      color: #b10e73;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .item-quantity {
      display: flex;
      align-items: center;
      margin-bottom: 5px;
    }

    .item-total {
      font-weight: bold;
      color: #122349;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #555;
      font-weight: 600;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      border-color: #b10e73;
      outline: none;
    }

    .radio-group {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .radio-option {
      display: flex;
      align-items: center;
    }

    .radio-option input {
      width: auto;
      margin-right: 10px;
    }

    .order-summary {
      background: #f9f9f9;
      padding: 20px;
      border-radius: 10px;
      margin-top: 20px;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
    }

    .summary-row:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }

    .summary-row.total {
      font-weight: bold;
      font-size: 1.1rem;
      color: #122349;
    }

    .checkout-btn {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #b10e73, #ff6b9e);
      color: white;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: 600;
      transition: all 0.3s;
      box-shadow: 0 3px 10px rgba(177, 14, 115, 0.3);
      margin-top: 20px;
    }

    .checkout-btn:hover {
      background: linear-gradient(135deg, #850000, #b10e73);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(177, 14, 115, 0.4);
    }

    @media (max-width: 768px) {
      .checkout-columns {
        flex-direction: column;
      }
      
      .checkout-container {
        margin: 100px 20px 30px;
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <?php include 'footHead/header.php'; ?>

  <div class="checkout-container">
    <h1 class="checkout-title">Checkout</h1>
    
    <?php if (!empty($error)): ?>
      <div class="error-message" style="color: red; margin-bottom: 20px; padding: 10px; background: #ffeeee; border-radius: 5px;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>
    
    <div class="checkout-columns">
      <div class="checkout-items">
        <div class="checkout-section">
          <h2 class="section-title">Your Order</h2>
          <?php foreach ($cart as $item): ?>
            <div class="cart-item">
              <img src="dashboard/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
              <div class="item-details">
                <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                <div class="item-price">₱<?= number_format($item['price'], 2) ?></div>
                <div class="item-quantity">
                  <span>Quantity: <?= htmlspecialchars($item['quantity']) ?></span>
                </div>
                <div class="item-total">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      
      <div class="checkout-form">
        <form method="POST" id="delivery-form">
          <div class="checkout-section">
            <h2 class="section-title">Delivery Information</h2>
            <div class="form-group">
              <label for="delivery-address">Full Address</label>
              <textarea id="delivery-address" name="delivery_address" required>
                <?php 
                if(mysqli_num_rows($select) > 0){
                  $address = mysqli_fetch_assoc($select);
                  echo $address['address'];
                }
                ?>
                </textarea>
            </div>
            
            <div class="form-group">
              <label for="delivery-date">Delivery Date</label>
              <input type="date" id="delivery-date" name="delivery_date" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
            </div>
            
            <div class="form-group">
              <label for="delivery-time">Delivery Time</label>
              <input type="time" id="delivery-time" name="delivery_time" required>
            </div>
            
            <div class="form-group">
              <label for="delivery-notes">Special Instructions (Optional)</label>
              <textarea id="delivery-notes" name="delivery_notes"></textarea>
            </div>
          </div>
          
          <div class="checkout-section">
            <h2 class="section-title">Payment Method</h2>
            <div class="radio-group">
              <div class="radio-option">
                <input type="radio" id="payment-cod" name="payment_method" value="cod" checked>
                <label for="payment-cod">Cash on Delivery</label>
              </div>
              <div class="radio-option">
                <input type="radio" id="payment-gcash" name="payment_method" value="gcash">
                <label for="payment-gcash">GCash</label>
              </div>
              <div class="radio-option">
                <input type="radio" id="payment-card" name="payment_method" value="card">
                <label for="payment-card">Credit/Debit Card</label>
              </div>
            </div>
          </div>
          
          <div class="checkout-section">
            <h2 class="section-title">Order Summary</h2>
            <div class="order-summary">
              <div class="summary-row">
                <span>Subtotal:</span>
                <span id="subtotal-amount">₱<?= number_format($subtotal, 2) ?></span>
              </div>
              <div class="summary-row">
                <span>Tax (5%):</span>
                <span id="tax-amount">₱<?= number_format($tax, 2) ?></span>
              </div>
              <div class="summary-row">
                <span>Delivery Fee:</span>
                <span id="delivery-fee">₱<?= number_format($deliveryFee, 2) ?></span>
              </div>
              <div class="summary-row total">
                <span>Total:</span>
                <span id="total-amount">₱<?= number_format($total, 2) ?></span>
              </div>
            </div>
          </div>
          
          <!-- Hidden field to preserve cart data -->
          <input type="hidden" name="cart_data" value="<?= htmlspecialchars(json_encode($cart)) ?>">
          
          <button type="submit" name="place_order" class="checkout-btn">Place Order</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Set minimum date to tomorrow
    document.addEventListener('DOMContentLoaded', function() {
      const today = new Date();
      const tomorrow = new Date(today);
      tomorrow.setDate(tomorrow.getDate() + 1);
      
      const minDate = tomorrow.toISOString().split('T')[0];
      document.getElementById('delivery-date').min = minDate;

      document.getElementById('delivery-form').addEventListener('submit', function () {
      localStorage.clear();
    });
    });
  </script>
</body>
</html>