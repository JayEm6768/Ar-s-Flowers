<?php
session_start();
header('Content-Type: application/json');

if(isset($_SESSION['admin'])){
    
}

if ($_SESSION['role_id'] == 1) {

    echo json_encode([
        'loggedIn' => true,
        'username' => $_SESSION['user']
    ]);

} elseif($_SESSION['role_id'] == 2) {
    echo json_encode([
        'loggedInAdmin' => true,
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
