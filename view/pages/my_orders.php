<?php
// Include necessary files
include_once __DIR__ . '/../../model/donhang.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';

// Kiểm tra đăng nhập
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: /project/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để xem đơn hàng'));
    exit();
}

$userId = $currentUser['id_user'];
$donHang = new DonHang();
$userOrders = $donHang->getUserOrders($userId);

$content = '
<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 bg-white min-h-screen py-8">
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
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Đơn hàng của tôi</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Đơn hàng của tôi</h1>
        <p class="text-gray-600">Theo dõi trạng thái và chi tiết các đơn hàng của bạn</p>
    </div>

    <!-- Orders List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        ' . (empty($userOrders) ? '
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="ri-shopping-bag-3-line text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có đơn hàng nào</h3>
            <p class="text-gray-500 mb-6">Bạn chưa có đơn hàng nào. Hãy bắt đầu mua sắm ngay!</p>
            <a href="/project/index.php?act=sanpham" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="ri-store-line mr-2"></i>
                Mua sắm ngay
            </a>
        </div>
        ' : '
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mã đơn hàng
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày đặt
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tổng tiền
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ' . implode('', array_map(function($order) {
                        $statusColors = [
                            'Chờ xác nhận' => 'bg-yellow-100 text-yellow-800',
                            'Đã xác nhận' => 'bg-blue-100 text-blue-800',
                            'Đang giao hàng' => 'bg-purple-100 text-purple-800',
                            'Đã giao hàng' => 'bg-green-100 text-green-800',
                            'Đã hủy' => 'bg-red-100 text-red-800'
                        ];
                        
                        $statusColor = $statusColors[$order['trangthai']] ?? 'bg-gray-100 text-gray-800';
                        
                        return '
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#' . $order['id_dh'] . '</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">' . date('d/m/Y H:i', strtotime($order['ngaydat'])) . '</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">' . number_format($order['tongdh']) . ' ₫</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . $statusColor . '">
                                    ' . $order['trangthai'] . '
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/project/index.php?act=order_detail&id=' . $order['id_dh'] . '" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="ri-eye-line mr-1"></i>Xem chi tiết
                                </a>
                            </td>
                        </tr>
                        ';
                    }, $userOrders)) . '
                </tbody>
            </table>
        </div>
        ') . '
    </div>
</div>
';

// Sử dụng layout chung
include_once __DIR__ . '/../layout/layout.php';
?> 