<?php
// If the request is for an existing file, serve it
if (php_sapi_name() === 'cli-server') {
    $path = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($path)) {
        return false;
    }
}

// Otherwise, always serve index.php
require_once __DIR__ . '/index.php';
