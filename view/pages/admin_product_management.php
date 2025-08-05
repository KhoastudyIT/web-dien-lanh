<?php
include_once __DIR__ . '/../../model/sanpham.php';
include_once __DIR__ . '/../../model/danhmuc.php';
include_once __DIR__ . '/../../model/hang.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';

// Kiểm tra đăng nhập và quyền admin
$currentUser = getCurrentUser();
if (!$currentUser || $currentUser['position'] !== 'admin') {
    header('Location: /project/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang này'));
    exit();
}

$sanpham = new sanpham();
$danhmuc = new danhmuc();
$hang = new hang();
$message = '';
$error = '';

// Xử lý các action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'delete_product':
                    $productId = (int)$_POST['product_id'];
                    if ($sanpham->deleteProduct($productId)) {
                        $message = 'Đã xóa sản phẩm thành công!';
                    } else {
                        $error = 'Không thể xóa sản phẩm!';
                    }
                    break;
                    
                case 'update_stock':
                    $productId = (int)$_POST['product_id'];
                    $quantity = (int)$_POST['quantity'];
                    if ($sanpham->updateStock($productId, $quantity)) {
                        $message = 'Đã cập nhật số lượng tồn kho thành công!';
                    } else {
                        $error = 'Không thể cập nhật số lượng!';
                    }
                    break;
            }
        } catch (Exception $e) {
            $error = 'Có lỗi xảy ra: ' . $e->getMessage();
        }
    }
}

// Lấy các tham số lọc và tìm kiếm
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$brand = $_GET['brand'] ?? '';
$status = $_GET['status'] ?? '';
$sort = $_GET['sort'] ?? 'id_desc';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

// Lấy dữ liệu
$allProducts = $sanpham->getAllProducts($search, $category, $brand, $status, $sort, $offset, $limit);
$totalProducts = $sanpham->getTotalProducts($search, $category, $brand, $status);
$categories = $danhmuc->getAllCategories();
$brands = $hang->getAllBrands();
$totalPages = ceil($totalProducts / $limit);

// Thống kê
$productStats = [
    'total' => $sanpham->getTotalProducts(),
    'in_stock' => $sanpham->getTotalProducts('', '', '', 'in_stock'),
    'out_of_stock' => $sanpham->getTotalProducts('', '', '', 'out_of_stock'),
    'low_stock' => $sanpham->getTotalProducts('', '', '', 'low_stock')
];

$content = '
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                    <i class="ri-store-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Quản lý sản phẩm</h1>
                    <p class="text-gray-600">Quản lý tất cả sản phẩm trong hệ thống</p>
                </div>
            </div>
            <a href="/project/index.php?act=admin_add_product" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-2">
                <i class="ri-add-line"></i>
                Thêm sản phẩm
            </a>
        </div>
    </div>

    <!-- Messages -->
    ' . ($message ? '<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
        <i class="ri-check-line mr-2"></i>
        ' . htmlspecialchars($message) . '
    </div>' : '') . '
    
    ' . ($error ? '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
        <i class="ri-error-warning-line mr-2"></i>
        ' . htmlspecialchars($error) . '
    </div>' : '') . '

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="ri-box-line text-blue-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng sản phẩm</p>
                    <p class="text-2xl font-bold text-gray-900">' . number_format($productStats['total']) . '</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="ri-check-line text-green-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Còn hàng</p>
                    <p class="text-2xl font-bold text-gray-900">' . number_format($productStats['in_stock']) . '</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="ri-close-line text-red-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Hết hàng</p>
                    <p class="text-2xl font-bold text-gray-900">' . number_format($productStats['out_of_stock']) . '</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="ri-alert-line text-yellow-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sắp hết hàng</p>
                    <p class="text-2xl font-bold text-gray-900">' . number_format($productStats['low_stock']) . '</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="space-y-4">
            <input type="hidden" name="act" value="admin_product_management">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                    <input type="text" name="search" value="' . htmlspecialchars($search) . '" 
                           placeholder="Tên sản phẩm, ID..."
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                </div>
                
                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                    <select name="category" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                        <option value="">Tất cả danh mục</option>
                        ' . implode('', array_map(function($cat) use ($category) {
                            $selected = $category == $cat['id'] ? 'selected' : '';
                            return '<option value="' . $cat['id'] . '" ' . $selected . '>' . htmlspecialchars($cat['name']) . '</option>';
                        }, $categories)) . '
                    </select>
                </div>
                
                <!-- Brand Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hãng</label>
                    <select name="brand" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                        <option value="">Tất cả hãng</option>
                        ' . implode('', array_map(function($brand_item) use ($brand) {
                            $selected = $brand == $brand_item['id_hang'] ? 'selected' : '';
                            return '<option value="' . $brand_item['id_hang'] . '" ' . $selected . '>' . htmlspecialchars($brand_item['ten_hang']) . '</option>';
                        }, $brands)) . '
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                    <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                        <option value="">Tất cả</option>
                        <option value="in_stock"' . ($status === 'in_stock' ? ' selected' : '') . '>Còn hàng</option>
                        <option value="out_of_stock"' . ($status === 'out_of_stock' ? ' selected' : '') . '>Hết hàng</option>
                        <option value="low_stock"' . ($status === 'low_stock' ? ' selected' : '') . '>Sắp hết hàng</option>
                    </select>
                </div>
                
                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sắp xếp</label>
                    <select name="sort" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                        <option value="id_desc"' . ($sort === 'id_desc' ? ' selected' : '') . '>Mới nhất</option>
                        <option value="id_asc"' . ($sort === 'id_asc' ? ' selected' : '') . '>Cũ nhất</option>
                        <option value="name_asc"' . ($sort === 'name_asc' ? ' selected' : '') . '>Tên A-Z</option>
                        <option value="name_desc"' . ($sort === 'name_desc' ? ' selected' : '') . '>Tên Z-A</option>
                        <option value="price_asc"' . ($sort === 'price_asc' ? ' selected' : '') . '>Giá tăng</option>
                        <option value="price_desc"' . ($sort === 'price_desc' ? ' selected' : '') . '>Giá giảm</option>
                        <option value="stock_asc"' . ($sort === 'stock_asc' ? ' selected' : '') . '>Tồn kho tăng</option>
                        <option value="stock_desc"' . ($sort === 'stock_desc' ? ' selected' : '') . '>Tồn kho giảm</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-between items-center">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <i class="ri-search-line"></i>
                    Lọc
                </button>
                
                <a href="/project/index.php?act=admin_product_management" class="text-gray-600 hover:text-gray-800 flex items-center gap-2">
                    <i class="ri-refresh-line"></i>
                    Làm mới
                </a>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Danh sách sản phẩm</h2>
            <p class="text-sm text-gray-600">Hiển thị ' . count($allProducts) . ' / ' . number_format($totalProducts) . ' sản phẩm</p>
        </div>
        
        ' . (empty($allProducts) ? '<p class="text-gray-500 text-center py-8">Không tìm thấy sản phẩm nào</p>' : '
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hãng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn kho</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ' . implode('', array_map(function($product) {
                        $statusClass = '';
                        $statusText = '';
                        $statusIcon = '';
                        
                        if ($product['Mount'] == 0) {
                            $statusClass = 'bg-red-100 text-red-800';
                            $statusText = 'Hết hàng';
                            $statusIcon = 'ri-close-circle-line';
                        } elseif ($product['Mount'] <= 5) {
                            $statusClass = 'bg-yellow-100 text-yellow-800';
                            $statusText = 'Sắp hết hàng';
                            $statusIcon = 'ri-alert-line';
                        } else {
                            $statusClass = 'bg-green-100 text-green-800';
                            $statusText = 'Còn hàng';
                            $statusIcon = 'ri-check-line';
                        }
                        
                        return '
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="/project/view/image/' . htmlspecialchars($product['image']) . '" 
                                         alt="' . htmlspecialchars($product['Name']) . '" 
                                         class="w-12 h-12 object-cover rounded-lg shadow border"
                                         onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">' . htmlspecialchars($product['Name']) . '</div>
                                        <div class="text-sm text-gray-500">ID: ' . $product['id_sp'] . '</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">' . htmlspecialchars($product['ten_danhmuc'] ?? 'N/A') . '</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">' . htmlspecialchars($product['ten_hang'] ?? 'N/A') . '</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">' . number_format($product['Price']) . '₫</div>
                                ' . ($product['Sale'] > 0 ? '<div class="text-sm text-red-600">Giảm ' . $product['Sale'] . '%</div>' : '') . '
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">' . $product['Mount'] . ' sản phẩm</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $statusClass . '">
                                    <i class="' . $statusIcon . ' mr-1"></i>
                                    ' . $statusText . '
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="/project/index.php?act=admin_edit_product&id=' . $product['id_sp'] . '" 
                                       class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded-md">
                                        <i class="ri-edit-line mr-1"></i>Sửa
                                    </a>
                                    <button onclick="showStockModal(' . $product['id_sp'] . ', \'' . htmlspecialchars($product['Name']) . '\', ' . $product['Mount'] . ')" 
                                            class="text-green-600 hover:text-green-900 bg-green-50 px-3 py-1 rounded-md">
                                        <i class="ri-stock-line mr-1"></i>Kho
                                    </button>
                                    <button onclick="showDeleteModal(' . $product['id_sp'] . ', \'' . htmlspecialchars($product['Name']) . '\')" 
                                            class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md">
                                        <i class="ri-delete-bin-line mr-1"></i>Xóa
                                    </button>
                                </div>
                            </td>
                        </tr>
                        ';
                    }, $allProducts)) . '
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        ' . ($totalPages > 1 ? '
        <div class="flex items-center justify-between mt-6">
            <div class="text-sm text-gray-700">
                Hiển thị ' . (($page - 1) * $limit + 1) . ' đến ' . min($page * $limit, $totalProducts) . ' trong tổng số ' . number_format($totalProducts) . ' sản phẩm
            </div>
            <div class="flex space-x-2">
                ' . ($page > 1 ? '<a href="?act=admin_product_management&page=' . ($page - 1) . '&search=' . urlencode($search) . '&category=' . urlencode($category) . '&brand=' . urlencode($brand) . '&status=' . urlencode($status) . '&sort=' . urlencode($sort) . '" class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Trước</a>' : '') . '
                
                ' . implode('', array_map(function($p) use ($page, $search, $category, $brand, $status, $sort) {
                    if ($p == $page) {
                        return '<span class="px-3 py-2 border border-blue-500 rounded-md text-sm font-medium text-blue-600 bg-blue-50">' . $p . '</span>';
                    } else {
                        return '<a href="?act=admin_product_management&page=' . $p . '&search=' . urlencode($search) . '&category=' . urlencode($category) . '&brand=' . urlencode($brand) . '&status=' . urlencode($status) . '&sort=' . urlencode($sort) . '" class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">' . $p . '</a>';
                    }
                }, range(max(1, $page - 2), min($totalPages, $page + 2)))) . '
                
                ' . ($page < $totalPages ? '<a href="?act=admin_product_management&page=' . ($page + 1) . '&search=' . urlencode($search) . '&category=' . urlencode($category) . '&brand=' . urlencode($brand) . '&status=' . urlencode($status) . '&sort=' . urlencode($sort) . '" class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Sau</a>' : '') . '
            </div>
        </div>
        ' : '') . '
        ') . '
    </div>
</div>

<!-- Stock Update Modal -->
<div id="stockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cập nhật tồn kho</h3>
            
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="update_stock">
                <input type="hidden" name="product_id" id="stockProductId">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sản phẩm:</label>
                    <p class="text-sm text-gray-900" id="stockProductName"></p>
                </div>
                
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Số lượng tồn kho:</label>
                    <input type="number" name="quantity" id="quantity" min="0" value="0" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideStockModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Product Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Xóa sản phẩm</h3>
            <p class="text-sm text-gray-500 mb-4">Bạn có chắc chắn muốn xóa sản phẩm này? Hành động này không thể hoàn tác.</p>
            
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="delete_product">
                <input type="hidden" name="product_id" id="deleteProductId">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sản phẩm:</label>
                    <p class="text-sm text-gray-900" id="deleteProductName"></p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideDeleteModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Xóa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showStockModal(productId, productName, currentStock) {
    document.getElementById("stockProductId").value = productId;
    document.getElementById("stockProductName").textContent = productName;
    document.getElementById("quantity").value = currentStock;
    document.getElementById("stockModal").classList.remove("hidden");
}

function hideStockModal() {
    document.getElementById("stockModal").classList.add("hidden");
}

function showDeleteModal(productId, productName) {
    document.getElementById("deleteProductId").value = productId;
    document.getElementById("deleteProductName").textContent = productName;
    document.getElementById("deleteModal").classList.remove("hidden");
}

function hideDeleteModal() {
    document.getElementById("deleteModal").classList.add("hidden");
}

// Đóng modal khi click bên ngoài
window.onclick = function(event) {
    const stockModal = document.getElementById("stockModal");
    const deleteModal = document.getElementById("deleteModal");
    
    if (event.target === stockModal) {
        hideStockModal();
    }
    if (event.target === deleteModal) {
        hideDeleteModal();
    }
}
</script>';

// Include admin layout
include_once __DIR__ . '/../layout/admin_layout.php';
?> 