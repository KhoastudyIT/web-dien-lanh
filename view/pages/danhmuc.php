<?php
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/danhmuc.php';
include_once __DIR__ . '/../../controller/controller.php';

$controller = new controller();
$brands = $controller->getAllBrands();

$content = '
<div class="space-y-8">
    <!-- Logo các hãng -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Các hãng nổi bật</h2>
        <div class="grid grid-cols-3 gap-6 max-w-4xl mx-auto">';

if(isset($brands) && count($brands) > 0) {
    // hiển thị 6 hãng  (2 hàng x 3 cột)
    $displayBrands = array_slice($brands, 0, 6);
    
    foreach($displayBrands as $brand) {
        $content .= '
        <div class="flex flex-col items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-white hover:shadow-md transition-all duration-300 cursor-pointer">
            <div class="w-20 h-20 flex items-center justify-center bg-white border border-gray-200 rounded-lg mb-3 shadow-sm">
                <img src="/project/view/image/' . htmlspecialchars($brand["logo_hang"]) . '" 
                     alt="' . htmlspecialchars($brand["ten_hang"]) . '" 
                     class="w-16 h-16 object-contain" 
                     loading="lazy"
                     onerror="this.src=\'/project/view/image/logodienlanh.png\'"/>
            </div>
            <span class="text-sm font-medium text-gray-700 text-center">
                ' . htmlspecialchars($brand["ten_hang"]) . '
            </span>
        </div>';
    }
}

$content .= '
        </div>
    </div>';

// Danh mục và sản phẩm nổi bật
$content .= '
    <div class="space-y-8 mt-8">';

// Lấy danh mục
$danhmuc = new danhmuc();
$danhmuc_list = $danhmuc->getDS_Danhmuc();

if(isset($danhmuc_list) && count($danhmuc_list) > 0) {
    foreach($danhmuc_list as $dm) {
        // Lấy sản phẩm nổi bật cho từng danh mục
        $featured_products = $controller->getFeaturedProductsByCategory($dm['id'], 4);
        
        $content .= '
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-primary mb-4">' . htmlspecialchars($dm["name"]) . '</h3>';
        
        if(isset($featured_products) && count($featured_products) > 0) {
            $content .= '
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">';
            
            foreach($featured_products as $sp) {
                $gia_sau_giam = $sp['Price'] - ($sp['Price'] * $sp['Sale'] / 100);
                $gia_format = number_format($gia_sau_giam, 0, ',', '.');
                $gia_goc = number_format($sp['Price'], 0, ',', '.');
                
                $content .= '
                <div class="border rounded-lg p-4 flex flex-col items-center hover:shadow-md transition-shadow">
                    <img src="/project/view/image/' . htmlspecialchars($sp["image"]) . '" 
                         alt="' . htmlspecialchars($sp["Name"]) . '" 
                         class="h-24 w-auto object-contain mb-2" 
                         loading="lazy"
                         onerror="this.src=\'/project/view/image/logodienlanh.png\'"/>
                    <div class="font-medium text-gray-800 text-center text-sm mb-1">' . htmlspecialchars($sp["Name"]) . '</div>
                    <div class="text-primary font-bold text-lg">' . $gia_format . ' ₫</div>';
                
                if(isset($sp["Sale"]) && $sp["Sale"] > 0) {
                    $content .= '
                    <div class="text-xs text-red-500 font-semibold">Giảm ' . $sp["Sale"] . '%</div>
                    <div class="text-xs text-gray-500 line-through">' . $gia_goc . ' ₫</div>';
                }
                
                $content .= '
                </div>';
            }
            
            $content .= '
            </div>';
        } else {
            $content .= '
            <div class="text-gray-500 italic text-center py-8">Chưa có sản phẩm nổi bật</div>';
        }
        
        $content .= '
        </div>';
    }
}

$content .= '
    </div>
</div>

<style>
/* Responsive cho logo hãng */
@media (max-width: 768px) {
    .grid-cols-3 { 
        grid-template-columns: repeat(2, minmax(0, 1fr)); 
    }
}

@media (max-width: 480px) {
    .grid-cols-3 { 
        grid-template-columns: repeat(1, minmax(0, 1fr)); 
    }
}

/* Hover effects */
.grid-cols-3 > div:hover {
    transform: translateY(-2px);
}

.grid-cols-3 > div:hover img {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}
</style>

<script>
// Thêm click event cho brand card
document.addEventListener("DOMContentLoaded", function() {
    const brandCards = document.querySelectorAll(".grid-cols-3 > div");
    brandCards.forEach(card => {
        card.addEventListener("click", function() {
            const brandName = this.querySelector("span").textContent.trim();
            // Chuyển đến trang sản phẩm của hãng
            window.location.href = `/project/index.php?act=sanpham&search=${encodeURIComponent(brandName)}`;
        });
    });
});
</script>';

renderPage("Danh mục & Sản phẩm nổi bật", $content);
?>