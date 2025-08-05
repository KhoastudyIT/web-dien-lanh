<?php
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';
include_once __DIR__ . '/../../model/user.php';

// Kiểm tra đăng nhập
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: /project/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để xem thông tin cá nhân'));
    exit();
}

$error = '';
$success = '';

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $fullname = $_POST['fullname'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        
        $user = new User();
        if ($user->updateUser($currentUser['id_user'], $fullname, $email, $phone, $address)) {
            $success = 'Cập nhật thông tin thành công!';
            // Cập nhật thông tin user hiện tại
            $currentUser = $user->getUserById($currentUser['id_user']);
        } else {
            $error = 'Có lỗi xảy ra khi cập nhật thông tin!';
        }
    } elseif (isset($_POST['change_password'])) {
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        if ($new_password !== $confirm_password) {
            $error = 'Mật khẩu xác nhận không khớp!';
        } elseif (strlen($new_password) < 6) {
            $error = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
        } else {
            $user = new User();
            $result = $user->changePassword($currentUser['id_user'], $old_password, $new_password);
            if ($result['success']) {
                $success = $result['message'];
            } else {
                $error = $result['message'];
            }
        }
    }
}

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
                    <span class="text-sm font-medium text-gray-500">Thông tin cá nhân</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                <i class="ri-user-line text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Thông tin cá nhân</h1>
                <p class="text-gray-600">Quản lý thông tin tài khoản của bạn</p>
            </div>
        </div>
    </div>

    <!-- Messages -->
    ' . ($error ? '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
        <i class="ri-error-warning-line mr-2"></i>
        ' . htmlspecialchars($error) . '
    </div>' : '') . '
    
    ' . ($success ? '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
        <i class="ri-check-line mr-2"></i>
        ' . htmlspecialchars($success) . '
    </div>' : '') . '

    <!-- Profile Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Profile Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <i class="ri-user-settings-line text-primary mr-2"></i>
                Thông tin cá nhân
            </h2>
            
            <form method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Tên đăng nhập</label>
                    <input type="text" id="username" value="' . htmlspecialchars($currentUser['username']) . '" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50" readonly>
                    <p class="text-sm text-gray-500 mt-1">Tên đăng nhập không thể thay đổi</p>
                </div>
                
                <div>
                    <label for="fullname" class="block text-sm font-medium text-gray-700 mb-2">Họ và tên *</label>
                    <input type="text" name="fullname" id="fullname" required 
                           value="' . htmlspecialchars($currentUser['fullname']) . '"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" id="email" required 
                           value="' . htmlspecialchars($currentUser['email']) . '"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại *</label>
                    <input type="tel" name="phone" id="phone" required 
                           value="' . htmlspecialchars($currentUser['phone']) . '"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ *</label>
                    <textarea name="address" id="address" rows="3" required 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">' . htmlspecialchars($currentUser['address']) . '</textarea>
                </div>
                
                <button type="submit" name="update_profile" 
                        class="w-full bg-primary text-white py-3 px-6 rounded-lg hover:bg-primary/90 transition-colors font-semibold">
                    <i class="ri-save-line mr-2"></i>
                    Cập nhật thông tin
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <i class="ri-lock-password-line text-primary mr-2"></i>
                Đổi mật khẩu
            </h2>
            
            <form method="POST" class="space-y-6">
                <div>
                    <label for="old_password" class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu hiện tại *</label>
                    <input type="password" name="old_password" id="old_password" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu mới *</label>
                    <input type="password" name="new_password" id="new_password" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Mật khẩu phải có ít nhất 6 ký tự</p>
                </div>
                
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Xác nhận mật khẩu mới *</label>
                    <input type="password" name="confirm_password" id="confirm_password" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <button type="submit" name="change_password" 
                        class="w-full bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    <i class="ri-lock-password-line mr-2"></i>
                    Đổi mật khẩu
                </button>
            </form>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
            <i class="ri-settings-3-line text-primary mr-2"></i>
            Thao tác nhanh
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="/project/index.php?act=my_orders" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-shopping-bag-3-line text-blue-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Đơn hàng của tôi</h3>
                    <p class="text-sm text-gray-600">Xem lịch sử đơn hàng</p>
                </div>
            </a>
            
            <a href="/project/index.php?act=cart" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-shopping-cart-line text-green-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Giỏ hàng</h3>
                    <p class="text-sm text-gray-600">Xem giỏ hàng hiện tại</p>
                </div>
            </a>
            
            <a href="/project/index.php?act=logout" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
               onclick="return confirm(\'Bạn có chắc chắn muốn đăng xuất?\')">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-logout-box-line text-red-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Đăng xuất</h3>
                    <p class="text-sm text-gray-600">Thoát khỏi tài khoản</p>
                </div>
            </a>
        </div>
    </div>
</div>';

renderPage("Thông tin cá nhân - Điện Lạnh KV", $content);
?>
