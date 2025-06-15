<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cart;

class CartController extends Controller {

    public function add() {
        $productId = $_POST['product_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;

        if (!$productId || !is_numeric($productId)) {
            http_response_code(400);
            echo "Некорректный товар";
            return;
        }

        Cart::addToCart($userId, (int)$productId);
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/cart'));
        exit;
    }

    public function showCart() {
        $userId = $_SESSION['user_id'] ?? null;
        $sort = $_GET['sort'] ?? null;

        $items = Cart::getUserCartItems($userId, $sort);
        $this->view('cart', ['items' => $items, 'sort' => $sort]);
    }

    public function remove() {
        $productId = $_POST['product_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;

        if ($productId) {
            Cart::removeFromCart($userId, (int)$productId);
        }

        header('Location: /cart');
        exit;
    }

    public function updateQuantity() {
        $userId = $_SESSION['user_id'] ?? null;
        $productId = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? null;

        if ($productId && is_numeric($quantity)) {
            Cart::updateQuantity($userId, (int)$productId, (int)$quantity);
        }

        header('Location: /cart');
        exit;
    }

    public function clear() {
        $userId = $_SESSION['user_id'] ?? null;
        Cart::clearCart($userId);
        header('Location: /cart');
        exit;
    }
}
