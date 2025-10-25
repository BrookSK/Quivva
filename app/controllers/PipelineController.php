<?php
namespace App\controllers;

use App\core\Auth;
use App\core\Controller;
use App\models\Pipeline;

class PipelineController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $user = Auth::user();
        $pipelineModel = new Pipeline();
        $pipelines = $pipelineModel->all(['company_id' => $user['company_id']], 'position ASC');
        $this->view('pipelines/index', ['pipelines' => $pipelines]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        $this->view('pipelines/create', []);
    }

    public function store(): void
    {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $this->redirect('pipelines/index');
        }
        $user = Auth::user();
        $pipelineModel = new Pipeline();
        $pipelineModel->create([
            'company_id' => $user['company_id'],
            'name' => trim($_POST['name'] ?? ''),
            'position' => (int)($_POST['position'] ?? 0),
        ]);
        $this->redirect('pipelines/index');
    }

    public function edit($id): void
    {
        Auth::requireLogin();
        $pipelineModel = new Pipeline();
        $pipeline = $pipelineModel->find($id);
        if (!$pipeline) { $this->redirect('pipelines/index'); }
        $this->view('pipelines/edit', ['pipeline' => $pipeline]);
    }

    public function update($id): void
    {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $this->redirect('pipelines/index');
        }
        $pipelineModel = new Pipeline();
        $pipelineModel->update($id, [
            'name' => trim($_POST['name'] ?? ''),
            'position' => (int)($_POST['position'] ?? 0),
        ]);
        $this->redirect('pipelines/index');
    }

    public function delete($id): void
    {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $this->redirect('pipelines/index');
        }
        $pipelineModel = new Pipeline();
        $pipelineModel->delete($id);
        $this->redirect('pipelines/index');
    }

    public function reorder(): void
    {
        Auth::requireLogin();
        header('Content-Type: application/json');
        $payload = json_decode(file_get_contents('php://input'), true);
        if (!$payload || !isset($payload['order']) || !is_array($payload['order'])) {
            $this->json(['error' => 'Invalid payload'], 400);
            return;
        }
        $pipelineModel = new Pipeline();
        foreach ($payload['order'] as $position => $id) {
            $pipelineModel->update((int)$id, ['position' => (int)$position + 1]);
        }
        $this->json(['status' => 'ok']);
    }
}
