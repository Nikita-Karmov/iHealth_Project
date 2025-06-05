<?php

class Router {
    private $routes = [];

    public function add($path, $handler) {
        $this->routes[$path] = $handler;
    }

    public function dispatch($uri) {
        $parsed = parse_url($uri);
        $path = $parsed['path'];

        if (!isset($this->routes[$path])) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        [$controllerName, $method] = explode('@', $this->routes[$path]);

        $controllerClass = "App\\Controllers\\$controllerName";
        require_once __DIR__ . "/../controllers/$controllerName.php";

        $controller = new $controllerClass();
        $controller->$method();
    }
}
