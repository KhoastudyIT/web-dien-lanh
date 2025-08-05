<?php
// Sử dụng đường dẫn tương đối đơn giản
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/cart.php';
include_once __DIR__ . '/../../model/donhang.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';

// Kiểm tra đăng nhập
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: /project/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để thanh toán'));
    exit();
}

// Lấy thông tin giỏ hàng
$cart = new Cart();
$cart_items = $cart->getCart();
$total = $cart->getTotal();

// Kiểm tra giỏ hàng có sản phẩm không
if (empty($cart_items)) {
    header('Location: /project/index.php?act=cart&error=' . urlencode('Giỏ hàng trống, vui lòng thêm sản phẩm'));
    exit();
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
                    <a href="/project/index.php?act=cart" class="text-sm font-medium text-gray-700 hover:text-primary">Giỏ hàng</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ri-arrow-right-s-line text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Thanh toán</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                <i class="ri-bank-card-line text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Thanh toán đơn hàng</h1>
                <p class="text-gray-600">Hoàn tất thông tin để đặt hàng</p>
            </div>
        </div>
    </div>

    <!-- Checkout Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="/project/index.php?act=process_checkout" class="space-y-8">
                <!-- Thông tin giao hàng -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="ri-map-pin-line text-primary mr-2"></i>
                        Thông tin giao hàng
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="ten_nguoi_nhan" class="block text-sm font-medium text-gray-700 mb-2">Họ và tên người nhận *</label>
                            <input type="text" name="ten_nguoi_nhan" id="ten_nguoi_nhan" required 
                                   value="' . htmlspecialchars($currentUser['fullname']) . '"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="sdt_nguoi_nhan" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại *</label>
                            <input type="tel" name="sdt_nguoi_nhan" id="sdt_nguoi_nhan" required 
                                   value="' . htmlspecialchars($currentUser['phone']) . '"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="dia_chi_giao" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ giao hàng *</label>
                            <textarea name="dia_chi_giao" id="dia_chi_giao" rows="3" required 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder="Nhập địa chỉ giao hàng chi tiết">' . htmlspecialchars($currentUser['address']) . '</textarea>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="ghi_chu" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                            <textarea name="ghi_chu" id="ghi_chu" rows="2" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder="Ghi chú về đơn hàng (không bắt buộc)"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Phương thức thanh toán -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="ri-bank-card-line text-primary mr-2"></i>
                        Phương thức thanh toán
                    </h2>
                    
                    <div class="space-y-4">
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="phuong_thuc_thanh_toan" value="Tiền mặt" checked 
                                   class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
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
                                   class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <i class="ri-bank-line text-blue-500 text-xl mr-2"></i>
                                    <span class="font-medium text-gray-900">Chuyển khoản ngân hàng</span>
                                </div>
                                <p class="text-sm text-gray-500">Chuyển khoản trước khi giao hàng</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="phuong_thuc_thanh_toan" value="Ví điện tử" 
                                   class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <i class="ri-wallet-line text-orange-500 text-xl mr-2"></i>
                                    <span class="font-medium text-gray-900">Ví điện tử</span>
                                </div>
                                <p class="text-sm text-gray-500">Thanh toán qua ví điện tử (Momo, ZaloPay...)</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Thông tin đơn hàng -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="ri-shopping-bag-line text-primary mr-2"></i>
                        Thông tin đơn hàng
                    </h2>
                    
                    <div class="space-y-4">
                        ' . implode('', array_map(function($item) {
                            $price = $item['Price'];
                            if ($item['Sale'] > 0) {
                                $price = $price * (1 - $item['Sale'] / 100);
                            }
                            
                            return '
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
                                    <div class="font-semibold text-primary">' . number_format($price) . '₫</div>
                                </div>
                            </div>
                            ';
                        }, $cart_items)) . '
                    </div>
                </div>
            </form>
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
                            <span class="text-primary">' . number_format($total) . '₫</span>
                        </div>
                    </div>
                </div>
                
                <button type="submit" form="checkout-form" 
                        class="w-full bg-primary text-white py-4 px-6 rounded-lg font-semibold hover:bg-primary-dark transition-colors mb-4">
                    <i class="ri-check-line mr-2"></i>
                    Đặt hàng ngay
                </button>
                
                <a href="/project/index.php?act=cart" 
                   class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 transition-colors text-center block">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Quay lại giỏ hàng
                </a>
                
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="ri-shield-check-line text-blue-500 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-blue-800 mb-1">Bảo mật thanh toán</h4>
                            <p class="text-sm text-blue-700">Thông tin thanh toán của bạn được bảo mật hoàn toàn</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 p-4 bg-green-50 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="ri-truck-line text-green-500 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-green-800 mb-1">Giao hàng miễn phí</h4>
                            <p class="text-sm text-green-700">Giao hàng miễn phí trong phạm vi TP.HCM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Auto-fill form with user info
    const form = document.querySelector("form");
    form.id = "checkout-form";
    
    // Validate form before submit
    form.addEventListener("submit", function(e) {
        const requiredFields = ["ten_nguoi_nhan", "sdt_nguoi_nhan", "dia_chi_giao"];
        let isValid = true;
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                input.classList.add("border-red-500");
                isValid = false;
            } else {
                input.classList.remove("border-red-500");
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert("Vui lòng điền đầy đủ thông tin bắt buộc");
        }
    });
});
</script>';

renderPage("Thanh toán - Điện Lạnh KV", $content);
?> 