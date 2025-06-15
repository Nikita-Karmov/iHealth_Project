<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cart;

class CartController extends Controller {

    public function add() {
        $productId = $_POST['product_id'] ?? null;

        if (!$productId) {
            http_response_code(400);
            echo "Missing product ID";
            return;
        }

        $quantity = 1;

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            \App\Models\Cart::addToCart($userId, (int)$productId, $quantity);
        } else {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] += $quantity;
            } else {
                $_SESSION['cart'][$productId] = $quantity;
            }
        }

        header('Location: /cart');
        exit;
    }

    public function showCart() {
        $sort = $_GET['sort'] ?? null;

        if (isset($_SESSION['user_id'])) {
            // Авторизованный — читаем из БД
            $items = \App\Models\Cart::getUserCartItems($_SESSION['user_id'], $sort);
        } else {
            // Гость — читаем из $_SESSION['cart']
            $items = [];

            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $productId => $qty) {
                    $product = \App\Models\Product::getById($productId);
                    if ($product) {
                        $product['quantity'] = $qty;
                        $items[] = $product;
                    }
                }

                if ($sort === 'asc') {
                    usort($items, fn($a, $b) => $a['price'] <=> $b['price']);
                } elseif ($sort === 'desc') {
                    usort($items, fn($a, $b) => $b['price'] <=> $a['price']);
                }
            }
        }

        $this->view('cart', ['items' => $items, 'sort' => $sort]);
    }

    public function remove() {
        $productId = $_POST['product_id'] ?? null;

        if ($productId) {
            if (isset($_SESSION['user_id'])) {
                Cart::removeFromCart($_SESSION['user_id'], (int)$productId);
            } else {
                unset($_SESSION['cart'][$productId]);
            }
        }

        header('Location: /cart');
        exit;
    }

    public function updateQuantity() {
        $productId = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? null;

        if ($productId && is_numeric($quantity)) {
            if (isset($_SESSION['user_id'])) {
                Cart::updateQuantity($_SESSION['user_id'], (int)$productId, (int)$quantity);
            } else {
                if ((int)$quantity > 0) {
                    $_SESSION['cart'][$productId] = (int)$quantity;
                } else {
                    unset($_SESSION['cart'][$productId]);
                }
            }
        }

        header('Location: /cart');
        exit;
    }

    public function clear() {
        if (isset($_SESSION['user_id'])) {
            Cart::clearCart($_SESSION['user_id']);
        } else {
            unset($_SESSION['cart']);
        }

        header('Location: /cart');
        exit;
    }
}
