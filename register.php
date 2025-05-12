<?php
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
        exit;
    }

    $name = $data['name'];
    $username = $data['username'];
    $password = $data['password'];
    $email = $data['email'];
    $phone = $data['phone'];
    $role_id = 2;

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=inventory", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO users (name, username, pass, email, phone, role_id) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->execute([$name, $username, $password, $email, $phone]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
?>