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
        $base = $segments[0];
        // Normalize controller segment
        $map = [
            'leads' => 'LeadController',
            'pipelines' => 'PipelineController',
            'flows' => 'FlowController',
            'whatsapp' => 'WhatsAppController',
        ];
        if (isset($map[$base])) {
            $controllerName = $map[$base];
        } else {
            // simple plural to singular (strip trailing 's') fallback
            $normalized = rtrim($base, '/');
            if (str_ends_with($normalized, 's')) {
                $normalized = substr($normalized, 0, -1);
            }
            $controllerName = ucfirst($normalized) . 'Controller';
        }
        $method = $segments[1] ?? 'index';
        $params = array_slice($segments, 2);

        $controllerClass = 'App\\controllers\\' . $controllerName;
        // Let autoloader resolve the class; avoid manual require to prevent open_basedir issues
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
