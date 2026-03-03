<?php

// 1. Definisikan path dasar
$basePath = realpath(__DIR__ . '/../');

// 2. Vercel hanya mengizinkan penulisan di folder /tmp
$storagePath = '/tmp/storage';

// Buat struktur folder storage di /tmp jika belum ada
$folders = [
    $storagePath,
    $storagePath . '/framework',
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

// 3. Paksa Laravel menggunakan path di /tmp ini
putenv('APP_STORAGE=' . $storagePath);
putenv('VIEW_COMPILED_PATH=' . $storagePath . '/framework/views');
putenv('SESSION_DIRECTORY=' . $storagePath . '/framework/sessions');

// 4. Mencegah Laravel mencoba membaca cache lama dari bootstrap/cache
// Kita beritahu Laravel untuk mengabaikan file cache config/routes di Vercel
unset($_ENV['APP_CONFIG_CACHE']);
unset($_ENV['APP_ROUTES_CACHE']);
unset($_ENV['APP_SERVICES_CACHE']);
unset($_ENV['APP_PACKAGES_CACHE']);

// 5. Jalankan aplikasi melalui public/index.php asli
require $basePath . '/public/index.php';
