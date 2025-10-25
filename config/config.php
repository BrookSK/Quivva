<?php
// Environment-aware configuration
// Env selection: APP_ENV or QUIVVA_ENV. Expected values: 'production' or 'development'.
$env = getenv('APP_ENV') ?: getenv('QUIVVA_ENV') ?: 'development';
$isProd = in_array(strtolower($env), ['prod', 'production'], true);

// Helper to fetch env with fallback list
$envVal = function (array $keys, $default = null) {
    foreach ($keys as $k) {
        $v = getenv($k);
        if ($v !== false && $v !== '') return $v;
    }
    return $default;
};

$db = [
    'host' => $envVal($isProd ? ['QUIVVA_DB_HOST_PROD', 'QUIVVA_DB_HOST'] : ['QUIVVA_DB_HOST_DEV', 'QUIVVA_DB_HOST'], $isProd ? '127.0.0.1' : '127.0.0.1'),
    'port' => $envVal($isProd ? ['QUIVVA_DB_PORT_PROD', 'QUIVVA_DB_PORT'] : ['QUIVVA_DB_PORT_DEV', 'QUIVVA_DB_PORT'], '3306'),
    'name' => $envVal($isProd ? ['QUIVVA_DB_NAME_PROD', 'QUIVVA_DB_NAME'] : ['QUIVVA_DB_NAME_DEV', 'QUIVVA_DB_NAME'], 'quivva'),
    'user' => $envVal($isProd ? ['QUIVVA_DB_USER_PROD', 'QUIVVA_DB_USER'] : ['QUIVVA_DB_USER_DEV', 'QUIVVA_DB_USER'], $isProd ? 'root' : 'root'),
    'pass' => $envVal($isProd ? ['QUIVVA_DB_PASS_PROD', 'QUIVVA_DB_PASS'] : ['QUIVVA_DB_PASS_DEV', 'QUIVVA_DB_PASS'], $isProd ? '' : ''),
    'charset' => 'utf8mb4',
];

$baseUrl = $envVal($isProd ? ['QUIVVA_BASE_URL_PROD', 'QUIVVA_BASE_URL'] : ['QUIVVA_BASE_URL_DEV', 'QUIVVA_BASE_URL'], '/');

return [
    'app_name' => 'Quivva',
    'env' => $isProd ? 'production' : 'development',
    'base_url' => $baseUrl,
    'db' => $db,
    'security' => [
        'csrf_key' => $envVal(['QUIVVA_CSRF_KEY'], 'change_this_csrf_key'),
    ],
];
