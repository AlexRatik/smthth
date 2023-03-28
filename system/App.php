<?php
declare(strict_types=1);

namespace system;

use controllers\AppController;

class App
{

    private AppController $appController;

    public function __construct()
    {
        $this->appController = new AppController();
    }

    public function run(): void
    {
        $this->appController->index();
    }

}
