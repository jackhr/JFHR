<?php

namespace Core;

class Router
{
    private $routes;
    private $groupPrefix;

    public function __construct()
    {
        $this->routes = [];
        $this->groupPrefix = '';
    }

    public function group($prefix, $callback)
    {
        $previousPrefix = $this->groupPrefix;
        $this->groupPrefix = $previousPrefix . $prefix;
        $callback($this);
        $this->groupPrefix = $previousPrefix;
    }

    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
    }

    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
    }

    private function addRoute($method, $path, $callback)
    {
        $fullPath = $this->groupPrefix . $path;
        $routeKey = $this->normalizeRoutePath($fullPath);
        $this->routes[$method][$routeKey] = $callback;
    }

    private function normalizeRoutePath($path)
    {
        $path = '/' . ltrim((string) $path, '/');
        if (str_ends_with($path, '/*')) {
            $basePath = substr($path, 0, -2);
            $basePath = $basePath === '' ? '/' : rtrim($basePath, '/');
            return $basePath . '/*';
        }

        if ($path !== '/') {
            $path = rtrim($path, '/');
            if ($path === '') {
                $path = '/';
            }
        }

        return $path;
    }

    private function normalizeRequestPath($path)
    {
        $path = '/' . ltrim((string) $path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
            if ($path === '') {
                $path = '/';
            }
        }

        return $path;
    }

    private function executeCallback($callback)
    {
        if (is_array($callback) && count($callback) == 2) {
            list($controllerName, $methodName) = $callback;
            $controller = new $controllerName();
            $controller->$methodName();
            return true;
        }

        if (is_callable($callback)) {
            call_user_func($callback);
            return true;
        }

        return false;
    }

    public function dispatch()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath = $this->normalizeRequestPath($requestUri);

        if (isset($this->routes[$requestMethod][$requestPath])) {
            $callback = $this->routes[$requestMethod][$requestPath];
            if ($this->executeCallback($callback)) {
                return;
            }

            header("HTTP/1.0 500 Internal Server Error");
            echo "Invalid route callback.";
            return;
        }

        $wildcardRoutes = [];
        foreach ($this->routes[$requestMethod] ?? [] as $routePath => $callback) {
            if (str_ends_with((string) $routePath, '/*')) {
                $wildcardRoutes[$routePath] = $callback;
            }
        }

        if ($wildcardRoutes !== []) {
            uksort($wildcardRoutes, static function ($left, $right) {
                return strlen((string) $right) <=> strlen((string) $left);
            });

            foreach ($wildcardRoutes as $routePath => $callback) {
                $prefix = substr((string) $routePath, 0, -2);
                if ($requestPath === $prefix || str_starts_with($requestPath, $prefix . '/')) {
                    if ($this->executeCallback($callback)) {
                        return;
                    }

                    header("HTTP/1.0 500 Internal Server Error");
                    echo "Invalid route callback.";
                    return;
                }
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found brooooo";
    }
}
