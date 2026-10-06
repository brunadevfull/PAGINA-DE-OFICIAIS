<?php
// Cabeçalhos para permitir CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Se for uma requisição OPTIONS, apenas retornar com os cabeçalhos CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/includes/DutyAssignmentsRepository.php';

$repository = new DutyAssignmentsRepository();
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    switch (strtoupper($method)) {
        case 'GET':
            handleGet($repository);
            break;
        case 'PUT':
            handlePut($repository);
            break;
        default:
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'Método não permitido.'
            ], JSON_UNESCAPED_UNICODE);
            break;
    }
} catch (RuntimeException $exception) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => $exception->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}

function handleGet(DutyAssignmentsRepository $repository): void
{
    header('Content-Type: application/json');

    $assignment = $repository->getCurrentAssignment();

    if ($assignment === null) {
        echo json_encode([
            'success' => true,
            'officers' => [
                'id' => null,
                'officerName' => null,
                'officerRank' => null,
                'masterName' => null,
                'masterRank' => null,
                'validFrom' => null,
                'updatedAt' => null,
            ],
        ], JSON_UNESCAPED_UNICODE);
        return;
    }

    echo json_encode([
        'success' => true,
        'officers' => $assignment,
    ], JSON_UNESCAPED_UNICODE);
}

function handlePut(DutyAssignmentsRepository $repository): void
{
    header('Content-Type: application/json');

    $rawBody = file_get_contents('php://input');
    $payload = json_decode($rawBody ?? '', true);

    if (!is_array($payload)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Corpo da requisição inválido. Use JSON válido.',
        ], JSON_UNESCAPED_UNICODE);
        return;
    }

    $officerName = isset($payload['officerName']) ? trim((string)$payload['officerName']) : '';
    $masterName = isset($payload['masterName']) ? trim((string)$payload['masterName']) : '';

    if ($officerName === '' && $masterName === '') {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Selecione pelo menos um oficial de serviço.',
        ], JSON_UNESCAPED_UNICODE);
        return;
    }

    $assignment = $repository->createAssignment([
        'officerName' => $payload['officerName'] ?? null,
        'officerRank' => $payload['officerRank'] ?? null,
        'masterName' => $payload['masterName'] ?? null,
        'masterRank' => $payload['masterRank'] ?? null,
        'validFrom' => $payload['validFrom'] ?? null,
    ]);

    echo json_encode([
        'success' => true,
        'officers' => $assignment,
    ], JSON_UNESCAPED_UNICODE);
}
