<?php
// Include necessary files
include_once __DIR__ . '/../../model/donhang.php';

// Kiểm tra đăng nhập
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: /project/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để xem đơn hàng'));
    exit();
}

// Kiểm tra ID đơn hàng
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /project/index.php?act=my_orders');
    exit();
}

$orderId = (int)$_GET['id'];
$userId = $currentUser['id_user'];
$donHang = new DonHang();

// Lấy chi tiết đơn hàng
$orderDetails = $donHang->getOrderDetails($orderId);

// Kiểm tra đơn hàng có thuộc về user này không
if (!$orderDetails || $orderDetails['id_user'] != $userId) {
    header('Location: /project/index.php?act=my_orders');
    exit();
}

$content = '
<!-- Main Content -->
<div class="max-w-4xl mx-auto px-4 bg-white min-h-screen py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/project/index.php" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="ri-home-line mr-2"></i>
                    Trang chủ
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ri-arrow-right-s-line text-gray-400"></i>
                    <a href="/project/index.php?act=my_orders" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Đơn hàng của tôi</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ri-arrow-right-s-line text-gray-400"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Chi tiết đơn hàng #' . $orderId . '</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Đơn hàng #' . $orderId . '</h1>
                <p class="text-gray-600">Đặt hàng lúc ' . date('d/m/Y H:i', strtotime($orderDetails['ngaydat'])) . '</p>
            </div>
            <div class="text-right">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full ' . 
                    ($orderDetails['trangthai'] == 'Chờ xác nhận' ? 'bg-yellow-100 text-yellow-800' :
                     ($orderDetails['trangthai'] == 'Đã xác nhận' ? 'bg-blue-100 text-blue-800' :
                      ($orderDetails['trangthai'] == 'Đang giao hàng' ? 'bg-purple-100 text-purple-800' :
                       ($orderDetails['trangthai'] == 'Đã giao hàng' ? 'bg-green-100 text-green-800' :
                        'bg-red-100 text-red-800')))) . '">
                    ' . $orderDetails['trangthai'] . '
                </span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Shipping Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Thông tin giao hàng</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium">Người nhận:</span> ' . htmlspecialchars($orderDetails['ten_nguoi_nhan']) . '</p>
                    <p><span class="font-medium">Số điện thoại:</span> ' . htmlspecialchars($orderDetails['sdt_nguoi_nhan']) . '</p>
                    <p><span class="font-medium">Địa chỉ:</span> ' . nl2br(htmlspecialchars($orderDetails['dia_chi_giao'])) . '</p>
                    ' . ($orderDetails['ghi_chu'] ? '<p><span class="font-medium">Ghi chú:</span> ' . nl2br(htmlspecialchars($orderDetails['ghi_chu'])) . '</p>' : '') . '
                </div>
            </div>
            
            <!-- Payment Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Thông tin thanh toán</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium">Phương thức:</span> ' . htmlspecialchars($orderDetails['phuong_thuc_thanh_toan']) . '</p>
                    <p><span class="font-medium">Tổng tiền:</span> <span class="font-bold text-lg text-red-600">' . number_format($orderDetails['tongdh']) . ' ₫</span></p>
                    <p><span class="font-medium">Cập nhật lần cuối:</span> ' . date('d/m/Y H:i', strtotime($orderDetails['ngay_cap_nhat'])) . '</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Sản phẩm đã đặt</h3>
        <div class="space-y-4">
            ' . implode('', array_map(function($item) {
                return '
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <img src="/project/view/image/' . htmlspecialchars($item['image']) . '" 
                             alt="' . htmlspecialchars($item['Name']) . '" 
                             class="w-16 h-16 object-cover rounded-lg"
                             onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 truncate">' . htmlspecialchars($item['Name']) . '</h4>
                        <p class="text-sm text-gray-500">Số lượng: ' . $item['soluong'] . '</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">' . number_format($item['gia_ban']) . ' ₫</p>
                        <p class="text-sm text-gray-500">Tổng: ' . number_format($item['tong_dh']) . ' ₫</p>
                    </div>
                </div>
                ';
            }, $orderDetails['items'])) . '
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-between items-center">
        <a href="/project/index.php?act=my_orders" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <i class="ri-arrow-left-line mr-2"></i>
            Quay lại danh sách
        </a>
        
        <div class="flex space-x-3">
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                <i class="ri-printer-line mr-2"></i>
                In đơn hàng
            </button>
            <a href="/project/index.php?act=sanpham" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                <i class="ri-store-line mr-2"></i>
                Mua thêm
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    body {
        background: white !important;
    }
    .bg-white {
        background: white !important;
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>
';

// Sử dụng layout chung
include_once __DIR__ . '/../layout/layout.php';
?> 