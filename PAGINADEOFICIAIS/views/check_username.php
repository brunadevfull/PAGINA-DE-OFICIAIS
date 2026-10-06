<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);

    try {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
        $stmt->execute([':username' => $username]);
        $userExists = $stmt->fetchColumn();

        if ($userExists) {
            echo 'exists';
        } else {
            echo 'not exists';
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage();
    }
}
?>
