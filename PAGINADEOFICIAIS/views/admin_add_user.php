<?php
session_start();
include '../config/config.php';

// Verifica se o usuÃ¡rio estÃ¡ logado e Ã© administrador
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, password, is_admin) VALUES (:username, :password, :is_admin)');
        $stmt->execute(['username' => $username, 'password' => $password, 'is_admin' => $is_admin]);
        echo "UsuÃ¡rio adicionado com sucesso!";
    } catch (PDOException $e) {
        echo "Erro ao adicionar usuÃ¡rio: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <title>Adicionar UsuÃ¡rio</title>
</head>
<body>
<div class="container">
    <h2 class="mt-5">Adicionar UsuÃ¡rio</h2>
    <form action="admin_add_user.php" method="POST">
        <div class="form-group">
            <label for="username">UsuÃ¡rio:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="is_admin">Administrador:</label>
            <input type="checkbox" id="is_admin" name="is_admin">
        </div>
        <button type="submit" class="btn btn-primary">Adicionar</button>
    </form>
</div>
</body>
</html>
