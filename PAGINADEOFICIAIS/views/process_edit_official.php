<?php
session_start();
include '../config/config.php';
include '../controllers/OficialController.php';

if (!isset($_SESSION['user_id']) ) {
    header('Location: login.php');
    exit();
}

$controller = new OficialController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $controller->edit();
        echo "<script>
                $(document).ready(function() {
                    $('#editSuccessModal').modal('show');
                });
              </script>";
    } catch (Exception $e) {
        echo "Erro ao editar oficial: " . htmlspecialchars($e->getMessage());
    }
}
?>
