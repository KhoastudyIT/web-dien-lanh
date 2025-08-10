<?php
include_once __DIR__ . '/../../model/donhang.php';
include_once __DIR__ . '/../../model/sanpham.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';

// Kiểm tra quyền admin
$currentUser = getCurrentUser();
if (!$currentUser || $currentUser['position'] !== 'admin') {
    header('Location: /project/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang này'));
    exit();
}

$donhang = new DonHang();
$sanpham = new sanpham();

// Lấy tham số lọc
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');

// Lấy dữ liệu thống kê
$orderStats = $donhang->getOrderStats();
$monthlyRevenue = $donhang->getMonthlyRevenue($year);
$topSellingProducts = $donhang->getTopSellingProducts(10);
$recentOrders = $donhang->getRecentOrders(5);

// Tính toán doanh thu theo tháng
$monthlyData = [];
for ($i = 1; $i <= 12; $i++) {
    $monthlyData[$i] = 0;
}

foreach ($monthlyRevenue as $revenue) {
    $monthlyData[(int)$revenue['month']] = (float)$revenue['total_revenue'];
}

// Tính tổng doanh thu năm
$totalYearRevenue = array_sum($monthlyData);

// Tính doanh thu tháng hiện tại
$currentMonthRevenue = $monthlyData[$month];

// Tính phần trăm tăng trưởng so với tháng trước
$previousMonth = $month == 1 ? 12 : $month - 1;
$previousMonthRevenue = $monthlyData[$previousMonth];
$growthRate = $previousMonthRevenue > 0 ? (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 : 0;

$content = '
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                    <i class="ri-bar-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Báo cáo doanh thu</h1>
                    <p class="text-gray-600">Theo dõi hiệu suất kinh doanh và phân tích dữ liệu</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <select id="yearSelect" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    ' . implode('', array_map(function($y) use ($year) {
                        return "<option value=\"$y\"" . ($y == $year ? ' selected' : '') . ">Năm $y</option>";
                    }, range(date('Y')-5, date('Y')+1))) . '
                </select>
                <select id="monthSelect" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    ' . implode('', array_map(function($m) use ($month) {
                        $monthNames = [
                            1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4',
                            5 => 'Tháng 5', 6 => 'Tháng 6', 7 => 'Tháng 7', 8 => 'Tháng 8',
                            9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12'
                        ];
                        return "<option value=\"$m\"" . ($m == $month ? ' selected' : '') . ">{$monthNames[$m]}</option>";
                    }, range(1, 12))) . '
                </select>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tổng đơn hàng</p>
                    <p class="text-2xl font-bold text-gray-900">' . number_format($orderStats['tong_don_hang']) . '</p>
                    <p class="text-sm text-gray-500">Tất cả thời gian</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="ri-shopping-bag-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Doanh thu tháng</p>
                    <p class="text-2xl font-bold text-gray-900">' . number_format($currentMonthRevenue, 0, ',', '.') . ' ₫</p>
                    <p class="text-sm ' . ($growthRate >= 0 ? 'text-green-500' : 'text-red-500') . '">
                        ' . ($growthRate >= 0 ? '+' : '') . number_format($growthRate, 1) . '% so với tháng trước
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="ri-money-dollar-circle-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Doanh thu năm</p>
                    <p class="text-2xl font-bold text-gray-900">' . number_format($totalYearRevenue, 0, ',', '.') . ' ₫</p>
                    <p class="text-sm text-gray-500">Năm ' . $year . '</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="ri-calendar-line text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Đơn hàng chờ xử lý</p>
                    <p class="text-2xl font-bold text-gray-900">' . number_format($orderStats['cho_xu_ly']) . '</p>
                    <p class="text-sm text-orange-500">Cần xử lý</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="ri-time-line text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Revenue Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Doanh thu theo tháng</h3>
            <div class="h-64">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
        
        <!-- Top Selling Products -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Sản phẩm bán chạy</h3>
            <div class="space-y-3">
                ' . implode('', array_map(function($product, $index) {
                    $rank = $index + 1;
                    $rankClass = $rank <= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800';
                    return '
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold ' . $rankClass . '">
                                ' . $rank . '
                            </span>
                            <div>
                                <p class="font-medium text-gray-800">' . htmlspecialchars($product['product_name']) . '</p>
                                <p class="text-sm text-gray-500">' . number_format($product['total_quantity']) . ' sản phẩm</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">' . number_format($product['total_revenue'], 0, ',', '.') . ' ₫</p>
                        </div>
                    </div>';
                }, $topSellingProducts, array_keys($topSellingProducts))) . '
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Đơn hàng gần đây</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ' . implode('', array_map(function($order) {
                        $statusColors = [
                            'Chờ xác nhận' => 'bg-yellow-100 text-yellow-800',
                            'Đã xác nhận' => 'bg-blue-100 text-blue-800',
                            'Đang giao' => 'bg-purple-100 text-purple-800',
                            'Đã giao' => 'bg-green-100 text-green-800',
                            'Đã hủy' => 'bg-red-100 text-red-800'
                        ];
                        $statusColor = $statusColors[$order['trangthai']] ?? 'bg-gray-100 text-gray-800';
                        
                        return '
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #' . $order['id_dh'] . '
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ' . htmlspecialchars($order['ten_nguoi_nhan']) . '
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ' . number_format($order['tongdh'], 0, ',', '.') . ' ₫
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $statusColor . '">
                                    ' . htmlspecialchars($order['trangthai']) . '
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ' . date('d/m/Y H:i', strtotime($order['ngaydat'])) . '
                            </td>
                        </tr>';
                    }, $recentOrders)) . '
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Monthly Revenue Chart
    const ctx = document.getElementById("monthlyRevenueChart").getContext("2d");
    const monthlyChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: ["T1", "T2", "T3", "T4", "T5", "T6", "T7", "T8", "T9", "T10", "T11", "T12"],
            datasets: [{
                label: "Doanh thu (VNĐ)",
                data: [' . implode(',', $monthlyData) . '],
                borderColor: "rgb(59, 130, 246)",
                backgroundColor: "rgba(59, 130, 246, 0.1)",
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat("vi-VN", {
                                style: "currency",
                                currency: "VND"
                            }).format(value);
                        }
                    }
                }
            }
        }
    });

    // Filter handlers
    document.getElementById("yearSelect").addEventListener("change", function() {
        const year = this.value;
        const month = document.getElementById("monthSelect").value;
        window.location.href = `/project/index.php?act=admin_reports&year=${year}&month=${month}`;
    });

    document.getElementById("monthSelect").addEventListener("change", function() {
        const year = document.getElementById("yearSelect").value;
        const month = this.value;
        window.location.href = `/project/index.php?act=admin_reports&year=${year}&month=${month}`;
    });
});
</script>';

// Include admin layout
include_once __DIR__ . '/../layout/admin_layout.php';
?>
