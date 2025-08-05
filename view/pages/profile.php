<?php
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/user.php';
require_once __DIR__ . '/../../helpers/jwt_helper.php';

$currentUser = getCurrentUser();

if (!$currentUser) {
    header('Location: index.php?act=login');
    exit();
}

$content = '
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Thông tin tài khoản
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Chi tiết thông tin cá nhân của bạn.
            </p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Tên đăng nhập
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        ' . htmlspecialchars($currentUser['username']) . '
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Họ và tên
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        ' . htmlspecialchars($currentUser['fullname']) . '
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Email
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        ' . htmlspecialchars($currentUser['email']) . '
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Số điện thoại
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        ' . htmlspecialchars($currentUser['phone']) . '
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Địa chỉ
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        ' . htmlspecialchars($currentUser['address']) . '
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Vai trò
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        ' . ($currentUser['position'] == 'admin' ? 'Admin' : 'Người dùng') . '
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    
    <div class="mt-6 flex space-x-3">
        ' . ($currentUser['position'] == 'admin' ? '
        <a href="/project/index.php?act=admin" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
            <i class="ri-admin-line mr-2"></i>
            Quản Lý Admin
        </a>' : '') . '
        <a href="/project/index.php?act=edit_profile" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-700">
            <i class="ri-edit-line mr-2"></i>
            Chỉnh sửa thông tin
        </a>
        <a href="/project/index.php?act=change_password" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <i class="ri-lock-line mr-2"></i>
            Đổi mật khẩu
        </a>
        <a href="/project/index.php?act=logout" 
           class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50"
           onclick="return confirm(\'Bạn có chắc chắn muốn đăng xuất?\')">
            <i class="ri-logout-box-line mr-2"></i>
            Đăng xuất
        </a>
    </div>
</div>';

renderPage("Thông tin tài khoản", $content);
?>
