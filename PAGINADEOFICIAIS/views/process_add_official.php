<?php
session_start();
include '../config/config.php';  // Certifique-se de que o caminho estÃ¡ correto
include '../controllers/OficialController.php';  // Certifique-se de que o caminho estÃ¡ correto



// Instancia o controlador
$controller = new OficialController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
        try {
            $controller->add(); // Adiciona o oficial
         
        } catch (Exception $e) {
            $_SESSION['error'] = htmlspecialchars($e->getMessage()); // Define uma variÃ¡vel de erro na sessÃ£o
        }
        header('Location: ../index.php'); // Redireciona para a pÃ¡gina onde o modal deve aparecer
        exit();
}
?>
