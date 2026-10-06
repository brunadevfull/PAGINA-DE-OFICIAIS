<?php
require_once __DIR__ . '/../includes/bootstrap.php';

require_post('none');

$username = trim((string)($_POST['username'] ?? ''));
$password = (string)($_POST['password'] ?? '');

$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Novo ID de sessão e novo token CSRF após autenticar (evita fixação de sessão)
    session_regenerate_id(true);
    unset($_SESSION['csrf_token']);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['is_admin'] = in_array($user['is_admin'], [true, 't', 'true', '1', 1], true);
    $_SESSION['username'] = $user['username'];

    $client_ip_address = $_SERVER['REMOTE_ADDR'];

    $audit_stmt = $pdo->prepare('INSERT INTO login_audit (user_id, username, ip_cliente) VALUES (?, ?, ?)');
    $audit_stmt->execute([$user['id'], $user['username'], $client_ip_address]);

    header('Location: ../index.php');
    exit();
}

$_SESSION['login_error'] = 'Usuário ou senha incorretos.';
header('Location: ../index.php#loginModal');
exit();
