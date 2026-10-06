<?php
require_once __DIR__ . '/../includes/bootstrap.php';

// Logout por POST com token CSRF, para que um link ou imagem de terceiros não derrube a sessão
require_post('none');

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

session_destroy();

header('Location: ../index.php');
exit();
