<?php

spl_autoload_register(function ($className) {
    $directories = [
        "./",
        "./app/",
        "./system/",
        "./bootstrap/",
        "./database/"
    ];

    $className = str_replace("\\", "/", $className);
    foreach ($directories as $directory) {
        $file = $directory . $className . ".php";
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

$dotenv = Dotenv\Dotenv::createImmutable('./bootstrap');
$dotenv->load();

require 'vendor/autoload.php';
