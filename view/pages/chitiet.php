<?php
// Sử dụng đường dẫn tương đối đơn giản
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/sanpham.php';
include_once __DIR__ . '/../../model/danhmuc.php';

$sanpham = new sanpham();
$danhmuc = new danhmuc();

// Lấy ID sản phẩm từ URL
$id_sp = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_sp <= 0) {
    header('Location: ../../index.php?act=sanpham');
    exit();
}

// Lấy thông tin sản phẩm
$sp = $sanpham->getSanphamById($id_sp);

if (!$sp) {
    header('Location: ../../index.php?act=sanpham');
    exit();
}

// Cập nhật lượt xem
$sanpham->updateViewsp($id_sp);

// Lấy sản phẩm liên quan
$sanpham_lienquan = $sanpham->getDS_SanphamByDanhmuc($sp['id_danhmuc']);
$sanpham_lienquan = array_filter($sanpham_lienquan, function($item) use ($id_sp) {
    return $item['id_sp'] != $id_sp;
});
$sanpham_lienquan = array_slice($sanpham_lienquan, 0, 4);

// Tính giá
$gia_sau_giam = $sanpham->getGiaSauGiam($sp['Price'], $sp['Sale']);
$gia_format = $sanpham->formatPrice($gia_sau_giam);
$gia_goc = $sanpham->formatPrice($sp['Price']);

// Làm sạch dữ liệu
$sp_name_clean = htmlspecialchars($sp['Name'], ENT_QUOTES, 'UTF-8');
$sp_desc_clean = htmlspecialchars($sp['Decribe'], ENT_QUOTES, 'UTF-8');
$sp_brand_clean = htmlspecialchars($sp['ten_hang'], ENT_QUOTES, 'UTF-8');
$sp_category_clean = htmlspecialchars($sp['ten_danhmuc'], ENT_QUOTES, 'UTF-8');

$content = '
<div class="space-y-8">
    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="../../index.php" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary">
                    <i class="ri-home-line mr-2"></i>Trang chủ
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ri-arrow-right-s-line text-gray-400"></i>
                    <a href="../../index.php?act=sanpham" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary md:ml-2">Sản phẩm</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ri-arrow-right-s-line text-gray-400"></i>
                    <a href="../../index.php?act=sanpham&danhmuc=' . $sp['id_danhmuc'] . '" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary md:ml-2">' . $sp_category_clean . '</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="ri-arrow-right-s-line text-gray-400"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">' . $sp_name_clean . '</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Product Details -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
            <!-- Product Images -->
            <div class="space-y-4">
                <div class="relative">
                    <img src="/project/view/image/' . $sp['image'] . '" alt="' . $sp_name_clean . '" class="w-full h-96 object-cover rounded-lg">';
                    
if ($sp['Sale'] > 0) {
    $content .= '<div class="absolute top-4 left-4 bg-red-500 text-white px-3 py-2 rounded-lg text-lg font-bold">-' . $sp['Sale'] . '%</div>';
}

$content .= '
                </div>
                
                <!-- Product Stats -->
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-primary">' . number_format($sp['Viewsp']) . '</div>
                        <div class="text-sm text-gray-600">Lượt xem</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-green-600">' . $sp['Mount'] . '</div>
                        <div class="text-sm text-gray-600">Còn lại</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-blue-600">' . $sp_brand_clean . '</div>
                        <div class="text-sm text-gray-600">Thương hiệu</div>
                    </div>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="space-y-6">
                <!-- Brand & Category -->
                <div class="flex items-center space-x-4">
                    <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-medium">' . $sp_brand_clean . '</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">' . $sp_category_clean . '</span>
                </div>
                
                <!-- Product Name -->
                <h1 class="text-3xl font-bold text-gray-800">' . $sp_name_clean . '</h1>
                
                <!-- Price -->
                <div class="space-y-2">
                    <div class="flex items-center space-x-4">
                        <span class="text-4xl font-bold text-primary">' . $gia_format . '</span>';
                        
if ($sp['Sale'] > 0) {
    $content .= '<span class="text-2xl text-gray-500 line-through">' . $gia_goc . '</span>';
}

$content .= '
                    </div>';
                    
if ($sp['Sale'] > 0) {
    $content .= '<div class="text-green-600 font-medium">Tiết kiệm ' . $sanpham->formatPrice($sp['Price'] - $gia_sau_giam) . '</div>';
}

$content .= '
                </div>
                
                <!-- Description -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Mô tả sản phẩm</h3>
                    <p class="text-gray-600 leading-relaxed">' . nl2br($sp_desc_clean) . '</p>
                </div>
                
                <!-- Product Details -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Mã sản phẩm:</span>
                        <div class="font-medium">SP-' . str_pad($sp['id_sp'], 6, '0', STR_PAD_LEFT) . '</div>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Ngày nhập:</span>
                        <div class="font-medium">' . date('d/m/Y', strtotime($sp['Date_import'])) . '</div>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Thương hiệu:</span>
                        <div class="flex items-center gap-2 font-medium text-blue-600">
  <img src="/project/view/image/' . $sp['logo_hang'] . '" alt="' . $sp_brand_clean . '" style="width:24px; height:24px; object-fit:contain;">
  ' . $sp_brand_clean . '
</div>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Danh mục:</span>
                        <div class="font-medium">' . $sp_category_clean . '</div>
                    </div>
                </div>
                
                <!-- Stock Status -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm text-gray-500">Tình trạng:</span>';
                            
if ($sp['Mount'] > 0) {
    $content .= '<div class="font-medium text-green-600">Còn hàng (' . $sp['Mount'] . ' sản phẩm)</div>';
} else {
    $content .= '<div class="font-medium text-red-600">Hết hàng</div>';
}

$content .= '
                        </div>
                        <div class="text-right">
                            <span class="text-sm text-gray-500">Lượt xem:</span>
                            <div class="font-medium">' . number_format($sp['Viewsp']) . '</div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="space-y-4">
                    <div class="flex space-x-4">
                        <button onclick="addToCart(' . $sp['id_sp'] . ')" class="flex-1 bg-primary text-white py-3 px-6 rounded-lg hover:bg-primary/90 transition duration-200 font-semibold">
                            <i class="ri-shopping-cart-line mr-2"></i>Thêm vào giỏ hàng
                        </button>
                        <button onclick="buyNow(' . $sp['id_sp'] . ')" class="flex-1 bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition duration-200 font-semibold">
                            <i class="ri-shopping-bag-line mr-2"></i>Mua ngay
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>';

// Related Products
if (!empty($sanpham_lienquan)) {
    $content .= '
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Sản phẩm liên quan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">';
        
    foreach ($sanpham_lienquan as $sp_lq) {
        $gia_sau_giam_lq = $sanpham->getGiaSauGiam($sp_lq['Price'], $sp_lq['Sale']);
        $gia_format_lq = $sanpham->formatPrice($gia_sau_giam_lq);
        $sp_lq_name_clean = htmlspecialchars($sp_lq['Name'], ENT_QUOTES, 'UTF-8');
        
        $content .= '
        <div class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-md transition duration-200">
            <div class="relative">
                <img src="/project/view/image/' . $sp_lq['image'] . '" alt="' . $sp_lq_name_clean . '" class="w-full h-32 object-cover">';
                
        if ($sp_lq['Sale'] > 0) {
            $content .= '<div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">-' . $sp_lq['Sale'] . '%</div>';
        }
        
        $content .= '
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">
                    <a href="/project/index.php?act=chitiet&id=' . $sp_lq['id_sp'] . '" class="hover:text-primary transition duration-200">
                        ' . $sp_lq_name_clean . '
                    </a>
                </h3>
                <div class="flex items-center justify-between">
                    <span class="font-bold text-primary">' . $gia_format_lq . '</span>
                    <a href="/project/index.php?act=chitiet&id=' . $sp_lq['id_sp'] . '" class="text-sm text-primary hover:underline">Chi tiết</a>
                </div>
            </div>
        </div>';
    }
    
    $content .= '
        </div>
    </div>';
}

$content .= '
</div>';

renderPage($sp_name_clean . " - Điện Lạnh KV", $content);
?>

<script>
function addToCart(productId) {
    // Tạo form để submit
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "/project/index.php?act=add_to_cart&id=" + productId;
    
    // Thêm input cho quantity
    const quantityInput = document.createElement("input");
    quantityInput.type = "hidden";
    quantityInput.name = "quantity";
    quantityInput.value = 1;
    
    form.appendChild(quantityInput);
    document.body.appendChild(form);
    form.submit();
}

function buyNow(productId) {
    // Thêm vào giỏ hàng trước, sau đó chuyển đến trang thanh toán
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "/project/index.php?act=add_to_cart&id=" + productId;
    
    const quantityInput = document.createElement("input");
    quantityInput.type = "hidden";
    quantityInput.name = "quantity";
    quantityInput.value = 1;
    
    form.appendChild(quantityInput);
    document.body.appendChild(form);
    form.submit();
}

function addToWishlist(productId) {
    fetch('/project/api/wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'add',
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cập nhật trạng thái nút
            const wishlistBtn = document.querySelector('[onclick="addToWishlist(' + productId + ')"]');
            if (wishlistBtn) {
                wishlistBtn.innerHTML = '<i class="ri-heart-fill mr-2 text-red-500"></i>Đã yêu thích';
                wishlistBtn.onclick = () => removeFromWishlist(productId);
                wishlistBtn.classList.remove('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                wishlistBtn.classList.add('border-red-300', 'text-red-600', 'hover:bg-red-50');
            }
            showNotification('Đã thêm sản phẩm vào danh sách yêu thích!', 'success');
            // Cập nhật số lượng wishlist trên header
            if (typeof window.updateWishlistCount === 'function') {
                window.updateWishlistCount();
            }
        } else {
            if (data.message && data.message.includes('đăng nhập')) {
                showNotification('Vui lòng đăng nhập để sử dụng tính năng yêu thích!', 'error');
                // Chuyển hướng đến trang đăng nhập sau 2 giây
                setTimeout(() => {
                    window.location.href = '/project/controller/index.php?act=login';
                }, 2000);
            } else {
                showNotification(data.message || 'Có lỗi xảy ra!', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra khi thêm vào danh sách yêu thích!', 'error');
    });
}

function removeFromWishlist(productId) {
    fetch('/project/api/wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'remove',
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cập nhật trạng thái nút
            const wishlistBtn = document.querySelector('[onclick="removeFromWishlist(' + productId + ')"]');
            if (wishlistBtn) {
                wishlistBtn.innerHTML = '<i class="ri-heart-line mr-2"></i>Yêu thích';
                wishlistBtn.onclick = () => addToWishlist(productId);
                wishlistBtn.classList.remove('border-red-300', 'text-red-600', 'hover:bg-red-50');
                wishlistBtn.classList.add('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
            }
            showNotification('Đã xóa sản phẩm khỏi danh sách yêu thích!', 'success');
            // Cập nhật số lượng wishlist trên header
            if (typeof window.updateWishlistCount === 'function') {
                window.updateWishlistCount();
            }
        } else {
            showNotification(data.message || 'Có lỗi xảy ra!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra khi xóa khỏi danh sách yêu thích!', 'error');
    });
}

function showNotification(message, type) {
    // Tạo notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    // Thiết lập style dựa trên type
    if (type === 'success') {
        notification.classList.add('bg-green-500', 'text-white');
    } else if (type === 'error') {
        notification.classList.add('bg-red-500', 'text-white');
    } else {
        notification.classList.add('bg-blue-500', 'text-white');
    }
    
    notification.textContent = message;
    
    // Thêm vào body
    document.body.appendChild(notification);
    
    // Hiển thị notification
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Tự động ẩn sau 3 giây
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: "Sản phẩm điện lạnh",
            text: "Xem sản phẩm này",
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href).then(function() {
            alert("Đã sao chép link sản phẩm!");
        });
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const style = document.createElement("style");
    style.textContent = `
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    `;
    document.head.appendChild(style);
    
    // Kiểm tra trạng thái wishlist khi trang được tải
    checkWishlistStatus();
});

function checkWishlistStatus() {
    const productId = <?php echo $id_sp; ?>;
    
    fetch('/project/api/wishlist.php?action=check&product_id=' + productId)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.in_wishlist) {
            // Sản phẩm đã có trong wishlist
            const wishlistBtn = document.querySelector('[onclick="addToWishlist(' + productId + ')"]');
            if (wishlistBtn) {
                wishlistBtn.innerHTML = '<i class="ri-heart-fill mr-2 text-red-500"></i>Đã yêu thích';
                wishlistBtn.onclick = () => removeFromWishlist(productId);
                wishlistBtn.classList.remove('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                wishlistBtn.classList.add('border-red-300', 'text-red-600', 'hover:bg-red-50');
            }
        }
    })
    .catch(error => {
        // Không hiển thị lỗi nếu người dùng chưa đăng nhập
        if (error.message && !error.message.includes('401')) {
            console.error('Error checking wishlist status:', error);
        }
    });
}
</script>
?> 