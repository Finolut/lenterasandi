<?php

// Setup writable storage directory in /tmp for Vercel Serverless environment
$storage = '/tmp/storage';
foreach (['framework/views', 'framework/sessions', 'framework/cache/data', 'logs'] as $dir) {
    $target = "{$storage}/{$dir}";
    if (!is_dir($target)) {
        @mkdir($target, 0755, true);
    }
}

putenv("APP_STORAGE={$storage}");
$_ENV['APP_STORAGE'] = $storage;
$_SERVER['APP_STORAGE'] = $storage;

// Forward request to Laravel public/index.php
require __DIR__ . '/../public/index.php';
