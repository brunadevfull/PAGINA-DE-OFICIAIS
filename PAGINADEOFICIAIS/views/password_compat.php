<?php
if (!function_exists('password_verify')) {
    function password_verify($password, $hash) {
        return crypt($password, $hash) === $hash;
    }
}

if (!function_exists('password_hash')) {
    define('PASSWORD_BCRYPT', 1);

    function password_hash($password, $algo, array $options = array()) {
        if ($algo !== PASSWORD_BCRYPT) {
            trigger_error("password_hash(): Unknown hashing algorithm: " . $algo, E_USER_WARNING);
            return null;
        }

        $cost = isset($options['cost']) ? $options['cost'] : 10;
        $salt = isset($options['salt']) ? $options['salt'] : bin2hex(openssl_random_pseudo_bytes(22));

        $hash = crypt($password, sprintf('$2y$%02d$', $cost) . $salt);

        if (strlen($hash) <= 13) {
            return false;
        }

        return $hash;
    }
}
?>
