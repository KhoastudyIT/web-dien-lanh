<?php
require_once __DIR__ . '/../../helpers/jwt_helper.php';

$currentUser = getCurrentUser();
if (!$currentUser || $currentUser['position'] !== 'admin') {
    header('Location: /project/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang admin'));
    exit();
}

$action = $_GET['action'] ?? 'dashboard';

// Include các model cần thiết
include_once __DIR__ . '/../../model/sanpham.php';
include_once __DIR__ . '/../../model/danhmuc.php';
include_once __DIR__ . '/../../model/hang.php';
include_once __DIR__ . '/../../model/user.php';
include_once __DIR__ . '/../../model/donhang.php';

$sanpham = new sanpham();
$danhmuc = new danhmuc();
$hang = new hang();
$user = new user();
$donhang = new DonHang();

$categories = $danhmuc->getAllCategories();
$brands = $hang->getAllBrands();
$message = $_GET['message'] ?? '';
$error = $_GET['error'] ?? '';

// Xử lý thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $mount = $_POST['mount'] ?? 0;
    $sale = $_POST['sale'] ?? 0;
    $describe = $_POST['describe'] ?? '';
    $id_danhmuc = $_POST['id_danhmuc'] ?? 0;
    $id_hang = $_POST['id_hang'] ?? 0;
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = __DIR__ . '/../image/';
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image = $file_name;
        }
    }
    
    if ($sanpham->addProduct($name, $price, $mount, $sale, $describe, $image, $id_danhmuc, $id_hang)) {
        $message = 'Thêm sản phẩm thành công!';
    } else {
        $error = 'Lỗi khi thêm sản phẩm!';
    }
}

// Xử lý xóa sản phẩm
if (isset($_GET['delete_product'])) {
    $id = $_GET['delete_product'];
    if ($sanpham->deleteProduct($id)) {
        $message = 'Xóa sản phẩm thành công!';
    } else {
        $error = 'Lỗi khi xóa sản phẩm!';
    }
}

// Tạo nội dung cho layout
ob_start();
if ($action === 'dashboard'): ?>
    <!-- Dashboard Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="ri-product-hunt-line text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Tổng Sản Phẩm</p>
                    <p class="text-2xl font-bold text-blue-600"><?php echo $sanpham->getTotalProducts(); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                    <i class="ri-shopping-cart-line text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Tổng Đơn Hàng</p>
                    <p class="text-2xl font-bold text-green-600"><?php echo $donhang->getTotalOrders(); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                    <i class="ri-user-line text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Tổng Người Dùng</p>
                    <p class="text-2xl font-bold text-yellow-600"><?php echo $user->getTotalUsers(); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                    <i class="ri-folder-line text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Tổng Danh Mục</p>
                    <p class="text-2xl font-bold text-purple-600"><?php echo $danhmuc->getTotalCategories(); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Sản phẩm mới nhất</h3>
            <div class="space-y-3">
                <?php 
                $latestProducts = $sanpham->getLatestProducts(5);
                foreach ($latestProducts as $product): 
                ?>
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded">
                    <img src="/project/view/image/<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['Name']); ?>" 
                         class="w-12 h-12 object-cover rounded"
                         onerror="this.src='/project/view/image/logodienlanh.png'">
                    <div class="flex-1">
                        <p class="font-medium"><?php echo htmlspecialchars($product['Name']); ?></p>
                        <p class="text-sm text-gray-600"><?php echo number_format($product['Price']); ?> VNĐ</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Đơn hàng gần đây</h3>
            <div class="space-y-3">
                <?php 
                $recentOrders = $donhang->getRecentOrders(5);
                foreach ($recentOrders as $order): 
                ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-medium">Đơn hàng #<?php echo $order['id_dh']; ?></p>
                        <p class="text-sm text-gray-600"><?php echo number_format($order['tong_tien']); ?> VNĐ</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded <?php echo $order['trang_thai'] === 'Đã xác nhận' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                        <?php echo htmlspecialchars($order['trang_thai']); ?>
                    </span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                <?php elseif ($action === 'products'): ?>
                    <!-- Products Management -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <div class="flex justify-between items-center">
                                <h2 class="text-xl font-semibold">Quản lý sản phẩm</h2>
                                <button onclick="openAddProductModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="ri-add-line mr-2"></i>Thêm sản phẩm
                                </button>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình ảnh</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên sản phẩm</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn kho</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php 
                                        $products = $sanpham->getDS_Sanpham();
                                        foreach ($products as $product): 
                                        ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <img src="/project/view/image/<?php echo htmlspecialchars($product['image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($product['Name']); ?>" 
                                                     class="w-12 h-12 object-cover rounded"
                                                     onerror="this.src='/project/view/image/logodienlanh.png'">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($product['Name']); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($product['ten_danhmuc']); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo number_format($product['Price']); ?> VNĐ
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo $product['Mount']; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="editProduct(<?php echo $product['id_sp']; ?>)" class="text-blue-600 hover:text-blue-900 mr-3">Sửa</button>
                                                <button onclick="deleteProduct(<?php echo $product['id_sp']; ?>)" class="text-red-600 hover:text-red-900">Xóa</button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php elseif ($action === 'categories'): ?>
                    <!-- Categories Management -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold">Quản lý danh mục</h2>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php foreach ($categories as $category): ?>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-semibold"><?php echo htmlspecialchars($category['name']); ?></h3>
                                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($category['description'] ?? ''); ?></p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button onclick="editCategory(<?php echo $category['id']; ?>)" class="text-blue-600 hover:text-blue-900">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button onclick="deleteCategory(<?php echo $category['id']; ?>)" class="text-red-600 hover:text-red-900">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                <?php elseif ($action === 'users'): ?>
                    <!-- Users Management -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold">Quản lý người dùng</h2>
                        </div>
                        
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vai trò</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php 
                                        $users = $user->getAllUsers();
                                        foreach ($users as $user_item): 
                                        ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user_item['fullname']); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo htmlspecialchars($user_item['email']); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded <?php echo $user_item['position'] === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                                                    <?php echo htmlspecialchars($user_item['position']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="editUser(<?php echo $user_item['id']; ?>)" class="text-blue-600 hover:text-blue-900 mr-3">Sửa</button>
                                                <?php if ($user_item['id'] != $currentUser['id']): ?>
                                                <button onclick="deleteUser(<?php echo $user_item['id']; ?>)" class="text-red-600 hover:text-red-900">Xóa</button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php elseif ($action === 'reports'): ?>
                    <!-- Reports -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold mb-4">Báo cáo doanh thu</h2>
                        <p class="text-gray-600">Tính năng báo cáo đang được phát triển...</p>
                    </div>

                <?php elseif ($action === 'settings'): ?>
                    <!-- Settings -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold mb-4">Cài đặt hệ thống</h2>
                        <p class="text-gray-600">Tính năng cài đặt đang được phát triển...</p>
                    </div>

                <?php endif; 
$content = ob_get_clean();

// Include admin layout
include_once __DIR__ . '/../layout/admin_layout.php';
?> 