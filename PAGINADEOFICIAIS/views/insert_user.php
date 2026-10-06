<?php
include '../config/config.php';

try {
    $username = 'admin2';
    $password = password_hash('adminpassword', PASSWORD_DEFAULT); // Usar password_hash para criar um hash seguro

    // Preparar e executar a consulta
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->execute(['username' => $username, 'password' => $password]);

    echo "UsuÃ¡rio adicionado com sucesso!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

