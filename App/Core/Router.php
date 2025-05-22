<?php
namespace Core;

class Router
{
    private $routes = [];

    public function add($method, $url, $controller, $action)
    {
        $this->routes[] = [
            'method'     => $method,
            'url'        => $url,
            'controller' => $controller,
            'action'     => $action
        ];
    }

    public function dispatch($method, $uri)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([^/]+)', $route['url']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // İlk match, tüm uri oluyor

                $controllerName = "App\\Controllers\\" . $route['controller'];
                $action = $route['action'];
                $controller = new $controllerName();

                return call_user_func_array([$controller, $action], $matches);
            }
        }

        http_response_code(404);
        echo "404 Sayfa Bulunamadı";
    }
}
