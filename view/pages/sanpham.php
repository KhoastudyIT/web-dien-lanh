<?php
// Sử dụng đường dẫn 
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/sanpham.php';
include_once __DIR__ . '/../../model/danhmuc.php';
include_once __DIR__ . '/../../controller/controller.php';

$sanpham = new sanpham();
$danhmuc = new danhmuc();

// Lấy danh sách danh mục và hãng
$danhmuc_list = $danhmuc->getDS_Danhmuc();
$controller = new controller();
$brands_list = $controller->getAllBrands();

// Xử lý bộ lọc - lấy tất cả sản phẩm trước
$all_products = $sanpham->getDS_Sanpham();

// Xử lý bộ lọc
$id_danhmuc = isset($_GET['danhmuc']) ? (int)$_GET['danhmuc'] : 0;
$id_hang = isset($_GET['hang']) ? (int)$_GET['hang'] : 0;
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 0;
$sale_filter = isset($_GET['sale']) ? $_GET['sale'] : '';
$stock_filter = isset($_GET['stock']) ? $_GET['stock'] : '';

// Lọc sản phẩm dựa trên tất cả điều kiện
$sanpham_list = $all_products;

// Filter theo từ khóa tìm kiếm
if ($keyword) {
    $sanpham_list = array_filter($sanpham_list, function($sp) use ($keyword) {
        return stripos($sp['Name'], $keyword) !== false || 
               stripos($sp['Decribe'], $keyword) !== false ||
               stripos($sp['ten_hang'], $keyword) !== false ||
               stripos($sp['ten_danhmuc'], $keyword) !== false;
    });
}

// Filter theo danh mục
if ($id_danhmuc > 0) {
    $sanpham_list = array_filter($sanpham_list, function($sp) use ($id_danhmuc) {
        return $sp['id_danhmuc'] == $id_danhmuc;
    });
}

// Filter theo hãng
if ($id_hang > 0) {
    $sanpham_list = array_filter($sanpham_list, function($sp) use ($id_hang) {
        return $sp['id_hang'] == $id_hang;
    });
}

// Filter theo giá
if ($min_price > 0 || $max_price > 0) {
    $sanpham_list = array_filter($sanpham_list, function($sp) use ($min_price, $max_price) {
        if ($min_price > 0 && $sp['Price'] < $min_price) return false;
        if ($max_price > 0 && $sp['Price'] > $max_price) return false;
        return true;
    });
}

// Filter theo khuyến mãi
if ($sale_filter === 'yes') {
    $sanpham_list = array_filter($sanpham_list, function($sp) {
        return $sp['Sale'] > 0;
    });
} elseif ($sale_filter === 'no') {
    $sanpham_list = array_filter($sanpham_list, function($sp) {
        return $sp['Sale'] == 0;
    });
}

// Filter theo tình trạng hàng
if ($stock_filter === 'in_stock') {
    $sanpham_list = array_filter($sanpham_list, function($sp) {
        return $sp['Mount'] > 0;
    });
} elseif ($stock_filter === 'low_stock') {
    $sanpham_list = array_filter($sanpham_list, function($sp) {
        return $sp['Mount'] > 0 && $sp['Mount'] <= 10;
    });
} elseif ($stock_filter === 'out_of_stock') {
    $sanpham_list = array_filter($sanpham_list, function($sp) {
        return $sp['Mount'] == 0;
    });
}

// Reset array keys
$sanpham_list = array_values($sanpham_list);

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
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Sản phẩm điện lạnh KV</h1>
        <p class="text-gray-600">Khám phá các sản phẩm điện lạnh chất lượng cao từ các thương hiệu uy tín</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="" id="filterForm" class="space-y-4">
            <input type="hidden" name="act" value="sanpham">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                    <div class="relative">
                        <input type="text" name="search" value="' . htmlspecialchars($keyword) . '" 
                               placeholder="Tên sản phẩm, hãng..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <i class="ri-search-line text-gray-400"></i>
                        </div>
                    </div>
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
                
                <!-- Hãng -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hãng</label>
                    <select name="hang" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Tất cả hãng</option>';
                        foreach ($brands_list as $brand) {
                            $selected = ($id_hang == $brand['id_hang']) ? 'selected' : '';
                            $content .= '<option value="' . $brand['id_hang'] . '" ' . $selected . '>' . $brand['ten_hang'] . '</option>';
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
            
            <!-- Advanced Filters -->
            <div class="border-t pt-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-700">Bộ lọc chi tiết -> </h3>
                    <button type="button" onclick="toggleAdvancedFilters()" class="text-primary text-sm hover:underline">
                        <i class="ri-filter-line mr-1"></i>Hiển thị bộ lọc
                    </button>
                </div>
                <div id="advancedFilters" class="hidden grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Khoảng giá</label>
                        <div class="flex space-x-2">
                            <input type="number" name="min_price" placeholder="Từ" value="' . $min_price . '"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                            <input type="number" name="max_price" placeholder="Đến" value="' . $max_price . '"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                        </div>
                    </div>
                    
                    <!-- Sale Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Khuyến mãi</label>
                        <select name="sale" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                            <option value="">Tất cả</option>
                            <option value="yes" ' . ($sale_filter === 'yes' ? 'selected' : '') . '>Có khuyến mãi</option>
                            <option value="no" ' . ($sale_filter === 'no' ? 'selected' : '') . '>Không khuyến mãi</option>
                        </select>
                    </div>
                    
                    <!-- Stock Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tình trạng</label>
                        <select name="stock" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                            <option value="">Tất cả</option>
                            <option value="in_stock" ' . ($stock_filter === 'in_stock' ? 'selected' : '') . '>Còn hàng</option>
                            <option value="low_stock" ' . ($stock_filter === 'low_stock' ? 'selected' : '') . '>Sắp hết hàng</option>
                            <option value="out_of_stock" ' . ($stock_filter === 'out_of_stock' ? 'selected' : '') . '>Hết hàng</option>
                        </select>
                    </div>
                    
                    <!-- Clear Filters -->
                    <div class="flex items-end">
                        <a href="/project/index.php?act=sanpham" class="w-full bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-200 text-center">
                            <i class="ri-refresh-line mr-2"></i>Xóa bộ lọc
                        </a>
                    </div>
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
                <img src="/project/view/image/' . $sp['image'] . '" alt="' . htmlspecialchars($sp['Name']) . '" 
                     class="w-full h-48 object-cover group-hover:scale-105 transition duration-200" onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                ' . ($sp['Sale'] > 0 ? '<div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold">-' . $sp['Sale'] . '%</div>' : '') . '
                <div class="absolute top-2 right-2 bg-gray-800/80 text-white px-2 py-1 rounded text-xs">
                    <i class="ri-eye-line mr-1"></i>' . number_format($sp['Viewsp']) . '
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="p-4">
                <!-- Brand & Category -->
                <div class="flex items-center justify-between mb-2">
                    <span class="flex items-center gap-1">
                        <img src="/project/view/image/' . $sp['logo_hang'] . '" alt="' . htmlspecialchars($sp['ten_hang']) . '" style="width:20px; height:20px; object-fit:contain;" onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                        <span class="text-xs bg-primary/10 text-primary px-2 py-1 rounded">' . $sp['ten_hang'] . '</span>
                    </span>
                    <span class="text-xs text-gray-500">' . $sp['ten_danhmuc'] . '</span>
                </div>
                
                <!-- Product Name -->
                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-primary transition duration-200">
                    <a href="/project/index.php?act=chitiet&id=' . $sp['id_sp'] . '">' . htmlspecialchars($sp['Name']) . '</a>
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
                        <form method="POST" action="/project/index.php?act=add_to_cart&id=' . $sp['id_sp'] . '" style="display:inline;">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="bg-primary text-white px-3 py-1 rounded text-sm hover:bg-primary/90 transition duration-200">
                                <i class="ri-shopping-cart-line mr-1"></i>Mua
                            </button>
                        </form>
                        <a href="/project/index.php?act=chitiet&id=' . $sp['id_sp'] . '" 
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
                    <img src="/project/view/image/' . $sp['image'] . '" alt="' . htmlspecialchars($sp['Name']) . '" 
                         class="w-32 h-32 object-cover rounded-lg" onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                    ' . ($sp['Sale'] > 0 ? '<div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold">-' . $sp['Sale'] . '%</div>' : '') . '
                </div>
                
                <!-- Product Info -->
                <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-2">
                        <span class="flex items-center gap-1">
                            <img src="/project/view/image/' . $sp['logo_hang'] . '" alt="' . htmlspecialchars($sp['ten_hang']) . '" style="width:20px; height:20px; object-fit:contain;" onerror="this.src=\'/project/view/image/logodienlanh.png\'">
                            <span class="text-sm bg-primary/10 text-primary px-2 py-1 rounded">' . $sp['ten_hang'] . '</span>
                        </span>
                        <span class="text-sm text-gray-500">' . $sp['ten_danhmuc'] . '</span>
                        <span class="text-sm text-gray-500">
                            <i class="ri-eye-line mr-1"></i>' . number_format($sp['Viewsp']) . ' lượt xem
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">
                        <a href="/project/index.php?act=chitiet&id=' . $sp['id_sp'] . '" class="hover:text-primary transition duration-200">
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
                            <form method="POST" action="/project/index.php?act=add_to_cart&id=' . $sp['id_sp'] . '" style="display:inline;">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition duration-200">
                                    <i class="ri-shopping-cart-line mr-2"></i>Thêm vào giỏ
                                </button>
                            </form>
                            <a href="/project/index.php?act=chitiet&id=' . $sp['id_sp'] . '" 
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

function toggleAdvancedFilters() {
    const advancedFilters = document.getElementById("advancedFilters");
    const toggleButton = document.querySelector("[onclick=\'toggleAdvancedFilters()\']");
    
    if (advancedFilters.classList.contains("hidden")) {
        advancedFilters.classList.remove("hidden");
        toggleButton.innerHTML = "<i class=\"ri-filter-line mr-1\"></i>Ẩn bộ lọc";
    } else {
        advancedFilters.classList.add("hidden");
        toggleButton.innerHTML = "<i class=\"ri-filter-line mr-1\"></i>Hiển thị bộ lọc";
    }
}

// Add line-clamp utility
document.addEventListener("DOMContentLoaded", function() {
    const style = document.createElement("style");
    style.textContent = ".line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }";
    document.head.appendChild(style);
    
    // Enhanced filter functionality
    const filterForm = document.getElementById("filterForm");
    const filterInputs = filterForm.querySelectorAll("input, select");
    
    // Auto-submit form when filters change (with debounce for text inputs)
    let searchTimeout;
    
    filterInputs.forEach(input => {
        if (input.type === "text" || input.type === "number") {
            input.addEventListener("input", function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    filterForm.submit();
                }, 500);
            });
        } else {
            input.addEventListener("change", function() {
                filterForm.submit();
            });
        }
    });
    
    // Show loading state during filter
    filterForm.addEventListener("submit", function() {
        const submitBtn = filterForm.querySelector("button[type=submit]");
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = "<i class=\"ri-loader-4-line animate-spin mr-2\"></i>Đang tìm...";
        submitBtn.disabled = true;
        
        // Reset button after 3 seconds if page doesn\'t reload
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });
    
    // Price range validation
    const minPriceInput = filterForm.querySelector("input[name=min_price]");
    const maxPriceInput = filterForm.querySelector("input[name=max_price]");
    
    if (minPriceInput && maxPriceInput) {
        minPriceInput.addEventListener("blur", function() {
            const minPrice = parseInt(this.value);
            const maxPrice = parseInt(maxPriceInput.value);
            
            if (minPrice > 0 && maxPrice > 0 && minPrice > maxPrice) {
                alert("Giá tối thiểu không thể lớn hơn giá tối đa!");
                this.value = "";
            }
        });
        
        maxPriceInput.addEventListener("blur", function() {
            const minPrice = parseInt(minPriceInput.value);
            const maxPrice = parseInt(this.value);
            
            if (minPrice > 0 && maxPrice > 0 && minPrice > maxPrice) {
                alert("Giá tối đa không thể nhỏ hơn giá tối thiểu!");
                this.value = "";
            }
        });
    }
    
    // Quick filter buttons
    const quickFilters = document.createElement("div");
    quickFilters.className = "flex flex-wrap gap-2 mt-4";
    quickFilters.innerHTML = `
        <button type="button" onclick="applyQuickFilter(\'sale\')" class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm hover:bg-red-200 transition-colors">
            <i class="ri-percent-line mr-1"></i>Khuyến mãi
        </button>
        <button type="button" onclick="applyQuickFilter(\'in_stock\')" class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm hover:bg-green-200 transition-colors">
            <i class="ri-store-line mr-1"></i>Còn hàng
        </button>
        <button type="button" onclick="applyQuickFilter(\'price_under_10m\')" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm hover:bg-blue-200 transition-colors">
            <i class="ri-money-dollar-circle-line mr-1"></i>Dưới 10 triệu
        </button>
        <button type="button" onclick="applyQuickFilter(\'price_10m_20m\')" class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm hover:bg-purple-200 transition-colors">
            <i class="ri-money-dollar-circle-line mr-1"></i>10-20 triệu
        </button>
    `;
    
    // Insert quick filters after the filter form
    filterForm.parentNode.insertBefore(quickFilters, filterForm.nextSibling);
});

// Quick filter functions
function applyQuickFilter(type) {
    const form = document.getElementById("filterForm");
    
    switch(type) {
        case "sale":
            form.querySelector("select[name=sale]").value = "yes";
            break;
        case "in_stock":
            form.querySelector("select[name=stock]").value = "in_stock";
            break;
        case "price_under_10m":
            form.querySelector("input[name=max_price]").value = "10000000";
            break;
        case "price_10m_20m":
            form.querySelector("input[name=min_price]").value = "10000000";
            form.querySelector("input[name=max_price]").value = "20000000";
            break;
    }
    
    form.submit();
}
</script>';

renderPage("Sản phẩm - Điện Lạnh KV", $content);
?> 