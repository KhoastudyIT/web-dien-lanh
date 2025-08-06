<?php
include_once __DIR__ . '/../layout/layout.php';

$content = '
<!-- Hero Section with Banner -->
<section class="relative bg-cover bg-center bg-no-repeat py-32" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(\'/project/view/image/bannerGioiThieu.jpg\');">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">GIỚI THIỆU THI CÔNG ĐIỆN LẠNH KV</h1>
            <p class="text-xl md:text-2xl mb-8 max-w-4xl mx-auto">
                Với những ưu điểm vượt trội, mô hình tổng thầu thiết kế và thi công (Design & Build – D&B) đang ngày càng được áp dụng rộng rãi trong ngành xây dựng.
            </p>
            <div class="flex flex-wrap justify-center gap-8 mt-12">
                <div class="text-center">
                    <div class="text-3xl font-bold mb-2">50+</div>
                    <div class="text-blue-200">Dự án thi công</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold mb-2">100+</div>
                    <div class="text-blue-200">Tỷ đồng doanh số</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold mb-2">25+</div>
                    <div class="text-blue-200">Nhân viên</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold mb-2">15+</div>
                    <div class="text-blue-200">Đội thi công</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Company Introduction -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">CÔNG TY TNHH ĐIỆN LẠNH KV</h2>
                <p class="text-lg text-gray-700 mb-6">
                    Với sứ mệnh tạo ra không gian sống và làm việc lý tưởng, Công ty TNHH điện lạnh KV luôn đi đầu trong lĩnh vực cung cấp các sản phẩm và dịch vụ điều hòa không khí chất lượng cao.
                </p>
                <p class="text-gray-600 mb-6">
                    Sở hữu đội ngũ kĩ thuật viên tận tâm, chuyên nghiệp và giàu kinh nghiệm, chúng tôi mang tới cho quý khách hàng một loạt các dịch vụ đa dạng từ cung cấp thiết bị, lắp đặt, thi công hệ thống máy lạnh đến sửa chữa, vệ sinh, bảo trì máy lạnh định kỳ.
                </p>
                <p class="text-gray-600">
                    Đảm bảo các thiết bị máy lạnh của bạn luôn hoạt động với hiệu suất tuyệt vời, mang tới cho bạn không gian sống, làm việc luôn luôn thoải mái và tiện nghi nhất.
                </p>
            </div>
            <div class="relative">
                <img src="/project/view/image/hinhgioithieu1.jpg" alt="Điện Lạnh KV - Thi công chuyên nghiệp" class="w-full h-96 object-cover rounded-lg shadow-lg">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent rounded-lg"></div>
            </div>
        </div>
    </div>
</section>

<!-- Additional Info Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="relative">
                <img src="/project/view/image/hinhgioithieu2.jpg" alt="Điện Lạnh KV - Dịch vụ chất lượng cao" class="w-full h-96 object-cover rounded-lg shadow-lg">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent rounded-lg"></div>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">KINH NGHIỆM VÀ UY TÍN</h2>
                <p class="text-lg text-gray-700 mb-6">
                    Với nhiều năm kinh nghiệm trong lĩnh vực điện lạnh, chúng tôi đã thi công thành công hàng trăm dự án lớn nhỏ khác nhau, từ nhà ở dân dụng đến các công trình thương mại, văn phòng.
                </p>
                <p class="text-gray-600 mb-6">
                    Đội ngũ kỹ sư và thợ thi công của chúng tôi được đào tạo bài bản, có chứng chỉ hành nghề và thường xuyên được cập nhật các công nghệ mới nhất trong ngành điện lạnh.
                </p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600 mb-2">6+</div>
                        <div class="text-sm text-gray-600">Năm kinh nghiệm</div>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600 mb-2">500+</div>
                        <div class="text-sm text-gray-600">Khách hàng hài lòng</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Dịch vụ Điện Lạnh KV</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Chúng tôi cung cấp đầy đủ các dịch vụ điện lạnh từ thiết kế, thi công đến bảo trì, sửa chữa
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition duration-300 group border border-gray-100">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-200 transition duration-300">
                    <i class="ri-tools-line text-3xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Lắp Đặt Máy Lạnh</h3>
                <p class="text-gray-600">Lắp đặt chuyên nghiệp, đảm bảo an toàn và hiệu quả tối ưu cho mọi loại máy lạnh</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition duration-300 group border border-gray-100">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-green-200 transition duration-300">
                    <i class="ri-settings-3-line text-3xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Bảo Trì Định Kỳ</h3>
                <p class="text-gray-600">Bảo trì định kỳ giúp máy lạnh hoạt động ổn định và tiết kiệm điện năng</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition duration-300 group border border-gray-100">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-red-200 transition duration-300">
                    <i class="ri-wrench-line text-3xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Sửa Chữa Nhanh</h3>
                <p class="text-gray-600">Sửa chữa nhanh chóng, chính xác với đội ngũ kỹ thuật viên giàu kinh nghiệm</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition duration-300 group border border-gray-100">
                <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-purple-200 transition duration-300">
                    <i class="ri-customer-service-2-line text-3xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Tư Vấn Miễn Phí</h3>
                <p class="text-gray-600">Tư vấn chọn máy lạnh phù hợp với không gian và nhu cầu sử dụng</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition duration-300 group border border-gray-100">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-yellow-200 transition duration-300">
                    <i class="ri-shield-check-line text-3xl text-yellow-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Bảo Hành Chính Hãng</h3>
                <p class="text-gray-600">Bảo hành chính hãng với thời gian bảo hành dài hạn, đảm bảo quyền lợi khách hàng</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition duration-300 group border border-gray-100">
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-indigo-200 transition duration-300">
                    <i class="ri-truck-line text-3xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Vận Chuyển Miễn Phí</h3>
                <p class="text-gray-600">Vận chuyển và lắp đặt miễn phí trong khu vực nội thành và các tỉnh lân cận</p>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Tại Sao Chọn Điện Lạnh KV?</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Chúng tôi cam kết mang đến dịch vụ chất lượng cao với những ưu điểm vượt trội
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-medal-line text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Chất Lượng Cao</h3>
                <p class="text-gray-600">Sử dụng thiết bị và vật tư chất lượng cao, đảm bảo độ bền và hiệu quả</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-time-line text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Nhanh Chóng</h3>
                <p class="text-gray-600">Thời gian thi công nhanh chóng, không làm gián đoạn sinh hoạt của gia đình</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-money-dollar-circle-line text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Giá Cả Hợp Lý</h3>
                <p class="text-gray-600">Báo giá minh bạch, cạnh tranh và không phát sinh chi phí phụ</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-customer-service-2-line text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hỗ Trợ 24/7</h3>
                <p class="text-gray-600">Đội ngũ hỗ trợ khách hàng hoạt động 24/7, sẵn sàng giải đáp mọi thắc mắc</p>
            </div>
        </div>
    </div>
</section>

<!-- Partner Brands Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Đối Tác Thương Hiệu</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Chúng tôi là đại lý chính thức của các thương hiệu điện lạnh hàng đầu thế giới
            </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 items-center">
            <div class="flex justify-center">
                <img src="/project/view/image/logoDaikin.jpg" alt="Daikin" class="h-16 object-contain filter grayscale hover:grayscale-0 transition-all duration-300">
            </div>
            <div class="flex justify-center">
                <img src="/project/view/image/logoLG.jpg" alt="LG" class="h-16 object-contain filter grayscale hover:grayscale-0 transition-all duration-300">
            </div>
            <div class="flex justify-center">
                <img src="/project/view/image/logoMitsubishi.jpg" alt="Mitsubishi" class="h-16 object-contain filter grayscale hover:grayscale-0 transition-all duration-300">
            </div>
            <div class="flex justify-center">
                <img src="/project/view/image/logoPanasonic.jpg" alt="Panasonic" class="h-16 object-contain filter grayscale hover:grayscale-0 transition-all duration-300">
            </div>
            <div class="flex justify-center">
                <img src="/project/view/image/logosamsung.jpg" alt="Samsung" class="h-16 object-contain filter grayscale hover:grayscale-0 transition-all duration-300">
            </div>
            <div class="flex justify-center">
                <img src="/project/view/image/logoToshiba.jpg" alt="Toshiba" class="h-16 object-contain filter grayscale hover:grayscale-0 transition-all duration-300">
            </div>
        </div>
    </div>
</section>

<!-- Contact Information -->
<section class="py-16 bg-blue-600 text-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12">
            <div>
                <h2 class="text-3xl font-bold mb-6">Thông tin liên hệ</h2>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="ri-map-pin-line text-2xl mr-4"></i>
                        <div>
                            <div class="font-semibold">Trụ sở chính:</div>
                            <div>Số 163/50/7, Thủ Đức, TP.HCM</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="ri-phone-line text-2xl mr-4"></i>
                        <div>
                            <div class="font-semibold">Hotline:</div>
                            <div class="text-2xl font-bold">1900 6789</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="ri-mail-line text-2xl mr-4"></i>
                        <div>
                            <div class="font-semibold">Email:</div>
                            <div>dienlanhkv@gmail.com</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="ri-global-line text-2xl mr-4"></i>
                        <div>
                            <div class="font-semibold">Website:</div>
                            <div>www.dienlanhkv.com</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-3xl font-bold mb-6">Thời gian làm việc</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-blue-700 rounded-lg">
                        <span class="font-semibold">Thứ 2 - Thứ 7:</span>
                        <span>8h30 - 19h30</span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-blue-700 rounded-lg">
                        <span class="font-semibold">Chủ nhật:</span>
                        <span>8h30 - 12h</span>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Theo dõi chúng tôi</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="w-12 h-12 bg-blue-700 rounded-full flex items-center justify-center hover:bg-blue-800 transition-colors">
                            <i class="ri-facebook-fill text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-blue-700 rounded-full flex items-center justify-center hover:bg-blue-800 transition-colors">
                            <i class="ri-youtube-fill text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-blue-700 rounded-full flex items-center justify-center hover:bg-blue-800 transition-colors">
                            <i class="ri-instagram-fill text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="relative bg-cover bg-center bg-no-repeat py-20" style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url(\'/project/view/image/banner2.png\');">
    <div class="max-w-7xl mx-auto px-4 text-center text-white">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">SẴN SÀNG TƯ VẤN VÀ THI CÔNG</h2>
        <p class="text-xl mb-8 max-w-3xl mx-auto">
            Hãy liên hệ ngay với chúng tôi để được tư vấn miễn phí và nhận báo giá chi tiết cho dự án của bạn
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/project/index.php?act=lienhe" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                <i class="ri-phone-line mr-2"></i>Liên hệ ngay
            </a>
            <a href="/project/index.php?act=sanpham" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-gray-900 font-bold py-3 px-8 rounded-lg transition-colors">
                <i class="ri-shopping-bag-line mr-2"></i>Xem sản phẩm
            </a>
        </div>
    </div>
</section>';

renderPage("Giới thiệu - Điện Lạnh KV", $content);
?> 