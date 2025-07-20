<?php
// Sử dụng đường dẫn tuyệt đối từ root của project
$project_root = dirname(dirname(__DIR__));
include $project_root . "/view/layout/layout.php";

$content = '
<div class="space-y-12">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary to-secondary text-white rounded-2xl p-8 md:p-12">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Chào mừng đến với MyWeb</h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90">Nền tảng quản lý và phát triển web hiện đại</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="index.php?act=sanpham" class="bg-white text-primary hover:bg-gray-100 font-semibold py-3 px-8 rounded-lg transition duration-200">
                    Xem sản phẩm
                </a>
                <a href="index.php?act=danhmuc" class="bg-white text-primary hover:bg-gray-100 font-semibold py-3 px-8 rounded-lg transition duration-200">
                    Quản lý danh mục
                </a>
                <a href="index.php?act=login" class="border-2 border-white text-white hover:bg-white hover:text-primary font-semibold py-3 px-8 rounded-lg transition duration-200">
                    Đăng nhập
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-database-2-line text-3xl text-primary"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Quản lý dữ liệu</h3>
            <p class="text-gray-600">Hệ thống quản lý danh mục và dữ liệu hiệu quả, dễ dàng thao tác.</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="w-16 h-16 bg-secondary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-shield-check-line text-3xl text-secondary"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Bảo mật cao</h3>
            <p class="text-gray-600">Đảm bảo an toàn thông tin với các biện pháp bảo mật tiên tiến.</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-speed-line text-3xl text-green-600"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Hiệu suất tốt</h3>
            <p class="text-gray-600">Tối ưu hóa hiệu suất, tải trang nhanh và trải nghiệm mượt mà.</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Thao tác nhanh</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="index.php?act=danhmuc" class="group block p-6 border border-gray-200 rounded-lg hover:border-primary hover:shadow-md transition duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center group-hover:bg-primary/20 transition duration-200">
                        <i class="ri-folder-line text-primary text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 group-hover:text-primary">Danh mục</h3>
                        <p class="text-sm text-gray-600">Quản lý danh mục</p>
                    </div>
                </div>
            </a>
            
            <a href="index.php?act=login" class="group block p-6 border border-gray-200 rounded-lg hover:border-primary hover:shadow-md transition duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-secondary/10 rounded-lg flex items-center justify-center group-hover:bg-secondary/20 transition duration-200">
                        <i class="ri-user-line text-secondary text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 group-hover:text-secondary">Đăng nhập</h3>
                        <p class="text-sm text-gray-600">Truy cập hệ thống</p>
                    </div>
                </div>
            </a>
            
            <a href="index.php?act=register" class="group block p-6 border border-gray-200 rounded-lg hover:border-primary hover:shadow-md transition duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition duration-200">
                        <i class="ri-user-add-line text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 group-hover:text-green-600">Đăng ký</h3>
                        <p class="text-sm text-gray-600">Tạo tài khoản mới</p>
                    </div>
                </div>
            </a>
            
            <a href="#" class="group block p-6 border border-gray-200 rounded-lg hover:border-primary hover:shadow-md transition duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition duration-200">
                        <i class="ri-settings-line text-purple-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 group-hover:text-purple-600">Cài đặt</h3>
                        <p class="text-sm text-gray-600">Cấu hình hệ thống</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Sản phẩm nổi bật</h2>
            <a href="index.php?act=sanpham" class="text-primary hover:text-primary/80 font-medium">
                Xem tất cả <i class="ri-arrow-right-line ml-1"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">';
        
        // Lấy sản phẩm nổi bật
        include_once $project_root . "/model/sanpham.php";
        $sanpham = new sanpham();
        $sanpham_noibat = $sanpham->getSanphamNoiBat();
        
        if (empty($sanpham_noibat)) {
            $sanpham_noibat = $sanpham->getSanphamMoiNhat();
        }
        
        foreach (array_slice($sanpham_noibat, 0, 4) as $sp) {
            $gia_sau_giam = $sanpham->getGiaSauGiam($sp['Price'], $sp['Sale']);
            $gia_format = $sanpham->formatPrice($gia_sau_giam);
            
            $content .= '
            <div class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-md transition duration-200 group">
                <div class="relative">
                    <img src="' . $sp['image'] . '" alt="' . htmlspecialchars($sp['Name']) . '" 
                         class="w-full h-48 object-cover group-hover:scale-105 transition duration-200">
                    ' . ($sp['Sale'] > 0 ? '<div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold">-' . $sp['Sale'] . '%</div>' : '') . '
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs bg-primary/10 text-primary px-2 py-1 rounded">' . $sp['ten_hang'] . '</span>
                        <span class="text-xs text-gray-500">' . $sp['ten_danhmuc'] . '</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-primary transition duration-200">
                        <a href="index.php?act=chitiet&id=' . $sp['id_sp'] . '">' . htmlspecialchars($sp['Name']) . '</a>
                    </h3>
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-primary">' . $gia_format . '</span>
                        <a href="index.php?act=chitiet&id=' . $sp['id_sp'] . '" 
                           class="text-sm text-primary hover:underline">Chi tiết</a>
                    </div>
                </div>
            </div>';
        }
        
        $content .= '
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="text-3xl font-bold text-primary mb-2">5</div>
            <div class="text-gray-600">Danh mục</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="text-3xl font-bold text-secondary mb-2">125+</div>
            <div class="text-gray-600">Sản phẩm</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="text-3xl font-bold text-green-600 mb-2">13</div>
            <div class="text-gray-600">Thương hiệu</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="text-3xl font-bold text-purple-600 mb-2">100%</div>
            <div class="text-gray-600">Chất lượng</div>
        </div>
    </div>
</div>';

renderPage("Trang chủ - MyWeb", $content);
?> 