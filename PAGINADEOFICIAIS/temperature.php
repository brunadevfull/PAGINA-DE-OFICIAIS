<?php
/**
 * Endpoint JSON da temperatura (Rio de Janeiro).
 * O servidor busca nas APIs externas e guarda cache de 30 minutos,
 * assim os navegadores não precisam de acesso direto à internet.
 */
ini_set('display_errors', '0');
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

require_once __DIR__ . '/includes/TemperatureUtils.php';

$weather = TemperatureUtils::getCurrentTemperature();

if (!$weather) {
    http_response_code(503);
    echo json_encode(['error' => 'Temperatura indisponível']);
    exit;
}

echo json_encode($weather);
