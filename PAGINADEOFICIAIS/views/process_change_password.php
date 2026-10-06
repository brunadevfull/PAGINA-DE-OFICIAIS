<?php
require_once __DIR__ . '/../includes/bootstrap.php';

// Qualquer usuário logado troca a própria senha
require_post('any');

const MIN_PASSWORD_LENGTH = 8;

function change_password_fail(string $message, string $hash = '#passwordErrorModal'): void
{
    $_SESSION['error'] = $message;
    header('Location: ../index.php' . $hash);
    exit();
}

$current_password = (string)($_POST['current_password'] ?? '');
$new_password = (string)($_POST['new_password'] ?? '');
$confirm_password = (string)($_POST['confirm_password'] ?? '');

if ($new_password !== $confirm_password) {
    change_password_fail('A nova senha e a confirmação não coincidem.', '');
}

if (strlen($new_password) < MIN_PASSWORD_LENGTH) {
    change_password_fail('A senha deve ter pelo menos ' . MIN_PASSWORD_LENGTH . ' caracteres.', '');
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare('SELECT password FROM users WHERE id = :id');
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($current_password, $user['password'])) {
        change_password_fail('Senha atual incorreta.', '');
    }

    $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
    $stmt->execute(['password' => $new_password_hash, 'id' => $user_id]);

    $_SESSION['password_change_success'] = 'Senha redefinida com sucesso!';
    header('Location: ../index.php#passwordChangeSuccessModal');
    exit();
} catch (PDOException $e) {
    error_log('Erro ao trocar senha: ' . $e->getMessage());
    change_password_fail('Erro ao redefinir senha.');
}
