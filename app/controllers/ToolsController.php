<?php
namespace App\controllers;

use App\core\Controller;

class ToolsController extends Controller
{
    // GET /tools/hash?token=...
    public function hash(): void
    {
        $token = $_GET['token'] ?? '';
        $envToken = getenv('QUIVVA_SETUP_TOKEN') ?: 'LRV@web@2025##';
        if (!$token || !hash_equals($envToken, $token)) {
            $this->json(['error' => 'Forbidden'], 403);
            return;
        }
        $password = 'Admin@2025!';
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $this->json([
            'password' => $password,
            'password_hash' => $hash,
            'note' => 'Use este hash para atualizar users.password_hash e remova esta rota após o uso (ou altere QUIVVA_SETUP_TOKEN).'
        ]);
    }
}
