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
        <!-- White Login Box -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-gradient-to-r from-blue-600 to-blue-700 mb-6 shadow-lg">
                    <i class="ri-user-line text-white text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Đăng nhập vào tài khoản
                </h2>
                <p class="text-gray-600">
                    Hoặc
                    <a href="/project/index.php?act=register" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                        đăng ký tài khoản mới
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
            
            <!-- Login Form -->
            <form class="space-y-6" action="/project/controller/index.php?act=xl_login" method="POST">
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

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember_me" type="checkbox" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-700 transition-colors">
                            Quên mật khẩu?
                        </a>
                    </div>
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="ri-lock-line text-blue-300 group-hover:text-blue-200"></i>
                        </span>
                        Đăng nhập
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Bằng cách đăng nhập, bạn đồng ý với 
                <a href="#" class="text-blue-600 hover:text-blue-700">Điều khoản sử dụng</a> 
                và 
                <a href="#" class="text-blue-600 hover:text-blue-700">Chính sách bảo mật</a>
            </p>
        </div>
    </div>
</div>';

renderPage("Đăng nhập - Điện Lạnh KV", $content);
?>
