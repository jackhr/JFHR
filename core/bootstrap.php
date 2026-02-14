<?php

// Autoload Classes
spl_autoload_register(function ($class) {
    // Base directories for different namespaces
    $namespaceBaseDirs = [
        'App' => __DIR__ . '/../app/',
        'Core' => __DIR__ . '/../core/',
        'Config' => __DIR__ . '/../config/',
    ];

    // Iterate through the namespaces and attempt to load the class
    foreach ($namespaceBaseDirs as $prefix => $baseDir) {
        // Does the class use this namespace prefix?
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // Class does not use the current namespace prefix, skip it
            continue;
        }

        // Get the relative class name (strip the prefix)
        $relativeClass = substr($class, $len);

        // Trim any leading slashes in $relativeClass
        $relativeClass = ltrim($relativeClass, '\\');

        // Split the namespace by backslashes to get each part
        $parts = explode('\\', $relativeClass);

        // Iterate over parts and convert them to lowercase unless they are 'Controllers' or 'Models'
        $leaveNextPart = false;

        foreach ($parts as &$part) {
            if ($prefix === 'Core') {
                // means that we are already looking at the filename
                continue;
            }

            // Check if the current part is 'Controllers', or 'Models'
            if ($part === 'Controllers' || $part === 'Models' || $part === 'Helpers') {
                // Set flag to leave the next part unchanged (i.e., the filename)
                $leaveNextPart = true;
                $part = strtolower($part);
                continue;
            }

            // If the current part is not the filename (i.e., not the last part) or the leaveNextPart flag is false, lowercase it
            if (!$leaveNextPart) {
                $part = strtolower($part);
            } else {
                // Reset flag since we're processing the next part after the namespace match
                $leaveNextPart = false;
            }
        }

        // Rebuild the relative path with directory separators
        $filePath = implode('/', $parts) . '.php';

        // Build the full path to the file
        $file = $baseDir . $filePath;

        // Require the file if it exists
        if (file_exists($file)) {
            require_once $file;
            return; // Exit after loading the class
        } else {
            error_log("Autoloader could not find file: " . $file);
        }
    }
});

error_reporting(E_ALL);
ini_set('display_errors', getenv('APP_ENV') === 'development' ? '1' : '0');

// Load Environment Variables (Using a Simple Implementation)
if (file_exists(__DIR__ . '/../config/.env')) {
    $lines = file(__DIR__ . '/../config/.env');
    foreach ($lines as $line) {
        if (trim($line) && strpos($line, '=') !== false) {
            putenv(trim($line));
        }
    }
}
