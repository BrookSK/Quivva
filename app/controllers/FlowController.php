<?php
namespace App\controllers;

use App\core\Auth;
use App\core\Controller;
use App\models\ChatbotFlow;
use App\models\Lead;
use App\models\Message;

class FlowController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $user = Auth::user();
        $flowModel = new ChatbotFlow();
        $flows = $flowModel->all(['company_id' => $user['company_id']], 'created_at DESC');
        $this->view('flows/index', ['flows' => $flows]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        $this->view('flows/create', []);
    }

    public function store(): void
    {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $this->redirect('flows/index');
        }
        $user = Auth::user();
        $name = trim($_POST['name'] ?? 'Novo Fluxo');
        $definition = $_POST['definition'] ?? '{"blocks": []}';
        (new ChatbotFlow())->create([
            'company_id' => $user['company_id'],
            'name' => $name,
            'definition' => $definition,
        ]);
        $this->redirect('flows/index');
    }

    public function edit($id): void
    {
        Auth::requireLogin();
        $flow = (new ChatbotFlow())->find((int)$id);
        if (!$flow) { $this->redirect('flows/index'); }
        $this->view('flows/edit', ['flow' => $flow]);
    }

    public function update($id): void
    {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $this->redirect('flows/index');
        }
        $name = trim($_POST['name'] ?? 'Fluxo');
        $definition = $_POST['definition'] ?? '{"blocks": []}';
        (new ChatbotFlow())->update((int)$id, [
            'name' => $name,
            'definition' => $definition,
        ]);
        $this->redirect('flows/index');
    }

    public function delete($id): void
    {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $this->redirect('flows/index');
        }
        (new ChatbotFlow())->delete((int)$id);
        $this->redirect('flows/index');
    }

    public function run($id): void
    {
        Auth::requireLogin();
        $user = Auth::user();
        $flow = (new ChatbotFlow())->find((int)$id);
        if (!$flow) { $this->redirect('flows/index'); }
        $leads = (new Lead())->all(['company_id' => $user['company_id']], 'created_at DESC');
        $this->view('flows/run', ['flow' => $flow, 'leads' => $leads]);
    }

    public function execute(): void
    {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $this->redirect('flows/index');
        }
        $flowId = (int)($_POST['flow_id'] ?? 0);
        $leadId = (int)($_POST['lead_id'] ?? 0);
        $flow = (new ChatbotFlow())->find($flowId);
        if (!$flow || $leadId <= 0) { $this->redirect('flows/index'); }
        $def = json_decode($flow['definition'] ?? '{}', true);
        $blocks = $def['blocks'] ?? [];

        $messageModel = new Message();
        // Simulate sending blocks: replace {{var}} placeholders with sample values or keep tags
        foreach ($blocks as $b) {
            if (($b['type'] ?? '') === 'text') {
                $text = (string)($b['text'] ?? '');
                $messageModel->create(['lead_id' => $leadId, 'sender' => 'system', 'message' => $text]);
            } elseif (($b['type'] ?? '') === 'question') {
                $text = (string)($b['text'] ?? '');
                $messageModel->create(['lead_id' => $leadId, 'sender' => 'system', 'message' => $text]);
            }
        }
        $this->redirect('chat/index/' . $leadId);
    }
}
