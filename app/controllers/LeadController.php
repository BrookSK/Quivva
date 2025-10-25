<?php
namespace App\controllers;

use App\core\Auth;
use App\core\Controller;
use App\models\Lead;
use App\models\Pipeline;

class LeadController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $user = Auth::user();
        $leadModel = new Lead();
        $leads = $leadModel->all(['company_id' => $user['company_id']], 'created_at DESC');
        $this->view('leads/index', ['leads' => $leads]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        $pipelineModel = new Pipeline();
        $user = Auth::user();
        $pipelines = $pipelineModel->all(['company_id' => $user['company_id']], 'position ASC');
        $this->view('leads/create', ['pipelines' => $pipelines]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $this->redirect('leads/index');
        }
        $user = Auth::user();
        $leadModel = new Lead();
        $id = $leadModel->create([
            'company_id' => $user['company_id'],
            'user_id' => $user['id'],
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'stage' => trim($_POST['stage'] ?? 'Novo'),
            'source' => trim($_POST['source'] ?? ''),
        ]);
        $this->redirect('leads/index');
    }

    public function edit($id): void
    {
        Auth::requireLogin();
        $leadModel = new Lead();
        $lead = $leadModel->find($id);
        if (!$lead) { $this->redirect('leads/index'); }
        $user = Auth::user();
        if ((int)$lead['company_id'] !== (int)$user['company_id']) { $this->redirect('leads/index'); }
        $this->view('leads/edit', ['lead' => $lead]);
    }

    public function update($id): void
    {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $this->redirect('leads/index');
        }
        $leadModel = new Lead();
        $lead = $leadModel->find($id);
        if (!$lead) { $this->redirect('leads/index'); }
        $leadModel->update($id, [
            'name' => trim($_POST['name'] ?? $lead['name']),
            'phone' => trim($_POST['phone'] ?? $lead['phone']),
            'email' => trim($_POST['email'] ?? $lead['email']),
            'stage' => trim($_POST['stage'] ?? $lead['stage']),
            'source' => trim($_POST['source'] ?? $lead['source']),
        ]);
        $this->redirect('leads/index');
    }

    public function delete($id): void
    {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $this->redirect('leads/index');
        }
        $leadModel = new Lead();
        $leadModel->delete($id);
        $this->redirect('leads/index');
    }
}
