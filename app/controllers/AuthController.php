<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\User;
use App\Core\Database;

class AuthController extends Controller
{
    public function __construct(private Database $db)
    {
    }

    public function showLogin(): void
    {
        $this->view('auth/login');
    }

    public function login(): void
    {
        if (!verify_csrf($_POST['csrf'] ?? '')) {
            http_response_code(422);
            echo 'Invalid CSRF token';
            return;
        }

        $userModel = new User($this->db);
        if (Auth::attempt($_POST['email'], $_POST['password'], $userModel)) {
            header('Location: /dashboard');
            return;
        }

        $this->view('auth/login', ['error' => 'Invalid credentials']);
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: /login');
    }
}
