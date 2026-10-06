<?php
return [
    'base_url' => getenv('NODE_API_BASE_URL') ?: 'http://10.1.129.46:5001',
    'timeout' => (int)(getenv('NODE_API_TIMEOUT') ?: 10),
    'auth' => [
        'type' => getenv('NODE_API_AUTH_TYPE') ?: null,
        'token' => getenv('NODE_API_TOKEN') ?: null,
        'username' => getenv('NODE_API_USERNAME') ?: null,
        'password' => getenv('NODE_API_PASSWORD') ?: null,
    ],
];
