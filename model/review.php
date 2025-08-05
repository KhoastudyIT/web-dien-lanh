<?php
require_once 'database.php';

class Review {
    private $db;
    
    public function __construct() {
        $this->db = new database();
    }
    
    // Thêm đánh giá mới
    public function addReview($userId, $productId, $rating, $comment) {
        try {
            $conn = $this->db->connection_database();
            
            // Kiểm tra xem user đã đánh giá sản phẩm này chưa
            $stmt = $conn->prepare("SELECT id FROM danh_gia WHERE id_user = ? AND id_sp = ?");
            $stmt->execute([$userId, $productId]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Bạn đã đánh giá sản phẩm này rồi'];
            }
            
            // Thêm đánh giá mới
            $stmt = $conn->prepare("INSERT INTO danh_gia (id_user, id_sp, rating, comment, ngay_danh_gia) VALUES (?, ?, ?, ?, NOW())");
            $result = $stmt->execute([$userId, $productId, $rating, $comment]);
            
            if ($result) {
                // Cập nhật rating trung bình của sản phẩm
                $this->updateProductAverageRating($productId);
                return ['success' => true, 'message' => 'Đánh giá thành công'];
            } else {
                return ['success' => false, 'message' => 'Đánh giá thất bại'];
            }
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()];
        }
    }
    
    // Cập nhật đánh giá
    public function updateReview($reviewId, $rating, $comment) {
        try {
            $conn = $this->db->connection_database();
            
            $stmt = $conn->prepare("UPDATE danh_gia SET rating = ?, comment = ?, ngay_cap_nhat = NOW() WHERE id = ?");
            $result = $stmt->execute([$rating, $comment, $reviewId]);
            
            if ($result) {
                // Lấy product_id để cập nhật rating trung bình
                $stmt = $conn->prepare("SELECT id_sp FROM danh_gia WHERE id = ?");
                $stmt->execute([$reviewId]);
                $productId = $stmt->fetchColumn();
                
                if ($productId) {
                    $this->updateProductAverageRating($productId);
                }
                
                return ['success' => true, 'message' => 'Cập nhật đánh giá thành công'];
            } else {
                return ['success' => false, 'message' => 'Cập nhật đánh giá thất bại'];
            }
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()];
        }
    }
    
    // Xóa đánh giá
    public function deleteReview($reviewId) {
        try {
            $conn = $this->db->connection_database();
            
            // Lấy product_id trước khi xóa
            $stmt = $conn->prepare("SELECT id_sp FROM danh_gia WHERE id = ?");
            $stmt->execute([$reviewId]);
            $productId = $stmt->fetchColumn();
            
            $stmt = $conn->prepare("DELETE FROM danh_gia WHERE id = ?");
            $result = $stmt->execute([$reviewId]);
            
            if ($result && $productId) {
                $this->updateProductAverageRating($productId);
                return ['success' => true, 'message' => 'Xóa đánh giá thành công'];
            } else {
                return ['success' => false, 'message' => 'Xóa đánh giá thất bại'];
            }
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()];
        }
    }
    
    // Lấy đánh giá theo sản phẩm
    public function getProductReviews($productId, $limit = 10, $offset = 0) {
        try {
            $conn = $this->db->connection_database();
            
            $sql = "SELECT dg.*, tk.fullname, tk.username 
                    FROM danh_gia dg 
                    JOIN taikhoan tk ON dg.id_user = tk.id_user 
                    WHERE dg.id_sp = ? 
                    ORDER BY dg.ngay_danh_gia DESC 
                    LIMIT ? OFFSET ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([$productId, $limit, $offset]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }
    
    // Lấy đánh giá của user cho sản phẩm
    public function getUserReview($userId, $productId) {
        try {
            $conn = $this->db->connection_database();
            
            $stmt = $conn->prepare("SELECT * FROM danh_gia WHERE id_user = ? AND id_sp = ?");
            $stmt->execute([$userId, $productId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return null;
        }
    }
    
    // Lấy thống kê đánh giá của sản phẩm
    public function getProductReviewStats($productId) {
        try {
            $conn = $this->db->connection_database();
            
            $sql = "SELECT 
                        COUNT(*) as total_reviews,
                        AVG(rating) as average_rating,
                        COUNT(CASE WHEN rating = 5 THEN 1 END) as five_star,
                        COUNT(CASE WHEN rating = 4 THEN 1 END) as four_star,
                        COUNT(CASE WHEN rating = 3 THEN 1 END) as three_star,
                        COUNT(CASE WHEN rating = 2 THEN 1 END) as two_star,
                        COUNT(CASE WHEN rating = 1 THEN 1 END) as one_star
                    FROM danh_gia 
                    WHERE id_sp = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([$productId]);
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Tính phần trăm cho mỗi sao
            if ($stats['total_reviews'] > 0) {
                $stats['five_star_percent'] = round(($stats['five_star'] / $stats['total_reviews']) * 100);
                $stats['four_star_percent'] = round(($stats['four_star'] / $stats['total_reviews']) * 100);
                $stats['three_star_percent'] = round(($stats['three_star'] / $stats['total_reviews']) * 100);
                $stats['two_star_percent'] = round(($stats['two_star'] / $stats['total_reviews']) * 100);
                $stats['one_star_percent'] = round(($stats['one_star'] / $stats['total_reviews']) * 100);
            } else {
                $stats['five_star_percent'] = 0;
                $stats['four_star_percent'] = 0;
                $stats['three_star_percent'] = 0;
                $stats['two_star_percent'] = 0;
                $stats['one_star_percent'] = 0;
            }
            
            return $stats;
            
        } catch (PDOException $e) {
            return null;
        }
    }
    
    // Cập nhật rating trung bình của sản phẩm
    private function updateProductAverageRating($productId) {
        try {
            $conn = $this->db->connection_database();
            
            $sql = "UPDATE sanpham SET rating_trung_binh = (
                        SELECT AVG(rating) FROM danh_gia WHERE id_sp = ?
                    ) WHERE id_sp = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([$productId, $productId]);
            
        } catch (PDOException $e) {
            // Log error if needed
        }
    }
    
    // Lấy tất cả đánh giá (cho admin)
    public function getAllReviews($limit = 20, $offset = 0) {
        try {
            $conn = $this->db->connection_database();
            
            $sql = "SELECT dg.*, tk.fullname, tk.username, sp.Name as product_name 
                    FROM danh_gia dg 
                    JOIN taikhoan tk ON dg.id_user = tk.id_user 
                    JOIN sanpham sp ON dg.id_sp = sp.id_sp 
                    ORDER BY dg.ngay_danh_gia DESC 
                    LIMIT ? OFFSET ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([$limit, $offset]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }
    
    // Tìm kiếm đánh giá
    public function searchReviews($keyword, $rating = '', $limit = 20, $offset = 0) {
        try {
            $conn = $this->db->connection_database();
            
            $sql = "SELECT dg.*, tk.fullname, tk.username, sp.Name as product_name 
                    FROM danh_gia dg 
                    JOIN taikhoan tk ON dg.id_user = tk.id_user 
                    JOIN sanpham sp ON dg.id_sp = sp.id_sp 
                    WHERE 1=1";
            
            $params = [];
            
            if (!empty($keyword)) {
                $sql .= " AND (tk.fullname LIKE ? OR tk.username LIKE ? OR sp.Name LIKE ? OR dg.comment LIKE ?)";
                $keyword = "%$keyword%";
                $params = array_merge($params, [$keyword, $keyword, $keyword, $keyword]);
            }
            
            if (!empty($rating)) {
                $sql .= " AND dg.rating = ?";
                $params[] = $rating;
            }
            
            $sql .= " ORDER BY dg.ngay_danh_gia DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }
}
?> 