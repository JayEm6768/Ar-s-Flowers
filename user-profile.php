<?php
// Start session and check authentication
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once 'connect.php';

// Get current user data
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Initialize messages
$error = $success = '';

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Profile Picture Upload
    if (isset($_FILES['profile_picture'])) {
        $target_dir = "uploads/profile_pics/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $imageFileType = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        
        if ($check !== false && in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $new_filename = "user_" . $user_id . "_" . time() . "." . $imageFileType;
            $target_path = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_path)) {
                // Delete old profile picture if it exists and isn't the default
                if (!empty($user['profile_picture']) && strpos($user['profile_picture'], 'default_profile.jpg') === false) {
                    @unlink($user['profile_picture']);
                }
                
                $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?")
                   ->execute([$target_path, $user_id]);
                $user['profile_picture'] = $target_path;
                $success = "Profile picture updated successfully!";
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not a valid image (JPG, JPEG, PNG, GIF only).";
        }
    }
    
    // Password Change
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        
        if (password_verify($current_password, $user['password'])) {
            if (strlen($new_password) >= 8) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")
                   ->execute([$hashed_password, $user_id]);
                $success = "Password updated successfully!";
            } else {
                $error = "Password must be at least 8 characters long";
            }
        } else {
            $error = "Current password is incorrect";
        }
    }
    
    // Email Change
    if (isset($_POST['change_email'])) {
        $new_email = trim($_POST['new_email']);
        if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$new_email, $user_id]);
            
            if ($stmt->rowCount() == 0) {
                $pdo->prepare("UPDATE users SET email = ? WHERE id = ?")
                   ->execute([$new_email, $user_id]);
                $user['email'] = $new_email;
                $success = "Email updated successfully!";
            } else {
                $error = "This email is already in use by another account";
            }
        } else {
            $error = "Invalid email format";
        }
    }
    
    // Complaint Submission
    if (isset($_POST['submit_complaint'])) {
        $order_id = (int)$_POST['order_id'];
        $description = trim($_POST['description']);
        
        if (!empty($description) && $order_id > 0) {
            try {
                $pdo->prepare("INSERT INTO complaints (user_id, complaint_date, description, order_id, status) 
                              VALUES (?, NOW(), ?, ?, 'pending')")
                    ->execute([$user_id, $description, $order_id]);
                $success = "Complaint submitted successfully!";
            } catch (PDOException $e) {
                $error = "Error submitting complaint: " . $e->getMessage();
            }
        } else {
            $error = "Please provide valid complaint details";
        }
    }
}

// Fetch user's orders
$orders = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error loading orders: " . $e->getMessage();
}

// Fetch user's complaints
$complaints = [];
try {
    $stmt = $pdo->prepare("SELECT c.*, o.order_date 
                         FROM complaints c
                         JOIN orders o ON c.order_id = o.id
                         WHERE c.user_id = ?
                         ORDER BY c.complaint_date DESC");
    $stmt->execute([$user_id]);
    $complaints = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error loading complaints: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile - ARS Flowershop</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* Profile Container */
    .profile-container {
      display: flex;
      max-width: 1200px;
      margin: 110px auto 20px;
      padding: 20px;
      flex-wrap: wrap;
      gap: 20px;
    }
    
    /* Sidebar Styles */
    .profile-sidebar {
      width: 300px;
      padding: 20px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    /* Content Styles */
    .profile-content {
      flex: 1;
      min-width: 300px;
      padding: 20px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    /* Profile Picture */
    .profile-picture {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #b10e73;
      margin-bottom: 20px;
    }
    
    /* Order and Complaint Cards */
    .order-card, .complaint-card {
      border: 1px solid #eee;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 5px;
      position: relative;
    }
    
    /* Complaint Form */
    .complaint-form {
      display: none;
      margin-top: 10px;
      padding: 10px;
      background: #f9f9f9;
      border-radius: 5px;
    }
    
    /* Tab System */
    .tab-buttons {
      display: flex;
      margin-bottom: 20px;
      border-bottom: 1px solid #ddd;
    }
    
    .tab-button {
      padding: 10px 20px;
      background: none;
      border: none;
      cursor: pointer;
      font-weight: 600;
      color: #555;
      border-bottom: 3px solid transparent;
    }
    
    .tab-button.active {
      color: #b10e73;
      border-bottom: 3px solid #b10e73;
    }
    
    .tab-content {
      display: none;
    }
    
    .tab-content.active {
      display: block;
    }
    
    /* Buttons */
    .btn {
      padding: 8px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, #b10e73, #ff6b9e);
      color: white;
    }
    
    .btn-primary:hover {
      background: linear-gradient(135deg, #850000, #b10e73);
      transform: translateY(-2px);
    }
    
    .btn-secondary {
      background: #f0f0f0;
      color: #555;
    }
    
    .btn-secondary:hover {
      background: #e0e0e0;
    }
    
    /* Status Indicators */
    .status-pending {
      color: #ff9800;
      font-weight: bold;
    }
    
    .status-resolved {
      color: #4caf50;
      font-weight: bold;
    }
    
    /* Alerts */
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 5px;
      width: 100%;
    }
    
    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    
    /* Form Styles */
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-control {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .profile-container {
        flex-direction: column;
      }
      
      .profile-sidebar, .profile-content {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>
  
  <div class="profile-container">
    <!-- Display error/success messages -->
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div class="profile-sidebar">
      <!-- Profile Section -->
      <div class="text-center">
        <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'images/default_profile.jpg'); ?>" 
             class="profile-picture" id="profile-picture">
        <h2><?php echo htmlspecialchars($user['username']); ?></h2>
        <p><?php echo htmlspecialchars($user['email']); ?></p>
        
        <!-- Profile Picture Form -->
        <form method="post" enctype="multipart/form-data">
          <input type="file" name="profile_picture" id="profile-upload" accept="image/*" style="display:none;">
          <button type="button" onclick="document.getElementById('profile-upload').click()" 
                  class="btn btn-primary">
            Change Profile Picture
          </button>
          <button type="submit" class="btn btn-secondary">Save Changes</button>
        </form>
      </div>

      <!-- Account Settings -->
      <div class="mt-4">
        <h4>Account Settings</h4>
        
        <!-- Email Update Form -->
        <form method="post">
          <div class="form-group">
            <label>New Email</label>
            <input type="email" name="new_email" class="form-control" 
                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <button type="submit" name="change_email" class="btn btn-primary mt-2">
              Update Email
            </button>
          </div>
        </form>
        
        <!-- Password Update Form -->
        <form method="post" class="mt-3">
          <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" required>
            <button type="submit" name="change_password" class="btn btn-primary mt-2">
              Change Password
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="profile-content">
      <h2>My Account</h2>
      
      <div class="tab-buttons">
        <button class="tab-button active" onclick="openTab('orders')">My Orders</button>
        <button class="tab-button" onclick="openTab('complaints')">My Complaints</button>
      </div>
      
      <div id="orders" class="tab-content active">
        <?php if (empty($orders)): ?>
          <p>You haven't placed any orders yet.</p>
        <?php else: ?>
          <?php foreach ($orders as $order): ?>
            <div class="order-card">
              <h4>Order #<?php echo htmlspecialchars($order['id']); ?></h4>
              <p>Date: <?php echo date('M d, Y h:i A', strtotime($order['order_date'])); ?></p>
              <p>Status: <?php echo ucfirst(htmlspecialchars($order['status'])); ?></p>
              <p>Total: ₱<?php echo number_format($order['total_amount'], 2); ?></p>
              
              <?php if ($order['status'] == 'completed'): ?>
                <button onclick="showComplaintForm(<?php echo $order['id']; ?>)" class="btn btn-primary">
                  File Complaint
                </button>
                
                <div id="complaint-form-<?php echo $order['id']; ?>" class="complaint-form">
                  <form method="post">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <textarea name="description" class="form-control" 
                              placeholder="Describe your issue..." required></textarea>
                    <button type="submit" name="submit_complaint" class="btn btn-primary mt-2">
                      Submit Complaint
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
          <p>You haven't filed any complaints yet.</p>
        <?php else: ?>
          <?php foreach ($complaints as $complaint): ?>
            <div class="complaint-card">
              <h4>Complaint #<?php echo htmlspecialchars($complaint['complaint_id']); ?></h4>
              <p>Order #<?php echo htmlspecialchars($complaint['order_id']); ?> - 
                 <?php echo date('M d, Y', strtotime($complaint['order_date'])); ?></p>
              <p>Filed on: <?php echo date('M d, Y h:i A', strtotime($complaint['complaint_date'])); ?></p>
              <p>Status: <span class="status-<?php echo htmlspecialchars($complaint['status']); ?>">
                <?php echo ucfirst(htmlspecialchars($complaint['status'])); ?>
              </span></p>
              <p><strong>Description:</strong> <?php echo htmlspecialchars($complaint['description']); ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
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
    }
  </script>
</body>
</html>