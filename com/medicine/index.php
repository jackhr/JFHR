<?php

declare(strict_types=1);

$publicEntry = __DIR__ . '/public/index.php';
$fallbackEntry = __DIR__ . '/../../../medicine-log/public/index.php';

if (is_file($publicEntry)) {
    require_once $publicEntry;
    return;
}

if (is_file($fallbackEntry)) {
    require_once $fallbackEntry;
    return;
}

http_response_code(500);
echo 'Medicine app entry point not found.';
