<?php
namespace App\controllers;

use App\core\Auth;
use App\core\Controller;
use App\models\User;

class AuthController extends Controller
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
                $this->redirect('auth/login');
            }
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $userModel = new User();
            $user = $userModel->findByEmail($email);
            if ($user && password_verify($password, $user['password_hash'])) {
                Auth::login($user);
                $this->redirect('dashboard/index');
                return;
            }
            Auth::flash('error', 'Credenciais inválidas.');
        }
        $this->view('auth/login', []);
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
                $this->redirect('auth/register');
            }
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $company_id = (int)($_POST['company_id'] ?? 1);
            if ($name && $email && $password) {
                $userModel = new User();
                if ($userModel->findByEmail($email)) {
                    Auth::flash('error', 'E-mail já cadastrado.');
                } else {
                    $id = $userModel->create([
                        'company_id' => $company_id,
                        'name' => $name,
                        'email' => $email,
                        'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                        'role' => 'user'
                    ]);
                    Auth::flash('success', 'Registro criado. Faça login.');
                    $this->redirect('auth/login');
                    return;
                }
            } else {
                Auth::flash('error', 'Preencha os campos obrigatórios.');
            }
        }
        $this->view('auth/register', []);
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('auth/login');
    }
}
