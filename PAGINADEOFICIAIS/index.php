<?php
session_start();
include 'config/config.php';
include 'controllers/OficialController.php';
include 'controllers/DutyOfficerController.php';

// Verificar se há um parâmetro para a rota
$route = $_GET['route'] ?? 'oficiais';

// Selecionar o controlador com base na rota
if ($route === 'duty-officers') {
    $controller = new DutyOfficerController();
    $controller->index();
} else {
    // Rota padrão: oficiais
    $controller = new OficialController();
    $controller->index();
}
?>