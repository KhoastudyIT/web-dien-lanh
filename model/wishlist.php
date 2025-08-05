<?php
require_once 'database.php';

class Wishlist {
    private $db;
    
    public function __construct() {
        $this->db = new database();
    }
    
    // Thêm sản phẩm vào wishlist
    public function addToWishlist($userId, $productId) {
        try {
            $conn = $this->db->connection_database();
            
            // Kiểm tra xem sản phẩm đã có trong wishlist chưa
            $stmt = $conn->prepare("SELECT id FROM wishlist WHERE id_user = ? AND id_sp = ?");
            $stmt->execute([$userId, $productId]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Sản phẩm đã có trong danh sách yêu thích'];
            }
            
            // Thêm vào wishlist
            $stmt = $conn->prepare("INSERT INTO wishlist (id_user, id_sp, ngay_them) VALUES (?, ?, NOW())");
            $result = $stmt->execute([$userId, $productId]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Đã thêm vào danh sách yêu thích'];
            } else {
                return ['success' => false, 'message' => 'Thêm vào wishlist thất bại'];
            }
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()];
        }
    }
    
    // Xóa sản phẩm khỏi wishlist
    public function removeFromWishlist($userId, $productId) {
        try {
            $conn = $this->db->connection_database();
            
            $stmt = $conn->prepare("DELETE FROM wishlist WHERE id_user = ? AND id_sp = ?");
            $result = $stmt->execute([$userId, $productId]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Đã xóa khỏi danh sách yêu thích'];
            } else {
                return ['success' => false, 'message' => 'Xóa khỏi wishlist thất bại'];
            }
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()];
        }
    }
    
    // Kiểm tra sản phẩm có trong wishlist không
    public function isInWishlist($userId, $productId) {
        try {
            $conn = $this->db->connection_database();
            
            $stmt = $conn->prepare("SELECT id FROM wishlist WHERE id_user = ? AND id_sp = ?");
            $stmt->execute([$userId, $productId]);
            
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Lấy danh sách wishlist của user
    public function getUserWishlist($userId, $limit = 20, $offset = 0) {
        try {
            $conn = $this->db->connection_database();
            
            $sql = "SELECT w.*, sp.*, dm.name as ten_danhmuc, h.ten_hang, h.logo_hang 
                    FROM wishlist w 
                    JOIN sanpham sp ON w.id_sp = sp.id_sp 
                    LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                    LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                    WHERE w.id_user = ? 
                    ORDER BY w.ngay_them DESC 
                    LIMIT ? OFFSET ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId, $limit, $offset]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }
    
    // Đếm số sản phẩm trong wishlist
    public function getWishlistCount($userId) {
        try {
            $conn = $this->db->connection_database();
            
            $stmt = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE id_user = ?");
            $stmt->execute([$userId]);
            return $stmt->fetchColumn();
            
        } catch (PDOException $e) {
            return 0;
        }
    }
    
    // Xóa toàn bộ wishlist của user
    public function clearWishlist($userId) {
        try {
            $conn = $this->db->connection_database();
            
            $stmt = $conn->prepare("DELETE FROM wishlist WHERE id_user = ?");
            $result = $stmt->execute([$userId]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Đã xóa toàn bộ danh sách yêu thích'];
            } else {
                return ['success' => false, 'message' => 'Xóa wishlist thất bại'];
            }
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()];
        }
    }
    
    // Lấy sản phẩm yêu thích nhiều nhất
    public function getMostWishedProducts($limit = 10) {
        try {
            $conn = $this->db->connection_database();
            
            $sql = "SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang, h.logo_hang, 
                           COUNT(w.id) as wish_count
                    FROM wishlist w 
                    JOIN sanpham sp ON w.id_sp = sp.id_sp 
                    LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                    LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                    GROUP BY sp.id_sp 
                    ORDER BY wish_count DESC 
                    LIMIT ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }
    
    // Thêm sản phẩm từ wishlist vào giỏ hàng
    public function addWishlistToCart($userId, $productId) {
        try {
            $conn = $this->db->connection_database();
            
            // Kiểm tra sản phẩm có trong wishlist không
            if (!$this->isInWishlist($userId, $productId)) {
                return ['success' => false, 'message' => 'Sản phẩm không có trong danh sách yêu thích'];
            }
            
            // Lấy thông tin sản phẩm
            $stmt = $conn->prepare("SELECT * FROM sanpham WHERE id_sp = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$product) {
                return ['success' => false, 'message' => 'Sản phẩm không tồn tại'];
            }
            
            // Thêm vào giỏ hàng
            $cart = new Cart();
            $cart->add($product, 1);
            
            // Xóa khỏi wishlist
            $this->removeFromWishlist($userId, $productId);
            
            return ['success' => true, 'message' => 'Đã thêm vào giỏ hàng và xóa khỏi danh sách yêu thích'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()];
        }
    }
}
?> 