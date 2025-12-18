<?php

namespace App\Router;

class Router
{
    private array $routes = [];

    public function get(string $uri, array $action, array $middleware = []): void
    {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    public function post(string $uri, array $action, array $middleware = []): void
    {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    private function addRoute(
        string $method,
        string $uri,
        array $action,
        array $middleware
    ): void {
        $this->routes[] = [
            'method'     => $method,
            'uri'        => trim($uri, '/'),
            'action'     => $action,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = trim($uri, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $uri) {

                // Jalankan middleware
                foreach ($route['middleware'] as $middleware) {
                    (new $middleware())->handle();
                }

                [$controller, $action] = $route['action'];
                (new $controller())->$action();
                return;
            }
        }

        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Route not found'
        ]);
    }
}
