<?php
require_once __DIR__ . '/../../helpers/jwt_helper.php';

$currentUser = getCurrentUser();
if (!$currentUser || $currentUser['position'] !== 'admin') {
    header('Location: /project/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang admin'));
    exit();
}

include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/sanpham.php';
include_once __DIR__ . '/../../model/danhmuc.php';
include_once __DIR__ . '/../../model/hang.php';

$sanpham = new sanpham();
$danhmuc = new danhmuc();
$hang = new hang();
$categories = $danhmuc->getAllCategories();
$brands = $hang->getAllBrands();
$action = $_GET['action'] ?? 'dashboard';
$message = $_GET['message'] ?? '';
$error = '';

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
        // Lấy thông tin brand và category để tạo cấu trúc thư mục
        $hang = new hang();
        $danhmuc = new danhmuc();
        
        $brand_info = $hang->getBrandById($id_hang);
        $category_info = $danhmuc->getCategoryById($id_danhmuc);
        
        $brand_name = $brand_info ? $brand_info['ten_hang'] : 'Unknown';
        $category_name = $category_info ? $category_info['name'] : 'Unknown';
        
        // Tạo cấu trúc thư mục theo brand và category
        $base_upload_dir = __DIR__ . '/../image/';
        $brand_dir = 'sanPham' . ucfirst($brand_name) . '/';
        $category_dir = getCategoryFolderName($category_name) . $brand_name . '/';
        
        $upload_dir = $base_upload_dir . $brand_dir . $category_dir;
        
        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            // Lưu đường dẫn tương đối trong database
            $image = $brand_dir . $category_dir . $file_name;
        }
    }
    
    if ($sanpham->addProduct($name, $price, $mount, $sale, $describe, $image, $id_danhmuc, $id_hang)) {
        $message = 'Thêm sản phẩm thành công!';
        // Redirect để tránh form resubmission
        header('Location: ?act=admin&action=products&message=' . urlencode('Thêm sản phẩm thành công!'));
        exit();
    } else {
        $error = 'Lỗi khi thêm sản phẩm!';
    }
}

// Hàm helper để chuyển đổi tên category thành tên thư mục
function getCategoryFolderName($category_name) {
    $category_mapping = [
        'Máy lạnh hộp giấu trần' => 'Maylanhgiautran',
        'Máy lạnh hộp mắc trần' => 'Maylanhamtran',
        'Máy lạnh hộp dưới trần' => 'Maylanhdung',
        'Máy lạnh treo tường' => 'Maylanhtreotuong',
        'Tủ lạnh' => 'TuLanh'
    ];
    
    return $category_mapping[$category_name] ?? 'Other';
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

// Xử lý sửa sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $id = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $mount = $_POST['mount'] ?? 0;
    $sale = $_POST['sale'] ?? 0;
    $describe = $_POST['describe'] ?? '';
    $id_danhmuc = $_POST['id_danhmuc'] ?? 0;
    $id_hang = $_POST['id_hang'] ?? 0;
    
    $image = $_POST['current_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        // Lấy thông tin brand và category để tạo cấu trúc thư mục
        $hang = new hang();
        $danhmuc = new danhmuc();
        
        $brand_info = $hang->getBrandById($id_hang);
        $category_info = $danhmuc->getCategoryById($id_danhmuc);
        
        $brand_name = $brand_info ? $brand_info['ten_hang'] : 'Unknown';
        $category_name = $category_info ? $category_info['name'] : 'Unknown';
        
        // Tạo cấu trúc thư mục theo brand và category
        $base_upload_dir = __DIR__ . '/../image/';
        $brand_dir = 'sanPham' . ucfirst($brand_name) . '/';
        $category_dir = getCategoryFolderName($category_name) . $brand_name . '/';
        
        $upload_dir = $base_upload_dir . $brand_dir . $category_dir;
        
        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            // Lưu đường dẫn tương đối trong database
            $image = $brand_dir . $category_dir . $file_name;
        }
    }
    
    if ($sanpham->updateProduct($id, $name, $price, $mount, $sale, $describe, $image, $id_danhmuc, $id_hang)) {
        $message = 'Cập nhật sản phẩm thành công!';
        // Redirect để tránh form resubmission
        header('Location: ?act=admin&action=products&message=' . urlencode('Cập nhật sản phẩm thành công!'));
        exit();
    } else {
        $error = 'Lỗi khi cập nhật sản phẩm!';
    }
}

// Xử lý xóa nhiều sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_multiple'])) {
    $ids = $_POST['product_ids'] ?? [];
    if (!empty($ids)) {
        $success_count = 0;
        foreach ($ids as $id) {
            if ($sanpham->deleteProduct($id)) {
                $success_count++;
            }
        }
        if ($success_count > 0) {
            $message = "Đã xóa thành công $success_count sản phẩm!";
        } else {
            $error = 'Không thể xóa sản phẩm nào!';
        }
    } else {
        $error = 'Vui lòng chọn sản phẩm cần xóa!';
    }
}

// Xử lý tìm kiếm
$search = $_GET['search'] ?? '';
$category_filter = $_GET['category'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = intval($_GET['per_page'] ?? 10);

// Xử lý thêm danh mục
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = $_POST['category_name'] ?? '';
    if ($danhmuc->addCategory($name)) {
        $message = 'Thêm danh mục thành công!';
    } else {
        $error = 'Lỗi khi thêm danh mục!';
    }
}

// Xử lý xóa danh mục
if (isset($_GET['delete_category'])) {
    $id = $_GET['delete_category'];
    if ($danhmuc->deleteCategory($id)) {
        $message = 'Xóa danh mục thành công!';
    } else {
        $error = 'Lỗi khi xóa danh mục!';
    }
}

$content = '
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-blue-800 text-white">
        <div class="p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-lg">A</span>
                </div>
                <div>
                    <h3 class="font-semibold">' . htmlspecialchars($currentUser['fullname']) . '</h3>
                    <p class="text-blue-200 text-sm">Chào mừng bạn trở lại</p>
                </div>
            </div>
            
            <nav class="space-y-2">
                <a href="?act=admin&action=dashboard" class="flex items-center px-4 py-3 rounded-lg ' . ($action === 'dashboard' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700') . ' transition-colors">
                    <i class="ri-dashboard-line mr-3"></i>Bảng điều khiển
                </a>
                <a href="?act=admin&action=products" class="flex items-center px-4 py-3 rounded-lg ' . ($action === 'products' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700') . ' transition-colors">
                    <i class="ri-product-hunt-line mr-3"></i>Quản lý sản phẩm
                </a>
                <a href="?act=admin&action=categories" class="flex items-center px-4 py-3 rounded-lg ' . ($action === 'categories' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700') . ' transition-colors">
                    <i class="ri-folder-line mr-3"></i>Quản lý danh mục
                </a>
                <a href="/project/index.php?act=admin_orders" class="flex items-center px-4 py-3 rounded-lg text-blue-200 hover:bg-blue-700 transition-colors">
                    <i class="ri-shopping-cart-line mr-3"></i>Quản lý đơn hàng
                </a>
                <a href="?act=admin&action=users" class="flex items-center px-4 py-3 rounded-lg ' . ($action === 'users' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700') . ' transition-colors">
                    <i class="ri-user-line mr-3"></i>Quản lý người dùng
                </a>
                <a href="?act=admin&action=reports" class="flex items-center px-4 py-3 rounded-lg ' . ($action === 'reports' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700') . ' transition-colors">
                    <i class="ri-bar-chart-line mr-3"></i>Báo cáo doanh thu
                </a>
                <a href="?act=admin&action=settings" class="flex items-center px-4 py-3 rounded-lg ' . ($action === 'settings' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700') . ' transition-colors">
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
                    <h1 class="text-2xl font-bold text-gray-900">' . ucfirst($action) . '</h1>
                    <p class="text-gray-600">Quản lý hệ thống</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">' . date('d/m/Y - H giờ i phút s giây') . '</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="/project/index.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="ri-home-line mr-2"></i>Về Trang Chủ
                        </a>
                        <a href="/project/index.php?act=logout" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors" onclick="return confirm(\'Bạn có chắc chắn muốn đăng xuất?\')">
                            <i class="ri-logout-box-line mr-2"></i>Đăng Xuất
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Messages -->
        ' . ($message ? '<div class="mx-6 mt-4"><div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">' . htmlspecialchars($message) . '</div></div>' : '') . '
        ' . ($error ? '<div class="mx-6 mt-4"><div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">' . htmlspecialchars($error) . '</div></div>' : '') . '

        <!-- Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">';

// Dashboard
if ($action === 'dashboard') {
    $total_products = $sanpham->getTotalProducts();
    $total_categories = $danhmuc->getTotalCategories();
    
    $content .= '
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="ri-product-hunt-line text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Tổng Sản Phẩm</p>
                            <p class="text-2xl font-bold text-blue-600">' . $total_products . '</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                            <i class="ri-folder-line text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Tổng Danh Mục</p>
                            <p class="text-2xl font-bold text-green-600">' . $total_categories . '</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                            <i class="ri-shopping-cart-line text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Tổng Đơn Hàng</p>
                            <p class="text-2xl font-bold text-purple-600">0</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                            <i class="ri-user-line text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Người Dùng</p>
                            <p class="text-2xl font-bold text-yellow-600">0</p>
                        </div>
                    </div>
                </div>
            </div>';
}

// Quản lý sản phẩm
elseif ($action === 'products') {
    // Lấy dữ liệu với tìm kiếm và phân trang
    $total_products = $sanpham->getTotalProducts($search, $category_filter);
    $total_pages = ceil($total_products / $per_page);
    $offset = ($page - 1) * $per_page;
    
    $products = $sanpham->getProductsWithPagination($search, $category_filter, $offset, $per_page);
    
    $content .= '
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-900">Danh Sách Sản Phẩm</h2>
                        <div class="flex space-x-2">
                            <button onclick="showAddProductModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="ri-add-line mr-2"></i>Tạo mới sản phẩm
                            </button>
                            <!-- Đã xóa nút tải từ file -->
                            <button onclick="printProducts()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="ri-printer-line mr-2"></i>In dữ liệu
                            </button>
                            <button onclick="copyToClipboard()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="ri-file-copy-line mr-2"></i>Sao chép
                            </button>
                            <button onclick="exportToExcel()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="ri-file-excel-line mr-2"></i>Xuất Excel
                            </button>
                            <button onclick="exportToPDF()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="ri-file-pdf-line mr-2"></i>Xuất PDF
                            </button>
                            <button onclick="deleteSelected()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="ri-delete-bin-line mr-2"></i>Xóa đã chọn
                            </button>
                        </div>
                    </div>
                </div>
                
                                       <form method="GET" action="?act=admin&action=products" class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <select name="per_page" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2">
                                <option value="10" ' . ($per_page == 10 ? 'selected' : '') . '>Hiện 10 sản phẩm</option>
                                <option value="25" ' . ($per_page == 25 ? 'selected' : '') . '>Hiện 25 sản phẩm</option>
                                <option value="50" ' . ($per_page == 50 ? 'selected' : '') . '>Hiện 50 sản phẩm</option>
                                <option value="100" ' . ($per_page == 100 ? 'selected' : '') . '>Hiện 100 sản phẩm</option>
                            </select>
                            <select name="category" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">Tất cả danh mục</option>';
    
    foreach ($categories as $category) {
        $content .= '<option value="' . $category['id'] . '" ' . ($category_filter == $category['id'] ? 'selected' : '') . '>' . htmlspecialchars($category['name']) . '</option>';
    }
    
    $content .= '
                            </select>
                            <div class="flex items-center space-x-2">
                                <label class="text-sm text-gray-600">Tìm kiếm:</label>
                                <input type="text" name="search" value="' . htmlspecialchars($search) . '" placeholder="Nhập tên sản phẩm..." class="border border-gray-300 rounded-lg px-3 py-2 w-64">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="ri-search-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <form method="POST" id="productsForm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Sản phẩm</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Sản Phẩm</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ảnh sản phẩm</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số Lượng</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh Mục</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tính năng</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">';
    
    if (!empty($products)) {
        foreach ($products as $product) {
            $content .= '
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="product_ids[]" value="' . $product['id_sp'] . '" class="product-checkbox rounded border-gray-300">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#' . $product['id_sp'] . '</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">' . htmlspecialchars($product['Name']) . '</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img src="/project/view/image/' . $product['image'] . '" alt="' . htmlspecialchars($product['Name']) . '" class="w-12 h-12 object-cover rounded">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">' . number_format($product['Price']) . ' VNĐ</div>
                                        ' . ($product['Sale'] > 0 ? '<div class="text-xs text-red-600">Giảm ' . $product['Sale'] . '%</div>' : '') . '
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">' . $product['Mount'] . '</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">' . htmlspecialchars($product['ten_danhmuc']) . '</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button type="button" onclick="editProduct(' . $product['id_sp'] . ')" class="bg-orange-500 hover:bg-orange-600 text-white px-2 py-1 rounded text-xs">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button type="button" onclick="deleteProduct(' . $product['id_sp'] . ')" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>';
        }
    } else {
        $content .= '
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                        Không tìm thấy sản phẩm nào
                                    </td>
                                </tr>';
    }
    
    $content .= '
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-700">
                                Hiện ' . ($offset + 1) . ' đến ' . min($offset + $per_page, $total_products) . ' của ' . $total_products . ' sản phẩm
                            </div>
                            <div class="flex space-x-2">';
    
    // Phân trang
    if ($total_pages > 1) {
        if ($page > 1) {
            $content .= '<a href="?act=admin&action=products&page=' . ($page - 1) . '&search=' . urlencode($search) . '&category=' . $category_filter . '&per_page=' . $per_page . '" class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">Lùi</a>';
        }
        
        for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++) {
            $content .= '<a href="?act=admin&action=products&page=' . $i . '&search=' . urlencode($search) . '&category=' . $category_filter . '&per_page=' . $per_page . '" class="px-3 py-1 border border-gray-300 rounded text-sm ' . ($i == $page ? 'bg-blue-600 text-white' : 'hover:bg-gray-50') . '">' . $i . '</a>';
        }
        
        if ($page < $total_pages) {
            $content .= '<a href="?act=admin&action=products&page=' . ($page + 1) . '&search=' . urlencode($search) . '&category=' . $category_filter . '&per_page=' . $per_page . '" class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">Tiếp</a>';
        }
    }
    
    $content .= '
                            </div>
                        </div>
                    </div>
                </form>
            </div>';
}

// Quản lý danh mục
elseif ($action === 'categories') {
    $categories = $danhmuc->getAllCategories();
    
    $content .= '
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-900">Danh Sách Danh Mục</h2>
                        <div class="flex space-x-2">
                            <button onclick="showAddCategoryModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="ri-add-line mr-2"></i>Tạo mới danh mục
                            </button>
                            <!-- Đã xóa nút tải từ file -->
                            <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="ri-printer-line mr-2"></i>In dữ liệu
                            </button>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="ri-file-copy-line mr-2"></i>Sao chép
                            </button>
                            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="ri-file-excel-line mr-2"></i>Xuất Excel
                            </button>
                            <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="ri-file-pdf-line mr-2"></i>Xuất PDF
                            </button>
                            <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="ri-delete-bin-line mr-2"></i>Xóa tất cả
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <select class="border border-gray-300 rounded-lg px-3 py-2">
                                <option>Hiện 10 danh mục</option>
                                <option>Hiện 25 danh mục</option>
                                <option>Hiện 50 danh mục</option>
                                <option>Hiện 100 danh mục</option>
                            </select>
                            <div class="flex items-center space-x-2">
                                <label class="text-sm text-gray-600">Tìm kiếm:</label>
                                <input type="text" placeholder="Nhập từ khóa..." class="border border-gray-300 rounded-lg px-3 py-2 w-64">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" class="rounded border-gray-300">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Danh Mục</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số Sản Phẩm</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tính năng</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">';
    
    foreach ($categories as $category) {
        $product_count = $danhmuc->getProductCount($category['id']);
        $content .= '
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="rounded border-gray-300">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . $category['id'] . '</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">' . htmlspecialchars($category['name']) . '</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">' . $product_count . '</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="editCategory(' . $category['id'] . ')" class="bg-orange-500 hover:bg-orange-600 text-white px-2 py-1 rounded text-xs">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <button onclick="deleteCategory(' . $category['id'] . ')" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>';
    }
    
    $content .= '
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Hiện 1 đến ' . count($categories) . ' của ' . count($categories) . ' danh mục
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border border-gray-300 rounded text-sm">Lùi</button>
                            <button class="px-3 py-1 bg-blue-600 text-white rounded text-sm">1</button>
                            <button class="px-3 py-1 border border-gray-300 rounded text-sm">Tiếp</button>
                        </div>
                    </div>
                </div>
            </div>';
}

// Các trang khác
else {
    $content .= '
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">' . ucfirst($action) . '</h2>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-blue-800">
                        <i class="ri-information-line mr-2"></i>
                        Tính năng này đang được phát triển. Vui lòng quay lại sau!
                    </p>
                </div>
            </div>';
}

$content .= '
        </main>
    </div>
</div>

<!-- Modal Thêm Sản Phẩm -->
<div id="addProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Thêm Sản Phẩm Mới</h3>
                <button onclick="hideAddProductModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <form method="POST" action="?act=admin&action=products" enctype="multipart/form-data" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tên Sản Phẩm *</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giá *</label>
                        <input type="number" name="price" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số Lượng *</label>
                        <input type="number" name="mount" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giảm Giá (%)</label>
                        <input type="number" name="sale" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Danh Mục *</label>
                        <select name="id_danhmuc" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">';
    foreach ($categories as $category) {
        $content .= '<option value="' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</option>';
    }
    $content .= '
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hãng Sản Xuất *</label>
                        <select name="id_hang" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">';
    foreach ($brands as $brand) {
        $content .= '<option value="' . $brand['id_hang'] . '">' . htmlspecialchars($brand['ten_hang']) . '</option>';
    }
    $content .= '
                        </select>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mô Tả</label>
                    <textarea name="describe" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh Sản Phẩm</label>
                    <div class="flex items-center space-x-4">
                        <img id="add_image_preview" src="/project/view/image/logodienlanh.png" alt="Ảnh hiện tại" class="w-16 h-16 object-cover rounded border" onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                        <input type="file" name="image" accept="image/*" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="hideAddProductModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Hủy
                    </button>
                    <button type="submit" name="add_product" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Thêm Sản Phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Upload File -->
<div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Tải Sản Phẩm Từ File</h3>
                <button onclick="hideUploadModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <form id="uploadForm" enctype="multipart/form-data" class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn File CSV</label>
                    <input type="file" name="excel_file" accept=".csv" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Chỉ hỗ trợ file CSV. Kích thước tối đa: 5MB</p>
                </div>
                
                <div class="mb-4">
                    <a href="api/download_template.php" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                        <i class="ri-download-line mr-1"></i>
                        Tải mẫu file CSV
                    </a>
                </div>

                <div id="uploadProgress" class="hidden mb-4">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p id="progressText" class="text-sm text-gray-600 mt-1">Đang tải lên...</p>
                </div>

                <div id="uploadResult" class="hidden mb-4 p-4 rounded-lg">
                    <div id="successResult" class="hidden">
                        <div class="flex items-center text-green-600">
                            <i class="ri-check-line mr-2"></i>
                            <span id="successMessage"></span>
                        </div>
                    </div>
                    <div id="errorResult" class="hidden">
                        <div class="flex items-center text-red-600">
                            <i class="ri-error-warning-line mr-2"></i>
                            <span id="errorMessage"></span>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="hideUploadModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Hủy
                    </button>
                    <button type="submit" id="uploadBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Tải Lên
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Sản Phẩm -->
<div id="editProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Sửa Sản Phẩm</h3>
                <button onclick="hideEditProductModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <form method="POST" action="?act=admin&action=products" enctype="multipart/form-data" class="p-6">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="current_image" id="edit_current_image">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tên Sản Phẩm *</label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giá *</label>
                        <input type="number" name="price" id="edit_price" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số Lượng *</label>
                        <input type="number" name="mount" id="edit_mount" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giảm Giá (%)</label>
                        <input type="number" name="sale" id="edit_sale" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Danh Mục *</label>
                        <select name="id_danhmuc" id="edit_id_danhmuc" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">';
    foreach ($categories as $category) {
        $content .= '<option value="' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</option>';
    }
    $content .= '
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hãng Sản Xuất *</label>
                        <select name="id_hang" id="edit_id_hang" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">';
    foreach ($brands as $brand) {
        $content .= '<option value="' . $brand['id_hang'] . '">' . htmlspecialchars($brand['ten_hang']) . '</option>';
    }
    $content .= '
                        </select>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mô Tả</label>
                    <textarea name="describe" id="edit_describe" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh Sản Phẩm</label>
                    <div class="flex items-center space-x-4">
                        <img id="edit_image_preview" src="/project/view/image/logodienlanh.png" alt="Ảnh hiện tại" class="w-16 h-16 object-cover rounded border" onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                        <input type="file" name="image" accept="image/*" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="hideEditProductModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Hủy
                    </button>
                    <button type="submit" name="edit_product" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                        Cập Nhật Sản Phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Thêm Danh Mục -->
<div id="addCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Thêm Danh Mục Mới</h3>
                <button onclick="hideAddCategoryModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <form method="POST" action="?act=admin&action=categories" class="p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tên Danh Mục *</label>
                    <input type="text" name="category_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="hideAddCategoryModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Hủy
                    </button>
                    <button type="submit" name="add_category" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Thêm Danh Mục
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal functions
function showAddProductModal() {
    document.getElementById("addProductModal").classList.remove("hidden");
}

function hideAddProductModal() {
    document.getElementById("addProductModal").classList.add("hidden");
}

function showEditProductModal() {
    document.getElementById("editProductModal").classList.remove("hidden");
}

function hideEditProductModal() {
    document.getElementById("editProductModal").classList.add("hidden");
}

function showUploadModal() {
    document.getElementById("uploadModal").classList.remove("hidden");
}

function hideUploadModal() {
    document.getElementById("uploadModal").classList.add("hidden");
}

function showAddCategoryModal() {
    document.getElementById("addCategoryModal").classList.remove("hidden");
}

function hideAddCategoryModal() {
    document.getElementById("addCategoryModal").classList.add("hidden");
}

// Product functions
function editProduct(id) {
    // Lấy thông tin sản phẩm bằng AJAX
    fetch(`/project/api/get_product.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const product = data.product;
                document.getElementById("edit_id").value = product.id_sp;
                document.getElementById("edit_name").value = product.Name;
                document.getElementById("edit_price").value = product.Price;
                document.getElementById("edit_mount").value = product.Mount;
                document.getElementById("edit_sale").value = product.Sale;
                document.getElementById("edit_describe").value = product.Decribe;
                document.getElementById("edit_id_danhmuc").value = product.id_danhmuc;
                document.getElementById("edit_id_hang").value = product.id_hang;
                document.getElementById("edit_current_image").value = product.image;
                document.getElementById("edit_image_preview").src = product.image ? `/project/view/image/${product.image}` : \'/project/view/image/logodienlanh.png\';
                showEditProductModal();
            } else {
                alert("Không thể lấy thông tin sản phẩm!");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Có lỗi xảy ra khi lấy thông tin sản phẩm!");
        });
}

function deleteProduct(id) {
    if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
        window.location.href = "?act=admin&action=products&delete_product=" + id;
    }
}

function deleteSelected() {
    const checkboxes = document.querySelectorAll(".product-checkbox:checked");
    if (checkboxes.length === 0) {
        alert("Vui lòng chọn sản phẩm cần xóa!");
        return;
    }
    
    if (confirm(`Bạn có chắc chắn muốn xóa ${checkboxes.length} sản phẩm đã chọn?`)) {
        document.getElementById("productsForm").action = "?act=admin&action=products";
        const form = document.getElementById("productsForm");
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "delete_multiple";
        input.value = "1";
        form.appendChild(input);
        form.submit();
    }
}

// Checkbox functions
document.addEventListener("DOMContentLoaded", function() {
    const selectAllCheckbox = document.getElementById("selectAll");
    const productCheckboxes = document.querySelectorAll(".product-checkbox");
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function() {
            const checkedBoxes = document.querySelectorAll(".product-checkbox:checked");
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedBoxes.length === productCheckboxes.length;
            }
        });
    });
    
    // Setup image preview functionality
    setupImagePreview();
});

// Export functions
function exportToExcel() {
    const search = new URLSearchParams(window.location.search).get("search") || "";
    const category = new URLSearchParams(window.location.search).get("category") || "";
    window.open(`/project/api/export_products.php?format=excel&search=${search}&category=${category}`, "_blank");
}

function exportToPDF() {
    const search = new URLSearchParams(window.location.search).get("search") || "";
    const category = new URLSearchParams(window.location.search).get("category") || "";
    window.open(`/project/api/export_products.php?format=pdf&search=${search}&category=${category}`, "_blank");
}

function printProducts() {
    window.print();
}

// Upload functions
document.addEventListener("DOMContentLoaded", function() {
    const uploadForm = document.getElementById("uploadForm");
    if (uploadForm) {
        uploadForm.addEventListener("submit", function(e) {
            e.preventDefault();
            uploadFile();
        });
    }
});

function uploadFile() {
    const formData = new FormData(document.getElementById("uploadForm"));
    const uploadBtn = document.getElementById("uploadBtn");
    const progressDiv = document.getElementById("uploadProgress");
    const progressBar = document.getElementById("progressBar");
    const progressText = document.getElementById("progressText");
    const resultDiv = document.getElementById("uploadResult");
    const successDiv = document.getElementById("successResult");
    const errorDiv = document.getElementById("errorResult");
    const successMessage = document.getElementById("successMessage");
    const errorMessage = document.getElementById("errorMessage");

    // Reset UI
    uploadBtn.disabled = true;
    uploadBtn.textContent = "Đang tải lên...";
    progressDiv.classList.remove("hidden");
    resultDiv.classList.add("hidden");
    progressBar.style.width = "0%";
    progressText.textContent = "Đang tải lên...";

    // Simulate progress
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 30;
        if (progress > 90) progress = 90;
        progressBar.style.width = progress + "%";
    }, 200);

    fetch("/project/api/upload_products.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        clearInterval(progressInterval);
        progressBar.style.width = "100%";
        progressText.textContent = "Hoàn thành!";

        if (data.success) {
            successMessage.textContent = data.message;
            successDiv.classList.remove("hidden");
            errorDiv.classList.add("hidden");
            
            // Reload page after 2 seconds to show new products
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            errorMessage.textContent = data.message;
            errorDiv.classList.remove("hidden");
            successDiv.classList.add("hidden");
        }
        
        resultDiv.classList.remove("hidden");
        uploadBtn.disabled = false;
        uploadBtn.textContent = "Tải Lên";
    })
    .catch(error => {
        clearInterval(progressInterval);
        console.error("Error:", error);
        errorMessage.textContent = "Có lỗi xảy ra khi tải lên file!";
        errorDiv.classList.remove("hidden");
        successDiv.classList.add("hidden");
        resultDiv.classList.remove("hidden");
        uploadBtn.disabled = false;
        uploadBtn.textContent = "Tải Lên";
    });
}

function copyToClipboard() {
    const table = document.querySelector("table");
    const range = document.createRange();
    range.selectNode(table);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);
    document.execCommand("copy");
    window.getSelection().removeAllRanges();
    alert("Đã sao chép dữ liệu vào clipboard!");
}

// Image preview functions
function setupImagePreview() {
    const addImageInput = document.querySelector(\'#addProductModal input[name="image"]\');
    const addImagePreview = document.getElementById(\'add_image_preview\');
    
    if (addImageInput && addImagePreview) {
        addImageInput.addEventListener(\'change\', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    addImagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    const editImageInput = document.querySelector(\'#editProductModal input[name="image"]\');
    const editImagePreview = document.getElementById(\'edit_image_preview\');
    
    if (editImageInput && editImagePreview) {
        editImageInput.addEventListener(\'change\', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    editImagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

// Category functions
function deleteCategory(id) {
    if (confirm("Bạn có chắc chắn muốn xóa danh mục này?")) {
        window.location.href = "?act=admin&action=categories&delete_category=" + id;
    }
}

function editCategory(id) {
    alert("Tính năng sửa danh mục đang được phát triển!");
}
</script>';

renderPage("Quản Lý Admin - Điện Lạnh KV", $content);
?>  