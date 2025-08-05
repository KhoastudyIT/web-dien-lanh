<?php
require_once __DIR__ . '/../../helpers/jwt_helper.php';

$currentUser = getCurrentUser();
if (!$currentUser || $currentUser['position'] !== 'admin') {
    header('Location: /project/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang này'));
    exit();
}

include_once __DIR__ . '/../../model/danhmuc.php';

$danhmuc = new danhmuc();

// Xử lý POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $name = trim($_POST['name'] ?? '');
        if (!empty($name)) {
            if ($danhmuc->addCategory($name)) {
                $success_message = 'Thêm danh mục thành công!';
            } else {
                $error_message = 'Lỗi khi thêm danh mục!';
            }
        } else {
            $error_message = 'Tên danh mục không được để trống!';
        }
    } elseif (isset($_POST['update_category'])) {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        if ($id > 0 && !empty($name)) {
            if ($danhmuc->updateCategory($id, $name)) {
                $success_message = 'Cập nhật danh mục thành công!';
            } else {
                $error_message = 'Lỗi khi cập nhật danh mục!';
            }
        } else {
            $error_message = 'Dữ liệu không hợp lệ!';
        }
    } elseif (isset($_POST['delete_category'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            // Kiểm tra xem danh mục có sản phẩm nào không
            $product_count = $danhmuc->getProductCount($id);
            if ($product_count > 0) {
                $error_message = 'Không thể xóa danh mục này vì có ' . $product_count . ' sản phẩm đang sử dụng!';
            } else {
                if ($danhmuc->deleteCategory($id)) {
                    $success_message = 'Xóa danh mục thành công!';
                } else {
                    $error_message = 'Lỗi khi xóa danh mục!';
                }
            }
        } else {
            $error_message = 'ID danh mục không hợp lệ!';
        }
    }
}

// Lấy tham số tìm kiếm và phân trang
$search = trim($_GET['search'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Lấy danh sách danh mục với tìm kiếm và phân trang
$categories = $danhmuc->getAllCategories();
$total_categories = count($categories);

// Lọc theo tìm kiếm
if (!empty($search)) {
    $categories = array_filter($categories, function($category) use ($search) {
        return stripos($category['name'], $search) !== false || 
               stripos($category['id'], $search) !== false;
    });
    $total_categories = count($categories);
}

// Phân trang
$total_pages = ceil($total_categories / $per_page);
$categories = array_slice($categories, $offset, $per_page);

// Thống kê
$total_all = $danhmuc->getTotalCategories();
$total_with_products = 0;
$total_empty = 0;

$all_categories = $danhmuc->getAllCategories();
foreach ($all_categories as $cat) {
    $product_count = $danhmuc->getProductCount($cat['id']);
    if ($product_count > 0) {
        $total_with_products++;
    } else {
        $total_empty++;
    }
}

// Xác định action hiện tại để highlight menu
$current_action = 'categories';
$current_page = 'admin_category_management';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Quản lý Danh mục</title>
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
                    <a href="?act=admin&action=dashboard" class="flex items-center px-4 py-3 rounded-lg text-blue-200 hover:bg-blue-700 transition-colors">
                        <i class="ri-dashboard-line mr-3"></i>Bảng điều khiển
                    </a>
                    <a href="/project/index.php?act=admin_product_management" class="flex items-center px-4 py-3 rounded-lg text-blue-200 hover:bg-blue-700 transition-colors">
                        <i class="ri-product-hunt-line mr-3"></i>Quản lý sản phẩm
                    </a>
                    <a href="/project/index.php?act=admin_category_management" class="flex items-center px-4 py-3 rounded-lg bg-blue-700 text-white transition-colors">
                        <i class="ri-folder-line mr-3"></i>Quản lý danh mục
                    </a>
                    <a href="/project/index.php?act=admin_orders" class="flex items-center px-4 py-3 rounded-lg text-blue-200 hover:bg-blue-700 transition-colors">
                        <i class="ri-shopping-cart-line mr-3"></i>Quản lý đơn hàng
                    </a>
                    <a href="?act=admin&action=users" class="flex items-center px-4 py-3 rounded-lg text-blue-200 hover:bg-blue-700 transition-colors">
                        <i class="ri-user-line mr-3"></i>Quản lý người dùng
                    </a>
                    <a href="?act=admin&action=reports" class="flex items-center px-4 py-3 rounded-lg text-blue-200 hover:bg-blue-700 transition-colors">
                        <i class="ri-bar-chart-line mr-3"></i>Báo cáo doanh thu
                    </a>
                    <a href="?act=admin&action=settings" class="flex items-center px-4 py-3 rounded-lg text-blue-200 hover:bg-blue-700 transition-colors">
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
                        <h1 class="text-2xl font-bold text-gray-900">Quản lý Danh mục</h1>
                        <p class="text-gray-600">Quản lý hệ thống</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm text-gray-600"><?php echo date('d/m/Y - H:i:s'); ?></p>
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
                <div class="container mx-auto px-4 py-8">
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Quản lý Danh mục</h1>
                        <p class="text-gray-600">Quản lý các danh mục sản phẩm trong hệ thống</p>
                    </div>

                    <!-- Thống kê -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                    <i class="ri-folder-line text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Tổng danh mục</p>
                                    <p class="text-2xl font-semibold text-gray-900"><?php echo $total_all; ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600">
                                    <i class="ri-shopping-bag-line text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Có sản phẩm</p>
                                    <p class="text-2xl font-semibold text-gray-900"><?php echo $total_with_products; ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                    <i class="ri-folder-open-line text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Trống</p>
                                    <p class="text-2xl font-semibold text-gray-900"><?php echo $total_empty; ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                    <i class="ri-search-line text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Kết quả tìm kiếm</p>
                                    <p class="text-2xl font-semibold text-gray-900"><?php echo $total_categories; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông báo -->
                    <?php if (isset($success_message)): ?>
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <i class="ri-check-line mr-2"></i>
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <i class="ri-error-warning-line mr-2"></i>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Tìm kiếm và Thêm mới -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex-1">
                                <form method="GET" class="flex gap-4">
                                    <input type="hidden" name="act" value="admin_category_management">
                                    
                                    <div class="flex-1">
                                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                                               placeholder="Tìm kiếm theo tên danh mục hoặc ID..."
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    
                                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-2">
                                        <i class="ri-search-line"></i>
                                        Tìm kiếm
                                    </button>
                                    
                                    <?php if (!empty($search)): ?>
                                    <a href="?act=admin_category_management" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 flex items-center gap-2">
                                        <i class="ri-refresh-line"></i>
                                        Làm mới
                                    </a>
                                    <?php endif; ?>
                                </form>
                            </div>
                            
                            <button onclick="showAddCategoryModal()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2">
                                <i class="ri-add-line"></i>
                                Thêm danh mục
                            </button>
                        </div>
                    </div>

                    <!-- Bảng danh mục -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Danh sách danh mục</h2>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên danh mục</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số sản phẩm</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <i class="ri-folder-open-line text-4xl mb-2"></i>
                                                <p class="text-lg font-medium">Không tìm thấy danh mục nào</p>
                                                <p class="text-sm"><?php echo !empty($search) ? 'Thử thay đổi từ khóa tìm kiếm' : 'Hãy thêm danh mục đầu tiên'; ?></p>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($categories as $category): ?>
                                            <?php 
                                            $product_count = $danhmuc->getProductCount($category['id']);
                                            $status_class = $product_count > 0 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                                            $status_text = $product_count > 0 ? 'Có sản phẩm' : 'Trống';
                                            ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $category['id']; ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($category['name']); ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $product_count; ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $status_class; ?>">
                                                        <?php echo $status_text; ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <button onclick="showEditCategoryModal(<?php echo $category['id']; ?>, '<?php echo htmlspecialchars($category['name'], ENT_QUOTES); ?>')" 
                                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs flex items-center gap-1">
                                                            <i class="ri-edit-line"></i>
                                                            Sửa
                                                        </button>
                                                        <button onclick="showDeleteCategoryModal(<?php echo $category['id']; ?>, '<?php echo htmlspecialchars($category['name'], ENT_QUOTES); ?>', <?php echo $product_count; ?>)" 
                                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs flex items-center gap-1">
                                                            <i class="ri-delete-bin-line"></i>
                                                            Xóa
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Phân trang -->
                        <?php if ($total_pages > 1): ?>
                        <div class="px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Hiển thị <?php echo ($offset + 1); ?> đến <?php echo min($offset + $per_page, $total_categories); ?> của <?php echo $total_categories; ?> danh mục
                                </div>
                                <div class="flex space-x-2">
                                    <?php if ($page > 1): ?>
                                    <a href="?act=admin_category_management&page=<?php echo ($page - 1); ?>&search=<?php echo urlencode($search); ?>" 
                                       class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">
                                        Trước
                                    </a>
                                    <?php endif; ?>

                                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                        <?php $active_class = $i == $page ? 'bg-blue-600 text-white' : 'border border-gray-300 hover:bg-gray-50'; ?>
                                        <a href="?act=admin_category_management&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" 
                                           class="px-3 py-1 rounded text-sm <?php echo $active_class; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>

                                    <?php if ($page < $total_pages): ?>
                                    <a href="?act=admin_category_management&page=<?php echo ($page + 1); ?>&search=<?php echo urlencode($search); ?>" 
                                       class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">
                                        Sau
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
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
                
                <form method="POST" class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tên danh mục *</label>
                        <input type="text" name="name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Nhập tên danh mục...">
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="hideAddCategoryModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Hủy
                        </button>
                        <button type="submit" name="add_category" 
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Thêm danh mục
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Danh Mục -->
    <div id="editCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Sửa Danh Mục</h3>
                    <button onclick="hideEditCategoryModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
                
                <form method="POST" class="p-6">
                    <input type="hidden" name="id" id="edit_category_id">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tên danh mục *</label>
                        <input type="text" name="name" id="edit_category_name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Nhập tên danh mục...">
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="hideEditCategoryModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Hủy
                        </button>
                        <button type="submit" name="update_category" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Xóa Danh Mục -->
    <div id="deleteCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Xác nhận xóa</h3>
                    <button onclick="hideDeleteCategoryModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="mb-4">
                        <div class="flex items-center text-red-600 mb-2">
                            <i class="ri-error-warning-line text-xl mr-2"></i>
                            <span class="font-medium">Cảnh báo</span>
                        </div>
                        <p class="text-gray-700 mb-2">Bạn có chắc chắn muốn xóa danh mục <strong id="delete_category_name"></strong>?</p>
                        <p class="text-sm text-gray-600" id="delete_category_warning"></p>
                    </div>
                    
                    <form method="POST" class="flex justify-end space-x-4">
                        <input type="hidden" name="id" id="delete_category_id">
                        <button type="button" onclick="hideDeleteCategoryModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Hủy
                        </button>
                        <button type="submit" name="delete_category" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Xóa danh mục
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Modal functions
    function showAddCategoryModal() {
        document.getElementById("addCategoryModal").classList.remove("hidden");
    }

    function hideAddCategoryModal() {
        document.getElementById("addCategoryModal").classList.add("hidden");
    }

    function showEditCategoryModal(id, name) {
        document.getElementById("edit_category_id").value = id;
        document.getElementById("edit_category_name").value = name;
        document.getElementById("editCategoryModal").classList.remove("hidden");
    }

    function hideEditCategoryModal() {
        document.getElementById("editCategoryModal").classList.add("hidden");
    }

    function showDeleteCategoryModal(id, name, productCount) {
        document.getElementById("delete_category_id").value = id;
        document.getElementById("delete_category_name").textContent = name;
        
        if (productCount > 0) {
            document.getElementById("delete_category_warning").textContent = 
                "Danh mục này có " + productCount + " sản phẩm. Việc xóa có thể ảnh hưởng đến dữ liệu sản phẩm.";
            document.getElementById("delete_category_warning").className = "text-sm text-red-600 font-medium";
        } else {
            document.getElementById("delete_category_warning").textContent = 
                "Danh mục này không có sản phẩm nào.";
            document.getElementById("delete_category_warning").className = "text-sm text-gray-600";
        }
        
        document.getElementById("deleteCategoryModal").classList.remove("hidden");
    }

    function hideDeleteCategoryModal() {
        document.getElementById("deleteCategoryModal").classList.add("hidden");
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const addModal = document.getElementById("addCategoryModal");
        const editModal = document.getElementById("editCategoryModal");
        const deleteModal = document.getElementById("deleteCategoryModal");
        
        if (event.target === addModal) {
            hideAddCategoryModal();
        }
        if (event.target === editModal) {
            hideEditCategoryModal();
        }
        if (event.target === deleteModal) {
            hideDeleteCategoryModal();
        }
    }
    </script>
</body>
</html> 