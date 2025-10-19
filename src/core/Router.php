<?php
namespace TinyApi\core;

class Router
{
    private array $routes = [];
    private array $middlewares = [];

    public function add(string $method, string $path, $handler)
    {
        $route = [
            'path' => $path,
            'handler' => $handler,
            'middlewares' => [],
        ];

        $this->routes[strtoupper($method)][] = $route;
        return $this; // permite encadear .middleware()
    }

    public function get($path, $handler)    { return $this->add('GET', $path, $handler); }
    public function post($path, $handler)   { return $this->add('POST', $path, $handler); }
    public function put($path, $handler)    { return $this->add('PUT', $path, $handler); }
    public function delete($path, $handler) { return $this->add('DELETE', $path, $handler); }

    public function middleware($name)
    {
        $lastMethod = array_key_last($this->routes);
        $lastIndex  = count($this->routes[$lastMethod]) - 1;
        $this->routes[$lastMethod][$lastIndex]['middlewares'][] = $name;
        return $this;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = strtok($_SERVER['REQUEST_URI'], '?');

        foreach ($this->routes[$method] ?? [] as $route) {
            $pattern = "@^" . preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', $route['path']) . "$@";
            if (preg_match($pattern, $uri, $matches)) {
                // executa middlewares antes do handler
                foreach ($route['middlewares'] as $middlewareName) {
                    $middlewareClass = "\\TinyApi\\core\\middleware\\" . ucfirst($middlewareName) . "Middleware";
                    (new $middlewareClass())->handle();
                }

                return $this->runHandler($route['handler'], $matches);
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Rota não encontrada']);
    }

    private function runHandler($handler, $params)
    {
        if (is_string($handler)) {
            [$controller, $method] = explode('@', $handler);
            $controllerClass = "\\TinyApi\\controllers\\{$controller}";
            $instance = new $controllerClass();
            return call_user_func_array([$instance, $method], array_filter($params, 'is_string', ARRAY_FILTER_USE_KEY));
        }

        if (is_callable($handler)) {
            return call_user_func($handler, $params);
        }
    }
}
