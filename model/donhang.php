<?php
include_once __DIR__ . '/cart.php';
include_once __DIR__ . '/database.php';

class DonHang {
    private $db;

    public function __construct() {
        $this->db = new database();
    }

    // Tạo đơn hàng mới
    public function createOrder($userId, $total, $shippingInfo) {
        try {
            error_log("DonHang::createOrder - Starting order creation for user: $userId");
            error_log("DonHang::createOrder - Total: $total");
            error_log("DonHang::createOrder - Shipping info: " . print_r($shippingInfo, true));
            
            $this->db->beginTransaction();
            error_log("DonHang::createOrder - Transaction started");
            
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
            error_log("DonHang::createOrder - Order created with ID: $orderId");
            
            // Thêm chi tiết đơn hàng
            $cart = new Cart();
            $cartItems = $cart->getCart();
            
            foreach ($cartItems as $item) {
                $price = $item['Price'];
                if ($item['Sale'] > 0) {
                    $price = $price * (1 - $item['Sale'] / 100);
                }
                $subtotal = $price * $item['quantity'];
                
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
                
                // Cập nhật số lượng tồn kho và kiểm tra hết hàng
                $sql = "UPDATE sanpham SET Mount = Mount - ? WHERE id_sp = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$item['quantity'], $item['id_sp']]);
                
                // Kiểm tra nếu sản phẩm hết hàng (Mount <= 0) thì ẩn sản phẩm
                $sql = "UPDATE sanpham SET Mount = 0, Sale = 0 WHERE id_sp = ? AND Mount <= 0";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$item['id_sp']]);
            }
            
            // Xóa giỏ hàng
            $cart->clear();
            error_log("DonHang::createOrder - Cart cleared");
            
            $this->db->commit();
            error_log("DonHang::createOrder - Transaction committed successfully");
            return $orderId;
            
        } catch (Exception $e) {
            error_log("DonHang::createOrder - Error: " . $e->getMessage());
            $this->db->rollback();
            error_log("DonHang::createOrder - Transaction rolled back");
            throw $e;
        }
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

    // Cập nhật trạng thái đơn hàng
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
            }
            
            $this->db->commit();
            return $result;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    // Thống kê đơn hàng
    public function getOrderStats() {
        try {
            $sql = "SELECT 
                        COUNT(*) as tong_don_hang,
                        COUNT(CASE WHEN trangthai = 'Chờ xác nhận' THEN 1 END) as cho_xu_ly,
                        COUNT(CASE WHEN trangthai = 'Đã xác nhận' THEN 1 END) as da_xac_nhan,
                        COUNT(CASE WHEN trangthai = 'Đang giao' THEN 1 END) as dang_giao,
                        COUNT(CASE WHEN trangthai = 'Đã giao' THEN 1 END) as da_giao,
                        COUNT(CASE WHEN trangthai = 'Đã hủy' THEN 1 END) as da_huy,
                        SUM(CASE WHEN trangthai = 'Đã giao' THEN tongdh ELSE 0 END) as tong_doanh_thu,
                        SUM(CASE WHEN trangthai != 'Đã hủy' THEN tongdh ELSE 0 END) as tong_doanh_thu_tat_ca
                    FROM donhang";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // If no rows are returned (e.g., no orders in the database), $result might be false.
            // Ensure it's an array with default values for all expected keys.
            if ($result === false || $result === null) {
                return [
                    'tong_don_hang' => 0,
                    'cho_xu_ly' => 0,
                    'da_xac_nhan' => 0,
                    'dang_giao' => 0,
                    'da_giao' => 0,
                    'da_huy' => 0,
                    'tong_doanh_thu' => 0,
                    'tong_doanh_thu_tat_ca' => 0
                ];
            }
            
            // Ensure all expected keys exist with default values if they're null
            $defaultValues = [
                'tong_don_hang' => 0,
                'cho_xu_ly' => 0,
                'da_xac_nhan' => 0,
                'dang_giao' => 0,
                'da_giao' => 0,
                'da_huy' => 0,
                'tong_doanh_thu' => 0,
                'tong_doanh_thu_tat_ca' => 0
            ];
            
            return array_merge($defaultValues, $result);
        } catch (Exception $e) {
            error_log("Error in getOrderStats: " . $e->getMessage());
            // Return default values if there's an error
            return [
                'tong_don_hang' => 0,
                'cho_xu_ly' => 0,
                'da_xac_nhan' => 0,
                'dang_giao' => 0,
                'da_giao' => 0,
                'da_huy' => 0,
                'tong_doanh_thu' => 0,
                'tong_doanh_thu_tat_ca' => 0
            ];
        }
    }

    // Lấy tổng doanh thu thực tế (chỉ đơn hàng đã giao)
    public function getActualRevenue() {
        try {
            $sql = "SELECT SUM(tongdh) as tong_doanh_thu_thuc_te
                    FROM donhang 
                    WHERE trangthai = 'Đã giao'";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['tong_doanh_thu_thuc_te'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getActualRevenue: " . $e->getMessage());
            return 0;
        }
    }

    // Lấy tổng doanh thu tất cả đơn hàng (trừ đã hủy)
    public function getTotalRevenue() {
        try {
            $sql = "SELECT SUM(tongdh) as tong_doanh_thu_tat_ca
                    FROM donhang 
                    WHERE trangthai != 'Đã hủy'";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['tong_doanh_thu_tat_ca'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getTotalRevenue: " . $e->getMessage());
            return 0;
        }
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
        try {
            if (!$year) {
                $year = date('Y');
            }
            
            $sql = "SELECT 
                        MONTH(ngaydat) as month,
                        COUNT(*) as so_don_hang,
                        SUM(CASE WHEN trangthai = 'Đã giao' THEN tongdh ELSE 0 END) as total_revenue
                    FROM donhang 
                    WHERE YEAR(ngaydat) = ?
                    GROUP BY MONTH(ngaydat)
                    ORDER BY month";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$year]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getMonthlyRevenue: " . $e->getMessage());
            return [];
        }
    }

    // Lấy top sản phẩm bán chạy
    public function getTopSellingProducts($limit = 10) {
        try {
            $limit = (int)$limit; // Đảm bảo limit là integer
            
            $sql = "SELECT 
                        sp.id_sp,
                        sp.Name as product_name,
                        sp.image,
                        SUM(dct.soluong) as total_quantity,
                        SUM(CASE WHEN dh.trangthai = 'Đã giao' THEN dct.tong_dh ELSE 0 END) as total_revenue
                    FROM dh_chitiet dct
                    JOIN sanpham sp ON dct.id_sp = sp.id_sp
                    JOIN donhang dh ON dct.id_dh = dh.id_dh
                    WHERE dh.trangthai != 'Đã hủy'
                    GROUP BY sp.id_sp
                    ORDER BY total_quantity DESC
                    LIMIT $limit";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getTopSellingProducts: " . $e->getMessage());
            return [];
        }
    }

    // Lấy tổng số đơn hàng
    public function getTotalOrders() {
        $sql = "SELECT COUNT(*) as total FROM donhang";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // Lấy đơn hàng gần đây
    public function getRecentOrders($limit = 5) {
        try {
            $limit = (int)$limit; // Đảm bảo limit là integer
            $sql = "SELECT dh.id_dh, dh.ngaydat, dh.tongdh, dh.trangthai, dh.ten_nguoi_nhan
                        FROM donhang dh
                        ORDER BY dh.ngaydat DESC
                        LIMIT $limit";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getRecentOrders: " . $e->getMessage());
            return [];
        }
    }

    // Xóa đơn hàng (chỉ admin)
    public function deleteOrder($orderId) {
        try {
            $this->db->beginTransaction();
            
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

    // Kiểm tra và cập nhật trạng thái sản phẩm sau khi thanh toán
    public function updateProductStatusAfterOrder($orderId) {
        try {
            $this->db->beginTransaction();
            
            // Lấy danh sách sản phẩm trong đơn hàng
            $sql = "SELECT dct.id_sp, dct.soluong, sp.Mount, sp.Name
                    FROM dh_chitiet dct
                    JOIN sanpham sp ON dct.id_sp = sp.id_sp
                    WHERE dct.id_dh = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $updatedProducts = [];
            
            foreach ($products as $product) {
                $newStock = $product['Mount'] - $product['soluong'];
                
                if ($newStock <= 0) {
                    // Sản phẩm hết hàng - ẩn khỏi cửa hàng
                    $sql = "UPDATE sanpham SET Mount = 0, Sale = 0 WHERE id_sp = ?";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([$product['id_sp']]);
                    
                    $updatedProducts[] = [
                        'id_sp' => $product['id_sp'],
                        'name' => $product['Name'],
                        'status' => 'out_of_stock',
                        'message' => 'Sản phẩm đã hết hàng và được ẩn khỏi cửa hàng'
                    ];
                } else {
                    // Cập nhật số lượng tồn kho
                    $sql = "UPDATE sanpham SET Mount = ? WHERE id_sp = ?";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([$newStock, $product['id_sp']]);
                    
                    $updatedProducts[] = [
                        'id_sp' => $product['id_sp'],
                        'name' => $product['Name'],
                        'status' => 'updated',
                        'new_stock' => $newStock,
                        'message' => 'Số lượng tồn kho đã được cập nhật'
                    ];
                }
            }
            
            $this->db->commit();
            return $updatedProducts;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    // Lấy danh sách sản phẩm hết hàng
    public function getOutOfStockProducts() {
        $sql = "SELECT id_sp, Name, image, Price, Price_old, Mount, Sale, id_danhmuc, id_hang
                FROM sanpham 
                WHERE Mount <= 0
                ORDER BY Name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Khôi phục sản phẩm về cửa hàng (thêm lại hàng)
    public function restoreProductToStore($productId, $quantity) {
        try {
            $sql = "UPDATE sanpham SET Mount = ? WHERE id_sp = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$quantity, $productId]);
            
            return $result;
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Xóa vĩnh viễn sản phẩm khỏi cửa hàng
    public function permanentlyRemoveProduct($productId) {
        try {
            $this->db->beginTransaction();
            

            

            
            // Xóa sản phẩm
            $sql = "DELETE FROM sanpham WHERE id_sp = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$productId]);
            
            $this->db->commit();
            return $result;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    // Thống kê sản phẩm theo trạng thái
    public function getProductStatusStats() {
        $sql = "SELECT 
                    COUNT(*) as tong_san_pham,
                    COUNT(CASE WHEN Mount > 0 THEN 1 END) as con_hang,
                    COUNT(CASE WHEN Mount = 0 THEN 1 END) as het_hang,
                    COUNT(CASE WHEN Mount <= 5 AND Mount > 0 THEN 1 END) as sap_het_hang,
                    SUM(Mount) as tong_ton_kho
                FROM sanpham";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách sản phẩm sắp hết hàng (còn ít hơn 5 sản phẩm)
    public function getLowStockProducts($threshold = 5) {
        $sql = "SELECT id_sp, Name, image, Price, Mount, id_danhmuc, id_hang
                FROM sanpham 
                WHERE Mount > 0 AND Mount <= ?
                ORDER BY Mount ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$threshold]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Cập nhật trạng thái thanh toán
    public function updatePaymentStatus($orderId, $paymentStatus) {
        try {
            $sql = "UPDATE donhang SET phuong_thuc_thanh_toan = ? WHERE id_dh = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$paymentStatus, $orderId]);
        } catch (Exception $e) {
            return false;
        }
    }
}
?> 