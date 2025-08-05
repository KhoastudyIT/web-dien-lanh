<?php
include_once __DIR__ . '/../../model/donhang.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';

// Kiểm tra quyền admin
$currentUser = getCurrentUser();
if (!$currentUser || $currentUser['position'] !== 'admin') {
    header('Location: /project/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang này'));
    exit();
}

$donhang = new DonHang();

// Xử lý POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        $orderId = (int)($_POST['order_id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $ghiChu = trim($_POST['ghi_chu'] ?? '');
        
        if ($orderId > 0 && !empty($status)) {
            if ($donhang->updateOrderStatus($orderId, $status, $currentUser['id_user'], $ghiChu)) {
                $success_message = 'Cập nhật trạng thái đơn hàng thành công!';
            } else {
                $error_message = 'Lỗi khi cập nhật trạng thái đơn hàng!';
            }
        } else {
            $error_message = 'Dữ liệu không hợp lệ!';
        }
    } elseif (isset($_POST['delete_order'])) {
        $orderId = (int)($_POST['order_id'] ?? 0);
        if ($orderId > 0) {
            if ($donhang->deleteOrder($orderId)) {
                $success_message = 'Xóa đơn hàng thành công!';
            } else {
                $error_message = 'Lỗi khi xóa đơn hàng!';
            }
        } else {
            $error_message = 'ID đơn hàng không hợp lệ!';
        }
    }
}

// Lấy tham số tìm kiếm và lọc
$search = trim($_GET['search'] ?? '');
$status_filter = trim($_GET['status'] ?? '');
$date_from = trim($_GET['date_from'] ?? '');
$date_to = trim($_GET['date_to'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Lấy danh sách đơn hàng với tìm kiếm và lọc
if (!empty($search) || !empty($status_filter) || !empty($date_from) || !empty($date_to)) {
    $orders = $donhang->searchOrders($search, $status_filter, $date_from, $date_to);
} else {
    $orders = $donhang->getAllOrders();
}

$total_orders = count($orders);
$total_pages = ceil($total_orders / $per_page);
$orders = array_slice($orders, $offset, $per_page);

// Lấy thống kê
$stats = $donhang->getOrderStats();

$content = '
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                    <i class="ri-shopping-bag-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Quản lý đơn hàng</h1>
                    <p class="text-gray-600">Quản lý và theo dõi tất cả đơn hàng</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tổng đơn hàng</p>
                    <p class="text-2xl font-bold text-gray-900">' . number_format($stats['tong_don_hang']) . '</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="ri-shopping-bag-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Chờ xác nhận</p>
                    <p class="text-2xl font-bold text-yellow-600">' . number_format($stats['cho_xac_nhan']) . '</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="ri-time-line text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Đang giao</p>
                    <p class="text-2xl font-bold text-blue-600">' . number_format($stats['dang_giao']) . '</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="ri-truck-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tổng doanh thu</p>
                    <p class="text-2xl font-bold text-green-600">' . number_format($stats['tong_doanh_thu']) . '₫</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="ri-money-dollar-circle-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="space-y-4">
            <input type="hidden" name="act" value="admin_orders">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                    <input type="text" name="search" value="' . htmlspecialchars($search) . '" 
                           placeholder="Mã đơn hàng, tên KH, SĐT..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tất cả trạng thái</option>
                        <option value="Chờ xác nhận"' . ($status_filter === 'Chờ xác nhận' ? ' selected' : '') . '>Chờ xác nhận</option>
                        <option value="Đã xác nhận"' . ($status_filter === 'Đã xác nhận' ? ' selected' : '') . '>Đã xác nhận</option>
                        <option value="Đang giao"' . ($status_filter === 'Đang giao' ? ' selected' : '') . '>Đang giao</option>
                        <option value="Đã giao"' . ($status_filter === 'Đã giao' ? ' selected' : '') . '>Đã giao</option>
                        <option value="Đã hủy"' . ($status_filter === 'Đã hủy' ? ' selected' : '') . '>Đã hủy</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
                    <input type="date" name="date_from" value="' . htmlspecialchars($date_from) . '" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
                    <input type="date" name="date_to" value="' . htmlspecialchars($date_to) . '" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            
            <div class="flex justify-between items-center">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <i class="ri-search-line"></i>
                    Tìm kiếm
                </button>
                
                ' . (!empty($search) || !empty($status_filter) || !empty($date_from) || !empty($date_to) ? '
                <a href="?act=admin_orders" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 flex items-center gap-2">
                    <i class="ri-refresh-line"></i>
                    Làm mới
                </a>
                ' : '') . '
            </div>
        </form>
    </div>

    <!-- Success/Error Messages -->
    ' . (isset($success_message) ? '
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        <div class="flex items-center">
            <i class="ri-check-line mr-2"></i>
            <span>' . htmlspecialchars($success_message) . '</span>
        </div>
    </div>' : '') . '
    
    ' . (isset($error_message) ? '
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <div class="flex items-center">
            <i class="ri-error-warning-line mr-2"></i>
            <span>' . htmlspecialchars($error_message) . '</span>
        </div>
    </div>' : '') . '

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Danh sách đơn hàng</h2>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Tổng: ' . number_format($total_orders) . ' đơn hàng</span>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ' . (empty($orders) ? '
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="ri-shopping-bag-line text-4xl mb-2"></i>
                                <p class="text-lg font-medium">Không tìm thấy đơn hàng nào</p>
                                <p class="text-sm">' . (!empty($search) || !empty($status_filter) ? 'Thử thay đổi bộ lọc tìm kiếm' : 'Chưa có đơn hàng nào trong hệ thống') . '</p>
                            </div>
                        </td>
                    </tr>
                    ' : implode('', array_map(function($order) {
                        $statusColors = [
                            'Chờ xác nhận' => 'bg-yellow-100 text-yellow-800',
                            'Đã xác nhận' => 'bg-blue-100 text-blue-800',
                            'Đang giao' => 'bg-purple-100 text-purple-800',
                            'Đã giao' => 'bg-green-100 text-green-800',
                            'Đã hủy' => 'bg-red-100 text-red-800'
                        ];
                        
                        $statusColor = $statusColors[$order['trangthai']] ?? 'bg-gray-100 text-gray-800';
                        
                        return '
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#' . $order['id_dh'] . '</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">' . htmlspecialchars($order['fullname']) . '</div>
                                <div class="text-sm text-gray-500">' . htmlspecialchars($order['email']) . '</div>
                                <div class="text-sm text-gray-500">' . htmlspecialchars($order['phone']) . '</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">' . number_format($order['tongdh']) . '₫</div>
                                <div class="text-sm text-gray-500">' . $order['so_san_pham'] . ' sản phẩm</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">' . date('d/m/Y H:i', strtotime($order['ngaydat'])) . '</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . $statusColor . '">
                                    ' . htmlspecialchars($order['trangthai']) . '
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="/project/index.php?act=admin_order_detail&id=' . $order['id_dh'] . '" 
                                       class="text-primary hover:text-primary-dark transition-colors">
                                        <i class="ri-eye-line"></i> Xem
                                    </a>
                                    <button onclick="openStatusModal(' . $order['id_dh'] . ', \'' . $order['trangthai'] . '\')" 
                                            class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="ri-edit-line"></i> Sửa
                                    </button>
                                    <button onclick="openDeleteModal(' . $order['id_dh'] . ')" 
                                            class="text-red-600 hover:text-red-800 transition-colors">
                                        <i class="ri-delete-bin-line"></i> Xóa
                                    </button>
                                </div>
                            </td>
                        </tr>
                        ';
                    }, $orders))) . '
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        ' . ($total_pages > 1 ? '
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Hiển thị ' . ($offset + 1) . ' đến ' . min($offset + $per_page, $total_orders) . ' của ' . $total_orders . ' đơn hàng
                </div>
                <div class="flex space-x-2">
                    ' . ($page > 1 ? '
                    <a href="?act=admin_orders&page=' . ($page - 1) . '&search=' . urlencode($search) . '&status=' . urlencode($status_filter) . '&date_from=' . urlencode($date_from) . '&date_to=' . urlencode($date_to) . '" 
                       class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">
                        Trước
                    </a>
                    ' : '') . '

                    ' . implode('', array_map(function($i) use ($page, $search, $status_filter, $date_from, $date_to) {
                        $active_class = $i == $page ? 'bg-blue-600 text-white' : 'border border-gray-300 hover:bg-gray-50';
                        return '
                        <a href="?act=admin_orders&page=' . $i . '&search=' . urlencode($search) . '&status=' . urlencode($status_filter) . '&date_from=' . urlencode($date_from) . '&date_to=' . urlencode($date_to) . '" 
                           class="px-3 py-1 rounded text-sm ' . $active_class . '">
                            ' . $i . '
                        </a>
                        ';
                    }, range(max(1, $page - 2), min($total_pages, $page + 2)))) . '

                    ' . ($page < $total_pages ? '
                    <a href="?act=admin_orders&page=' . ($page + 1) . '&search=' . urlencode($search) . '&status=' . urlencode($status_filter) . '&date_from=' . urlencode($date_from) . '&date_to=' . urlencode($date_to) . '" 
                       class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">
                        Sau
                    </a>
                    ' : '') . '
                </div>
            </div>
        </div>
        ' : '') . '
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cập nhật trạng thái đơn hàng</h3>
            <form method="POST" action="/project/index.php?act=admin_orders">
                <input type="hidden" name="order_id" id="modalOrderId">
                <input type="hidden" name="update_status" value="1">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái đơn hàng</label>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="Chờ xác nhận" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-white text-xs font-bold">1</span>
                                    </div>
                                    <span class="font-medium text-gray-900">Chờ xác nhận</span>
                                </div>
                                <p class="text-sm text-gray-500">Đơn hàng đã được đặt và đang chờ xác nhận</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="Đã xác nhận" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-white text-xs font-bold">2</span>
                                    </div>
                                    <span class="font-medium text-gray-900">Đã xác nhận</span>
                                </div>
                                <p class="text-sm text-gray-500">Đơn hàng đã được xác nhận và đang chuẩn bị</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="Đang giao" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-white text-xs font-bold">3</span>
                                    </div>
                                    <span class="font-medium text-gray-900">Đang giao hàng</span>
                                </div>
                                <p class="text-sm text-gray-500">Đơn hàng đang được giao đến địa chỉ của khách hàng</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="Đã giao" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-white text-xs font-bold">4</span>
                                    </div>
                                    <span class="font-medium text-gray-900">Đã giao hàng</span>
                                </div>
                                <p class="text-sm text-gray-500">Đơn hàng đã được giao thành công</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="Đã hủy" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-white text-xs font-bold">X</span>
                                    </div>
                                    <span class="font-medium text-gray-900">Đã hủy</span>
                                </div>
                                <p class="text-sm text-gray-500">Đơn hàng đã bị hủy</p>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái thanh toán</label>
                    <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Chưa thanh toán</option>
                        <option value="Tiền mặt">Đã thanh toán - Tiền mặt</option>
                        <option value="Chuyển khoản">Đã thanh toán - Chuyển khoản</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú (tùy chọn)</label>
                    <textarea name="ghi_chu" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="Nhập ghi chú về việc thay đổi trạng thái..."></textarea>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeStatusModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Order Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Xác nhận xóa đơn hàng</h3>
            <div class="mb-4">
                <div class="flex items-center text-red-600 mb-2">
                    <i class="ri-error-warning-line text-xl mr-2"></i>
                    <span class="font-medium">Cảnh báo</span>
                </div>
                <p class="text-gray-700 mb-2">Bạn có chắc chắn muốn xóa đơn hàng <strong id="deleteOrderId"></strong>?</p>
                <p class="text-sm text-red-600">Hành động này không thể hoàn tác!</p>
            </div>
            
            <form method="POST" action="/project/index.php?act=admin_orders">
                <input type="hidden" name="order_id" id="deleteOrderIdInput">
                <input type="hidden" name="delete_order" value="1">
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Xóa đơn hàng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStatusModal(orderId, currentStatus) {
    document.getElementById("modalOrderId").value = orderId;
    document.getElementById("modalStatus").value = currentStatus;
    document.getElementById("statusModal").classList.remove("hidden");
}

function closeStatusModal() {
    document.getElementById("statusModal").classList.add("hidden");
}

function openDeleteModal(orderId) {
    document.getElementById("deleteOrderId").textContent = "#" + orderId;
    document.getElementById("deleteOrderIdInput").value = orderId;
    document.getElementById("deleteModal").classList.remove("hidden");
}

function closeDeleteModal() {
    document.getElementById("deleteModal").classList.add("hidden");
}

// Close modals when clicking outside
document.getElementById("statusModal").addEventListener("click", function(e) {
    if (e.target === this) {
        closeStatusModal();
    }
});

document.getElementById("deleteModal").addEventListener("click", function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>';

// Include admin layout
include_once __DIR__ . '/../layout/admin_layout.php';
?> 