<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../controllers/OficialController.php';

require_post('admin');

$controller = new OficialController();

try {
    $controller->edit();
    echo 'Oficial editado com sucesso.';
} catch (Exception $e) {
    http_response_code(400);
    echo 'Erro ao editar oficial: ' . e($e->getMessage());
}
