<?php
class Cart {
    public function __construct() {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Thêm sản phẩm vào giỏ
    public function add($product, $quantity = 1) {
        $id = $product['id_sp'];
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$id] = [
                'id_sp' => $product['id_sp'],
                'Name' => $product['Name'],
                'Price' => $product['Price'],
                'Sale' => $product['Sale'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }
    }

    // Cập nhật số lượng
    public function update($id, $quantity) {
        if (isset($_SESSION['cart'][$id])) {
            if ($quantity > 0) {
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
    }

    // Xóa toàn bộ giỏ
    public function clear() {
        $_SESSION['cart'] = [];
    }

    // Lấy danh sách sản phẩm trong giỏ
    public function getCart() {
        return $_SESSION['cart'];
    }

    // Tính tổng tiền
    public function getTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $price = $item['Price'];
            if ($item['Sale'] > 0) {
                $price = $price * (1 - $item['Sale'] / 100);
            }
            $total += $price * $item['quantity'];
        }
        return $total;
    }

    // Đếm tổng số lượng sản phẩm
    public function getCount() {
        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }
} 