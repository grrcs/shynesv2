<?php

// 1. Definisikan path dasar
$basePath = realpath(__DIR__ . '/../');

// 2. Siapkan folder Storage di /tmp (hanya untuk Vercel)
if (isset($_SERVER['VERCEL'])) {
    $storagePath = '/tmp/storage';
    $folders = [
        $storagePath . '/framework/views',
        $storagePath . '/framework/cache',
        $storagePath . '/framework/sessions',
        $storagePath . '/logs',
    ];

    foreach ($folders as $folder) {
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }
    }
}

// 3. MATIKAN SEMUA CACHE FILES LOKAL (Sangat Penting untuk fix 'view' error)
// File ini tidak boleh terbawa ke Vercel
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

// 4. Jalankan aplikasi melalui index.php resmi
require $basePath . '/public/index.php';
