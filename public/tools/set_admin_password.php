<?php
// One-time tool to set admin password and print its bcrypt hash.
// Usage: /tools/set_admin_password.php?password=Admin@2025!&token=SET_A_SECRET
// IMPORTANT: Delete this file after use.

require __DIR__ . '/../../vendor/autoload.php';

use App\core\Database;

$config = require __DIR__ . '/../../config/config.php';
$expectedToken = getenv('QUIVVA_SETUP_TOKEN') ?: 'SET_A_SECRET';
$token = $_GET['token'] ?? '';
if (!$token || !hash_equals($expectedToken, $token)) {
    http_response_code(403);
    echo "Forbidden: missing or invalid token";
    exit;
}

$password = (string)($_GET['password'] ?? 'Admin@2025!');
if ($password === '') {
    http_response_code(400);
    echo "Password required";
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);

try {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE email = 'admin@acme.test' LIMIT 1");
    $stmt->execute(['hash' => $hash]);
} catch (Throwable $e) {
    http_response_code(500);
    echo "DB error: " . htmlspecialchars($e->getMessage());
    exit;
}

header('Content-Type: application/json');

echo json_encode([
    'status' => 'ok',
    'email' => 'admin@acme.test',
    'password' => $password,
    'password_hash' => $hash,
    'note' => 'Delete public/tools/set_admin_password.php after use.'
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
