<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../controllers/OficialController.php';

require_post('admin');

$controller = new OficialController();

try {
    $controller->add();
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

header('Location: ../index.php');
exit();
