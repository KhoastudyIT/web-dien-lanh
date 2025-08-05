<?php
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/donhang.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';

// Kiểm tra đăng nhập
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: /project/controller/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để xem đơn hàng'));
    exit();
}

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$orderId) {
    header('Location: /project/controller/index.php?act=cart');
    exit();
}

// Lấy thông tin đơn hàng
$donhang = new DonHang();
$order = $donhang->getOrderDetails($orderId);

if (!$order || $order['id_user'] != $currentUser['id_user']) {
    header('Location: /project/controller/index.php?act=cart');
    exit();
}

$content = '
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Success Message -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ri-check-line text-green-600 text-2xl"></i>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Đặt hàng thành công!</h1>
            <p class="text-gray-600 mb-6">Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đang chờ xác nhận từ chúng tôi.</p>
            
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Thông tin đơn hàng</h2>
                <div class="space-y-2 text-left">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Mã đơn hàng:</span>
                        <span class="font-semibold">#' . $orderId . '</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tổng tiền:</span>
                        <span class="font-semibold text-green-600">' . number_format($order['tongdh']) . '₫</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Trạng thái:</span>
                        <span class="font-semibold text-blue-600">' . $order['trangthai'] . '</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngày đặt:</span>
                        <span class="font-semibold">' . date('d/m/Y H:i', strtotime($order['ngaydat'])) . '</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Thông tin giao hàng</h3>
                <div class="space-y-2 text-left">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Người nhận:</span>
                        <span class="font-semibold">' . htmlspecialchars($order['ten_nguoi_nhan']) . '</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Số điện thoại:</span>
                        <span class="font-semibold">' . htmlspecialchars($order['sdt_nguoi_nhan']) . '</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Địa chỉ:</span>
                        <span class="font-semibold">' . htmlspecialchars($order['dia_chi_giao']) . '</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Phương thức thanh toán:</span>
                        <span class="font-semibold">' . htmlspecialchars($order['phuong_thuc_thanh_toan']) . '</span>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="/project/controller/index.php?act=my_orders" 
                   class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors text-center">
                    <i class="ri-list-check mr-2"></i>
                    Xem đơn hàng của tôi
                </a>
                <a href="/project/controller/index.php" 
                   class="flex-1 bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 transition-colors text-center">
                    <i class="ri-home-line mr-2"></i>
                    Về trang chủ
                </a>
            </div>
        </div>
    </div>
</div>';

renderPage("Đặt hàng thành công - Điện Lạnh KV", $content);
?> 