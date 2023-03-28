<?php
declare(strict_types=1);

function autoloader($className): void
{
    $directories = [
        "../",
        "../app/",
        "../system/",
        "../bootstrap/",
        "../database/"
    ];

    $className = str_replace("\\", "/", $className);
    foreach ($directories as $directory) {
        $file = $directory . $className . ".php";
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
}

spl_autoload_register("autoloader");
