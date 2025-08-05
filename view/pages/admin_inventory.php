<?php
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/donhang.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';

// Kiểm tra quyền admin
$currentUser = getCurrentUser();
if (!$currentUser || $currentUser['position'] !== 'admin') {
    header('Location: /project/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang này'));
    exit();
}

$donhang = new DonHang();

// Xử lý cập nhật tồn kho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $productId = $_POST['product_id'];
    $newQuantity = $_POST['new_quantity'];
    
    if ($donhang->updateProductStock($productId, $newQuantity)) {
        $success = 'Cập nhật tồn kho thành công!';
    } else {
        $error = 'Có lỗi xảy ra khi cập nhật tồn kho!';
    }
}

// Lấy dữ liệu tồn kho
$outOfStockProducts = $donhang->getOutOfStockProducts();
$lowStockProducts = $donhang->getLowStockProducts(5);

$content = '
<div class="space-y-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/project/index.php" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary">
                    <i class="ri-home-line mr-2"></i>
                    Trang chủ
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ri-arrow-right-s-line text-gray-400 mx-2"></i>
                    <a href="/project/index.php?act=admin" class="text-sm font-medium text-gray-700 hover:text-primary">Admin</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ri-arrow-right-s-line text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Quản lý tồn kho</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                <i class="ri-store-2-line text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Quản lý tồn kho</h1>
                <p class="text-gray-600">Theo dõi và cập nhật số lượng sản phẩm trong kho</p>
            </div>
        </div>
    </div>

    <!-- Messages -->
    ' . (isset($success) ? '<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
        <i class="ri-check-line mr-2"></i>
        ' . htmlspecialchars($success) . '
    </div>' : '') . '
    
    ' . (isset($error) ? '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
        <i class="ri-error-warning-line mr-2"></i>
        ' . htmlspecialchars($error) . '
    </div>' : '') . '

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                    <i class="ri-error-warning-line text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Hết hàng</h3>
                    <p class="text-2xl font-bold text-red-600">' . count($outOfStockProducts) . '</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center">
                    <i class="ri-alert-line text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Sắp hết hàng</h3>
                    <p class="text-2xl font-bold text-yellow-600">' . count($lowStockProducts) . '</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                    <i class="ri-shopping-cart-line text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Tổng sản phẩm</h3>
                    <p class="text-2xl font-bold text-blue-600">150</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Out of Stock Products -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="ri-error-warning-line text-red-500 mr-2"></i>
                Sản phẩm hết hàng (' . count($outOfStockProducts) . ')
            </h2>
        </div>
        
        <div class="p-6">
            ' . (empty($outOfStockProducts) ? '
            <div class="text-center py-8">
                <i class="ri-check-double-line text-green-500 text-4xl mb-4"></i>
                <p class="text-gray-600">Không có sản phẩm nào hết hàng!</p>
            </div>
            ' : '
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
                                        <img class="h-10 w-10 rounded-lg object-cover" src="/project/view/image/' . $product['image'] . '" alt="' . $product['Name'] . '">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">' . $product['Name'] . '</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . number_format($product['Price']) . ' VNĐ</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Hết hàng
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="openStockModal(' . $product['id_sp'] . ', \'' . $product['Name'] . '\', 0)" 
                                            class="text-blue-600 hover:text-blue-900">Cập nhật tồn kho</button>
                                </td>
                            </tr>';
                        }, $outOfStockProducts)) . '
                    </tbody>
                </table>
            </div>
            ') . '
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="ri-alert-line text-yellow-500 mr-2"></i>
                Sản phẩm sắp hết hàng (' . count($lowStockProducts) . ')
            </h2>
        </div>
        
        <div class="p-6">
            ' . (empty($lowStockProducts) ? '
            <div class="text-center py-8">
                <i class="ri-check-double-line text-green-500 text-4xl mb-4"></i>
                <p class="text-gray-600">Không có sản phẩm nào sắp hết hàng!</p>
            </div>
            ' : '
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
                                        <img class="h-10 w-10 rounded-lg object-cover" src="/project/view/image/' . $product['image'] . '" alt="' . $product['Name'] . '">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">' . $product['Name'] . '</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . number_format($product['Price']) . ' VNĐ</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        ' . $product['Mount'] . ' sản phẩm
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="openStockModal(' . $product['id_sp'] . ', \'' . $product['Name'] . '\', ' . $product['Mount'] . ')" 
                                            class="text-blue-600 hover:text-blue-900">Cập nhật tồn kho</button>
                                </td>
                            </tr>';
                        }, $lowStockProducts)) . '
                    </tbody>
                </table>
            </div>
            ') . '
        </div>
    </div>
</div>

<!-- Stock Update Modal -->
<div id="stockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cập nhật tồn kho</h3>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="product_id" id="modalProductId">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sản phẩm</label>
                    <p id="modalProductName" class="text-sm text-gray-900 bg-gray-50 p-2 rounded"></p>
                </div>
                <div>
                    <label for="new_quantity" class="block text-sm font-medium text-gray-700 mb-2">Số lượng mới</label>
                    <input type="number" id="modalQuantity" name="new_quantity" min="0" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStockModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Hủy
                    </button>
                    <button type="submit" name="update_stock"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStockModal(productId, productName, currentQuantity) {
    document.getElementById("modalProductId").value = productId;
    document.getElementById("modalProductName").textContent = productName;
    document.getElementById("modalQuantity").value = currentQuantity;
    document.getElementById("stockModal").classList.remove("hidden");
}

function closeStockModal() {
    document.getElementById("stockModal").classList.add("hidden");
}

// Đóng modal khi click bên ngoài
document.getElementById("stockModal").addEventListener("click", function(e) {
    if (e.target === this) {
        closeStockModal();
    }
});
</script>';

renderPage("Quản lý tồn kho - Admin", $content);
?> 