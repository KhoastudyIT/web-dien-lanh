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

// Lấy thông báo từ URL
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Lấy thống kê
$stats = $donhang->getOrderStats();

// Lấy danh sách đơn hàng
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
if ($status_filter) {
    $orders = $donhang->getOrdersByStatus($status_filter);
} else {
    $orders = $donhang->getAllOrders();
}

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

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-wrap items-center gap-4">
            <h3 class="text-lg font-semibold text-gray-800">Lọc theo trạng thái:</h3>
            <a href="/project/index.php?act=admin_orders" 
               class="px-4 py-2 rounded-lg ' . (!$status_filter ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200') . ' transition-colors">
                Tất cả
            </a>
            <a href="/project/index.php?act=admin_orders&status=Chờ xác nhận" 
               class="px-4 py-2 rounded-lg ' . ($status_filter === 'Chờ xác nhận' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200') . ' transition-colors">
                Chờ xác nhận
            </a>
            <a href="/project/index.php?act=admin_orders&status=Đã xác nhận" 
               class="px-4 py-2 rounded-lg ' . ($status_filter === 'Đã xác nhận' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200') . ' transition-colors">
                Đã xác nhận
            </a>
            <a href="/project/index.php?act=admin_orders&status=Đang giao" 
               class="px-4 py-2 rounded-lg ' . ($status_filter === 'Đang giao' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200') . ' transition-colors">
                Đang giao
            </a>
            <a href="/project/index.php?act=admin_orders&status=Đã giao" 
               class="px-4 py-2 rounded-lg ' . ($status_filter === 'Đã giao' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200') . ' transition-colors">
                Đã giao
            </a>
            <a href="/project/index.php?act=admin_orders&status=Đã hủy" 
               class="px-4 py-2 rounded-lg ' . ($status_filter === 'Đã hủy' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200') . ' transition-colors">
                Đã hủy
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

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Danh sách đơn hàng</h2>
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
                            <i class="ri-shopping-bag-line text-4xl mb-4 block"></i>
                            <p>Không có đơn hàng nào</p>
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
                                </div>
                            </td>
                        </tr>
                        ';
                    }, $orders))) . '
                </tbody>
            </table>
        </div>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái mới</label>
                    <select name="status" id="modalStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="Chờ xác nhận">Chờ xác nhận</option>
                        <option value="Đã xác nhận">Đã xác nhận</option>
                        <option value="Đang giao">Đang giao</option>
                        <option value="Đã giao">Đã giao</option>
                        <option value="Đã hủy">Đã hủy</option>
                    </select>
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

<script>
function openStatusModal(orderId, currentStatus) {
    document.getElementById("modalOrderId").value = orderId;
    document.getElementById("modalStatus").value = currentStatus;
    document.getElementById("statusModal").classList.remove("hidden");
}

function closeStatusModal() {
    document.getElementById("statusModal").classList.add("hidden");
}

// Close modal when clicking outside
document.getElementById("statusModal").addEventListener("click", function(e) {
    if (e.target === this) {
        closeStatusModal();
    }
});
</script>';

// Include admin layout
include_once __DIR__ . '/../layout/admin_layout.php';
?> 