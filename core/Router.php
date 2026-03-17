<?php
class Router {
    private $routes = [];

    public function get($url, $action) {
        $this->routes['GET'][$url] = $action;
    }

    public function post($url, $action) {
        $this->routes['POST'][$url] = $action;
    }
    public function dispatch($url, $method) {
        if (isset($this->routes[$method][$url])) {
            $action = $this->routes[$method][$url];
            [$controller, $func] = explode('@', $action);
            require_once __DIR__ . '/../app/controllers/' . $controller . '.php';
            $controllerInstance = new $controller();
            $controllerInstance->$func();
        } else {
            http_response_code(404);
        }
    }
} 