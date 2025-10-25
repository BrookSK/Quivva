<?php
namespace App\controllers;

use App\core\Auth;
use App\core\Controller;
use App\models\Lead;
use App\models\Message;

class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $user = Auth::user();

        $leadModel = new Lead();
        $messageModel = new Message();

        $leads = $leadModel->all(['company_id' => $user['company_id']], 'created_at DESC');
        $today = date('Y-m-d');
        $messagesToday = array_filter($messageModel->all(), function($m) use ($today) {
            return str_starts_with($m['created_at'], $today);
        });

        $this->view('dashboard/index', [
            'leads_count' => count($leads),
            'messages_today' => count($messagesToday),
            'conversion_rate' => '—',
        ]);
    }
}
