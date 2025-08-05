<?php
// Include necessary files
include_once __DIR__ . '/../../model/donhang.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';
include_once __DIR__ . '/../layout/layout.php';

// Kiểm tra đăng nhập
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: /project/controller/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để xem đơn hàng'));
    exit();
}

$userId = $currentUser['id_user'];
$donHang = new DonHang();

// Xử lý tìm kiếm và lọc
$search = trim($_GET['search'] ?? '');
$status_filter = trim($_GET['status'] ?? '');

// Lấy tất cả đơn hàng của user
$allUserOrders = $donHang->getUserOrders($userId);

// Lọc theo tìm kiếm và trạng thái
$userOrders = array_filter($allUserOrders, function($order) use ($search, $status_filter) {
    $matchSearch = empty($search) || strpos($order['id_dh'], $search) !== false;
    $matchStatus = empty($status_filter) || $order['trangthai'] === $status_filter;
    return $matchSearch && $matchStatus;
});

// Chuyển về array index
$userOrders = array_values($userOrders);

// Phân trang
$per_page = 10;
$total_orders_filtered = count($userOrders);
$total_pages = ceil($total_orders_filtered / $per_page);
$current_page = max(1, intval($_GET['page'] ?? 1));
$offset = ($current_page - 1) * $per_page;
$userOrders = array_slice($userOrders, $offset, $per_page);

// Thống kê đơn hàng của user (tính trên tất cả đơn hàng, không phải chỉ trang hiện tại)
$total_orders = count($allUserOrders);
$pending_orders = 0;
$completed_orders = 0;
$cancelled_orders = 0;

foreach ($allUserOrders as $order) {
    switch ($order['trangthai']) {
        case 'Chờ xác nhận':
        case 'Đã xác nhận':
        case 'Đang giao':
            $pending_orders++;
            break;
        case 'Đã giao':
            $completed_orders++;
            break;
        case 'Đã hủy':
            $cancelled_orders++;
            break;
    }
}

$content = '
<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 bg-white min-h-screen py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/project/controller/index.php" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
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

    <!-- Order Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="ri-shopping-bag-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng đơn hàng</p>
                    <p class="text-2xl font-semibold text-gray-900">' . $total_orders . '</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="ri-time-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Đang xử lý</p>
                    <p class="text-2xl font-semibold text-gray-900">' . $pending_orders . '</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="ri-check-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Đã hoàn thành</p>
                    <p class="text-2xl font-semibold text-gray-900">' . $completed_orders . '</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="ri-close-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Đã hủy</p>
                    <p class="text-2xl font-semibold text-gray-900">' . $cancelled_orders . '</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tìm kiếm và lọc</h3>
            <form method="GET" class="space-y-4">
                <input type="hidden" name="act" value="my_orders">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                        <input type="text" name="search" value="' . htmlspecialchars($_GET['search'] ?? '') . '" 
                               placeholder="Mã đơn hàng..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tất cả trạng thái</option>
                            <option value="Chờ xác nhận"' . (($_GET['status'] ?? '') === 'Chờ xác nhận' ? ' selected' : '') . '>Chờ xác nhận</option>
                            <option value="Đã xác nhận"' . (($_GET['status'] ?? '') === 'Đã xác nhận' ? ' selected' : '') . '>Đã xác nhận</option>
                            <option value="Đang giao"' . (($_GET['status'] ?? '') === 'Đang giao' ? ' selected' : '') . '>Đang giao</option>
                            <option value="Đã giao"' . (($_GET['status'] ?? '') === 'Đã giao' ? ' selected' : '') . '>Đã giao</option>
                            <option value="Đã hủy"' . (($_GET['status'] ?? '') === 'Đã hủy' ? ' selected' : '') . '>Đã hủy</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Thao tác</label>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="ri-search-line mr-2"></i>Tìm kiếm
                            </button>
                            <a href="/project/controller/index.php?act=my_orders" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                                <i class="ri-refresh-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        ' . (empty($userOrders) ? '
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="ri-shopping-bag-3-line text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                ' . (!empty($search) || !empty($status_filter) ? 'Không tìm thấy đơn hàng nào' : 'Chưa có đơn hàng nào') . '
            </h3>
            <p class="text-gray-500 mb-6">
                ' . (!empty($search) || !empty($status_filter) ? 'Thử thay đổi bộ lọc tìm kiếm hoặc' : 'Bạn chưa có đơn hàng nào. Hãy') . ' bắt đầu mua sắm ngay!
            </p>
            <a href="/project/controller/index.php?act=sanpham" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
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
                            Giao hàng
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
                        
                        // Tính thời gian từ khi đặt hàng
                        $orderDate = new DateTime($order['ngaydat']);
                        $now = new DateTime();
                        $interval = $orderDate->diff($now);
                        $timeAgo = '';
                        
                        if ($interval->days > 0) {
                            $timeAgo = $interval->days . ' ngày trước';
                        } elseif ($interval->h > 0) {
                            $timeAgo = $interval->h . ' giờ trước';
                        } elseif ($interval->i > 0) {
                            $timeAgo = $interval->i . ' phút trước';
                        } else {
                            $timeAgo = 'Vừa xong';
                        }
                        
                        return '
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#' . $order['id_dh'] . '</div>
                                <div class="text-xs text-gray-500">' . $timeAgo . '</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">' . date('d/m/Y H:i', strtotime($order['ngaydat'])) . '</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">' . number_format($order['tongdh']) . ' ₫</div>
                                <div class="text-xs text-gray-500">
                                    ' . $order['so_san_pham'] . ' sản phẩm
                                    ' . (!empty($order['danh_sach_sp']) ? '<br><span class="text-gray-400">' . substr($order['danh_sach_sp'], 0, 50) . (strlen($order['danh_sach_sp']) > 50 ? '...' : '') . '</span>' : '') . '
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">' . htmlspecialchars($order['ten_nguoi_nhan']) . '</div>
                                <div class="text-xs text-gray-500">' . htmlspecialchars($order['sdt_nguoi_nhan']) . '</div>
                                <div class="text-xs text-gray-400 truncate max-w-xs">' . htmlspecialchars($order['dia_chi_giao']) . '</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . $statusColor . '">
                                    ' . $order['trangthai'] . '
                                </span>
                                
                                <!-- Order Timeline -->
                                <div class="mt-2 flex items-center space-x-1">
                                    <div class="w-4 h-4 rounded-full ' . ($order['trangthai'] === 'Chờ xác nhận' || $order['trangthai'] === 'Đã xác nhận' || $order['trangthai'] === 'Đang giao' || $order['trangthai'] === 'Đã giao' ? 'bg-yellow-500' : 'bg-gray-300') . ' flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">1</span>
                                    </div>
                                    <div class="flex-1 h-1 ' . ($order['trangthai'] === 'Đã xác nhận' || $order['trangthai'] === 'Đang giao' || $order['trangthai'] === 'Đã giao' ? 'bg-yellow-500' : 'bg-gray-300') . '"></div>
                                    
                                    <div class="w-4 h-4 rounded-full ' . ($order['trangthai'] === 'Đã xác nhận' || $order['trangthai'] === 'Đang giao' || $order['trangthai'] === 'Đã giao' ? 'bg-blue-500' : 'bg-gray-300') . ' flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">2</span>
                                    </div>
                                    <div class="flex-1 h-1 ' . ($order['trangthai'] === 'Đang giao' || $order['trangthai'] === 'Đã giao' ? 'bg-blue-500' : 'bg-gray-300') . '"></div>
                                    
                                    <div class="w-4 h-4 rounded-full ' . ($order['trangthai'] === 'Đang giao' || $order['trangthai'] === 'Đã giao' ? 'bg-purple-500' : 'bg-gray-300') . ' flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">3</span>
                                    </div>
                                    <div class="flex-1 h-1 ' . ($order['trangthai'] === 'Đã giao' ? 'bg-purple-500' : 'bg-gray-300') . '"></div>
                                    
                                    <div class="w-4 h-4 rounded-full ' . ($order['trangthai'] === 'Đã giao' ? 'bg-green-500' : 'bg-gray-300') . ' flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">4</span>
                                    </div>
                                </div>
                                
                                <div class="text-xs text-gray-500 mt-1">
                                    ' . ($order['trangthai'] === 'Chờ xác nhận' ? 'Đang chờ xác nhận' : '') . '
                                    ' . ($order['trangthai'] === 'Đã xác nhận' ? 'Đã xác nhận và đang chuẩn bị' : '') . '
                                    ' . ($order['trangthai'] === 'Đang giao' ? 'Đang giao hàng' : '') . '
                                    ' . ($order['trangthai'] === 'Đã giao' ? 'Đã giao thành công' : '') . '
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="/project/controller/index.php?act=order_detail&id=' . $order['id_dh'] . '" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="ri-eye-line mr-1"></i>Xem chi tiết
                                    </a>
                                    ' . ($order['trangthai'] === 'Chờ xác nhận' ? '
                                    <button onclick="cancelOrder(' . $order['id_dh'] . ')" class="text-red-600 hover:text-red-900">
                                        <i class="ri-close-line mr-1"></i>Hủy đơn
                                    </button>
                                    ' : '') . '
                                </div>
                            </td>
                        </tr>
                        ';
                    }, $userOrders)) . '
                </tbody>
            </table>
        </div>
        ') . '
        
        <!-- Pagination -->
        ' . ($total_pages > 1 ? '
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Hiển thị ' . ($offset + 1) . ' đến ' . min($offset + $per_page, $total_orders_filtered) . ' của ' . $total_orders_filtered . ' đơn hàng
                </div>
                <div class="flex space-x-2">
                    ' . ($current_page > 1 ? '
                    <a href="?act=my_orders&page=' . ($current_page - 1) . '&search=' . urlencode($search) . '&status=' . urlencode($status_filter) . '" 
                       class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">
                        Trước
                    </a>
                    ' : '') . '

                    ' . implode('', array_map(function($i) use ($current_page, $search, $status_filter) {
                        $active_class = $i == $current_page ? 'bg-blue-600 text-white' : 'border border-gray-300 hover:bg-gray-50';
                        return '
                        <a href="?act=my_orders&page=' . $i . '&search=' . urlencode($search) . '&status=' . urlencode($status_filter) . '" 
                           class="px-3 py-1 rounded text-sm ' . $active_class . '">
                            ' . $i . '
                        </a>
                        ';
                    }, range(max(1, $current_page - 2), min($total_pages, $current_page + 2)))) . '

                    ' . ($current_page < $total_pages ? '
                    <a href="?act=my_orders&page=' . ($current_page + 1) . '&search=' . urlencode($search) . '&status=' . urlencode($status_filter) . '" 
                       class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">
                        Sau
                    </a>
                    ' : '') . '
                </div>
            </div>
        </div>
        ' : '') . '
    </div>

    <!-- Order Tracking Guide -->
    ' . (!empty($userOrders) ? '
    <div class="mt-8 bg-blue-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-4">
            <i class="ri-information-line mr-2"></i>
            Hướng dẫn theo dõi đơn hàng
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                    <span class="text-white text-xs font-bold">1</span>
                </div>
                <div>
                    <h4 class="font-medium text-blue-800">Chờ xác nhận</h4>
                    <p class="text-sm text-blue-700">Đơn hàng đã được đặt và đang chờ xác nhận</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                    <span class="text-white text-xs font-bold">2</span>
                </div>
                <div>
                    <h4 class="font-medium text-blue-800">Đã xác nhận</h4>
                    <p class="text-sm text-blue-700">Đơn hàng đã được xác nhận và đang chuẩn bị</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                    <span class="text-white text-xs font-bold">3</span>
                </div>
                <div>
                    <h4 class="font-medium text-blue-800">Đang giao hàng</h4>
                    <p class="text-sm text-blue-700">Đơn hàng đang được giao đến địa chỉ của bạn</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                    <span class="text-white text-xs font-bold">4</span>
                </div>
                <div>
                    <h4 class="font-medium text-blue-800">Đã giao hàng</h4>
                    <p class="text-sm text-blue-700">Đơn hàng đã được giao thành công</p>
                </div>
            </div>
        </div>
    </div>
    ' : '') . '

    <!-- Support Section -->
    <div class="mt-8 bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="ri-customer-service-line mr-2"></i>
            Cần hỗ trợ?
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center space-x-3">
                <i class="ri-phone-line text-blue-500 text-xl"></i>
                <div>
                    <p class="font-medium text-gray-800">Hotline</p>
                    <p class="text-sm text-gray-600">1900 6789</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <i class="ri-mail-line text-blue-500 text-xl"></i>
                <div>
                    <p class="font-medium text-gray-800">Email</p>
                    <p class="text-sm text-gray-600">info@dienlanhkv.vn</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <i class="ri-time-line text-blue-500 text-xl"></i>
                <div>
                    <p class="font-medium text-gray-800">Giờ làm việc</p>
                    <p class="text-sm text-gray-600">8:00 - 22:00 (T2-CN)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Xác nhận hủy đơn hàng</h3>
                <button onclick="hideCancelModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <div class="mb-4">
                    <div class="flex items-center text-red-600 mb-2">
                        <i class="ri-error-warning-line text-xl mr-2"></i>
                        <span class="font-medium">Cảnh báo</span>
                    </div>
                    <p class="text-gray-700 mb-2">Bạn có chắc chắn muốn hủy đơn hàng <strong id="cancelOrderId"></strong>?</p>
                    <p class="text-sm text-red-600">Hành động này không thể hoàn tác!</p>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button onclick="hideCancelModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Hủy
                    </button>
                    <button onclick="confirmCancelOrder()" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Xác nhận hủy
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentOrderId = null;

function cancelOrder(orderId) {
    currentOrderId = orderId;
    document.getElementById("cancelOrderId").textContent = "#" + orderId;
    document.getElementById("cancelModal").classList.remove("hidden");
}

function hideCancelModal() {
    document.getElementById("cancelModal").classList.add("hidden");
    currentOrderId = null;
}

function confirmCancelOrder() {
    if (currentOrderId) {
        // Gửi request hủy đơn hàng
        fetch("/project/controller/index.php?act=cancel_order", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: "order_id=" + currentOrderId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Hủy đơn hàng thành công!");
                location.reload();
            } else {
                alert("Có lỗi xảy ra: " + data.message);
            }
        })
        .catch(error => {
            alert("Có lỗi xảy ra khi hủy đơn hàng!");
        });
        
        hideCancelModal();
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById("cancelModal");
    if (event.target === modal) {
        hideCancelModal();
    }
}
</script>';

// Sử dụng layout chung
renderPage("Đơn hàng của tôi - Điện Lạnh KV", $content);
?> 