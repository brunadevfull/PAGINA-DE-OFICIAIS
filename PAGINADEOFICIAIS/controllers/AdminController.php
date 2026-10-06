<?php
class AdminController {
    private $adminModel;
    private $userModel;

    public function __construct($adminModel, $userModel) {
        $this->adminModel = $adminModel;
        $this->userModel = $userModel;
    }

    public function changeAdminPassword($currentPassword, $newPassword, $confirmPassword) {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Permissão negada';
            error_log("Permissão negada: usuário não logado.");
            header('Location: ../index.php');
            exit();
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'As novas senhas não coincidem';
            error_log("As novas senhas não coincidem.");
            header('Location: ../index.php');
            exit();
        }

        $admin = $this->adminModel->getAdminById($_SESSION['user_id']);
        error_log("Admin data: " . print_r($admin, true));

        if (!$admin || !password_verify($currentPassword, $admin['password'])) {
            $_SESSION['error'] = 'Senha atual incorreta';
            error_log("Senha atual incorreta ou admin não encontrado.");
            header('Location: ../index.php');
            exit();
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        error_log("Hashed new password: " . $hashedPassword);

        if ($this->adminModel->updateAdminPassword($_SESSION['user_id'], $hashedPassword)) {
            $_SESSION['message'] = 'Senha redefinida com sucesso';
            error_log("Senha redefinida com sucesso.");
        } else {
            $_SESSION['error'] = 'Erro ao redefinir a senha';
            error_log("Erro ao redefinir a senha.");
        }

        header('Location: ../index.php');
        exit();
    }

    public function resetUserPassword($userId, $newPassword, $confirmPassword) {
        session_start();

        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            $_SESSION['error'] = 'Permissão negada';
            error_log("Permissão negada: usuário não é admin.");
            header('Location: ../index.php');
            exit();
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'As novas senhas não coincidem';
            error_log("As novas senhas não coincidem.");
            header('Location: ../index.php');
            exit();
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        error_log("Hashed new password for user: " . $hashedPassword);

        if ($this->userModel->updateUserPassword($userId, $hashedPassword)) {
            $_SESSION['message'] = 'Senha do usuário redefinida com sucesso';
            error_log("Senha do usuário redefinida com sucesso.");
        } else {
            $_SESSION['error'] = 'Erro ao redefinir a senha do usuário';
            error_log("Erro ao redefinir a senha do usuário.");
        }

        header('Location: ../index.php');
        exit();
    }
}
?>
