<?php

namespace App\Controllers;

class MedicineController
{
    private $mountPath = '/com/medicine';

    public function handle()
    {
        $paths = $this->resolveMedicinePaths();
        if ($paths === null) {
            http_response_code(500);
            echo 'Medicine app not found. Expected it under /com/medicine (or local /medicine-log fallback).';
            return;
        }

        $requestUri = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $subPath = $this->extractSubPath($requestUri);

        if ($subPath !== '' && $this->serveStaticIfExists($paths['public_dir'], $subPath)) {
            return;
        }

        $_SERVER['PHP_SELF'] = $this->mountPath . '/index.php';
        $_SERVER['SCRIPT_NAME'] = $this->mountPath . '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = $paths['entry_file'];

        require $paths['entry_file'];
    }

    private function resolveMedicinePaths()
    {
        $candidates = [
            __DIR__ . '/../../com/medicine',
            __DIR__ . '/../../../medicine-log',
        ];

        foreach ($candidates as $candidate) {
            $baseDir = realpath($candidate);
            if ($baseDir === false || !is_dir($baseDir)) {
                continue;
            }

            $publicEntry = $baseDir . '/public/index.php';
            if (is_file($publicEntry)) {
                return [
                    'public_dir' => $baseDir . '/public',
                    'entry_file' => $publicEntry,
                ];
            }

            $rootEntry = $baseDir . '/index.php';
            if (is_file($rootEntry)) {
                return [
                    'public_dir' => $baseDir,
                    'entry_file' => $rootEntry,
                ];
            }
        }

        return null;
    }

    private function extractSubPath($requestPath)
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

    private function serveStaticIfExists($publicDir, $relativePath)
    {
        $requestMethod = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        if ($requestMethod !== 'GET' && $requestMethod !== 'HEAD') {
            return false;
        }

        $cleanPath = rawurldecode($relativePath);
        if (str_contains($cleanPath, '..')) {
            return false;
        }

        $publicRoot = realpath($publicDir);
        if ($publicRoot === false) {
            return false;
        }

        $requestedFile = realpath($publicRoot . '/' . ltrim($cleanPath, '/'));
        if ($requestedFile === false || !is_file($requestedFile)) {
            return false;
        }

        if (!str_starts_with($requestedFile, $publicRoot . '/')) {
            return false;
        }

        $extension = strtolower(pathinfo($requestedFile, PATHINFO_EXTENSION));
        if ($extension === 'php') {
            return false;
        }

        $allowedExtensions = ['css', 'js', 'json', 'svg', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'ico', 'txt', 'map'];
        if (!in_array($extension, $allowedExtensions, true)) {
            return false;
        }

        $mimeType = match ($extension) {
            'css' => 'text/css; charset=UTF-8',
            'js' => 'application/javascript; charset=UTF-8',
            'json' => 'application/json; charset=UTF-8',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
            default => 'application/octet-stream',
        };

        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($requestedFile));
        if ($requestMethod === 'HEAD') {
            return true;
        }
        readfile($requestedFile);
        return true;
    }
}
