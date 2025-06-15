<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Order;

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
    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? null;

        if (empty($name) || empty($email)) {
            $user = \App\Models\User::getById($_SESSION['user_id']);
            return $this->view('account', [
                'user' => $user,
                'error' => 'Имя и email обязательны'
            ]);
        }

        try {
            \App\Models\User::update($_SESSION['user_id'], $email, $name, $password);
            header('Location: /account');
            exit;
        } catch (\Exception $e) {
            $user = \App\Models\User::getById($_SESSION['user_id']);
            return $this->view('account', [
                'user' => $user,
                'error' => 'Ошибка при обновлении профиля'
            ]);
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

        $userId = $_SESSION['user_id'];
        $user = \App\Models\User::getById($userId);
        $orders = \App\Models\Order::getUserOrdersWithItems($userId);

        $this->view('account', [
            'user' => $user,
            'orders' => $orders
        ]);
    }
}

