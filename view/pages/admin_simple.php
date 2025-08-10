<?php
require_once __DIR__ . '/../../helpers/jwt_helper.php';

$currentUser = getCurrentUser();
if (!$currentUser || $currentUser['position'] !== 'admin') {
    header('Location: /project/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang admin'));
    exit();
}

$action = $_GET['action'] ?? 'dashboard';

// Include model đơn hàng để lấy dữ liệu
include_once __DIR__ . '/../../model/donhang.php';
$donhang = new DonHang();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Điện Lạnh KV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-800 text-white">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-lg">A</span>
                    </div>
                    <div>
                        <h3 class="font-semibold"><?php echo htmlspecialchars($currentUser['fullname']); ?></h3>
                        <p class="text-blue-200 text-sm">Chào mừng bạn trở lại</p>
                    </div>
                </div>
                
                <nav class="space-y-2">
                    <a href="?act=admin&action=dashboard" class="flex items-center px-4 py-3 rounded-lg <?php echo ($action === 'dashboard' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700'); ?> transition-colors">
                        <i class="ri-dashboard-line mr-3"></i>Bảng điều khiển
                    </a>
                    <a href="?act=admin&action=products" class="flex items-center px-4 py-3 rounded-lg <?php echo ($action === 'products' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700'); ?> transition-colors">
                        <i class="ri-product-hunt-line mr-3"></i>Quản lý sản phẩm
                    </a>
                    <a href="?act=admin&action=categories" class="flex items-center px-4 py-3 rounded-lg <?php echo ($action === 'categories' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700'); ?> transition-colors">
                        <i class="ri-folder-line mr-3"></i>Quản lý danh mục
                    </a>
                    <a href="/project/index.php?act=admin_orders" class="flex items-center px-4 py-3 rounded-lg text-blue-200 hover:bg-blue-700 transition-colors">
                        <i class="ri-shopping-cart-line mr-3"></i>Quản lý đơn hàng
                    </a>
                    <a href="/project/index.php?act=admin_product_management" class="flex items-center px-4 py-3 rounded-lg text-blue-200 hover:bg-blue-700 transition-colors">
                        <i class="ri-store-line mr-3"></i>Quản lý tồn kho
                    </a>
                    <a href="?act=admin&action=users" class="flex items-center px-4 py-3 rounded-lg <?php echo ($action === 'users' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700'); ?> transition-colors">
                        <i class="ri-user-line mr-3"></i>Quản lý người dùng
                    </a>
                    <a href="?act=admin&action=reports" class="flex items-center px-4 py-3 rounded-lg <?php echo ($action === 'reports' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700'); ?> transition-colors">
                        <i class="ri-bar-chart-line mr-3"></i>Báo cáo doanh thu
                    </a>
                    <a href="?act=admin&action=settings" class="flex items-center px-4 py-3 rounded-lg <?php echo ($action === 'settings' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700'); ?> transition-colors">
                        <i class="ri-settings-line mr-3"></i>Cài đặt hệ thống
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900"><?php echo ucfirst($action); ?></h1>
                        <p class="text-gray-600">Quản lý hệ thống</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm text-gray-600"><?php echo date('d/m/Y - H giờ i phút s giây'); ?></p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="/project/index.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="ri-home-line mr-2"></i>Về Trang Chủ
                            </a>
                            <a href="/project/index.php?act=logout" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">
                                <i class="ri-logout-box-line mr-2"></i>Đăng Xuất
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <?php if ($action === 'dashboard'): ?>
                <!-- Dashboard Content -->
                <div class="space-y-6">
                    <!-- Welcome Section -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">Chào mừng đến với Admin Panel</h2>
                        <p class="text-gray-600 mb-4">Bạn đang ở trang: <strong><?php echo ucfirst($action); ?></strong></p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="ri-product-hunt-line text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-600">Quản lý sản phẩm</p>
                                        <a href="?act=admin&action=products" class="text-blue-600 hover:underline">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                        <i class="ri-shopping-cart-line text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-600">Quản lý đơn hàng</p>
                                        <a href="/project/index.php?act=admin_orders" class="text-green-600 hover:underline">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                                        <i class="ri-store-line text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-600">Quản lý tồn kho</p>
                                        <a href="/project/index.php?act=admin_product_management" class="text-yellow-600 hover:underline">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                                        <i class="ri-user-line text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-600">Quản lý người dùng</p>
                                        <a href="?act=admin&action=users" class="text-purple-600 hover:underline">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-semibold mb-2">Thông tin tài khoản:</h3>
                            <p><strong>Tên:</strong> <?php echo htmlspecialchars($currentUser['fullname']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($currentUser['email']); ?></p>
                            <p><strong>Vai trò:</strong> <?php echo htmlspecialchars($currentUser['position']); ?></p>
                        </div>
                    </div>

                    <!-- Recent Orders Section -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Đơn hàng gần đây</h3>
                            <a href="/project/index.php?act=admin_orders" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Xem tất cả <i class="ri-arrow-right-line ml-1"></i>
                            </a>
                        </div>
                        
                        <div class="space-y-3">
                            <?php 
                            try {
                                $recentOrders = $donhang->getRecentOrders(5);
                                if ($recentOrders && count($recentOrders) > 0):
                                    foreach ($recentOrders as $order): 
                            ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="ri-shopping-bag-line text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Đơn hàng #<?php echo htmlspecialchars($order['id_dh']); ?></p>
                                        <p class="text-sm text-gray-600"><?php echo date('d/m/Y H:i', strtotime($order['ngaydat'])); ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-blue-600"><?php echo number_format($order['tongdh']); ?>₫</p>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php 
                                        echo $order['trangthai'] === 'Đã xác nhận' ? 'bg-green-100 text-green-800' : 
                                            ($order['trangthai'] === 'Đang giao' ? 'bg-purple-100 text-purple-800' : 
                                            ($order['trangthai'] === 'Đã giao' ? 'bg-blue-100 text-blue-800' : 
                                            ($order['trangthai'] === 'Đã hủy' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'))); ?>">
                                        <?php echo htmlspecialchars($order['trangthai']); ?>
                                    </span>
                                </div>
                            </div>
                            <?php 
                                    endforeach;
                                else:
                            ?>
                            <div class="text-center py-8 text-gray-500">
                                <i class="ri-shopping-cart-line text-4xl mb-2"></i>
                                <p>Chưa có đơn hàng nào</p>
                            </div>
                            <?php 
                                endif;
                            } catch (Exception $e) {
                                error_log("Error fetching recent orders: " . $e->getMessage());
                            ?>
                            <div class="text-center py-8 text-red-500">
                                <i class="ri-error-warning-line text-4xl mb-2"></i>
                                <p>Có lỗi xảy ra khi tải dữ liệu đơn hàng</p>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                
                <?php else: ?>
                <!-- Other Actions Content -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Chào mừng đến với Admin Panel</h2>
                    <p class="text-gray-600 mb-4">Bạn đang ở trang: <strong><?php echo ucfirst($action); ?></strong></p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <i class="ri-product-hunt-line text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Quản lý sản phẩm</p>
                                    <a href="?act=admin&action=products" class="text-blue-600 hover:underline">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                    <i class="ri-shopping-cart-line text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Quản lý đơn hàng</p>
                                    <a href="/project/index.php?act=admin_orders" class="text-green-600 hover:underline">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                                    <i class="ri-store-line text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Quản lý tồn kho</p>
                                    <a href="/project/index.php?act=admin_product_management" class="text-yellow-600 hover:underline">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                                    <i class="ri-user-line text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Quản lý người dùng</p>
                                    <a href="?act=admin&action=users" class="text-purple-600 hover:underline">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold mb-2">Thông tin tài khoản:</h3>
                        <p><strong>Tên:</strong> <?php echo htmlspecialchars($currentUser['fullname']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($currentUser['email']); ?></p>
                        <p><strong>Vai trò:</strong> <?php echo htmlspecialchars($currentUser['position']); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</body>
</html> 