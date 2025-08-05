<?php
include_once __DIR__ . '/../../model/donhang.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';

// Kiểm tra đăng nhập và quyền admin
$currentUser = getCurrentUser();
if (!$currentUser || $currentUser['position'] !== 'admin') {
    header('Location: /project/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang này'));
    exit();
}

$donhang = new DonHang();
$message = '';
$error = '';

// Xử lý các action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'restore_product':
                    $productId = (int)$_POST['product_id'];
                    $quantity = (int)$_POST['quantity'];
                    if ($donhang->restoreProductToStore($productId, $quantity)) {
                        $message = 'Đã khôi phục sản phẩm về cửa hàng thành công!';
                    }
                    break;
                    
                case 'remove_product':
                    $productId = (int)$_POST['product_id'];
                    if ($donhang->permanentlyRemoveProduct($productId)) {
                        $message = 'Đã xóa sản phẩm khỏi cửa hàng thành công!';
                    }
                    break;
            }
        } catch (Exception $e) {
            $error = 'Có lỗi xảy ra: ' . $e->getMessage();
        }
    }
}

// Lấy dữ liệu
$outOfStockProducts = $donhang->getOutOfStockProducts();
$lowStockProducts = $donhang->getLowStockProducts();
$productStats = $donhang->getProductStatusStats();

$content = '
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="ri-store-line text-white text-xl"></i>
                    </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Quản lý sản phẩm</h1>
                <p class="text-gray-600">Theo dõi và quản lý trạng thái sản phẩm sau khi thanh toán</p>
            </div>
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
                    <p class="text-2xl font-bold text-gray-900">' . $productStats['tong_san_pham'] . '</p>
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
                    <p class="text-2xl font-bold text-gray-900">' . $productStats['con_hang'] . '</p>
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
                    <p class="text-2xl font-bold text-gray-900">' . $productStats['het_hang'] . '</p>
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
                    <p class="text-2xl font-bold text-gray-900">' . $productStats['sap_het_hang'] . '</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm hết hàng -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
            <i class="ri-close-circle-line text-red-500 mr-2"></i>
            Sản phẩm hết hàng (' . count($outOfStockProducts) . ')
        </h2>
        
        ' . (empty($outOfStockProducts) ? '<p class="text-gray-500 text-center py-8">Không có sản phẩm nào hết hàng</p>' : '
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn kho</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ' . implode('', array_map(function($product) {
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
                                <div class="text-sm text-gray-900">' . number_format($product['Price']) . '₫</div>
                                ' . ($product['Price_old'] > $product['Price'] ? '<div class="text-sm text-gray-500 line-through">' . number_format($product['Price_old']) . '₫</div>' : '') . '
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Hết hàng
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="showRestoreModal(' . $product['id_sp'] . ', \'' . htmlspecialchars($product['Name']) . '\')" 
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded-md">
                                        <i class="ri-refresh-line mr-1"></i>Khôi phục
                                    </button>
                                    <button onclick="showRemoveModal(' . $product['id_sp'] . ', \'' . htmlspecialchars($product['Name']) . '\')" 
                                            class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md">
                                        <i class="ri-delete-bin-line mr-1"></i>Xóa
                                    </button>
                                </div>
                            </td>
                        </tr>
                        ';
                    }, $outOfStockProducts)) . '
                </tbody>
            </table>
        </div>
        ') . '
    </div>

    <!-- Sản phẩm sắp hết hàng -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
            <i class="ri-alert-line text-yellow-500 mr-2"></i>
            Sản phẩm sắp hết hàng (' . count($lowStockProducts) . ')
        </h2>
        
        ' . (empty($lowStockProducts) ? '<p class="text-gray-500 text-center py-8">Không có sản phẩm nào sắp hết hàng</p>' : '
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn kho</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ' . implode('', array_map(function($product) {
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
                                <div class="text-sm text-gray-900">' . number_format($product['Price']) . '₫</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">' . $product['Mount'] . ' sản phẩm</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Sắp hết hàng
                                </span>
                            </td>
                        </tr>
                        ';
                    }, $lowStockProducts)) . '
                </tbody>
            </table>
        </div>
        ') . '
    </div>
</div>

<!-- Restore Product Modal -->
<div id="restoreModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Khôi phục sản phẩm</h3>
            <p class="text-sm text-gray-500 mb-4">Nhập số lượng muốn thêm vào kho:</p>
            
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="restore_product">
                <input type="hidden" name="product_id" id="restoreProductId">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sản phẩm:</label>
                    <p class="text-sm text-gray-900" id="restoreProductName"></p>
                </div>
                
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Số lượng:</label>
                    <input type="number" name="quantity" id="quantity" min="1" value="1" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideRestoreModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Khôi phục
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove Product Modal -->
<div id="removeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Xóa sản phẩm</h3>
            <p class="text-sm text-gray-500 mb-4">Bạn có chắc chắn muốn xóa vĩnh viễn sản phẩm này khỏi cửa hàng?</p>
            
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="remove_product">
                <input type="hidden" name="product_id" id="removeProductId">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sản phẩm:</label>
                    <p class="text-sm text-gray-900" id="removeProductName"></p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideRemoveModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Xóa vĩnh viễn
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRestoreModal(productId, productName) {
    document.getElementById("restoreProductId").value = productId;
    document.getElementById("restoreProductName").textContent = productName;
    document.getElementById("restoreModal").classList.remove("hidden");
}

function hideRestoreModal() {
    document.getElementById("restoreModal").classList.add("hidden");
}

function showRemoveModal(productId, productName) {
    document.getElementById("removeProductId").value = productId;
    document.getElementById("removeProductName").textContent = productName;
    document.getElementById("removeModal").classList.remove("hidden");
}

function hideRemoveModal() {
    document.getElementById("removeModal").classList.add("hidden");
}

// Đóng modal khi click bên ngoài
window.onclick = function(event) {
    const restoreModal = document.getElementById("restoreModal");
    const removeModal = document.getElementById("removeModal");
    
    if (event.target === restoreModal) {
        hideRestoreModal();
    }
    if (event.target === removeModal) {
        hideRemoveModal();
    }
}
</script>';

// Include admin layout
include_once __DIR__ . '/../layout/admin_layout.php';
?> 