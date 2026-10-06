<?php
require_once __DIR__ . '/../includes/bootstrap.php';

require_post('admin');

const MIN_PASSWORD_LENGTH = 8;

$user_id = filter_var($_POST['username'] ?? null, FILTER_VALIDATE_INT);
$new_password = (string)($_POST['new_password'] ?? '');
$confirm_password = (string)($_POST['confirm_password'] ?? '');

function reset_password_fail(string $message): void
{
    $_SESSION['error'] = $message;
    header('Location: ../index.php#passwordErrorModal');
    exit();
}

if ($user_id === false || $user_id === null) {
    reset_password_fail('Usuário inválido.');
}

if ($new_password !== $confirm_password) {
    reset_password_fail('A nova senha e a confirmação não coincidem.');
}

if (strlen($new_password) < MIN_PASSWORD_LENGTH) {
    reset_password_fail('A senha deve ter pelo menos ' . MIN_PASSWORD_LENGTH . ' caracteres.');
}

try {
    $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
    $stmt->execute(['password' => $new_password_hash, 'id' => $user_id]);

    if ($stmt->rowCount() === 0) {
        reset_password_fail('Usuário não encontrado.');
    }

    unset($_SESSION['error']);
    $_SESSION['success'] = 'Senha do usuário redefinida com sucesso.';
    header('Location: ../index.php#resetPasswordSuccessModal');
    exit();
} catch (PDOException $e) {
    error_log('Erro ao redefinir senha: ' . $e->getMessage());
    reset_password_fail('Erro ao redefinir senha.');
}
