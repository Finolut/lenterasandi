<?php

// Enable error reporting for Vercel runtime troubleshooting
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Setup writable storage and bootstrap cache directories in /tmp for Vercel Serverless environment
$storage = '/tmp/storage';
$dirs = [
    'framework/views',
    'framework/sessions',
    'framework/cache/data',
    'bootstrap/cache',
    'logs',
];

foreach ($dirs as $dir) {
    $target = "{$storage}/{$dir}";
    if (!is_dir($target)) {
        @mkdir($target, 0755, true);
    }
}

// Copy pre-existing package and service caches if they exist
$srcCache = __DIR__ . '/../bootstrap/cache';
if (is_dir($srcCache)) {
    foreach (['packages.php', 'services.php'] as $file) {
        $source = "{$srcCache}/{$file}";
        $dest = "{$storage}/bootstrap/cache/{$file}";
        if (file_exists($source) && !file_exists($dest)) {
            @copy($source, $dest);
        }
    }
}

// Configure environment variables for serverless runtime
$envSettings = [
    'APP_STORAGE' => $storage,
    'VIEW_COMPILED_PATH' => "{$storage}/framework/views",
    'APP_CONFIG_CACHE' => "{$storage}/bootstrap/cache/config.php",
    'APP_EVENTS_CACHE' => "{$storage}/bootstrap/cache/events.php",
    'APP_PACKAGES_CACHE' => "{$storage}/bootstrap/cache/packages.php",
    'APP_ROUTES_CACHE' => "{$storage}/bootstrap/cache/routes-v7.php",
    'APP_SERVICES_CACHE' => "{$storage}/bootstrap/cache/services.php",
];

foreach ($envSettings as $key => $value) {
    putenv("{$key}={$value}");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

// Register shutdown function to catch fatal PHP errors
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(500);
        header('Content-Type: text/html; charset=UTF-8');
        echo '<div style="font-family: system-ui, -apple-system, sans-serif; padding: 2rem; background: #fff1f2; color: #9f1239; border-radius: 8px; margin: 2rem auto; max-width: 800px; border: 1px solid #fecdd3;">';
        echo '<h2 style="margin-top: 0;">Fatal PHP Error on Vercel</h2>';
        echo '<p><strong>Message:</strong> ' . htmlspecialchars($error['message']) . '</p>';
        echo '<p><strong>File:</strong> ' . htmlspecialchars($error['file']) . ' on line ' . $error['line'] . '</p>';
        echo '</div>';
    }
});

try {
    // Forward request to Laravel public/index.php
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/html; charset=UTF-8');
    echo '<div style="font-family: system-ui, -apple-system, sans-serif; padding: 2rem; background: #fff1f2; color: #9f1239; border-radius: 8px; margin: 2rem auto; max-width: 800px; border: 1px solid #fecdd3;">';
    echo '<h2 style="margin-top: 0;">Laravel Serverless Startup Error</h2>';
    echo '<p><strong>Exception:</strong> ' . htmlspecialchars(get_class($e)) . '</p>';
    echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</p>';
    echo '<h3 style="margin-top: 1.5rem; font-size: 1rem;">Stack Trace:</h3>';
    echo '<pre style="background: #ffffff; padding: 1rem; border-radius: 6px; overflow-x: auto; font-size: 0.85rem; border: 1px solid #e2e8f0; color: #1e293b;">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '</div>';
    error_log($e->getMessage() . "\n" . $e->getTraceAsString());
}

