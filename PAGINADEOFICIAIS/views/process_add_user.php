<?php
require_once __DIR__ . '/../includes/bootstrap.php';

require_post('admin');

// Sem Content-Type JSON de propósito: js/scripts.js faz JSON.parse(response) sobre o texto bruto
header('Content-Type: text/html; charset=utf-8');

const MIN_PASSWORD_LENGTH = 8;

function add_user_respond(string $status, string $message): void
{
    echo json_encode(['status' => $status, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit();
}

$username = trim((string)($_POST['username'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$is_admin = isset($_POST['is_admin']) ? 1 : 0;

if ($username === '') {
    add_user_respond('error', 'Informe o nome do usuário.');
}

if (strlen($password) < MIN_PASSWORD_LENGTH) {
    add_user_respond('error', 'A senha deve ter pelo menos ' . MIN_PASSWORD_LENGTH . ' caracteres.');
}

try {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);

    if ($stmt->fetchColumn() > 0) {
        add_user_respond('error', 'Usuário já existe.');
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('INSERT INTO users (username, password, is_admin) VALUES (:username, :password, :is_admin)');
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $hashed_password);
    $stmt->bindValue(':is_admin', $is_admin === 1, PDO::PARAM_BOOL);
    $stmt->execute();

    add_user_respond('success', 'Usuário adicionado com sucesso.');
} catch (PDOException $e) {
    error_log('Erro ao adicionar usuário: ' . $e->getMessage());
    add_user_respond('error', 'Erro ao adicionar usuário.');
}
