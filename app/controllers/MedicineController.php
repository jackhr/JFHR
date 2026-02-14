<?php

namespace App\Controllers;

class MedicineController
{
    private string $mountPath = '/com/medicine';
    private string $targetOrigin = 'https://medicine.jackrainey.com';

    public function handle(): void
    {
        $requestPath = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $subPath = $this->extractSubPath($requestPath);

        $targetUrl = rtrim($this->targetOrigin, '/');
        if ($subPath !== '') {
            $targetUrl .= '/' . ltrim($subPath, '/');
        }

        $queryString = (string) ($_SERVER['QUERY_STRING'] ?? '');
        if ($queryString !== '') {
            $targetUrl .= '?' . $queryString;
        }

        $requestMethod = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $statusCode = ($requestMethod === 'GET' || $requestMethod === 'HEAD') ? 302 : 307;

        header('Location: ' . $targetUrl, true, $statusCode);
        exit;
    }

    private function extractSubPath(string $requestPath): string
    {
        $mountWithSlash = $this->mountPath . '/';
        if ($requestPath === $this->mountPath || $requestPath === $mountWithSlash) {
            return '';
        }

        if (str_starts_with($requestPath, $mountWithSlash)) {
            return ltrim(substr($requestPath, strlen($mountWithSlash)), '/');
        }

        return '';
    }
}
