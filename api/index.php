<?php

// Tampilkan Error untuk Debugging (matikan nanti di production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Path dasar
$basePath = realpath(__DIR__ . '/../');

// File ini HANYA dipanggil dari Vercel, jadi SELALU siapkan /tmp
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

// Set environment variables SEBELUM Laravel dimuat
putenv('VERCEL=1');
putenv('APP_STORAGE=' . $storagePath);
putenv('VIEW_COMPILED_PATH=' . $storagePath . '/framework/views');

// Hapus file cache lama yang bisa menyebabkan error
$cacheFiles = glob($basePath . '/bootstrap/cache/*.php');
foreach ($cacheFiles as $file) {
    @unlink($file);
}

// Jalankan Laravel
require $basePath . '/public/index.php';
