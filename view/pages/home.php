<?php
// Sử dụng đường dẫn tương đối đơn giản
include_once __DIR__ . '/../layout/layout.php';
include_once __DIR__ . '/../../model/sanpham.php';

$content = '
<!-- Success/Error Messages -->
' . (isset($_GET['success']) ? '
<div class="max-w-7xl mx-auto px-4 py-4">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <div class="flex items-center">
            <i class="ri-check-line mr-2"></i>
            <span>' . htmlspecialchars($_GET['success']) . '</span>
        </div>
    </div>
</div>' : '') . '

<!-- Hero Banner Section -->
<section class="relative overflow-hidden">
    <!-- Single Banner -->
    <div class="absolute inset-0">
        <img src="/project/view/image/banner2.png" alt="Banner Điện Lạnh KV" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
    </div>
    
    <!-- Content -->
    <div class="relative max-w-7xl mx-auto px-4 py-20 lg:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="text-white">
                <h1 class="text-4xl lg:text-6xl font-bold mb-6 leading-tight">
                    Điện Lạnh <span class="text-yellow-400">KV</span><br>
                    <span class="text-2xl lg:text-3xl font-normal">Chuyên Nghiệp - Uy Tín</span>
                </h1>
                <p class="text-xl mb-8 text-gray-100 leading-relaxed">
                    Chuyên cung cấp, lắp đặt, bảo trì và sửa chữa máy lạnh chất lượng cao. 
                    Đội ngũ kỹ thuật viên giàu kinh nghiệm, dịch vụ tận tâm.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/project/index.php?act=sanpham" 
                       class="bg-yellow-400 text-blue-900 hover:bg-yellow-300 font-bold py-4 px-8 rounded-lg transition duration-300 text-center">
                        Xem Sản Phẩm
                    </a>
                    <a href="/project/index.php?act=lienhe" 
                       class="border-2 border-white text-white hover:bg-white hover:text-black font-bold py-4 px-8 rounded-lg transition duration-300 text-center">
                        Liên Hệ Ngay
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Dịch Vụ Chuyên Nghiệp</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Chúng tôi cung cấp đầy đủ các dịch vụ điện lạnh từ lắp đặt đến bảo trì, sửa chữa
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition duration-300 group">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-200 transition duration-300">
                    <i class="ri-tools-line text-3xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Lắp Đặt Máy Lạnh</h3>
                <p class="text-gray-600">Lắp đặt chuyên nghiệp, đảm bảo an toàn và hiệu quả tối ưu</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition duration-300 group">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-green-200 transition duration-300">
                    <i class="ri-settings-3-line text-3xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Bảo Trì Định Kỳ</h3>
                <p class="text-gray-600">Bảo trì định kỳ giúp máy lạnh hoạt động ổn định và tiết kiệm điện</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition duration-300 group">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-red-200 transition duration-300">
                    <i class="ri-wrench-line text-3xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Sửa Chữa Nhanh</h3>
                <p class="text-gray-600">Sửa chữa nhanh chóng, chính xác với đội ngũ kỹ thuật viên giàu kinh nghiệm</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition duration-300 group">
                <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-purple-200 transition duration-300">
                    <i class="ri-customer-service-2-line text-3xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Tư Vấn Miễn Phí</h3>
                <p class="text-gray-600">Tư vấn chọn máy lạnh phù hợp với không gian và nhu cầu sử dụng</p>
            </div>
        </div>
    </div>
</section>

<!-- About Company Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">Về Điện Lạnh KV</h2>
                <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                    Điện Lạnh KV là đơn vị chuyên cung cấp dịch vụ điện lạnh uy tín tại Thủ Đức, TP.HCM. 
                    Với hơn 10 năm kinh nghiệm trong lĩnh vực điện lạnh, chúng tôi tự hào mang đến cho khách hàng 
                    những sản phẩm chất lượng cao và dịch vụ chuyên nghiệp.
                </p>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    Đội ngũ kỹ thuật viên của chúng tôi được đào tạo bài bản, có chứng chỉ hành nghề và 
                    kinh nghiệm thực tế. Chúng tôi cam kết mang đến sự hài lòng tuyệt đối cho mọi khách hàng.
                </p>
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">10+</div>
                        <div class="text-gray-600">Năm Kinh Nghiệm</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">1000+</div>
                        <div class="text-gray-600">Khách Hàng Hài Lòng</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">24/7</div>
                        <div class="text-gray-600">Hỗ Trợ Khách Hàng</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">100%</div>
                        <div class="text-gray-600">Chất Lượng Đảm Bảo</div>
                    </div>
                </div>
            </div>
            
            <div class="relative">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-8 text-white">
                    <h3 class="text-2xl font-bold mb-6">Tại Sao Chọn Chúng Tôi?</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <i class="ri-check-line text-yellow-400 text-xl mt-1"></i>
                            <div>
                                <h4 class="font-semibold">Đội ngũ kỹ thuật viên chuyên nghiệp</h4>
                                <p class="text-blue-100 text-sm">Có chứng chỉ hành nghề và kinh nghiệm thực tế</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="ri-check-line text-yellow-400 text-xl mt-1"></i>
                            <div>
                                <h4 class="font-semibold">Sản phẩm chính hãng</h4>
                                <p class="text-blue-100 text-sm">Cam kết 100% hàng chính hãng với bảo hành dài hạn</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="ri-check-line text-yellow-400 text-xl mt-1"></i>
                            <div>
                                <h4 class="font-semibold">Dịch vụ nhanh chóng</h4>
                                <p class="text-blue-100 text-sm">Phục vụ 24/7, có mặt trong vòng 30 phút</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="ri-check-line text-yellow-400 text-xl mt-1"></i>
                            <div>
                                <h4 class="font-semibold">Giá cả hợp lý</h4>
                                <p class="text-blue-100 text-sm">Báo giá minh bạch, không phát sinh chi phí</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Sản Phẩm Nổi Bật</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Khám phá các sản phẩm máy lạnh chất lượng cao từ các thương hiệu uy tín
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">';
        
        // Lấy sản phẩm nổi bật
        $sanpham = new sanpham();
        $sanpham_noibat = $sanpham->getSanphamNoiBat();
        
        if (empty($sanpham_noibat)) {
            $sanpham_noibat = $sanpham->getSanphamMoiNhat();
        }
        
        foreach (array_slice($sanpham_noibat, 0, 8) as $sp) {
            $gia_sau_giam = $sanpham->getGiaSauGiam($sp['Price'], $sp['Sale']);
            $gia_format = $sanpham->formatPrice($gia_sau_giam);
            
            $content .= '
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 group">
                <div class="relative">
                    <img src="/project/view/image/' . $sp['image'] . '" alt="' . htmlspecialchars($sp['Name']) . '" 
                         class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                    ' . ($sp['Sale'] > 0 ? '<div class="absolute top-3 left-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">-' . $sp['Sale'] . '%</div>' : '') . '
                    <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition duration-300">
                        <button class="bg-white/90 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg">
                            <i class="ri-heart-line"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">' . $sp['ten_hang'] . '</span>
                        <span class="text-xs text-gray-500">' . $sp['ten_danhmuc'] . '</span>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition duration-300">
                        <a href="/project/index.php?act=chitiet&id=' . $sp['id_sp'] . '">' . htmlspecialchars($sp['Name']) . '</a>
                    </h3>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xl font-bold text-blue-600">' . $gia_format . '</span>
                        ' . ($sp['Sale'] > 0 ? '<span class="text-sm text-gray-500 line-through">' . $sanpham->formatPrice($sp['Price']) . '</span>' : '') . '
                    </div>
                    <div class="flex space-x-2">
                        <a href="/project/index.php?act=chitiet&id=' . $sp['id_sp'] . '" 
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg font-medium transition duration-300">
                            Chi tiết
                        </a>
                        <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 p-2 rounded-lg transition duration-300">
                            <i class="ri-shopping-cart-line"></i>
                        </button>
                    </div>
                </div>
            </div>';
        }
        
        $content .= '
        </div>
        
        <div class="text-center mt-12">
            <a href="/project/index.php?act=sanpham" 
               class="inline-flex items-center bg-blue-400 hover:bg-blue-500 text-white font-bold py-4 px-8 rounded-lg transition duration-300">
                Xem Tất Cả Sản Phẩm
                <i class="ri-arrow-right-line ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-blue-50 to-indigo-100 text-gray-900">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-3xl lg:text-4xl font-bold mb-6">Cần Tư Vấn Hoặc Hỗ Trợ?</h2>
        <p class="text-xl mb-8 text-gray-600 max-w-3xl mx-auto">
            Đội ngũ chuyên viên của chúng tôi luôn sẵn sàng tư vấn và hỗ trợ bạn 24/7. 
            Hãy liên hệ ngay để được tư vấn miễn phí!
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="tel:19006789" 
               class="bg-blue-600 text-white hover:bg-blue-700 font-bold py-4 px-8 rounded-lg transition duration-300">
                <i class="ri-phone-line mr-2"></i>
                Gọi Ngay: 1900 6789
            </a>
            <a href="/project/index.php?act=lienhe" 
               class="border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-bold py-4 px-8 rounded-lg transition duration-300">
                Liên Hệ Tư Vấn
            </a>
        </div>
    </div>
</section>';

renderPage("Trang chủ - Điện Lạnh KV", $content);
?> 