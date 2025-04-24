<?php
session_start();
$host = "localhost";
$db = "inventory";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    die("DB error: " . $e->getMessage());
}

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && $password === $user['pass']) {
    $_SESSION['user'] = $user['username'];
    $_SESSION['user_id'] = $user['user_id']; 
    $_SESSION['role_id'] = $user['role_id'];

    // decide destination based on role_id
    if ($user['role_id'] == 2) {
        echo "Welcome, {$user['username']}!|dashboard\dashboard.php";
    } else {
        echo "Welcome, {$user['username']}!|\productPage.php";
    }
}  else {
    echo "Invalid username or password.";
}
?>