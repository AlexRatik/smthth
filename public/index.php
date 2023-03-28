<?php
require_once "../bootstrap/autoload.php";
require '../vendor/autoload.php';

use system\App;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dotenv = Dotenv\Dotenv::createImmutable('../bootstrap');
$dotenv->load();

$loader = new FilesystemLoader("../app/views/");
$twig = new Environment($loader, [
    "strict_variables" => true
]);

$app = new App();
$app->run();
