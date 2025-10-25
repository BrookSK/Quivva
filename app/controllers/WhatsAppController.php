<?php
namespace App\controllers;

use App\core\Auth;
use App\core\Controller;

class WhatsAppController extends Controller
{
    public function connect(): void
    {
        Auth::requireLogin();
        // Generate or reuse a session token to simulate QR content
        if (empty($_SESSION['wa_login_token'])) {
            $_SESSION['wa_login_token'] = bin2hex(random_bytes(8));
        }
        $token = $_SESSION['wa_login_token'];
        $qrData = 'whatsapp:login:' . $token;
        $this->view('whatsapp/connect', [
            'qr_data' => $qrData,
            'connected' => !empty($_SESSION['wa_connected'])
        ]);
    }

    public function status(): void
    {
        Auth::requireLogin();
        $this->json(['connected' => !empty($_SESSION['wa_connected'])]);
    }

    public function simulate(): void
    {
        Auth::requireLogin();
        // Simulate device scan
        $_SESSION['wa_connected'] = true;
        $this->json(['status' => 'ok', 'connected' => true]);
    }

    public function disconnect(): void
    {
        Auth::requireLogin();
        unset($_SESSION['wa_connected']);
        $this->redirect('whatsapp/connect');
    }
}
