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
    echo "Welcome, " . htmlspecialchars($user['username']) . "!";
} else {
    echo "Invalid username or password.";
}
?>