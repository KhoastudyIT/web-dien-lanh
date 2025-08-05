<?php
// Sử dụng đường dẫn tương đối đơn giản
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/donhang.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';

// Kiểm tra đăng nhập
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: /project/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để xem đơn hàng'));
    exit();
}

// Lấy thông tin đơn hàng
$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$orderId) {
    header('Location: /project/index.php?act=cart');
    exit();
}

$donhang = new DonHang();
$order = $donhang->getOrderDetails($orderId);

if (!$order || $order['id_user'] != $currentUser['id_user']) {
    header('Location: /project/index.php?act=cart');
    exit();
}

$content = '
<div class="space-y-8">
    <!-- Success Message -->
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="ri-check-line text-green-500 text-4xl"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Đặt hàng thành công!</h1>
        <p class="text-xl text-gray-600 mb-8">Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ xử lý đơn hàng của bạn sớm nhất có thể.</p>
        
        <div class="bg-blue-50 rounded-lg p-6 mb-8">
            <h2 class="text-lg font-semibold text-blue-800 mb-2">Mã đơn hàng: #' . $orderId . '</h2>
            <p class="text-blue-700">Vui lòng lưu lại mã đơn hàng để theo dõi trạng thái</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/project/index.php?act=order_detail&id=' . $orderId . '" 
               class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                <i class="ri-eye-line mr-2"></i>
                Xem chi tiết đơn hàng
            </a>
            <a href="/project/index.php?act=my_orders" 
               class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                <i class="ri-list-check mr-2"></i>
                Xem tất cả đơn hàng
            </a>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Tóm tắt đơn hàng</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold text-gray-800 mb-4">Thông tin đơn hàng</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Mã đơn hàng:</span>
                        <span class="font-semibold">#' . $orderId . '</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngày đặt:</span>
                        <span class="font-semibold">' . date('d/m/Y H:i', strtotime($order['ngaydat'])) . '</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Trạng thái:</span>
                        <span class="font-semibold text-primary">' . htmlspecialchars($order['trangthai']) . '</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Phương thức thanh toán:</span>
                        <span class="font-semibold">' . htmlspecialchars($order['phuong_thuc_thanh_toan']) . '</span>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="font-semibold text-gray-800 mb-4">Thông tin giao hàng</h3>
                <div class="space-y-2">
                    <div>
                        <span class="text-gray-600">Người nhận:</span>
                        <span class="font-semibold block">' . htmlspecialchars($order['ten_nguoi_nhan']) . '</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Số điện thoại:</span>
                        <span class="font-semibold block">' . htmlspecialchars($order['sdt_nguoi_nhan']) . '</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Địa chỉ:</span>
                        <span class="font-semibold block">' . htmlspecialchars($order['dia_chi_giao']) . '</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="border-t border-gray-200 pt-6">
            <h3 class="font-semibold text-gray-800 mb-4">Sản phẩm đã đặt</h3>
            <div class="space-y-4">
                ' . implode('', array_map(function($item) {
                    return '
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-4">
                            <img src="/project/view/image/' . htmlspecialchars($item['image']) . '" 
                                 alt="' . htmlspecialchars($item['Name']) . '" 
                                 class="w-16 h-16 object-cover rounded-lg shadow border"
                                 onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                            <div>
                                <h4 class="font-semibold text-gray-800">' . htmlspecialchars($item['Name']) . '</h4>
                                <p class="text-sm text-gray-600">Số lượng: ' . $item['soluong'] . '</p>
                                <p class="text-sm text-gray-600">Giá: ' . number_format($item['gia_ban']) . '₫</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-primary">' . number_format($item['tong_dh']) . '₫</div>
                        </div>
                    </div>
                    ';
                }, $order['items'])) . '
            </div>
            
            <div class="border-t border-gray-200 pt-4 mt-6">
                <div class="flex justify-between text-lg font-bold text-gray-800">
                    <span>Tổng cộng:</span>
                    <span class="text-primary">' . number_format($order['tongdh']) . '₫</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Next Steps -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Những bước tiếp theo</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-check-line text-blue-500 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">1. Xác nhận đơn hàng</h3>
                <p class="text-sm text-gray-600">Chúng tôi sẽ xác nhận đơn hàng của bạn trong vòng 24 giờ</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-truck-line text-yellow-500 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">2. Chuẩn bị & giao hàng</h3>
                <p class="text-sm text-gray-600">Sản phẩm sẽ được chuẩn bị và giao đến địa chỉ của bạn</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-home-line text-green-500 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">3. Nhận hàng</h3>
                <p class="text-sm text-gray-600">Kiểm tra và nhận hàng tại địa chỉ đã đăng ký</p>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="bg-blue-50 rounded-lg p-6">
        <h2 class="text-xl font-semibold text-blue-800 mb-4">Cần hỗ trợ?</h2>
        <p class="text-blue-700 mb-4">Nếu bạn có bất kỳ câu hỏi nào về đơn hàng, vui lòng liên hệ với chúng tôi:</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center gap-3">
                <i class="ri-phone-line text-blue-500 text-xl"></i>
                <div>
                    <p class="font-semibold text-blue-800">Hotline</p>
                    <p class="text-blue-700">1900 6789</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <i class="ri-mail-line text-blue-500 text-xl"></i>
                <div>
                    <p class="font-semibold text-blue-800">Email</p>
                    <p class="text-blue-700">info@dienlanhkv.vn</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/project/index.php?act=sanpham" 
           class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition-colors text-center">
            <i class="ri-store-line mr-2"></i>
            Tiếp tục mua sắm
        </a>
        
        <a href="/project/index.php?act=order_detail&id=' . $orderId . '" 
           class="bg-gray-100 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors text-center">
            <i class="ri-eye-line mr-2"></i>
            Xem chi tiết đơn hàng
        </a>
    </div>
</div>';

renderPage("Đặt hàng thành công - Điện Lạnh KV", $content);
?> 