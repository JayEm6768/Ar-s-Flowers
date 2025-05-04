  <?php include 'footHead/header.php'; ?>
  <?php
// Start session only if not already active
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Redirect if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once 'connect.php';

// Initialize variables with default values
$user = [
    'user_id' => null,
    'username' => 'Guest',
    'email' => '',
    'profile_picture' => '/images/default_profile.jpg',
    'created_at' => date('Y-m-d H:i:s')
];
$error = '';
$success = '';

try {
    // Get current user data
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch();

    if ($user_data) {
        // Merge default values with database values
        $user = array_merge($user, $user_data);
    } else {
        session_destroy();
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
    error_log("User Profile Error: " . $e->getMessage());
}

$id = $_SESSION['user_id'];
$query = "SELECT * FROM `ordertable` WHERE `ordertable`.`customer_id` = $id;";
$conn = mysqli_connect("localhost:3306","root", "", "inventory");
$orders = mysqli_query($conn, $query);



// Rest of your existing code...
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile - ARS Flowershop</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary: #b10e73;
      --primary-dark: #850000;
      --primary-light: #ff6b9e;
      --secondary: #122349;
      --light: #FFF9F9;
      --gray: #f0f0f0;
      --dark-gray: #555;
      --success: #4caf50;
      --warning: #ff9800;
      --danger: #f44336;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Arial', sans-serif;
    }
    
    body {
      background-color: var(--light);
      color: var(--secondary);
      line-height: 1.6;
      margin-top: 120px;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    
    /* Profile Container */
    .profile-container {
      display: flex;
      gap: 30px;
      margin-bottom: 40px;
    }
    
    /* Sidebar Styles */
    .profile-sidebar {
      width: 320px;
      padding: 30px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
      position: sticky;
      top: 140px;
      height: fit-content;
    }
    
    /* Content Styles */
    .profile-content {
      flex: 1;
      min-width: 300px;
      padding: 30px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    /* Profile Picture */
    .profile-picture-container {
      position: relative;
      width: 150px;
      height: 150px;
      margin: 0 auto 20px;
    }
    
    .profile-picture {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid var(--primary);
      box-shadow: 0 3px 10px rgba(177, 14, 115, 0.2);
    }
    
    .profile-picture-edit {
      position: absolute;
      bottom: 5px;
      right: 5px;
      background: var(--primary);
      color: white;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: 2px solid white;
      transition: all 0.3s;
    }
    
    .profile-picture-edit:hover {
      background: var(--primary-dark);
      transform: scale(1.1);
    }
    
    /* Order and Complaint Cards */
    .order-card, .complaint-card {
      border: 1px solid #eee;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 10px;
      position: relative;
      transition: all 0.3s;
      background: white;
    }
    
    .order-card:hover, .complaint-card:hover {
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transform: translateY(-2px);
    }
    
    /* Complaint Form */
    .complaint-form {
      display: none;
      margin-top: 15px;
      padding: 15px;
      background: #f9f9f9;
      border-radius: 8px;
      border-left: 4px solid var(--primary);
    }
    
    /* Tab System */
    .tab-buttons {
      display: flex;
      margin-bottom: 25px;
      border-bottom: 1px solid #ddd;
    }
    
    .tab-button {
      padding: 12px 25px;
      background: none;
      border: none;
      cursor: pointer;
      font-weight: 600;
      color: var(--dark-gray);
      border-bottom: 3px solid transparent;
      transition: all 0.3s;
    }
    
    .tab-button.active {
      color: var(--primary);
      border-bottom: 3px solid var(--primary);
    }
    
    .tab-content {
      display: none;
    }
    
    .tab-content.active {
      display: block;
    }
    
    /* Buttons */
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    
    .btn i {
      font-size: 14px;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--primary), var(--primary-light));
      color: white;
      box-shadow: 0 3px 10px rgba(177, 14, 115, 0.3);
    }
    
    .btn-primary:hover {
      background: linear-gradient(135deg, var(--primary-dark), var(--primary));
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(177, 14, 115, 0.4);
    }
    
    .btn-secondary {
      background: var(--gray);
      color: var(--dark-gray);
    }
    
    .btn-secondary:hover {
      background: #e0e0e0;
    }
    
    /* Status Indicators */
    .status-pending {
      color: var(--warning);
      font-weight: bold;
    }
    
    .status-resolved {
      color: var(--success);
      font-weight: bold;
    }
    
    .status-rejected {
      color: var(--danger);
      font-weight: bold;
    }
    
    /* Alerts */
    .alert {
      padding: 15px;
      margin-bottom: 25px;
      border-radius: 8px;
      width: 100%;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .alert i {
      font-size: 20px;
    }
    
    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border-left: 4px solid var(--danger);
    }
    
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border-left: 4px solid var(--success);
    }
    
    /* Form Styles */
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--secondary);
    }
    
    .form-control {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      transition: all 0.3s;
    }
    
    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(177, 14, 115, 0.1);
      outline: none;
    }
    
    /* Profile Header */
    .profile-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }
    
    .profile-header h2 {
      color: var(--primary);
      font-size: 28px;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 992px) {
      .profile-container {
        flex-direction: column;
      }
      
      .profile-sidebar {
        width: 100%;
        position: static;
      }
    }
    
    @media (max-width: 576px) {
      .tab-buttons {
        flex-direction: column;
      }
      
      .tab-button {
        text-align: left;
        border-bottom: none;
        border-left: 3px solid transparent;
      }
      
      .tab-button.active {
        border-left: 3px solid var(--primary);
        border-bottom: none;
      }
    }
  </style>
</head>
<body>

  
  <div class="container">
    <!-- Display error/success messages -->
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo htmlspecialchars($success); ?>
      </div>
    <?php endif; ?>

    <div class="profile-container">
      <div class="profile-sidebar">
        <!-- Profile Section -->
        <div class="text-center">
          <div class="profile-picture-container">
            <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? '/images/default_profile.jpg'); ?>" 
                 class="profile-picture" id="profile-picture">
            <div class="profile-picture-edit" onclick="document.getElementById('profile-upload').click()">
              <i class="fas fa-camera"></i>
            </div>
          </div>
          <h2><?php echo htmlspecialchars($user['username']); ?></h2>
          <p><?php echo htmlspecialchars($user['email']); ?></p>
          
          <!-- Profile Picture Form -->
          <form method="post" enctype="multipart/form-data" class="mt-4">
            <input type="file" name="profile_picture" id="profile-upload" accept="image/*" style="display:none;">
            <button type="submit" class="btn btn-primary w-100">
              <i class="fas fa-save"></i> Save Changes
            </button>
          </form>
        </div>

        <!-- Account Settings -->
        <div class="mt-5">
          <h4 class="mb-4" style="color: var(--primary); border-bottom: 2px solid var(--primary); padding-bottom: 8px;">
            <i class="fas fa-cog mr-2"></i>Account Settings
          </h4>
          
          <!-- Email Update Form -->
          <form method="post">
            <div class="form-group">
              <label><i class="fas fa-envelope mr-2"></i>Email Address</label>
              <input type="email" name="new_email" class="form-control" 
                     value="<?php echo htmlspecialchars($user['email']); ?>" required>
              <button type="submit" name="change_email" class="btn btn-primary mt-3 w-100">
                <i class="fas fa-sync-alt"></i> Update Email
              </button>
            </div>
          </form>
          
          <!-- Password Update Form -->
          <form method="post" class="mt-4">
            <div class="form-group">
              <label><i class="fas fa-lock mr-2"></i>Change Password</label>
              <input type="password" name="current_password" class="form-control" placeholder="Current Password" required>
              <input type="password" name="new_password" class="form-control mt-3" placeholder="New Password" required>
              <button type="submit" name="change_password" class="btn btn-primary mt-3 w-100">
                <i class="fas fa-key"></i> Change Password
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="profile-content">
        <div class="profile-header">
          <h2><i class="fas fa-user-circle mr-2"></i>My Account</h2>
          <div class="text-muted">Member since <?php echo date('M Y', strtotime($user['created_at'])); ?></div>
        </div>
        
        <div class="tab-buttons">
          <button class="tab-button active" onclick="openTab('orders')">
            <i class="fas fa-shopping-bag mr-2"></i>My Orders
          </button>
          <button class="tab-button" onclick="openTab('complaints')">
            <i class="fas fa-exclamation-circle mr-2"></i>My Complaints
          </button>
        </div>
        
        <div id="orders" class="tab-content active">
          <?php if (empty($orders)): ?>
            <div class="text-center py-5">
              <i class="fas fa-shopping-bag fa-3x mb-3" style="color: var(--gray);"></i>
              <h4>No Orders Yet</h4>
              <p class="text-muted">You haven't placed any orders yet</p>
              <a href="productPage.php" class="btn btn-primary mt-3">
                <i class="fas fa-store mr-2"></i>Start Shopping
              </a>
            </div>
          <?php else: ?>
            <?php foreach ($orders as $order): ?>
              <div class="order-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h4>Order #<?php echo htmlspecialchars($order['order_id']); ?></h4>
                  <span class="badge" style="background: #e3f2fd; color: #1976d2; padding: 5px 10px; border-radius: 20px;">
                    <?php echo ucfirst(htmlspecialchars($order['status'])); ?>
                  </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <div>
                    <i class="fas fa-calendar-alt mr-2 text-muted"></i>
                    <?php echo date('M d, Y h:i A', strtotime($order['order_date'])); ?>
                  </div>
                  <div>
                    <strong>₱<?php echo number_format($order['total_amount'], 2); ?></strong>
                  </div>
                </div>
                
                <?php if ($order['status'] == 'completed'): ?>
                  <button onclick="showComplaintForm(<?php echo $order['order_id']; ?>)" class="btn btn-primary mt-3">
                    <i class="fas fa-exclamation-circle mr-2"></i>File Complaint
                  </button>
                  
                  <div id="complaint-form-<?php echo $order['order_id']; ?>" class="complaint-form">
                    <form method="post">
                      <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                      <div class="form-group">
                        <label>Describe your issue</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                      </div>
                      <button type="submit" name="submit_complaint" class="btn btn-primary">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Complaint
                      </button>
                    </form>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        
        <div id="complaints" class="tab-content">
          <?php if (empty($complaints)): ?>
            <div class="text-center py-5">
              <i class="fas fa-check-circle fa-3x mb-3" style="color: var(--gray);"></i>
              <h4>No Complaints</h4>
              <p class="text-muted">You haven't filed any complaints yet</p>
            </div>
          <?php else: ?>
            <?php foreach ($complaints as $complaint): ?>
              <div class="complaint-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h4>Complaint #<?php echo htmlspecialchars($complaint['complaint_id']); ?></h4>
                  <span class="status-<?php echo htmlspecialchars($complaint['status']); ?>">
                    <i class="fas fa-circle mr-1" style="font-size: 10px;"></i>
                    <?php echo ucfirst(htmlspecialchars($complaint['status'])); ?>
                  </span>
                </div>
                
                <div class="mb-3">
                  <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-shopping-bag mr-2 text-muted"></i>
                    Order #<?php echo htmlspecialchars($complaint['order_id']); ?>
                    <span class="mx-2">•</span>
                    <i class="fas fa-calendar-day mr-2 text-muted"></i>
                    <?php echo date('M d, Y', strtotime($complaint['order_date'])); ?>
                  </div>
                  
                  <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-clock mr-2 text-muted"></i>
                    Filed on <?php echo date('M d, Y h:i A', strtotime($complaint['complaint_date'])); ?>
                  </div>
                </div>
                
                <div class="complaint-details">
                  <h5 class="mb-2"><i class="fas fa-align-left mr-2 text-muted"></i>Description</h5>
                  <p><?php echo htmlspecialchars($complaint['description']); ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Preview profile picture before upload
    document.getElementById('profile-upload').addEventListener('change', function(e) {
      if (e.target.files.length > 0) {
        const reader = new FileReader();
        reader.onload = function(event) {
          document.getElementById('profile-picture').src = event.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
      }
    });

    function openTab(tabName) {
      // Hide all tab contents
      document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
      });
      
      // Deactivate all tab buttons
      document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
      });
      
      // Activate selected tab
      document.getElementById(tabName).classList.add('active');
      event.currentTarget.classList.add('active');
    }

    function showComplaintForm(orderId) {
      const form = document.getElementById('complaint-form-' + orderId);
      form.style.display = form.style.display === 'block' ? 'none' : 'block';
      
      // Smooth scroll to form
      if (form.style.display === 'block') {
        form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    }
  </script>
  
  <?php include 'footHead/footer.php'; ?>
</body>
</html>