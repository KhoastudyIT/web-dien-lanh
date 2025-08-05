<?php
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/wishlist.php';
include_once __DIR__ . '/../../helpers/jwt_helper.php';

// Kiểm tra đăng nhập
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: /project/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để xem danh sách yêu thích'));
    exit();
}

$wishlist = new Wishlist();
$wishlist_items = $wishlist->getUserWishlist($currentUser['id_user']);
$wishlist_count = $wishlist->getWishlistCount($currentUser['id_user']);

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
                    <span class="text-sm font-medium text-gray-500">Danh sách yêu thích</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                    <i class="ri-heart-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Danh sách yêu thích</h1>
                    <p class="text-gray-600">Xin chào, ' . htmlspecialchars($currentUser['fullname']) . '</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Sản phẩm yêu thích</p>
                <p class="text-2xl font-bold text-red-500">' . $wishlist_count . '</p>
            </div>
        </div>
    </div>

    <!-- Wishlist Content -->
    ' . (empty($wishlist_items) ? '
    <!-- Empty Wishlist -->
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="ri-heart-line text-4xl text-red-400"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Danh sách yêu thích trống</h3>
        <p class="text-gray-600 mb-6">Bạn chưa có sản phẩm nào trong danh sách yêu thích</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/project/index.php?act=sanpham" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-lg shadow-lg hover:bg-primary/90 transition-all duration-300 font-semibold">
                <i class="ri-store-line"></i>
                Khám phá sản phẩm
            </a>
        </div>
    </div>
    ' : '
    <!-- Wishlist Items -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Sản phẩm yêu thích</h2>
                <button onclick="clearWishlist()" class="text-red-600 hover:text-red-700 text-sm font-medium">
                    <i class="ri-delete-bin-line mr-1"></i>
                    Xóa tất cả
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
            ' . implode('', array_map(function($item) use ($wishlist, $currentUser) {
                $price = $item['Price'];
                $sale_price = $price;
                if ($item['Sale'] > 0) {
                    $sale_price = $price * (1 - $item['Sale'] / 100);
                }
                
                $stock_status = '';
                $stock_class = '';
                if ($item['Mount'] == 0) {
                    $stock_status = 'Hết hàng';
                    $stock_class = 'bg-red-100 text-red-800';
                } elseif ($item['Mount'] <= 10) {
                    $stock_status = 'Sắp hết hàng';
                    $stock_class = 'bg-yellow-100 text-yellow-800';
                } else {
                    $stock_status = 'Còn hàng';
                    $stock_class = 'bg-green-100 text-green-800';
                }
                
                return '
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <img src="/project/view/image/' . $item['image'] . '" alt="' . htmlspecialchars($item['Name']) . '" 
                             class="w-full h-48 object-cover" onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                        ' . ($item['Sale'] > 0 ? '
                        <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold">
                            -' . $item['Sale'] . '%
                        </div>' : '') . '
                        <div class="absolute top-2 right-2">
                            <span class="' . $stock_class . ' px-2 py-1 rounded text-xs font-medium">' . $stock_status . '</span>
                        </div>
                        <button onclick="removeFromWishlist(' . $item['id_sp'] . ')" 
                                class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                            <i class="ri-heart-fill text-sm"></i>
                        </button>
                    </div>
                    
                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <img src="/project/view/image/' . $item['logo_hang'] . '" alt="' . htmlspecialchars($item['ten_hang']) . '" 
                                 class="w-6 h-6" onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                            <span class="text-sm text-gray-600">' . htmlspecialchars($item['ten_hang']) . '</span>
                        </div>
                        
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2" title="' . htmlspecialchars($item['Name']) . '">
                            ' . htmlspecialchars($item['Name']) . '
                        </h3>
                        
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-lg font-bold text-primary">' . number_format($sale_price, 0, ',', '.') . ' ₫</span>
                            ' . ($item['Sale'] > 0 ? '
                            <span class="text-sm text-gray-500 line-through">' . number_format($price, 0, ',', '.') . ' ₫</span>' : '') . '
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Danh mục: ' . htmlspecialchars($item['ten_danhmuc']) . '</span>
                            <div class="flex gap-2">
                                <a href="/project/index.php?act=chitiet&id=' . $item['id_sp'] . '" 
                                   class="bg-primary text-white px-3 py-1 rounded text-sm hover:bg-primary/90 transition-colors">
                                    Chi tiết
                                </a>
                                ' . ($item['Mount'] > 0 ? '
                                <button onclick="addToCart(' . $item['id_sp'] . ')" 
                                        class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition-colors">
                                    Mua ngay
                                </button>' : '
                                <span class="bg-gray-300 text-gray-600 px-3 py-1 rounded text-sm cursor-not-allowed">
                                    Hết hàng
                                </span>') . '
                            </div>
                        </div>
                    </div>
                </div>';
            }, $wishlist_items)) . '
        </div>
    </div>
    ') . '
</div>

<script>
function removeFromWishlist(productId) {
    if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi danh sách yêu thích?")) {
        fetch("/project/api/wishlist.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                action: "remove",
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Có lỗi xảy ra");
        });
    }
}

function addToCart(productId) {
    fetch("/project/api/wishlist.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            action: "add_to_cart",
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Có lỗi xảy ra");
    });
}

function clearWishlist() {
    if (confirm("Bạn có chắc chắn muốn xóa toàn bộ danh sách yêu thích?")) {
        fetch("/project/api/wishlist.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                action: "clear"
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Có lỗi xảy ra");
        });
    }
}
</script>';

renderPage("Danh sách yêu thích - Điện Lạnh KV", $content);
?> 