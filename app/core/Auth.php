<?php
namespace App\core;

class Auth
{
    public static function startSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function user(): ?array
    {
        self::startSession();
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function login(array $user): void
    {
        self::startSession();
        unset($user['password_hash']);
        $_SESSION['user'] = $user;
    }

    public static function logout(): void
    {
        self::startSession();
        $_SESSION = [];
        session_destroy();
    }

    public static function csrfToken(): string
    {
        self::startSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf(?string $token): bool
    {
        self::startSession();
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /auth/login');
            exit;
        }
    }

    public static function flash(string $key, ?string $message = null): ?string
    {
        self::startSession();
        if ($message !== null) {
            $_SESSION['flash'][$key] = $message;
            return null;
        }
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }
}
