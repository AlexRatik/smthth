<?php
declare(strict_types=1);

namespace controllers;

use Router;

class AppController
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function index(): void
    {
        $request = $_SERVER['REQUEST_URI'];
        $this->router->handleRoute($request);
    }
}
