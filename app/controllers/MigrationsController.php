<?php
namespace App\controllers;

use App\core\Auth;
use App\core\Controller;
use App\core\Migrator;

class MigrationsController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $m = new Migrator(__DIR__ . '/../migrations');
        $this->view('migrations/index', [
            'applied' => $m->applied(),
            'pending' => array_map('basename', $m->pending()),
        ]);
    }

    public function up(): void
    {
        Auth::requireLogin();
        $m = new Migrator(__DIR__ . '/../migrations');
        $result = $m->up();
        $this->json($result);
    }
}
