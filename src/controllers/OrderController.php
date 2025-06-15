<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cart;
use Database;
use PDO;

class OrderController extends Controller {

    public function showForm() {
        $this->view('ordering');
    }
    
    public function placeOrder() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }


        $userId = $_SESSION['user_id'];
        $items = Cart::getUserCartItems($userId);

        if (empty($items)) {
            $this->view('cart', ['error' => 'Корзина пуста']);
            return;
        }

        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $db = Database::getConnection();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, 'создан', NOW()) RETURNING order_id");
            $stmt->execute([$userId, $total]);
            $orderId = $stmt->fetchColumn();

            $stmtItem = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
            foreach ($items as $item) {
                $stmtItem->execute([
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                ]);
            }

            Cart::clearCart($userId);

            $db->commit();
            header('Location: /account');
        } catch (\Exception $e) {
            $db->rollBack();
            $this->view('cart', ['error' => 'Не удалось оформить заказ']);
        }
    }
}
