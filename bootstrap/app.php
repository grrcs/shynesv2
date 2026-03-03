<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            return redirect()->back()->with('error', 'Ukuran file yang diupload terlalu besar! Silakan perkecil ukuran file atau tingkatkan batas upload di server.');
        });
    })
    ->create();

/*
|--------------------------------------------------------------------------
| Vercel Compatibility
|--------------------------------------------------------------------------
*/
if (isset($_SERVER['VERCEL']) || env('VERCEL')) {
    // Paksa storage ke folder /tmp (satu-satunya folder yang bisa ditulisi di Vercel)
    $app->useStoragePath('/tmp/storage');
    
    // Pastikan folder log ada agar tidak error saat menulis error
    if (!is_dir('/tmp/storage/logs')) {
        mkdir('/tmp/storage/logs', 0755, true);
    }
}

return $app;
