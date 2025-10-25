<?php
namespace App\core;

use Exception;

class App
{
    public function run(): void
    {
        Auth::startSession();
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
        $uri = trim($uri, '/');
        if ($uri === '') { $uri = 'dashboard/index'; }

        $segments = explode('/', $uri);
        $controllerName = ucfirst($segments[0]) . 'Controller';
        $method = $segments[1] ?? 'index';
        $params = array_slice($segments, 2);

        $controllerClass = 'App\\controllers\\' . $controllerName;
        $file = __DIR__ . '/../controllers/' . $controllerName . '.php';
        if (!file_exists($file)) {
            http_response_code(404);
            echo 'Controller not found';
            return;
        }
        require_once $file;

        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo 'Controller class missing';
            return;
        }

        $controller = new $controllerClass();
        if (!method_exists($controller, $method)) {
            http_response_code(404);
            echo 'Action not found';
            return;
        }

        try {
            call_user_func_array([$controller, $method], $params);
        } catch (Exception $e) {
            http_response_code(500);
            echo 'Application error: ' . htmlspecialchars($e->getMessage());
        }
    }
}
