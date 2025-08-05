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

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$product_id) {
    header('Location: /project/index.php?act=admin_product_management&error=' . urlencode('Không tìm thấy sản phẩm'));
    exit();
}

// Lấy thông tin sản phẩm
$product = $sanpham->getProductById($product_id);
if (!$product) {
    header('Location: /project/index.php?act=admin_product_management&error=' . urlencode('Không tìm thấy sản phẩm'));
    exit();
}

// Xử lý cập nhật sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = trim($_POST['name']);
        $price = (float)$_POST['price'];
        $mount = (int)$_POST['mount'];
        $sale = (int)$_POST['sale'];
        $describe = trim($_POST['describe']);
        $id_danhmuc = (int)$_POST['id_danhmuc'];
        $id_hang = (int)$_POST['id_hang'];
        
        // Validation
        if (empty($name)) {
            throw new Exception('Tên sản phẩm không được để trống');
        }
        
        if ($price <= 0) {
            throw new Exception('Giá sản phẩm phải lớn hơn 0');
        }
        
        if ($mount < 0) {
            throw new Exception('Số lượng tồn kho không được âm');
        }
        
        if ($sale < 0 || $sale > 100) {
            throw new Exception('Phần trăm giảm giá phải từ 0-100%');
        }
        
        // Xử lý upload ảnh mới
        $image = $product['image']; // Giữ ảnh cũ nếu không upload mới
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../../uploads/products/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($file_extension, $allowed_extensions)) {
                throw new Exception('Chỉ chấp nhận file ảnh: JPG, JPEG, PNG, GIF');
            }
            
            $file_name = uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Xóa ảnh cũ nếu có
                if (!empty($product['image']) && file_exists(__DIR__ . '/../../' . $product['image'])) {
                    unlink(__DIR__ . '/../../' . $product['image']);
                }
                $image = 'uploads/products/' . $file_name;
            } else {
                throw new Exception('Không thể upload file ảnh');
            }
        }
        
        // Cập nhật sản phẩm
        if ($sanpham->updateProduct($product_id, $name, $price, $mount, $sale, $describe, $image, $id_danhmuc, $id_hang)) {
            $message = 'Cập nhật sản phẩm thành công!';
            // Cập nhật lại thông tin sản phẩm
            $product = $sanpham->getProductById($product_id);
        } else {
            $error = 'Không thể cập nhật sản phẩm!';
        }
        
    } catch (Exception $e) {
        $error = 'Có lỗi xảy ra: ' . $e->getMessage();
    }
}

// Lấy dữ liệu cho form
$categories = $danhmuc->getAllCategories();
$brands = $hang->getAllBrands();

$content = '
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                    <i class="ri-edit-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Sửa sản phẩm</h1>
                    <p class="text-gray-600">Cập nhật thông tin sản phẩm</p>
                </div>
            </div>
            <a href="/project/index.php?act=admin_product_management" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 flex items-center gap-2">
                <i class="ri-arrow-left-line"></i>
                Quay lại
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

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tên sản phẩm -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên sản phẩm *</label>
                    <input type="text" name="name" id="name" required
                           value="' . htmlspecialchars($product['Name']) . '"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                           placeholder="Nhập tên sản phẩm">
                </div>
                
                <!-- Giá -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Giá (VNĐ) *</label>
                    <input type="number" name="price" id="price" min="0" step="1000" required
                           value="' . $product['Price'] . '"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                           placeholder="0">
                </div>
                
                <!-- Số lượng tồn kho -->
                <div>
                    <label for="mount" class="block text-sm font-medium text-gray-700 mb-2">Số lượng tồn kho *</label>
                    <input type="number" name="mount" id="mount" min="0" required
                           value="' . $product['Mount'] . '"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                           placeholder="0">
                </div>
                
                <!-- Phần trăm giảm giá -->
                <div>
                    <label for="sale" class="block text-sm font-medium text-gray-700 mb-2">Phần trăm giảm giá (%)</label>
                    <input type="number" name="sale" id="sale" min="0" max="100"
                           value="' . $product['Sale'] . '"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                           placeholder="0">
                </div>
                
                <!-- Danh mục -->
                <div>
                    <label for="id_danhmuc" class="block text-sm font-medium text-gray-700 mb-2">Danh mục *</label>
                    <select name="id_danhmuc" id="id_danhmuc" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                        <option value="">Chọn danh mục</option>
                        ' . implode('', array_map(function($cat) use ($product) {
                            $selected = $cat['id'] == $product['id_danhmuc'] ? 'selected' : '';
                            return '<option value="' . $cat['id'] . '" ' . $selected . '>' . htmlspecialchars($cat['name']) . '</option>';
                        }, $categories)) . '
                    </select>
                </div>
                
                <!-- Hãng -->
                <div>
                    <label for="id_hang" class="block text-sm font-medium text-gray-700 mb-2">Hãng *</label>
                    <select name="id_hang" id="id_hang" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                        <option value="">Chọn hãng</option>
                        ' . implode('', array_map(function($brand) use ($product) {
                            $selected = $brand['id_hang'] == $product['id_hang'] ? 'selected' : '';
                            return '<option value="' . $brand['id_hang'] . '" ' . $selected . '>' . htmlspecialchars($brand['ten_hang']) . '</option>';
                        }, $brands)) . '
                    </select>
                </div>
                
                <!-- Ảnh sản phẩm hiện tại -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh sản phẩm hiện tại</label>
                    ' . (!empty($product['image']) ? '
                    <div class="flex items-center space-x-4">
                        <img src="/project/' . htmlspecialchars($product['image']) . '" 
                             alt="' . htmlspecialchars($product['Name']) . '" 
                             class="w-24 h-24 object-cover rounded-lg shadow border"
                             onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                        <div class="text-sm text-gray-600">
                            <p>Đường dẫn: ' . htmlspecialchars($product['image']) . '</p>
                            <p>Để thay đổi ảnh, hãy chọn file mới bên dưới</p>
                        </div>
                    </div>
                    ' : '<p class="text-sm text-gray-500">Chưa có ảnh sản phẩm</p>') . '
                </div>
                
                <!-- Upload ảnh mới -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Thay đổi ảnh sản phẩm</label>
                    <input type="file" name="image" id="image" accept="image/*"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                    <p class="text-sm text-gray-500 mt-1">Chấp nhận: JPG, JPEG, PNG, GIF. Kích thước tối đa: 5MB. Để trống nếu không muốn thay đổi ảnh.</p>
                </div>
                
                <!-- Mô tả -->
                <div class="md:col-span-2">
                    <label for="describe" class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                    <textarea name="describe" id="describe" rows="4"
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                              placeholder="Nhập mô tả sản phẩm">' . htmlspecialchars($product['Decribe']) . '</textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <a href="/project/index.php?act=admin_product_management" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Hủy
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Cập nhật sản phẩm
                </button>
            </div>
        </form>
    </div>
</div>';

// Include admin layout
include_once __DIR__ . '/../layout/admin_layout.php';
?> 