<?php declare(strict_types=1);

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('bootstrap');
$dotenv->load();

spl_autoload_register(function ($class) {
    $dirs = [
        './'
    ];

    $fileName = str_replace('\\', '/', $class);

    foreach ($dirs as $dir) {
        $path = $dir . $fileName . '.php';
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});