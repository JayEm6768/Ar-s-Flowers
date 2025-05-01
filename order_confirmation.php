<?php
session_start();
require_once 'db_connection.php';

if (!isset($_GET['order_id']) || !isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$orderId = $_GET['order_id'];

// Get order details
$stmt = $pdo->prepare("SELECT o.*, u.name AS customer_name 
                      FROM ordertable o
                      JOIN users u ON o.customer_id = u.user_id
                      WHERE o.order_id = ? AND o.customer_id = ?");
$stmt->execute([$orderId, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: home.php");
    exit();
}

// Get order items
$stmt = $pdo->prepare("SELECT oi.*, p.name, p.image_url 
                      FROM orderitem oi
                      JOIN product p ON oi.product_id = p.flower_id
                      WHERE oi.order_id = ?");
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();

// Calculate totals for display
$subtotal = 0;
foreach ($items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$tax = $subtotal * 0.05;
$deliveryFee = 30;
$total = $subtotal + $tax + $deliveryFee;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmation - ARS Flowershop Davao</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #FFF9F9;
      margin: 0;
      padding: 0;
    }

    .confirmation-container {
      max-width: 800px;
      margin: 120px auto 50px;
      padding: 30px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    }

    .confirmation-title {
      color: #b10e73;
      margin-bottom: 20px;
      text-align: center;
      font-size: 2rem;
    }

    .confirmation-message {
      color: #555;
      margin-bottom: 30px;
      text-align: center;
      font-size: 1.2rem;
    }

    .order-details {
      margin-bottom: 30px;
      padding: 20px;
      background: #f9f9f9;
      border-radius: 10px;
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .detail-label {
      font-weight: bold;
      color: #122349;
    }

    .order-items {
      margin-top: 20px;
    }

    .order-item {
      display: flex;
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #eee;
    }

    .order-item img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
      margin-right: 15px;
    }

    .item-info {
      flex: 1;
    }

    .item-name {
      font-weight: bold;
      margin-bottom: 5px;
      color: #122349;
    }

    .item-price {
      color: #b10e73;
      margin-bottom: 5px;
    }

    .order-summary {
      margin-top: 30px;
      padding: 20px;
      background: #f9f9f9;
      border-radius: 10px;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .summary-total {
      font-weight: bold;
      font-size: 1.1rem;
      color: #122349;
      border-top: 1px solid #ddd;
      padding-top: 10px;
    }

    .continue-btn {
      display: block;
      width: 200px;
      margin: 30px auto 0;
      padding: 12px;
      background: linear-gradient(135deg, #b10e73, #ff6b9e);
      color: white;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: 600;
      text-align: center;
      transition: all 0.3s;
    }

    .continue-btn:hover {
      background: linear-gradient(135deg, #850000, #b10e73);
      transform: translateY(-2px);
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="confirmation-container">
    <h1 class="confirmation-title">Order Confirmation</h1>
    <p class="confirmation-message">Thank you for your order, <?= htmlspecialchars($order['customer_name']) ?>!</p>
    
    <div class="order-details">
      <div class="detail-row">
        <span class="detail-label">Order Number:</span>
        <span>#<?= $orderId ?></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Order Date:</span>
        <span><?= date('F j, Y g:i A', strtotime($order['order_date'])) ?></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Delivery Date:</span>
        <span><?= date('F j, Y', strtotime($order['shipping_date'])) ?></span>
      </div>
    </div>
    
    <div class="order-items">
      <h3>Your Items</h3>
      <?php foreach ($items as $item): ?>
        <div class="order-item">
          <img src="dashboard/uploads/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
          <div class="item-info">
            <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
            <div class="item-price">₱<?= number_format($item['price'], 2) ?> × <?= $item['quantity'] ?></div>
            <div>Subtotal: ₱<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    
    <div class="order-summary">
      <div class="summary-row">
        <span>Subtotal:</span>
        <span>₱<?= number_format($subtotal, 2) ?></span>
      </div>
      <div class="summary-row">
        <span>Tax (5%):</span>
        <span>₱<?= number_format($tax, 2) ?></span>
      </div>
      <div class="summary-row">
        <span>Delivery Fee:</span>
        <span>₱<?= number_format($deliveryFee, 2) ?></span>
      </div>
      <div class="summary-row summary-total">
        <span>Total:</span>
        <span>₱<?= number_format($total, 2) ?></span>
      </div>
    </div>
    
    <a href="home.php" class="continue-btn">Continue Shopping</a>
  </div>
</body>
</html>