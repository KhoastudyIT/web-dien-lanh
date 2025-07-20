<?php
// Sử dụng đường dẫn tuyệt đối từ root của project
$project_root = dirname(dirname(__DIR__));
include $project_root . "/view/layout/layout.php";

$error = '';
$success = '';

if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
if (isset($_GET['success'])) {
    $success = $_GET['success'];
}

$content = '
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-primary">
                <i class="ri-user-add-line text-white text-2xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Đăng ký tài khoản mới
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Hoặc
                <a href="index.php?act=login" class="font-medium text-primary hover:text-blue-500">
                    đăng nhập nếu đã có tài khoản
                </a>
            </p>
        </div>
        
        ' . ($error ? '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">' . htmlspecialchars($error) . '</div>' : '') . '
        ' . ($success ? '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">' . htmlspecialchars($success) . '</div>' : '') . '
        
        <form class="mt-8 space-y-6" action="index.php?act=xl_register" method="POST">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="username" class="sr-only">Tên đăng nhập</label>
                    <input id="username" name="username" type="text" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" 
                           placeholder="Tên đăng nhập">
                </div>
                <div>
                    <label for="password" class="sr-only">Mật khẩu</label>
                    <input id="password" name="password" type="password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" 
                           placeholder="Mật khẩu">
                </div>
                <div>
                    <label for="confirm_password" class="sr-only">Xác nhận mật khẩu</label>
                    <input id="confirm_password" name="confirm_password" type="password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" 
                           placeholder="Xác nhận mật khẩu">
                </div>
                <div>
                    <label for="fullname" class="sr-only">Họ và tên</label>
                    <input id="fullname" name="fullname" type="text" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" 
                           placeholder="Họ và tên">
                </div>
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" name="email" type="email" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" 
                           placeholder="Email">
                </div>
                <div>
                    <label for="phone" class="sr-only">Số điện thoại</label>
                    <input id="phone" name="phone" type="tel" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" 
                           placeholder="Số điện thoại">
                </div>
                <div>
                    <label for="address" class="sr-only">Địa chỉ</label>
                    <input id="address" name="address" type="text" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" 
                           placeholder="Địa chỉ">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="ri-user-add-line text-primary group-hover:text-blue-500"></i>
                    </span>
                    Đăng ký
                </button>
            </div>
        </form>
    </div>
</div>';

renderPage("Đăng ký", $content);
?>
