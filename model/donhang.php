<?php
class DonHang {
    private $db;

    public function __construct() {
        $this->db = new database();
    }

    // Tạo đơn hàng mới
    public function createOrder($userId, $total, $shippingInfo) {
        try {
            $this->db->beginTransaction();
            
            // Tạo đơn hàng
            $sql = "INSERT INTO donhang (id_user, tongdh, ngaydat, trangthai, 
                    ten_nguoi_nhan, sdt_nguoi_nhan, dia_chi_giao, ghi_chu, phuong_thuc_thanh_toan) 
                    VALUES (?, ?, NOW(), 'Chờ xác nhận', ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $userId,
                $total,
                $shippingInfo['ten_nguoi_nhan'],
                $shippingInfo['sdt_nguoi_nhan'],
                $shippingInfo['dia_chi_giao'],
                $shippingInfo['ghi_chu'] ?? '',
                $shippingInfo['phuong_thuc_thanh_toan']
            ]);
            
            $orderId = $this->db->lastInsertId();
            
            // Thêm chi tiết đơn hàng và cập nhật tồn kho
            $cart = new Cart();
            $cartItems = $cart->getCart();
            
            foreach ($cartItems as $item) {
                // Kiểm tra số lượng tồn kho
                $sql = "SELECT Mount FROM sanpham WHERE id_sp = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$item['id_sp']]);
                $currentStock = $stmt->fetchColumn();
                
                if ($currentStock < $item['quantity']) {
                    throw new Exception("Sản phẩm " . $item['Name'] . " chỉ còn " . $currentStock . " sản phẩm trong kho");
                }
                
                $price = $item['Price'];
                if ($item['Sale'] > 0) {
                    $price = $price * (1 - $item['Sale'] / 100);
                }
                $subtotal = $price * $item['quantity'];
                
                // Thêm chi tiết đơn hàng
                $sql = "INSERT INTO dh_chitiet (id_sp, id_dh, soluong, tong_dh, gia_ban) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $item['id_sp'],
                    $orderId,
                    $item['quantity'],
                    $subtotal,
                    $price
                ]);
                
                // Cập nhật số lượng tồn kho
                $newStock = $currentStock - $item['quantity'];
                $sql = "UPDATE sanpham SET Mount = ? WHERE id_sp = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$newStock, $item['id_sp']]);
                
                // Nếu hết hàng (Mount = 0), cập nhật trạng thái sản phẩm
                if ($newStock == 0) {
                    $sql = "UPDATE sanpham SET Mount = 0 WHERE id_sp = ?";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([$item['id_sp']]);
                }
            }
            
            // Xóa giỏ hàng
            $cart->clear();
            
            $this->db->commit();
            return $orderId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    // Cập nhật trạng thái đơn hàng và xử lý tồn kho
    public function updateOrderStatus($orderId, $status, $userId = null, $ghiChu = '') {
        try {
            $this->db->beginTransaction();
            
            // Lấy trạng thái hiện tại
            $sql = "SELECT trangthai FROM donhang WHERE id_dh = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId]);
            $currentStatus = $stmt->fetchColumn();
            
            // Cập nhật trạng thái mới
            $sql = "UPDATE donhang SET trangthai = ? WHERE id_dh = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$status, $orderId]);
            
            if ($result) {
                // Lưu lịch sử thay đổi trạng thái
                $sql = "INSERT INTO lich_su_trang_thai (id_dh, trang_thai_cu, trang_thai_moi, ghi_chu, nguoi_cap_nhat) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$orderId, $currentStatus, $status, $ghiChu, $userId]);
                
                // Xử lý tồn kho khi hủy đơn hàng
                if ($status == 'Đã hủy' && $currentStatus != 'Đã hủy') {
                    $this->restoreInventory($orderId);
                }
                
                // Xử lý khi xác nhận đơn hàng
                if ($status == 'Đã xác nhận' && $currentStatus == 'Chờ xác nhận') {
                    $this->confirmOrderInventory($orderId);
                }
            }
            
            $this->db->commit();
            return $result;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    // Khôi phục tồn kho khi hủy đơn hàng
    private function restoreInventory($orderId) {
        $sql = "SELECT dct.id_sp, dct.soluong, sp.Name 
                FROM dh_chitiet dct 
                JOIN sanpham sp ON dct.id_sp = sp.id_sp 
                WHERE dct.id_dh = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($items as $item) {
            $sql = "UPDATE sanpham SET Mount = Mount + ? WHERE id_sp = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$item['soluong'], $item['id_sp']]);
        }
    }

    // Xác nhận tồn kho khi đơn hàng được xác nhận
    private function confirmOrderInventory($orderId) {
        // Có thể thêm logic xử lý khi đơn hàng được xác nhận
        // Ví dụ: gửi email thông báo, cập nhật trạng thái sản phẩm, etc.
    }

    // Kiểm tra tồn kho trước khi đặt hàng
    public function checkInventory($cartItems) {
        $errors = [];
        
        foreach ($cartItems as $item) {
            $sql = "SELECT Mount, Name FROM sanpham WHERE id_sp = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$item['id_sp']]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$product) {
                $errors[] = "Sản phẩm không tồn tại";
                continue;
            }
            
            if ($product['Mount'] < $item['quantity']) {
                $errors[] = "Sản phẩm " . $product['Name'] . " chỉ còn " . $product['Mount'] . " sản phẩm trong kho";
            }
        }
        
        return $errors;
    }

    // Lấy danh sách sản phẩm hết hàng
    public function getOutOfStockProducts() {
        $sql = "SELECT id_sp, Name, image, Price, Mount 
                FROM sanpham 
                WHERE Mount = 0 
                ORDER BY Name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách sản phẩm sắp hết hàng (dưới 5 sản phẩm)
    public function getLowStockProducts($threshold = 5) {
        $sql = "SELECT id_sp, Name, image, Price, Mount 
                FROM sanpham 
                WHERE Mount > 0 AND Mount <= ? 
                ORDER BY Mount ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$threshold]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật số lượng tồn kho
    public function updateProductStock($productId, $newQuantity) {
        $sql = "UPDATE sanpham SET Mount = ? WHERE id_sp = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$newQuantity, $productId]);
    }

    // Lấy danh sách đơn hàng của user
    public function getUserOrders($userId) {
        $sql = "SELECT dh.*, 
                       COUNT(dct.id_chitiet) as so_san_pham,
                       GROUP_CONCAT(sp.Name SEPARATOR ', ') as danh_sach_sp
                FROM donhang dh
                LEFT JOIN dh_chitiet dct ON dh.id_dh = dct.id_dh
                LEFT JOIN sanpham sp ON dct.id_sp = sp.id_sp
                WHERE dh.id_user = ?
                GROUP BY dh.id_dh
                ORDER BY dh.ngaydat DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết đơn hàng
    public function getOrderDetails($orderId) {
        // Thông tin đơn hàng
        $sql = "SELECT dh.*, tk.fullname, tk.email, tk.phone
                FROM donhang dh
                JOIN taikhoan tk ON dh.id_user = tk.id_user
                WHERE dh.id_dh = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            return null;
        }
        
        // Chi tiết sản phẩm
        $sql = "SELECT dct.*, sp.Name, sp.image, sp.Price as gia_goc
                FROM dh_chitiet dct
                JOIN sanpham sp ON dct.id_sp = sp.id_sp
                WHERE dct.id_dh = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $order;
    }

    // Lấy tất cả đơn hàng (cho admin)
    public function getAllOrders() {
        $sql = "SELECT dh.*, tk.fullname, tk.email, tk.phone,
                       COUNT(dct.id_chitiet) as so_san_pham
                FROM donhang dh
                JOIN taikhoan tk ON dh.id_user = tk.id_user
                LEFT JOIN dh_chitiet dct ON dh.id_dh = dct.id_dh
                GROUP BY dh.id_dh
                ORDER BY dh.ngaydat DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thống kê đơn hàng
    public function getOrderStats() {
        $sql = "SELECT 
                    COUNT(*) as tong_don_hang,
                    COUNT(CASE WHEN trangthai = 'Chờ xác nhận' THEN 1 END) as cho_xac_nhan,
                    COUNT(CASE WHEN trangthai = 'Đã xác nhận' THEN 1 END) as da_xac_nhan,
                    COUNT(CASE WHEN trangthai = 'Đang giao' THEN 1 END) as dang_giao,
                    COUNT(CASE WHEN trangthai = 'Đã giao' THEN 1 END) as da_giao,
                    COUNT(CASE WHEN trangthai = 'Đã hủy' THEN 1 END) as da_huy,
                    SUM(tongdh) as tong_doanh_thu
                FROM donhang 
                WHERE trangthai != 'Đã hủy'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy đơn hàng theo trạng thái
    public function getOrdersByStatus($status) {
        $sql = "SELECT dh.*, tk.fullname, tk.email, tk.phone,
                       COUNT(dct.id_chitiet) as so_san_pham
                FROM donhang dh
                JOIN taikhoan tk ON dh.id_user = tk.id_user
                LEFT JOIN dh_chitiet dct ON dh.id_dh = dct.id_dh
                WHERE dh.trangthai = ?
                GROUP BY dh.id_dh
                ORDER BY dh.ngaydat DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy lịch sử trạng thái đơn hàng
    public function getOrderStatusHistory($orderId) {
        $sql = "SELECT lst.*, tk.fullname as nguoi_cap_nhat_ten
                FROM lich_su_trang_thai lst
                LEFT JOIN taikhoan tk ON lst.nguoi_cap_nhat = tk.id_user
                WHERE lst.id_dh = ?
                ORDER BY lst.ngay_cap_nhat DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm đơn hàng
    public function searchOrders($keyword, $status = '', $dateFrom = '', $dateTo = '') {
        $sql = "SELECT dh.*, tk.fullname, tk.email, tk.phone,
                       COUNT(dct.id_chitiet) as so_san_pham
                FROM donhang dh
                JOIN taikhoan tk ON dh.id_user = tk.id_user
                LEFT JOIN dh_chitiet dct ON dh.id_dh = dct.id_dh
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($keyword)) {
            $sql .= " AND (dh.id_dh LIKE ? OR tk.fullname LIKE ? OR tk.phone LIKE ? OR dh.ten_nguoi_nhan LIKE ?)";
            $keyword = "%$keyword%";
            $params = array_merge($params, [$keyword, $keyword, $keyword, $keyword]);
        }
        
        if (!empty($status)) {
            $sql .= " AND dh.trangthai = ?";
            $params[] = $status;
        }
        
        if (!empty($dateFrom)) {
            $sql .= " AND DATE(dh.ngaydat) >= ?";
            $params[] = $dateFrom;
        }
        
        if (!empty($dateTo)) {
            $sql .= " AND DATE(dh.ngaydat) <= ?";
            $params[] = $dateTo;
        }
        
        $sql .= " GROUP BY dh.id_dh ORDER BY dh.ngaydat DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thống kê doanh thu theo tháng
    public function getMonthlyRevenue($year = null) {
        if (!$year) $year = date('Y');
        
        $sql = "SELECT 
                    MONTH(ngaydat) as thang,
                    COUNT(*) as so_don_hang,
                    SUM(tongdh) as doanh_thu
                FROM donhang 
                WHERE YEAR(ngaydat) = ? AND trangthai != 'Đã hủy'
                GROUP BY MONTH(ngaydat)
                ORDER BY thang";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy top sản phẩm bán chạy
    public function getTopSellingProducts($limit = 10) {
        $sql = "SELECT 
                    sp.id_sp,
                    sp.Name,
                    sp.image,
                    SUM(dct.soluong) as tong_ban,
                    SUM(dct.tong_dh) as doanh_thu
                FROM dh_chitiet dct
                JOIN sanpham sp ON dct.id_sp = sp.id_sp
                JOIN donhang dh ON dct.id_dh = dh.id_dh
                WHERE dh.trangthai != 'Đã hủy'
                GROUP BY sp.id_sp
                ORDER BY tong_ban DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Xóa đơn hàng (chỉ admin)
    public function deleteOrder($orderId) {
        try {
            $this->db->beginTransaction();
            
            // Khôi phục tồn kho trước khi xóa
            $this->restoreInventory($orderId);
            
            // Xóa chi tiết đơn hàng trước
            $sql = "DELETE FROM dh_chitiet WHERE id_dh = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId]);
            
            // Xóa lịch sử trạng thái
            $sql = "DELETE FROM lich_su_trang_thai WHERE id_dh = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId]);
            
            // Xóa đơn hàng
            $sql = "DELETE FROM donhang WHERE id_dh = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$orderId]);
            
            $this->db->commit();
            return $result;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
?> 