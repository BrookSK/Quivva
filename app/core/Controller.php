<?php
namespace App\core;

class Controller
{
    protected array $data = [];

    protected function view(string $view, array $data = []): void
    {
        $this->data = $data;
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(404);
            echo "View not found: {$view}";
            return;
        }
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../views/partials/header.php';
        require $viewFile;
        require __DIR__ . '/../views/partials/footer.php';
    }

    protected function json($payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    protected function redirect(string $path): void
    {
        $base = (require __DIR__ . '/../../config/config.php')['base_url'] ?? '/';
        header('Location: ' . rtrim($base, '/') . '/' . ltrim($path, '/'));
        exit;
    }

    protected function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
}
