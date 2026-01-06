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

    // ✅ TAMBAHAN METHOD PUT
    public function put(string $uri, array $action, array $middleware = []): void
    {
        $this->addRoute('PUT', $uri, $action, $middleware);
    }

    private function addRoute(
        string $method,
        string $uri,
        array $action,
        array $middleware
    ): void {
        $this->routes[] = [
            'method'     => strtoupper($method),
            'uri'        => trim($uri, '/'),
            'action'     => $action,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = trim($uri, '/');
        $method = strtoupper($method);

        foreach ($this->routes as $route) {

            $params = [];

            if (
                $route['method'] === $method &&
                $this->matchUri($route['uri'], $uri, $params)
            ) {
                // 👉 simpan param ke global (sementara)
                $_SERVER['ROUTE_PARAMS'] = $params;

                // Jalankan middleware
                foreach ($route['middleware'] as $middleware) {
                    (new $middleware())->handle();
                }

                [$controller, $action] = $route['action'];

                // 🔹 Inject param ke method controller (opsional)
                $controllerInstance = new $controller();

                if (!empty($params)) {
                    call_user_func_array(
                        [$controllerInstance, $action],
                        array_values($params)
                    );
                } else {
                    $controllerInstance->$action();
                }

                return;
            }
        }

        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Route not found'
        ]);
    }

    /**
     * Cocokkan URI dengan support {param}
     */
    private function matchUri(
        string $routeUri,
        string $requestUri,
        array &$params
    ): bool {
        $routeParts   = explode('/', trim($routeUri, '/'));
        $requestParts = explode('/', trim($requestUri, '/'));

        if (count($routeParts) !== count($requestParts)) {
            return false;
        }

        foreach ($routeParts as $i => $part) {
            // Jika {param}
            if (preg_match('/^{(.+)}$/', $part, $matches)) {
                $params[$matches[1]] = $requestParts[$i];
                continue;
            }

            // Exact match
            if ($part !== $requestParts[$i]) {
                return false;
            }
        }

        return true;
    }
}
