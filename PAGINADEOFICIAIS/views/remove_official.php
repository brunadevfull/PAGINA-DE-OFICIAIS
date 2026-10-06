<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../controllers/OficialController.php';

require_post('admin');

$controller = new OficialController();

try {
    $controller->remove();
    echo 'Oficial removido com sucesso.';
} catch (Exception $e) {
    http_response_code(400);
    echo 'Erro ao remover oficial: ' . e($e->getMessage());
}
