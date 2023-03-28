<?php
declare(strict_types=1);

use controllers\UserController;
use controllers\OpenApiController;
use system\Request;

class Router
{
    private UserController $userController;


    private array $routes;

    public function __construct()
    {
        $this->routes = [];
        $this->configureRouter();
        $this->userController = new UserController();
    }

    public function addRoute(string $route, callable $cb): void
    {
        $route = preg_quote($route, '/');
        $route = str_replace('\{id\}', '(\d+)', $route);
        $route = str_replace('\{page\}', '(\d+)', $route);
        $route = '/^' . $route . '$/';
        $this->routes[$route] = $cb;
    }

    public function handleRoute(string $route, array $params = []): string
    {
        foreach ($this->routes as $routePattern => $routeCallback) {

            if (preg_match($routePattern, $route, $matches)) {

                if (isset($matches[1])) {
                    $params['id'] = $matches[1];
                }

                try {
                    $result = $routeCallback($params);

                    if (!is_string($result)) {
                        throw new Exception("Route callback must return a string.");
                    }

                    return $result;
                } catch (Exception $e) {
                    return "Route execution failed: " . $e->getMessage();
                }
            }
        }
        return "Route not found.";
    }

    public function configureRouter(): void
    {
        $this->addRoute('/', function () {
            header("Location: /users");
        });

        $this->addRoute('/users', function () {
            $this->userController->index();
        });

        $this->addRoute('/users?page={page}', function () {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $pageLimit = 10;
            $this->userController->getRecords($page, $pageLimit, new Request());
        });

        $this->addRoute('/users/total', function () {
            $this->userController->countAllRecords();
        });

        $this->addRoute("/user/{id}", function () {
            $this->userController->show();
        });

        $this->addRoute("/user/show/{id}", function ($params) {
            $id = intval($params['id']);
            $this->userController->getOne($id, new Request());
        });

        $this->addRoute('/users/new', function () {
            $this->userController->new();
        });

        $this->addRoute('/users/create', function () {
            $request = new Request();
            if ($request->getMethod() === 'POST') {
                $this->userController->create($request);
            }

        });

        $this->addRoute('/users/edit/{id}', function () {
            $this->userController->edit();
        });

        $this->addRoute('/users/update/{id}', function ($params) {
            $id = intval($params['id']);
            $this->userController->update(new Request(), $id);
        });

        $this->addRoute('/users/{id}', function ($params) {
            $id = intval($params['id']);
            $this->userController->delete($id, new Request());
        });

        $this->addRoute('/users/delete', function () {
            $request = new Request();
            if ($request->getMethod() === 'POST') {
                $this->userController->deleteMultiple(new Request());
            }
        });

        $this->addRoute('/swagger', function () {
            OpenApiController::index();
        });

        $this->addRoute('/swagger/docs', function () {
            OpenApiController::getDocs();
        });
    }
}