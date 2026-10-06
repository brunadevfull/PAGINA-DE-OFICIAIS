<?php
/**
 * Ponto único de entrada para sessão, conexão, autorização e CSRF.
 * Incluir com require_once __DIR__ . '/.../includes/bootstrap.php' no topo de cada endpoint.
 */

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']),
    ]);
    session_start();
}

require_once __DIR__ . '/../config/config.php';

/**
 * Escapa texto para HTML (corpo e atributos).
 */
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function auth_is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function auth_is_admin(): bool
{
    return auth_is_logged_in() && !empty($_SESSION['is_admin']);
}

/**
 * Encerra a requisição com um status HTTP e uma mensagem (texto ou JSON).
 */
function auth_deny(int $status, string $message, bool $json = false): void
{
    http_response_code($status);

    if ($json) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'error' => $message], JSON_UNESCAPED_UNICODE);
    } else {
        header('Content-Type: text/plain; charset=utf-8');
        echo $message;
    }

    exit;
}

function require_login(bool $json = false): void
{
    if (!auth_is_logged_in()) {
        auth_deny(401, 'Usuário não autenticado.', $json);
    }
}

function require_admin(bool $json = false): void
{
    require_login($json);

    if (!auth_is_admin()) {
        auth_deny(403, 'Acesso restrito ao administrador.', $json);
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Valida o token enviado no cabeçalho X-CSRF-Token (AJAX) ou no campo csrf_token (formulário).
 */
function csrf_verify(bool $json = false): void
{
    $sent = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['csrf_token'] ?? '');

    if (!is_string($sent) || $sent === '' || !hash_equals(csrf_token(), $sent)) {
        auth_deny(403, 'Token de segurança inválido ou ausente. Recarregue a página.', $json);
    }
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Exige método POST, usuário com o papel pedido e token CSRF válido.
 *
 * @param string $role 'any' (qualquer logado), 'admin' ou 'none' (só método e CSRF, ex.: login)
 */
function require_post(string $role = 'any', bool $json = false): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        auth_deny(405, 'Método não permitido.', $json);
    }

    if ($role === 'admin') {
        require_admin($json);
    } elseif ($role === 'any') {
        require_login($json);
    }

    csrf_verify($json);
}
