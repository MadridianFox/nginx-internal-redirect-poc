<?php

if ($_SERVER['REQUEST_URI'] == '/auth') {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
    if ($authHeader != 'Bearer 123') {
        http_response_code(401);
        header('X-Status: from-auth');
        die();
    }
}

echo "<pre>";
var_dump($_SERVER);
echo "</pre>";