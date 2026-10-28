<?php
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if (str_starts_with($path, '/PRACTICAL-07/storage')) {
    http_response_code(404);
    echo 'Not found';
    return true;
}
return false;
