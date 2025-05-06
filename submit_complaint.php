<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_complaint'])) {
    $user_id = $_SESSION['user_id'];
    $order_id = $_POST['order_id'];
    $description = $_POST['description'];
    $complaint_date = date('Y-m-d H:i:s');

    try {
        // Insert complaint into database
        $stmt = $pdo->prepare("INSERT INTO complaint (user_id, complaint_date, description, order_id, status) 
                              VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->execute([$user_id, $complaint_date, $description, $order_id]);

        $_SESSION['success'] = "Complaint submitted successfully!";
        header("Location: user-profile.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error submitting complaint: " . $e->getMessage();
        header("Location: user-profile.php");
        exit();
    }
} else {
    header("Location: user-profile.php");
    exit();
}
