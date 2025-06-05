<?php
namespace App\Models;

use Database;
use PDO;

class User {
    public static function create($email, $password, $name) {
        $db = Database::getConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (email, password_hash, full_name, created_at, updated_at)
                              VALUES (?, ?, ?, NOW(), NOW()) RETURNING user_id");
        $stmt->execute([$email, $hash, $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['user_id'];
    }

    public static function exists($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }

    public static function findByEmail($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function verifyCredentials($email, $password) {
        $user = self::findByEmail($email);
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user['user_id'];
        }
        return false;
    }

    public static function getById($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
