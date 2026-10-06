<?php
class UserController {
    public function login() {
        include '../config/config.php';

        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Consulta SQL para verificar o usuário
            $stmt = $pdo->prepare('SELECT id, password FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Autenticação bem-sucedida
                $_SESSION['user_id'] = $user['id'];
                header('Location: ../index.php');
                exit();
            } else {
                // Autenticação falhou
                $error = "Usuário ou senha inválidos.";
                header('Location: ../views/login.php?error=' . urlencode($error));
                exit();
            }
        } else {
            $error = "Método de solicitação inválido.";
            header('Location: ../views/login.php?error=' . urlencode($error));
            exit();
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: ../views/login.php');
        exit();
    }
}

// Inicializa o controlador
if (isset($_GET['method'])) {
    $controller = new UserController();
    $method = $_GET['method'];
    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        echo "Método não encontrado.";
    }
}
?>

