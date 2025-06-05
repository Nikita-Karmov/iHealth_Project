<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller {

    public function showForm() {
        $this->view('auth');
    }

    public function register() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $name = $_POST['full_name'] ?? '';

        if (empty($email) || empty($password) || empty($name)) {
            return $this->view('auth', ['error' => 'Все поля обязательны']);
        }

        try {
            if (User::exists($email)) {
                return $this->view('auth', ['error' => 'Пользователь уже существует']);
            }

            $userId = User::create($email, $password, $name);
            $_SESSION['user_id'] = $userId;
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            return $this->view('auth', ['error' => 'Ошибка регистрации']);
        }
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            return $this->view('auth', ['error' => 'Введите email и пароль']);
        }

        try {
            $userId = User::verifyCredentials($email, $password);
            if (!$userId) {
                return $this->view('auth', ['error' => 'Неверные данные']);
            }

            $_SESSION['user_id'] = $userId;
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            return $this->view('auth', ['error' => 'Ошибка авторизации']);
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }

    public function account() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    $user = \App\Models\User::getById($_SESSION['user_id']);
    $this->view('account', ['user' => $user]);
}

}
