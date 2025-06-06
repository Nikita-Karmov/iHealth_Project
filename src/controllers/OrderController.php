<?php
namespace App\Controllers;

class OrderController {
    public function showForm() {
        require_once __DIR__ . '/../views/ordering.php';
    }
}
