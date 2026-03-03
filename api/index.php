<?php

// 1. Definisikan path dasar
$basePath = realpath(__DIR__ . '/../');

// 2. Vercel hanya mengizinkan penulisan di folder /tmp
$storagePath = '/tmp/storage';

// Struktur folder yang dibutuhkan Laravel
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

// 3. Konfigurasi Environment khusus Vercel
putenv('APP_STORAGE=' . $storagePath);
putenv('VIEW_COMPILED_PATH=' . $storagePath . '/framework/views');
putenv('SESSION_DRIVER=cookie'); // Gunakan cookie agar tidak butuh DB/File untuk sesi sementara
putenv('LOG_CHANNEL=stderr');

// 4. MATIKAN SEMUA CACHE FILES (Sangat Penting)
// Laravel akan mencari file ini, jika ada, Laravel tidak akan loading ServiceProvider dengan benar
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

// 5. Jalankan aplikasi menggunakan public/index.php
require $basePath . '/public/index.php';
