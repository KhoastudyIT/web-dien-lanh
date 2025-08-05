<?php
include_once __DIR__ . '/../layout/layout.php';

$error = '';
$success = '';

if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
if (isset($_GET['success'])) {
    $success = $_GET['success'];
}

$content = '
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full">
        <!-- White Register Box -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-gradient-to-r from-green-600 to-green-700 mb-6 shadow-lg">
                    <i class="ri-user-add-line text-white text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Đăng ký tài khoản mới
                </h2>
                <p class="text-gray-600">
                    Hoặc
                    <a href="/project/index.php?act=login" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                        đăng nhập nếu đã có tài khoản
                    </a>
                </p>
            </div>
            
            <!-- Error/Success Messages -->
            ' . ($error ? '<div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                <i class="ri-error-warning-line mr-2"></i>
                ' . htmlspecialchars($error) . '
            </div>' : '') . '
            
            ' . ($success ? '<div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <i class="ri-check-line mr-2"></i>
                ' . htmlspecialchars($success) . '
            </div>' : '') . '
            
            <!-- Register Form -->
            <form class="space-y-6" action="/project/controller/index.php?act=xl_register" method="POST">
                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Tên đăng nhập
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-user-line text-gray-400"></i>
                        </div>
                        <input id="username" name="username" type="text" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               placeholder="Nhập tên đăng nhập">
                    </div>
                </div>
                
                <!-- Full Name Field -->
                <div>
                    <label for="fullname" class="block text-sm font-medium text-gray-700 mb-2">
                        Họ và tên
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-user-3-line text-gray-400"></i>
                        </div>
                        <input id="fullname" name="fullname" type="text" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               placeholder="Nhập họ và tên đầy đủ">
                    </div>
                </div>
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-mail-line text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               placeholder="Nhập địa chỉ email">
                    </div>
                </div>
                
                <!-- Phone Field -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Số điện thoại
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-phone-line text-gray-400"></i>
                        </div>
                        <input id="phone" name="phone" type="tel" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               placeholder="Nhập số điện thoại">
                    </div>
                </div>
                
                <!-- Address Field -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Địa chỉ
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-map-pin-line text-gray-400"></i>
                        </div>
                        <input id="address" name="address" type="text" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               placeholder="Nhập địa chỉ">
                    </div>
                </div>
                
                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Mật khẩu
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-lock-line text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               placeholder="Nhập mật khẩu">
                    </div>
                </div>
                
                <!-- Confirm Password Field -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Xác nhận mật khẩu
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-lock-password-line text-gray-400"></i>
                        </div>
                        <input id="confirm_password" name="confirm_password" type="password" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               placeholder="Nhập lại mật khẩu">
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="text-gray-700">
                            Tôi đồng ý với 
                            <a href="#" class="text-blue-600 hover:text-blue-700">Điều khoản sử dụng</a> 
                            và 
                            <a href="#" class="text-blue-600 hover:text-blue-700">Chính sách bảo mật</a>
                        </label>
                    </div>
                </div>

                <!-- Register Button -->
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="ri-user-add-line text-green-300 group-hover:text-green-200"></i>
                        </span>
                        Đăng ký
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Đã có tài khoản? 
                <a href="/project/index.php?act=login" class="text-blue-600 hover:text-blue-700 font-medium">
                    Đăng nhập ngay
                </a>
            </p>
        </div>
    </div>
</div>';

renderPage("Đăng ký - Điện Lạnh KV", $content);
?>
