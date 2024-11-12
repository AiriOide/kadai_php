<?php
function encrypt_data($data, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt(serialize($data), 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decrypt_data($data, $key) {
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return unserialize(openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv));
}

function set_secure_data($name, $data) {
    $key = getenv('ENCRYPTION_KEY') ?: 'default-secret-key';
    $_SESSION[$name] = encrypt_data($data, $key);
}

function get_secure_data($name) {
    if (!isset($_SESSION[$name])) {
        return null;
    }
    $key = getenv('ENCRYPTION_KEY') ?: 'default-secret-key';
    return decrypt_data($_SESSION[$name], $key);
}