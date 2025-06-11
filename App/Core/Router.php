<?php
namespace Core;

class Router
{
    /**
     * Yeni bir rota ekler.
     *
     * @param string $method     HTTP metodu (GET, POST, PUT, DELETE vb.)
     * @param string $url        URL deseni; {param} biçiminde parametrik segmentler içerebilir
     * @param string $controller Denetleyici sınıfının kısa adı (örneğin "HomeController")
     * @param string $action     Denetleyici içindeki metod adı
     * @return void
     */
    public function add($method, $url, $controller, $action)
    {
        $this->routes[] = [
            'method'     => $method,
            'url'        => $url,
            'controller' => $controller,
            'action'     => $action
        ];
    }

    /**
     * Gelen isteği tanımlı rotalarla eşleştirir ve ilgili kontrolcü metodunu çağırır.
     * Eşleşme bulunamazsa 404 sayfasını yükler ve script'i durdurur.
     *
     * @param string $method HTTP metodu
     * @param string $uri    İstek URI'si (base path çıkarılmış)
     * @return mixed         Kontrolcü metodun döndürdüğü değer veya hiç döndürmez
     */
    public function dispatch($method, $uri)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            // {param} kalıplarını yakalayıcı gruplara dönüştür
            $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([^/]+)', $route['url']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // İlk eleman tüm URI

                $controllerName = "App\\Controllers\\" . $route['controller'];
                $action         = $route['action'];
                $controller     = new $controllerName();

                return call_user_func_array([$controller, $action], $matches);
            }
        }

        http_response_code(404);
        require_once __DIR__ . '/../Views/Error/404.php';
        exit;
    }
}
