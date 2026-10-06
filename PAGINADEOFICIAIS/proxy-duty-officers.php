<?php
// CORS aberto apenas para leitura. A escrita (PUT) exige sessão e token CSRF, ou seja, mesma origem.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
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
            // Sessão e conexão do banco de oficiais só são necessárias para escrever
            require_once __DIR__ . '/includes/bootstrap.php';
            // Qualquer usuário logado pode gerir o oficial de serviço
            require_login(true);
            csrf_verify(true);
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
    error_log("RuntimeException no proxy: " . $exception->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => $exception->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $exception) {
    error_log("PDOException no proxy: " . $exception->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Erro de conexão com o banco de dados. Tente novamente.',
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $exception) {
    error_log("Exception genérica no proxy: " . $exception->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Erro interno do servidor. Tente novamente mais tarde.',
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $throwable) {
    error_log("Throwable no proxy: " . $throwable->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Erro crítico no servidor. Contate o administrador.',
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
