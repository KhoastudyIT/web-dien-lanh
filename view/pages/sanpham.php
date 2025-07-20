<?php
// Sử dụng đường dẫn tuyệt đối từ root của project
$project_root = dirname(dirname(__DIR__));
include $project_root . "/view/layout/layout.php";
include_once $project_root . "/model/sanpham.php";
include_once $project_root . "/model/danhmuc.php";

$sanpham = new sanpham();
$danhmuc = new danhmuc();

// Lấy danh sách danh mục
$danhmuc_list = $danhmuc->getDS_Danhmuc();

// Xử lý bộ lọc
$id_danhmuc = isset($_GET['danhmuc']) ? (int)$_GET['danhmuc'] : 0;
$id_hang = isset($_GET['hang']) ? (int)$_GET['hang'] : 0;
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Lấy danh sách sản phẩm
if ($keyword) {
    $sanpham_list = $sanpham->searchSanpham($keyword);
} elseif ($id_danhmuc > 0) {
    $sanpham_list = $sanpham->getDS_SanphamByDanhmuc($id_danhmuc);
} elseif ($id_hang > 0) {
    $sanpham_list = $sanpham->getDS_SanphamByHang($id_hang);
} else {
    $sanpham_list = $sanpham->getDS_Sanpham();
}

// Sắp xếp sản phẩm
if ($sort === 'price_asc') {
    usort($sanpham_list, function($a, $b) {
        return $a['Price'] - $b['Price'];
    });
} elseif ($sort === 'price_desc') {
    usort($sanpham_list, function($a, $b) {
        return $b['Price'] - $a['Price'];
    });
} elseif ($sort === 'name_asc') {
    usort($sanpham_list, function($a, $b) {
        return strcmp($a['Name'], $b['Name']);
    });
}

$content = '
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Sản phẩm điện lạnh</h1>
        <p class="text-gray-600">Khám phá các sản phẩm điện lạnh chất lượng cao từ các thương hiệu uy tín</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                    <input type="text" name="search" value="' . htmlspecialchars($keyword) . '" 
                           placeholder="Tên sản phẩm, hãng..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <!-- Danh mục -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                    <select name="danhmuc" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Tất cả danh mục</option>';
                        foreach ($danhmuc_list as $dm) {
                            $selected = ($id_danhmuc == $dm['id']) ? 'selected' : '';
                            $content .= '<option value="' . $dm['id'] . '" ' . $selected . '>' . $dm['name'] . '</option>';
                        }
$content .= '
                    </select>
                </div>
                
                <!-- Sắp xếp -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sắp xếp</label>
                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="newest" ' . ($sort === 'newest' ? 'selected' : '') . '>Mới nhất</option>
                        <option value="price_asc" ' . ($sort === 'price_asc' ? 'selected' : '') . '>Giá tăng dần</option>
                        <option value="price_desc" ' . ($sort === 'price_desc' ? 'selected' : '') . '>Giá giảm dần</option>
                        <option value="name_asc" ' . ($sort === 'name_asc' ? 'selected' : '') . '>Tên A-Z</option>
                    </select>
                </div>
                
                <!-- Nút tìm kiếm -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary/90 transition duration-200">
                        <i class="ri-search-line mr-2"></i>Tìm kiếm
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Info -->
    <div class="flex justify-between items-center">
        <p class="text-gray-600">Tìm thấy <span class="font-semibold">' . count($sanpham_list) . '</span> sản phẩm</p>
        <div class="flex space-x-2">
            <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50" onclick="setViewMode(\'grid\')">
                <i class="ri-grid-line"></i>
            </button>
            <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50" onclick="setViewMode(\'list\')">
                <i class="ri-list-check"></i>
            </button>
        </div>
    </div>

    <!-- Products Grid -->
    <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">';

if (empty($sanpham_list)) {
    $content .= '
        <div class="col-span-full text-center py-12">
            <i class="ri-search-line text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Không tìm thấy sản phẩm</h3>
            <p class="text-gray-500">Hãy thử tìm kiếm với từ khóa khác hoặc chọn danh mục khác</p>
        </div>';
} else {
    foreach ($sanpham_list as $sp) {
        $gia_sau_giam = $sanpham->getGiaSauGiam($sp['Price'], $sp['Sale']);
        $gia_format = $sanpham->formatPrice($gia_sau_giam);
        $gia_goc = $sanpham->formatPrice($sp['Price']);
        
        $content .= '
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200 group">
            <!-- Product Image -->
            <div class="relative overflow-hidden">
                <img src="' . $sp['image'] . '" alt="' . htmlspecialchars($sp['Name']) . '" 
                     class="w-full h-48 object-cover group-hover:scale-105 transition duration-200">
                ' . ($sp['Sale'] > 0 ? '<div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold">-' . $sp['Sale'] . '%</div>' : '') . '
                <div class="absolute top-2 right-2 bg-gray-800/80 text-white px-2 py-1 rounded text-xs">
                    <i class="ri-eye-line mr-1"></i>' . number_format($sp['Viewsp']) . '
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="p-4">
                <!-- Brand & Category -->
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs bg-primary/10 text-primary px-2 py-1 rounded">' . $sp['ten_hang'] . '</span>
                    <span class="text-xs text-gray-500">' . $sp['ten_danhmuc'] . '</span>
                </div>
                
                <!-- Product Name -->
                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-primary transition duration-200">
                    <a href="index.php?act=chitiet&id=' . $sp['id_sp'] . '">' . htmlspecialchars($sp['Name']) . '</a>
                </h3>
                
                <!-- Price -->
                <div class="mb-3">
                    <span class="text-lg font-bold text-primary">' . $gia_format . '</span>
                    ' . ($sp['Sale'] > 0 ? '<span class="text-sm text-gray-500 line-through ml-2">' . $gia_goc . '</span>' : '') . '
                </div>
                
                <!-- Description -->
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">' . htmlspecialchars($sp['Decribe']) . '</p>
                
                <!-- Stock & Actions -->
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">
                        <i class="ri-store-line mr-1"></i>Còn ' . $sp['Mount'] . ' sản phẩm
                    </span>
                    <div class="flex space-x-2">
                        <button onclick="addToCart(' . $sp['id_sp'] . ')" 
                                class="bg-primary text-white px-3 py-1 rounded text-sm hover:bg-primary/90 transition duration-200">
                            <i class="ri-shopping-cart-line mr-1"></i>Mua
                        </button>
                        <a href="index.php?act=chitiet&id=' . $sp['id_sp'] . '" 
                           class="border border-gray-300 text-gray-700 px-3 py-1 rounded text-sm hover:bg-gray-50 transition duration-200">
                            Chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>';
    }
}

$content .= '
    </div>

    <!-- Products List View (Hidden by default) -->
    <div id="products-list" class="hidden space-y-4">';

if (!empty($sanpham_list)) {
    foreach ($sanpham_list as $sp) {
        $gia_sau_giam = $sanpham->getGiaSauGiam($sp['Price'], $sp['Sale']);
        $gia_format = $sanpham->formatPrice($gia_sau_giam);
        $gia_goc = $sanpham->formatPrice($sp['Price']);
        
        $content .= '
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
            <div class="flex items-center space-x-6">
                <!-- Product Image -->
                <div class="relative">
                    <img src="' . $sp['image'] . '" alt="' . htmlspecialchars($sp['Name']) . '" 
                         class="w-32 h-32 object-cover rounded-lg">
                    ' . ($sp['Sale'] > 0 ? '<div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold">-' . $sp['Sale'] . '%</div>' : '') . '
                </div>
                
                <!-- Product Info -->
                <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-2">
                        <span class="text-sm bg-primary/10 text-primary px-2 py-1 rounded">' . $sp['ten_hang'] . '</span>
                        <span class="text-sm text-gray-500">' . $sp['ten_danhmuc'] . '</span>
                        <span class="text-sm text-gray-500">
                            <i class="ri-eye-line mr-1"></i>' . number_format($sp['Viewsp']) . ' lượt xem
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">
                        <a href="index.php?act=chitiet&id=' . $sp['id_sp'] . '" class="hover:text-primary transition duration-200">
                            ' . htmlspecialchars($sp['Name']) . '
                        </a>
                    </h3>
                    
                    <p class="text-gray-600 mb-3">' . htmlspecialchars($sp['Decribe']) . '</p>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-bold text-primary">' . $gia_format . '</span>
                            ' . ($sp['Sale'] > 0 ? '<span class="text-lg text-gray-500 line-through ml-2">' . $gia_goc . '</span>' : '') . '
                            <div class="text-sm text-gray-500 mt-1">
                                <i class="ri-store-line mr-1"></i>Còn ' . $sp['Mount'] . ' sản phẩm
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="addToCart(' . $sp['id_sp'] . ')" 
                                    class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition duration-200">
                                <i class="ri-shopping-cart-line mr-2"></i>Thêm vào giỏ
                            </button>
                            <a href="index.php?act=chitiet&id=' . $sp['id_sp'] . '" 
                               class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-50 transition duration-200">
                                Chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }
}

$content .= '
    </div>
</div>

<script>
function setViewMode(mode) {
    const gridView = document.getElementById("products-grid");
    const listView = document.getElementById("products-list");
    
    if (mode === "grid") {
        gridView.classList.remove("hidden");
        listView.classList.add("hidden");
    } else {
        gridView.classList.add("hidden");
        listView.classList.remove("hidden");
    }
}

function addToCart(productId) {
    // TODO: Implement add to cart functionality
    alert("Đã thêm sản phẩm vào giỏ hàng!");
}

// Add line-clamp utility
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
});
</script>';

renderPage("Sản phẩm - Điện Lạnh KV", $content);
?> 