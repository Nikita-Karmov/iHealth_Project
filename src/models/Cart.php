<?php
namespace App\Models;

use Database;
use PDO;

class Cart {
    public static function getOrCreateActiveCart($userId) {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT cart_id FROM carts WHERE user_id = ? AND is_active = true");
        $stmt->execute([$userId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) return $cart['cart_id'];

        $stmt = $db->prepare("INSERT INTO carts (user_id, is_active, created_at) VALUES (?, true, NOW()) RETURNING cart_id");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public static function addToCart($userId, $productId, $quantity = 1) {
        if ($userId) {
            // Авторизованный
            $cartId = self::getOrCreateActiveCart($userId);
            $db = Database::getConnection();

            $stmt = $db->prepare("SELECT cart_item_id FROM cart_items WHERE cart_id = ? AND product_id = ?");
            $stmt->execute([$cartId, $productId]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($item) {
                $stmt = $db->prepare("UPDATE cart_items SET quantity = quantity + ? WHERE cart_item_id = ?");
                $stmt->execute([$quantity, $item['cart_item_id']]);
            } else {
                $stmt = $db->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
                $stmt->execute([$cartId, $productId, $quantity]);
            }
        } else {
            // Гость
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] += $quantity;
            } else {
                $_SESSION['cart'][$productId] = $quantity;
            }
        }
    }

    public static function getUserCartItems($userId, $sort = null) {
        $db = Database::getConnection();

        if ($userId) {
            // Авторизованный
            $sql = "SELECT p.product_id, p.name, p.price, p.image_url, ci.quantity
                    FROM cart_items ci
                    JOIN products p ON ci.product_id = p.product_id
                    JOIN carts c ON ci.cart_id = c.cart_id
                    WHERE c.user_id = ? AND c.is_active = true";

            if ($sort === 'asc') {
                $sql .= " ORDER BY p.price ASC";
            } elseif ($sort === 'desc') {
                $sql .= " ORDER BY p.price DESC";
            }

            $stmt = $db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Гость
            if (empty($_SESSION['cart'])) return [];

            $productIds = array_keys($_SESSION['cart']);
            $placeholders = implode(',', array_fill(0, count($productIds), '?'));
            $stmt = $db->prepare("SELECT product_id, name, price, image_url FROM products WHERE product_id IN ($placeholders)");
            $stmt->execute($productIds);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($products as &$product) {
                $product['quantity'] = $_SESSION['cart'][$product['product_id']] ?? 1;
            }

            return $products;
        }
    }

    public static function updateQuantity($userId, $productId, $quantity) {
        if ($userId) {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT cart_id FROM carts WHERE user_id = ? AND is_active = TRUE");
            $stmt->execute([$userId]);
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cart) {
                if ($quantity > 0) {
                    $stmt = $db->prepare("UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND product_id = ?");
                    $stmt->execute([$quantity, $cart['cart_id'], $productId]);
                } else {
                    $stmt = $db->prepare("DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?");
                    $stmt->execute([$cart['cart_id'], $productId]);
                }
            }
        } else {
            if ($quantity > 0) {
                $_SESSION['cart'][$productId] = $quantity;
            } else {
                unset($_SESSION['cart'][$productId]);
            }
        }
    }

    public static function removeFromCart($userId, $productId) {
        if ($userId) {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT cart_id FROM carts WHERE user_id = ? AND is_active = TRUE");
            $stmt->execute([$userId]);
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cart) {
                $stmt = $db->prepare("DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?");
                $stmt->execute([$cart['cart_id'], $productId]);
            }
        } else {
            unset($_SESSION['cart'][$productId]);
        }
    }

    public static function clearCart($userId) {
        if ($userId) {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT cart_id FROM carts WHERE user_id = ? AND is_active = TRUE");
            $stmt->execute([$userId]);
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cart) {
                $stmt = $db->prepare("DELETE FROM cart_items WHERE cart_id = ?");
                $stmt->execute([$cart['cart_id']]);
            }
        } else {
            $_SESSION['cart'] = [];
        }
    }
}
