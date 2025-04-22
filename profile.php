<?php
session_start();
include 'connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 50px;
        }
        .profile-card {
            max-width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            background: #fff;
        }
        .profile-card h2 {
            margin-bottom: 20px;
        }
        .profile-card p {
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="profile-card">
    <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Role ID:</strong> <?php echo htmlspecialchars($user['role_id']); ?></p>
    <p><strong>Member Since:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
</div>

</body>
</html>
