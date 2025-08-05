<?php
// Sử dụng đường dẫn tương đối đơn giản
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/cart.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';

// Kiểm tra đăng nhập
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: /project/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để xem giỏ hàng'));
    exit();
}

// Lấy thông tin giỏ hàng
$cart = new Cart();
$cart_items = $cart->getCart();
$total = $cart->getTotal();

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
                    <span class="text-sm font-medium text-gray-500">Giỏ hàng</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                    <i class="ri-shopping-cart-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Giỏ hàng của bạn</h1>
                    <p class="text-gray-600">Xin chào, ' . htmlspecialchars($currentUser['fullname']) . '</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Tổng sản phẩm</p>
                <p class="text-2xl font-bold text-primary">' . count($cart_items) . '</p>
                <div class="mt-2">
                    <a href="/project/index.php?act=logout" class="text-sm text-red-600 hover:text-red-700" onclick="return confirm(&quot;Bạn có chắc chắn muốn đăng xuất?&quot;)">
                        <i class="ri-logout-box-line mr-1"></i>Đăng xuất
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            ' . (empty($cart_items) ? '
            <!-- Empty Cart -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ri-shopping-cart-line text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Giỏ hàng của bạn đang trống</h3>
                <p class="text-gray-600 mb-6">Hãy thêm sản phẩm vào giỏ hàng để bắt đầu mua sắm</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/project/index.php?act=sanpham" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-lg shadow-lg hover:bg-primary/90 transition-all duration-300 font-semibold">
                        <i class="ri-store-line"></i>
                        Tiếp tục mua sắm
                    </a>
                    <a href="/project/index.php?act=my_orders" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg shadow-lg hover:bg-gray-200 transition-all duration-300 font-semibold">
                        <i class="ri-shopping-bag-3-line"></i>
                        Xem đơn hàng của tôi
                    </a>
                </div>
            </div>
            ' : '
            <!-- Cart Items List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Sản phẩm trong giỏ hàng</h2>
                </div>
                
                <form method="POST" action="/project/index.php?act=update_cart">
                    <div class="divide-y divide-gray-200">
                        ' . implode('', array_map(function($item) {
                            $price = $item['Price'];
                            if ($item['Sale'] > 0) {
                                $price = $price * (1 - $item['Sale'] / 100);
                            }
                            
                            return '
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <img src="/project/view/image/' . htmlspecialchars($item['image']) . '" 
                                             alt="' . htmlspecialchars($item['Name']) . '" 
                                             class="w-20 h-20 object-cover rounded-lg shadow border"
                                             onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-1">' . htmlspecialchars($item['Name']) . '</h3>
                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            <span>Mã SP: ' . htmlspecialchars($item['id_sp']) . '</span>
                                            ' . ($item['Sale'] > 0 ? '<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">Giảm ' . $item['Sale'] . '%</span>' : '') . '
                                        </div>
                                    </div>
                                    
                                    <!-- Price -->
                                    <div class="text-right">
                                        ' . ($item['Sale'] > 0 ? '
                                        <div class="text-sm text-gray-400 line-through">' . number_format($item['Price']) . '₫</div>
                                        ' : '') . '
                                        <div class="text-lg font-bold text-primary">' . number_format($price) . '₫</div>
                                    </div>
                                    
                                    <!-- Quantity -->
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="updateQuantity(' . $item['id_sp'] . ', -1)" class="w-8 h-8 flex items-center justify-center bg-gray-200 rounded hover:bg-primary hover:text-white transition">
                                            <i class="ri-subtract-line"></i>
                                        </button>
                                        <input type="number" name="quantities[' . $item['id_sp'] . ']" value="' . $item['quantity'] . '" min="1" class="w-16 border rounded px-2 py-1 text-center focus:ring-primary focus:border-primary">
                                        <button type="button" onclick="updateQuantity(' . $item['id_sp'] . ', 1)" class="w-8 h-8 flex items-center justify-center bg-gray-200 rounded hover:bg-primary hover:text-white transition">
                                            <i class="ri-add-line"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Subtotal -->
                                    <div class="text-right min-w-[100px]">
                                        <div class="text-sm text-gray-600">Thành tiền</div>
                                        <div class="text-lg font-bold text-secondary">' . number_format($price * $item['quantity']) . '₫</div>
                                    </div>
                                    
                                    <!-- Remove Button -->
                                    <div>
                                        <a href="/project/index.php?act=remove_from_cart&id=' . $item['id_sp'] . '" 
                                           class="text-red-500 hover:text-white hover:bg-red-500 rounded-full p-2 transition" 
                                           title="Xóa sản phẩm"
                                           onclick="return confirm(\'Bạn có chắc muốn xóa sản phẩm này?\')">
                                            <i class="ri-delete-bin-6-line text-xl"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            ';
                        }, $cart_items)) . '
                    </div>
                    
                    <!-- Cart Actions -->
                    <div class="p-6 bg-gray-50 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <a href="/project/index.php?act=clear_cart" 
                               class="text-red-600 hover:text-red-800 text-sm flex items-center gap-1 transition"
                               onclick="return confirm(\'Bạn có chắc muốn xóa toàn bộ giỏ hàng?\')">
                                <i class="ri-delete-bin-2-line"></i>
                                Xóa toàn bộ giỏ hàng
                            </a>
                            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg shadow hover:bg-primary/90 transition font-semibold">
                                <i class="ri-refresh-line mr-2"></i>
                                Cập nhật giỏ hàng
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            ') . '
        </div>
        
        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Tóm tắt đơn hàng</h2>
                
                ' . (empty($cart_items) ? '
                <div class="text-center text-gray-500 py-8">
                    <i class="ri-shopping-bag-line text-4xl mb-4"></i>
                    <p>Chưa có sản phẩm nào</p>
                </div>
                ' : '
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
                
                <div class="space-y-12">
                    <a href="/project/index.php?act=sanpham" class="w-full bg-gray-100 text-gray-700 px-2 py-1.5 rounded text-center hover:bg-gray-200 transition text-xs font-medium">
                        <i class="ri-store-line mr-1"></i>
                        Tiếp tục mua sắm
                    </a>
                    <a href="/project/index.php?act=checkout" class="w-full bg-green-600 text-white px-2 py-1.5 rounded text-center hover:bg-green-700 transition text-xs font-semibold">
                        <i class="ri-shopping-bag-3-line mr-1"></i>
                        Tiến hành thanh toán
                    </a>
                </div>
                
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="ri-shield-check-line text-blue-500 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-blue-800 mb-1">Bảo mật thanh toán</h4>
                            <p class="text-sm text-blue-700">Thông tin thanh toán của bạn được bảo mật hoàn toàn</p>
                        </div>
                    </div>
                </div>
                ') . '
            </div>
        </div>
    </div>
</div>

<script>
function updateQuantity(productId, change) {
    const input = document.querySelector(`input[name="quantities[${productId}]"]`);
    const newValue = parseInt(input.value) + change;
    if (newValue >= 1) {
        input.value = newValue;
    }
}

// Auto-submit form when quantity changes
document.addEventListener("DOMContentLoaded", function() {
    const quantityInputs = document.querySelectorAll("input[name^=\'quantities\']");
    quantityInputs.forEach(input => {
        input.addEventListener("change", function() {
            // Auto-submit after 1 second delay
            setTimeout(() => {
                this.closest("form").submit();
            }, 1000);
        });
    });
});
</script>';

renderPage("Giỏ hàng - Điện Lạnh KV", $content);
?> 