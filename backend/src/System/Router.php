<?php

namespace App\System;

use App\Utils\Http;
use ReflectionMethod;

class Router
{
    private array $routes = [];
    private string $currentGroupPrefix = '';

    public function __construct(
        private readonly ControllerFactory $controllerFactory
    ) {}

    public function group(string $prefix, callable $callback): void
    {
        $old = $this->currentGroupPrefix;
        $normalized = '/' . trim($prefix, '/');
        if ($normalized === '/')
            $normalized = '';
        $this->currentGroupPrefix = $old . $normalized;
        $callback($this);
        $this->currentGroupPrefix = $old;
    }

    public function get(string $uri, array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, array $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function put(string $uri, array $action): void
    {
        $this->addRoute('PUT', $uri, $action);
    }

    public function delete(string $uri, array $action): void
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    protected function addRoute(string $method, string $uri, array $action): void
    {
        $full = rtrim($this->currentGroupPrefix, '/') . '/' . ltrim($uri, '/');

        $full = '/' . trim($full, '/');
        if ($full === '/')
            $full = '/';

        $this->routes[$method][$full] = $action;
    }

    public function dispatch(): bool
    {
        $uri = Http::getNormalizedUri();
        $httpMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$httpMethod] ?? [] as $route => $action) {

            $pattern = preg_replace('#\{[^\}]+\}#', '([^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                [$controllerClass, $methodName] = $action;

                $controller = $this->controllerFactory->make($controllerClass);
                $reflection = new ReflectionMethod($controller, $methodName);
                $paramCount = $reflection->getNumberOfParameters();

                $input = json_decode(file_get_contents('php://input'), true) ?? [];
                $queryParams = $_GET;

                $args = $matches;

                if ($httpMethod === 'GET' && $paramCount > count($args)) {
                    $args[] = $queryParams;
                } elseif ($httpMethod !== 'GET' && $paramCount > count($args)) {
                    $args[] = $input;
                }

                $controller->$methodName(...$args);
                return true;
            }
        }

        return false;
    }
}
