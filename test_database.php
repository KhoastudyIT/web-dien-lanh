<?php
// Script test database cho hệ thống đơn hàng
require_once 'model/database.php';
require_once 'model/donhang.php';

echo "<h2>Test Database - Hệ thống Đơn hàng</h2>";

try {
    // Test kết nối database
    $db = new Database();
    echo "<p style='color: green;'>✓ Kết nối database thành công!</p>";
    
    // Test tạo đối tượng DonHang
    $donHang = new DonHang();
    echo "<p style='color: green;'>✓ Tạo đối tượng DonHang thành công!</p>";
    
    // Kiểm tra các bảng
    $tables = ['donhang', 'dh_chitiet', 'lich_su_trang_thai', 'taikhoan', 'sanpham'];
    
    foreach ($tables as $table) {
        $sql = "SHOW TABLES LIKE '$table'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✓ Bảng '$table' tồn tại</p>";
        } else {
            echo "<p style='color: red;'>✗ Bảng '$table' không tồn tại</p>";
        }
    }
    
    // Test các method cơ bản
    echo "<h3>Test các method:</h3>";
    
    // Test getAllOrders
    try {
        $orders = $donHang->getAllOrders();
        echo "<p style='color: green;'>✓ Method getAllOrders() hoạt động - Có " . count($orders) . " đơn hàng</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Method getAllOrders() lỗi: " . $e->getMessage() . "</p>";
    }
    
    // Test getOrderStats
    try {
        $stats = $donHang->getOrderStats();
        echo "<p style='color: green;'>✓ Method getOrderStats() hoạt động</p>";
        echo "<pre>" . print_r($stats, true) . "</pre>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Method getOrderStats() lỗi: " . $e->getMessage() . "</p>";
    }
    
    // Test getTopSellingProducts
    try {
        $topProducts = $donHang->getTopSellingProducts(5);
        echo "<p style='color: green;'>✓ Method getTopSellingProducts() hoạt động - Có " . count($topProducts) . " sản phẩm</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Method getTopSellingProducts() lỗi: " . $e->getMessage() . "</p>";
    }
    
    // Test searchOrders
    try {
        $searchResults = $donHang->searchOrders('', '', '', '');
        echo "<p style='color: green;'>✓ Method searchOrders() hoạt động - Tìm thấy " . count($searchResults) . " kết quả</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Method searchOrders() lỗi: " . $e->getMessage() . "</p>";
    }
    
    echo "<h3>Thông tin Database:</h3>";
    echo "<p>Database: dienlanh_shop</p>";
    echo "<p>Host: localhost</p>";
    echo "<p>Charset: utf8mb4</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Lỗi: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Hướng dẫn sử dụng:</h3>";
echo "<ol>";
echo "<li>Chạy file <code>model/database.sql</code> để tạo toàn bộ database</li>";
echo "<li>Hoặc chạy file <code>model/import_orders.sql</code> để chỉ tạo các bảng đơn hàng</li>";
echo "<li>Đăng nhập với tài khoản admin: username='admin', password='admin123'</li>";
echo "<li>Truy cập trang quản lý đơn hàng: /project/index.php?act=admin_orders</li>";
echo "</ol>";

echo "<p><strong>Lưu ý:</strong> Đảm bảo XAMPP đang chạy và MySQL service đã được khởi động.</p>";
?> 