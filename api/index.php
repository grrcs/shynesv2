<?php

// 1. Tampilkan Error (Hanya untuk Debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Definisikan path dasar
$basePath = realpath(__DIR__ . '/../');

// 3. Siapkan folder Storage di /tmp (Hanya untuk Vercel)
if (isset($_SERVER['VERCEL']) || env('VERCEL')) {
    $storagePath = '/tmp/storage';
    $folders = [
        $storagePath . '/framework/views',
        $storagePath . '/framework/cache',
        $storagePath . '/framework/cache/data',
        $storagePath . '/framework/sessions',
        $storagePath . '/logs',
    ];

    foreach ($folders as $folder) {
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }
    }

    // Paksa Laravel menggunakan path ini
    putenv('APP_STORAGE=' . $storagePath);
    putenv('VIEW_COMPILED_PATH=' . $storagePath . '/framework/views');
    putenv('FRAMEWORK_CACHE_PATH=' . $storagePath . '/framework/cache');
}

// 4. MATIKAN SEMUA CACHE FILES LOKAL
$cacheFiles = [
    $basePath . '/bootstrap/cache/config.php',
    $basePath . '/bootstrap/cache/routes.php',
    $basePath . '/bootstrap/cache/services.php',
    $basePath . '/bootstrap/cache/packages.php',
];

foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        @unlink($file);
    }
}

// 5. Jalankan aplikasi melalu index.php resmi
require $basePath . '/public/index.php';
