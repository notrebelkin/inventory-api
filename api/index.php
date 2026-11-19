<?php

use Illuminate\Http\Request;
use Illuminate\Contracts\Http\Kernel;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';

// Menggunakan direktori sementara Vercel
$storage = sys_get_temp_dir().'/inventory-laravel';

foreach ([
    'framework/cache/data',
    'framework/sessions',
    'framework/views'
] as $dir) {
    $path = $storage.'/'.$dir;

    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }
}

$app->useStoragePath($storage);

// Menjalankan Laravel 9 melalui HTTP Kernel
$kernel = $app->make(Kernel::class);

$request = Request::capture();

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);
