<?php
// Include necessary files
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/cart.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';

// Kiểm tra đăng nhập
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: /project/controller/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để thanh toán'));
    exit();
}

// Lấy thông tin giỏ hàng
$cart = new Cart();
$cart_items = $cart->getCart();
$total = $cart->getTotal();

// Kiểm tra giỏ hàng có sản phẩm không
if (empty($cart_items)) {
    header('Location: /project/controller/index.php?act=cart&error=' . urlencode('Giỏ hàng trống, vui lòng thêm sản phẩm'));
    exit();
}

$content = '
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Thanh toán đơn hàng</h1>
            <p class="text-gray-600">Hoàn tất thông tin để đặt hàng</p>
        </div>

        <!-- Checkout Form -->
        <form method="POST" action="/project/controller/index.php?act=process_checkout" id="checkout-form">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Thông tin giao hàng -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Thông tin giao hàng</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="ten_nguoi_nhan" class="block text-sm font-medium text-gray-700 mb-2">Họ và tên người nhận *</label>
                                <input type="text" name="ten_nguoi_nhan" id="ten_nguoi_nhan" required 
                                       value="' . htmlspecialchars($currentUser['fullname']) . '"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="sdt_nguoi_nhan" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại *</label>
                                <input type="tel" name="sdt_nguoi_nhan" id="sdt_nguoi_nhan" required 
                                       value="' . htmlspecialchars($currentUser['phone']) . '"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="dia_chi_giao" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ giao hàng *</label>
                                <textarea name="dia_chi_giao" id="dia_chi_giao" rows="3" required 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Nhập địa chỉ giao hàng chi tiết">' . htmlspecialchars($currentUser['address']) . '</textarea>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="ghi_chu" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                                <textarea name="ghi_chu" id="ghi_chu" rows="2" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Ghi chú về đơn hàng (không bắt buộc)"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Phương thức thanh toán -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Phương thức thanh toán</h2>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="phuong_thuc_thanh_toan" value="Tiền mặt" checked 
                                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <i class="ri-money-dollar-circle-line text-green-500 text-xl mr-2"></i>
                                        <span class="font-medium text-gray-900">Thanh toán tiền mặt khi nhận hàng</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Thanh toán bằng tiền mặt khi giao hàng</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="phuong_thuc_thanh_toan" value="Chuyển khoản" 
                                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <i class="ri-bank-line text-blue-500 text-xl mr-2"></i>
                                        <span class="font-medium text-gray-900">Chuyển khoản ngân hàng</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Chuyển khoản trước khi giao hàng</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Thông tin đơn hàng -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Thông tin đơn hàng</h2>
                        
                        <div class="space-y-4">';

// Tạo HTML cho danh sách sản phẩm
foreach ($cart_items as $item) {
    $price = $item['Price'];
    if ($item['Sale'] > 0) {
        $price = $price * (1 - $item['Sale'] / 100);
    }
    
    $content .= '
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center gap-4">
                                    <img src="/project/view/image/' . htmlspecialchars($item['image']) . '" 
                                         alt="' . htmlspecialchars($item['Name']) . '" 
                                         class="w-16 h-16 object-cover rounded-lg shadow border"
                                         onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">' . htmlspecialchars($item['Name']) . '</h3>
                                        <p class="text-sm text-gray-600">Số lượng: ' . $item['quantity'] . '</p>
                                        ' . ($item['Sale'] > 0 ? '<p class="text-xs text-red-600">Giảm ' . $item['Sale'] . '%</p>' : '') . '
                                    </div>
                                </div>
                                <div class="text-right">
                                    ' . ($item['Sale'] > 0 ? '<div class="text-sm text-gray-400 line-through">' . number_format($item['Price']) . '₫</div>' : '') . '
                                    <div class="font-semibold text-blue-600">' . number_format($price) . '₫</div>
                                </div>
                            </div>';
}

$content .= '
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6">Tóm tắt đơn hàng</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Tạm tính (' . count($cart_items) . ' sản phẩm)</span>
                                <span>' . number_format($total) . '₫</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Phí vận chuyển</span>
                                <span class="text-green-600">Miễn phí</span>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-lg font-bold text-gray-800">
                                    <span>Tổng cộng</span>
                                    <span class="text-blue-600">' . number_format($total) . '₫</span>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors mb-4">
                            <i class="ri-check-line mr-2"></i>
                            Đặt hàng ngay
                        </button>
                        
                        <a href="/project/controller/index.php?act=cart" 
                           class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 transition-colors text-center block">
                            <i class="ri-arrow-left-line mr-2"></i>
                            Quay lại giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("checkout-form");
    if (form) {
        form.addEventListener("submit", function(e) {
            console.log("Form submit triggered");
            
            // Show loading message
            const submitBtn = form.querySelector("button[type=\'submit\']");
            if (submitBtn) {
                submitBtn.innerHTML = \'<i class="ri-loader-4-line mr-2 animate-spin"></i>Đang xử lý...\';
                submitBtn.disabled = true;
            }
            
            // Allow form to submit
            return true;
        });
    }
});
</script>';

renderPage("Thanh toán - Điện Lạnh KV", $content);
?>