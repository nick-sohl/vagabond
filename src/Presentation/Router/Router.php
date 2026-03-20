<?php

namespace FlightBookingSystem\Presentation\Router;

class Router
{
    // We store all registered routes from index php in this variable
    private array $routes;

    /**
     * @param array<string, string> $controller
     * The array takes a fully qualified class name as a string and the path as a string
     *
     */
    public function get(string $path, array $controller): void
    {
          $this->routes[] = ['method' => 'GET', 'path' => $path, 'controller' => $controller];
    }
    /**
     * @param array<string, string> $controller
     * The array takes a fully qualified class name as a string and the path as a string
     *
     */
    public function post(string $path, array $controller): void
    {
          $this->routes[] = ['method' => 'POST', 'path' => $path, 'controller' => $controller];
    }

    public function dispatch(string $method, string $path): void
    {
        // Loop through routes look up table and compare it with arguments
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                // Match found -> Get Class and Method
                $controller = $route['controller'][0]; // \FlightBookingSystem\Presentation\Controller\<Name>Controller;
                $method = $route['controller'][1]; // e.g. index;
                $controller->$method();
                return;
            }
        }

        // No route matched
        http_response_code(404);
    }
}
