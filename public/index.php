<?php
// PHP version guard
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 80100) {
    http_response_code(500);
    echo 'PHP 8.1+ é requerido. Versão atual: ' . PHP_VERSION;
    exit;
}

// Composer autoload or PSR-4 fallback
$autoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoload)) {
    require $autoload;
} else {
    spl_autoload_register(function ($class) {
        $prefix = 'App\\';
        $baseDir = __DIR__ . '/../app/';
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        $relative = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
        if (is_file($file)) {
            require $file;
        }
    });
}

use App\core\App;
use App\core\Env;

$envPath = __DIR__ . '/../.env';
if (class_exists(Env::class)) {
    Env::load($envPath);
} else {
    // Fallback minimal .env loader
    if (is_file($envPath)) {
        foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') continue;
            $pos = strpos($line, '=');
            if ($pos === false) continue;
            $key = trim(substr($line, 0, $pos));
            $val = trim(substr($line, $pos + 1));
            if (getenv($key) === false) putenv($key . '=' . $val);
        }
    }
}

$app = new App();
$app->run();
