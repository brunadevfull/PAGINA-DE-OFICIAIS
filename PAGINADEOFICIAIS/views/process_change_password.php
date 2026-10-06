<?php
include 'password_compat.php';

session_start();
include $_SERVER['DOCUMENT_ROOT'].'/config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

   if ($new_password != $confirm_password) {
        $_SESSION['error'] = 'A nova senha e a confirmação não coincidem.';
        header('Location: ../index.php');
        exit();
    }

    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = 'Usuário não autenticado.';
        header('Location: ../index.php#passwordErrorModal');
        exit();
    }

    $user_id = $_SESSION['user_id'];

    try {
        // Recupere os dados do usuário
        $stmt = $pdo->prepare('SELECT password FROM users WHERE id = :id AND is_admin = TRUE');
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($current_password, $user['password'])) {
            $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE id = :id AND is_admin = TRUE');
            $updateSuccess = $stmt->execute(['password' => $new_password_hash, 'id' => $user_id]);

            if ($updateSuccess) {
                $_SESSION['password_change_success'] = 'Senha redefinida com sucesso!';
                header('Location: ../index.php#passwordChangeSuccessModal'); 

//a porcaria do modal que ta la no html

                exit();
            } else {
                $_SESSION['error'] = 'Erro ao atualizar a senha.';
                header('Location: ../index.php#passwordErrorModal');
                exit();
            }
        } else {
            $_SESSION['error'] = 'Senha atual incorreta.';
            header('Location: ../index.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro ao redefinir senha: ' . $e->getMessage();
        header('Location: ../index.php#passwordErrorModal');
        exit();
    }
}
?>
