<?php
// One-time hash generator for Admin@2025!
// Protect with token; delete this file after use.
$token = $_GET['token'] ?? '';
if ($token !== 'LRV@web@2025##') {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Forbidden';
    exit;
}

$hash = password_hash('Admin@2025!', PASSWORD_BCRYPT);
header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    'password' => 'Admin@2025!',
    'password_hash' => $hash,
    'note' => 'Use este hash para atualizar users.password_hash e delete public/hash_gen.php após o uso.'
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
