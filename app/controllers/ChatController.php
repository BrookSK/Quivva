<?php
namespace App\controllers;

use App\core\Auth;
use App\core\Controller;
use App\models\Message;
use App\models\Lead;

class ChatController extends Controller
{
    public function index($lead_id): void
    {
        Auth::requireLogin();
        $leadModel = new Lead();
        $messageModel = new Message();
        $lead = $leadModel->find((int)$lead_id);
        if (!$lead) { $this->redirect('leads/index'); }
        $messages = $messageModel->all(['lead_id' => (int)$lead_id], 'created_at ASC');
        $this->view('chat/index', ['lead' => $lead, 'messages' => $messages]);
    }

    public function send(): void
    {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $this->json(['error' => 'Invalid request'], 400);
            return;
        }
        $lead_id = (int)($_POST['lead_id'] ?? 0);
        $message = trim($_POST['message'] ?? '');
        $this->sendMessage($lead_id, $message);
    }

    public function poll($lead_id): void
    {
        Auth::requireLogin();
        $messageModel = new Message();
        $messages = $messageModel->all(['lead_id' => (int)$lead_id], 'created_at ASC');
        $this->json(['messages' => $messages]);
    }

    public function webhook(): void
    {
        header('Content-Type: application/json');
        $payload = json_decode(file_get_contents('php://input'), true);
        if (!$payload || !isset($payload['lead_id'], $payload['message'])) {
            $this->json(['error' => 'Invalid payload'], 400);
            return;
        }
        $lead_id = (int)$payload['lead_id'];
        $incoming = trim((string)$payload['message']);
        $messageModel = new Message();
        // Save incoming
        $messageModel->create([
            'lead_id' => $lead_id,
            'sender' => 'lead',
            'message' => $incoming,
        ]);
        // Simple rules
        $lower = mb_strtolower($incoming, 'UTF-8');
        $reply = null;
        if (str_contains($lower, 'oi')) {
            $reply = 'Olá! Como posso ajudar?';
        } elseif (str_contains($lower, 'preço')) {
            $reply = 'Você pode informar o produto ou serviço desejado?';
        }
        if ($reply) {
            $messageModel->create([
                'lead_id' => $lead_id,
                'sender' => 'system',
                'message' => $reply,
            ]);
        }
        $this->json(['status' => 'ok', 'reply' => $reply]);
    }

    // Optional API-like action to send a message via URL params or POST, also used by AJAX
    public function sendMessage($lead_id = null, $message = null): void
    {
        Auth::requireLogin();
        if ($lead_id === null) {
            $lead_id = (int)($_POST['lead_id'] ?? $_GET['lead_id'] ?? 0);
        } else {
            $lead_id = (int)$lead_id;
        }
        if ($message === null) {
            $message = trim((string)($_POST['message'] ?? $_GET['message'] ?? ''));
        } else {
            $message = trim((string)$message);
        }
        if ($lead_id <= 0 || $message === '') {
            $this->json(['error' => 'Missing fields'], 422);
            return;
        }
        $messageModel = new Message();
        $messageModel->create([
            'lead_id' => $lead_id,
            'sender' => 'user',
            'message' => $message,
        ]);
        $this->json(['status' => 'ok']);
    }
}
