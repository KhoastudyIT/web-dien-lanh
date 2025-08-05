<?php
require_once __DIR__ . '/../../helpers/jwt_helper.php';

$currentUser = getCurrentUser();
if (!$currentUser || $currentUser['position'] !== 'admin') {
    header('Location: /project/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang admin'));
    exit();
}

// Lấy action hiện tại để highlight menu
$current_action = $_GET['action'] ?? 'dashboard';
$current_page = $_GET['act'] ?? 'admin';

// Xác định action hiện tại cho các trang riêng biệt
if ($current_page === 'admin_orders') {
    $current_action = 'orders';
} elseif ($current_page === 'admin_product_management') {
    $current_action = 'products';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Điện Lạnh KV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: "#2563eb", secondary: "#3b82f6" },
                },
            },
        };
    </script>
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
                    <a href="?act=admin&action=dashboard" class="flex items-center px-4 py-3 rounded-lg <?php echo ($current_action === 'dashboard' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700'); ?> transition-colors">
                        <i class="ri-dashboard-line mr-3"></i>Bảng điều khiển
                    </a>
                    <a href="/project/index.php?act=admin_product_management" class="flex items-center px-4 py-3 rounded-lg <?php echo ($current_action === 'products' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700'); ?> transition-colors">
                        <i class="ri-product-hunt-line mr-3"></i>Quản lý sản phẩm
                    </a>
                    <a href="?act=admin&action=categories" class="flex items-center px-4 py-3 rounded-lg <?php echo ($current_action === 'categories' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700'); ?> transition-colors">
                        <i class="ri-folder-line mr-3"></i>Quản lý danh mục
                    </a>
                    <a href="/project/index.php?act=admin_orders" class="flex items-center px-4 py-3 rounded-lg <?php echo ($current_action === 'orders' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700'); ?> transition-colors">
                        <i class="ri-shopping-cart-line mr-3"></i>Quản lý đơn hàng
                    </a>
                    <a href="?act=admin&action=users" class="flex items-center px-4 py-3 rounded-lg <?php echo ($current_action === 'users' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700'); ?> transition-colors">
                        <i class="ri-user-line mr-3"></i>Quản lý người dùng
                    </a>
                    <a href="?act=admin&action=reports" class="flex items-center px-4 py-3 rounded-lg <?php echo ($current_action === 'reports' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700'); ?> transition-colors">
                        <i class="ri-bar-chart-line mr-3"></i>Báo cáo doanh thu
                    </a>
                    <a href="?act=admin&action=settings" class="flex items-center px-4 py-3 rounded-lg <?php echo ($current_action === 'settings' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700'); ?> transition-colors">
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
                        <h1 class="text-2xl font-bold text-gray-900"><?php echo ucfirst($current_action); ?></h1>
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
                <?php echo $content ?? ''; ?>
            </main>
        </div>
    </div>
</body>
</html> 