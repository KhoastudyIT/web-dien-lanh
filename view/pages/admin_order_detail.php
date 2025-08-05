<?php
// Sử dụng layout chung
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/donhang.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';

// Kiểm tra quyền admin
$currentUser = getCurrentUser();
if (!$currentUser || $currentUser['position'] !== 'admin') {
    header('Location: /project/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang này'));
    exit();
}

// Lấy ID đơn hàng
$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$orderId) {
    header('Location: /project/index.php?act=admin_orders&error=' . urlencode('Không tìm thấy đơn hàng'));
    exit();
}

$donhang = new DonHang();
$order = $donhang->getOrderDetails($orderId);

if (!$order) {
    header('Location: /project/index.php?act=admin_orders&error=' . urlencode('Không tìm thấy đơn hàng'));
    exit();
}

// Xử lý cập nhật trạng thái
if (isset($_POST['update_status'])) {
    $status = $_POST['status'];
    if ($donhang->updateOrderStatus($orderId, $status)) {
        $success = "Cập nhật trạng thái đơn hàng thành công!";
        // Refresh order data
        $order = $donhang->getOrderDetails($orderId);
    } else {
        $error = "Có lỗi xảy ra khi cập nhật trạng thái!";
    }
}

$content = '
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                    <i class="ri-shopping-bag-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Chi tiết đơn hàng #' . $orderId . '</h1>
                    <p class="text-gray-600">Xem thông tin chi tiết đơn hàng</p>
                </div>
            </div>
            <a href="/project/index.php?act=admin_orders" 
               class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="ri-arrow-left-line mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    ' . (isset($success) ? '
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        <div class="flex items-center">
            <i class="ri-check-line mr-2"></i>
            <span>' . htmlspecialchars($success) . '</span>
        </div>
    </div>' : '') . '
    
    ' . (isset($error) ? '
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <div class="flex items-center">
            <i class="ri-error-warning-line mr-2"></i>
            <span>' . htmlspecialchars($error) . '</span>
        </div>
    </div>' : '') . '

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="ri-information-line text-primary mr-2"></i>
                    Thông tin đơn hàng
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mã đơn hàng</label>
                        <p class="text-lg font-semibold text-gray-900">#' . $order['id_dh'] . '</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngày đặt hàng</label>
                        <p class="text-lg font-semibold text-gray-900">' . date('d/m/Y H:i', strtotime($order['ngaydat'])) . '</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tổng tiền</label>
                        <p class="text-lg font-semibold text-primary">' . number_format($order['tongdh']) . '₫</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phương thức thanh toán</label>
                        <p class="text-lg font-semibold text-gray-900">' . htmlspecialchars($order['phuong_thuc_thanh_toan']) . '</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full ' . 
                                ($order['trangthai'] === 'Chờ xác nhận' ? 'bg-yellow-100 text-yellow-800' : 
                                ($order['trangthai'] === 'Đã xác nhận' ? 'bg-blue-100 text-blue-800' : 
                                ($order['trangthai'] === 'Đang giao' ? 'bg-purple-100 text-purple-800' : 
                                ($order['trangthai'] === 'Đã giao' ? 'bg-green-100 text-green-800' : 
                                'bg-red-100 text-red-800')))) . '">
                                ' . htmlspecialchars($order['trangthai']) . '
                            </span>
                            
                            <form method="POST" class="flex items-center gap-2">
                                <select name="status" class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="Chờ xác nhận" ' . ($order['trangthai'] === 'Chờ xác nhận' ? 'selected' : '') . '>Chờ xác nhận</option>
                                    <option value="Đã xác nhận" ' . ($order['trangthai'] === 'Đã xác nhận' ? 'selected' : '') . '>Đã xác nhận</option>
                                    <option value="Đang giao" ' . ($order['trangthai'] === 'Đang giao' ? 'selected' : '') . '>Đang giao</option>
                                    <option value="Đã giao" ' . ($order['trangthai'] === 'Đã giao' ? 'selected' : '') . '>Đã giao</option>
                                    <option value="Đã hủy" ' . ($order['trangthai'] === 'Đã hủy' ? 'selected' : '') . '>Đã hủy</option>
                                </select>
                                <button type="submit" name="update_status" value="1" 
                                        class="bg-primary text-white px-4 py-1 rounded-lg hover:bg-primary-dark transition-colors">
                                    Cập nhật
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="ri-user-line text-primary mr-2"></i>
                    Thông tin khách hàng
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên</label>
                        <p class="text-lg font-semibold text-gray-900">' . htmlspecialchars($order['fullname']) . '</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <p class="text-lg font-semibold text-gray-900">' . htmlspecialchars($order['email']) . '</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                        <p class="text-lg font-semibold text-gray-900">' . htmlspecialchars($order['phone']) . '</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Người nhận</label>
                        <p class="text-lg font-semibold text-gray-900">' . htmlspecialchars($order['ten_nguoi_nhan']) . '</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SĐT người nhận</label>
                        <p class="text-lg font-semibold text-gray-900">' . htmlspecialchars($order['sdt_nguoi_nhan']) . '</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ giao hàng</label>
                        <p class="text-lg font-semibold text-gray-900">' . htmlspecialchars($order['dia_chi_giao']) . '</p>
                    </div>
                    
                    ' . ($order['ghi_chu'] ? '
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                        <p class="text-lg font-semibold text-gray-900">' . htmlspecialchars($order['ghi_chu']) . '</p>
                    </div>
                    ' : '') . '
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="ri-shopping-cart-line text-primary mr-2"></i>
                    Sản phẩm đã đặt
                </h2>
                
                <div class="space-y-4">
                    ' . implode('', array_map(function($item) {
                        return '
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-4">
                                <img src="/project/view/image/' . htmlspecialchars($item['image']) . '" 
                                     alt="' . htmlspecialchars($item['Name']) . '" 
                                     class="w-16 h-16 object-cover rounded-lg shadow border"
                                     onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                                <div>
                                    <h3 class="font-semibold text-gray-800">' . htmlspecialchars($item['Name']) . '</h3>
                                    <p class="text-sm text-gray-600">Số lượng: ' . $item['soluong'] . '</p>
                                    <p class="text-sm text-gray-600">Giá bán: ' . number_format($item['gia_ban']) . '₫</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-primary">' . number_format($item['tong_dh']) . '₫</div>
                            </div>
                        </div>
                        ';
                    }, $order['items'])) . '
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Tóm tắt đơn hàng</h2>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between text-gray-600">
                        <span>Tạm tính (' . count($order['items']) . ' sản phẩm)</span>
                        <span>' . number_format($order['tongdh']) . '₫</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Phí vận chuyển</span>
                        <span class="text-green-600">Miễn phí</span>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between text-lg font-bold text-gray-800">
                            <span>Tổng cộng</span>
                            <span class="text-primary">' . number_format($order['tongdh']) . '₫</span>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <a href="/project/index.php?act=admin_orders" 
                       class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 transition-colors text-center block">
                        <i class="ri-arrow-left-line mr-2"></i>
                        Quay lại danh sách
                    </a>
                    
                    <button onclick="window.print()" 
                            class="w-full bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                        <i class="ri-printer-line mr-2"></i>
                        In đơn hàng
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
}
</style>';

renderPage("Chi tiết đơn hàng #" . $orderId . " - Admin", $content);
?> 