<?php

// Vercel only allows writing to /tmp
// We override the default storage paths for compiled views and other caches
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    mkdir($storagePath, 0755, true);
    mkdir($storagePath . '/framework/views', 0755, true);
}

putenv('VIEW_COMPILED_PATH=' . $storagePath . '/framework/views');

// Forward the request to the real index.php
require __DIR__ . '/../public/index.php';
