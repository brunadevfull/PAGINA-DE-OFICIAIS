<?php
include 'password_compat.php';
session_start();
header('Content-Type: text/html; charset=utf-8');
include $_SERVER['DOCUMENT_ROOT'].'/config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verifica se as senhas coincidem
    if ($new_password != $confirm_password) {
        $_SESSION['error'] = 'A nova senha e a confirmação não coincidem.';
        header('Location: ../index.php#passwordErrorModal');
        exit();
    }

    try {
        // Hash da nova senha

        $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        // Prepara a consulta para atualizar a senha do usuï¿½rio
        $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
        $stmt->execute(['password' => $new_password_hash, 'id' => $user_id]);
  unset($_SESSION['error']);

        $_SESSION['success'] = 'Senha do usuário redefinida com sucesso.';
        header('Location: ../index.php#resetPasswordSuccessModal'); 
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro ao redefinir senha: ' . $e->getMessage();
        header('Location: ../index.php#passwordErrorModal');
        exit();
    }
}
?>
