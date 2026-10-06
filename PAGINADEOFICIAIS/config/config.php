<?php
try {

    $pdo = new PDO('pgsql:host=localhost;dbname=paginadeoficiais', 'pagoficial_rw', 'Papem_RW@2024');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'UTF8'");
} catch (PDOException $e) {
    die('Não foi possível conectar ao banco de dados: ' . $e->getMessage());
}
?>
