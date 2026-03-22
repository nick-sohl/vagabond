<?php

/**
 * Router for PHP's built-in web server.
 * Serves static files directly, routes everything else through index.php.
 */

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

// Block access to sensitive files
if (preg_match('/\.(sqlite|db|sql|env|log)$/', $path) || str_contains($path, '/.')) {
    http_response_code(403);
    echo '403 Forbidden';
    return true;
}

// Serve static files directly
if ($path !== '/' && is_file($file)) {
    return false;
}

// Route everything else through index.php
require __DIR__ . '/index.php';
