<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/OficialStatusStore.php';

// Qualquer usuário logado pode alternar o status; o JS espera o texto abaixo em caso de sucesso
require_post('any');

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$status = $_POST['status'] ?? null;

if ($id === false || $id === null || $id <= 0 || !OficialStatusStore::isValidStatus($status)) {
    auth_deny(400, 'Dados inválidos.');
}

try {
    OficialStatusStore::set($id, $status);
} catch (Exception $e) {
    error_log('Erro ao atualizar status do oficial ' . $id . ': ' . $e->getMessage());
    auth_deny(500, 'Erro ao atualizar o status.');
}

echo 'Status atualizado com sucesso.';
