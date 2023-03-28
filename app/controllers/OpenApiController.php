<?php

namespace controllers;

use OpenApi\Generator;

class OpenApiController
{
    static function index(): void {
        global $twig;
        echo $twig->render("swagger.twig");
    }

    static function getDocs(): void {
        $openapi = Generator::scan(['../app/controllers']);
        header('Content-Type: application/x-yaml');
        echo $openapi->toYaml();
    }
}