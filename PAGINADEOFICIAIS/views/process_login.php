<?php
include 'password_compat.php';

session_start();
include $_SERVER['DOCUMENT_ROOT'].'/config/config.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Prepare a statement to fetch the user data
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['is_admin'] = $user['is_admin'];
    $_SESSION['username'] = $user['username']; 
    
    // Capture the client IP address
    $client_ip_address = $_SERVER['REMOTE_ADDR'];

    // Insert a record into the login_audit table
    $audit_stmt = $pdo->prepare('INSERT INTO login_audit (user_id, username, ip_cliente) VALUES (?, ?, ?)');
    $audit_stmt->execute([$user['id'], $user['username'], $client_ip_address]);

    // Redirect to the index page
    header('Location: ../index.php');
    exit();
} else {
    // Set an error message and redirect back to the login page
    $_SESSION['login_error'] = 'Usuário ou senha incorretos.';
    header('Location: ../index.php#loginModal');
    exit();
}
?>
