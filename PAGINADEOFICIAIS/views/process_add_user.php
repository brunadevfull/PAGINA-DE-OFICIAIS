<?php
include 'password_compat.php';
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/config/config.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    try {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Define a mensagem de erro corretamente
            $_SESSION['error'] = 'Usuário já existe.';
            $response['status'] = 'error';
            $response['message'] = $_SESSION['error'];
            echo json_encode($response);
            // Limpa a variável de sessão de erro após o uso
            unset($_SESSION['error']);
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (username, password, is_admin) VALUES (:username, :password, :is_admin)');
        $stmt->execute(['username' => $username, 'password' => $hashed_password, 'is_admin' => $is_admin]);

        $_SESSION['success'] = 'Usuário adicionado com sucesso.';
        $response['status'] = 'success';
        $response['message'] = $_SESSION['success'];
        echo json_encode($response);

        // Limpa a variável de sessão de sucesso após o uso
        unset($_SESSION['success']);
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro ao adicionar usuário: ' . $e->getMessage();
        $response['status'] = 'error';
        $response['message'] = $_SESSION['error'];
        echo json_encode($response);

        // Limpa a variável de sessão de erro após o uso
        unset($_SESSION['error']);
        exit();
    }
}
?>
