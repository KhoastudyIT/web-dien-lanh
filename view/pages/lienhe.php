<?php
// Sử dụng layout chung
include_once __DIR__ . '/../layout/layout.php';

$content = '
<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 bg-white min-h-screen py-8">
    <!-- Success/Error Messages -->
    ' . (isset($_GET['success']) ? '
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <div class="flex items-center">
            <i class="ri-check-line mr-2"></i>
            <span>' . htmlspecialchars($_GET['success']) . '</span>
        </div>
    </div>' : '') . '
    
    ' . (isset($_GET['error']) ? '
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <div class="flex items-center">
            <i class="ri-error-warning-line mr-2"></i>
            <span>' . htmlspecialchars($_GET['error']) . '</span>
        </div>
    </div>' : '') . '

    <!-- Page Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Liên hệ</h1>
        <div class="flex flex-col md:flex-row items-center justify-center space-y-4 md:space-y-0 md:space-x-6 text-gray-600">
            <div class="flex items-center">
                <i class="ri-phone-line text-primary mr-2"></i>
                <span>1900 6789</span>
            </div>
            <div class="flex items-center">
                <i class="ri-mail-line text-primary mr-2"></i>
                <span>info@dienlanhkv.vn</span>
            </div>
            <div class="flex items-center">
                <i class="ri-map-pin-line text-primary mr-2"></i>
                <span class="text-center md:text-left">163/50/7, Đặng Văn Bi, Thủ Đức</span>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        <!-- Contact Info -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Cần hỗ trợ về máy lạnh? <span class="text-primary">Hãy liên hệ với chúng tôi!</span></h2>
            
            <p class="text-gray-600 mb-8 leading-relaxed">
                Điện Lạnh KV luôn sẵn sàng lắng nghe và hỗ trợ bạn với các vấn đề về máy lạnh. Dù bạn cần tư vấn chọn máy, lắp đặt, bảo trì hay bất kỳ thắc mắc nào khác, hãy liên hệ với chúng tôi qua form liên hệ hoặc số điện thoại hotline.
            </p>
            
            <p class="text-gray-600 mb-8 leading-relaxed">
                Đội ngũ chuyên gia của Điện Lạnh KV luôn tận tâm mang đến cho bạn sự hài lòng và trải nghiệm mát lạnh hoàn hảo.
            </p>

            <!-- Contact Details -->
            <div class="space-y-6">
                <div class="flex items-start space-x-4 contact-info-item bg-blue-50 hover:bg-blue-100 p-4 rounded-lg transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 hover:bg-blue-700 transition-colors">
                        <i class="ri-map-pin-line text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-1">Địa chỉ</h3>
                        <p class="text-blue-700">163/50/7, Đặng Văn Bi, Thủ Đức</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4 contact-info-item bg-blue-50 hover:bg-blue-100 p-4 rounded-lg transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 hover:bg-blue-700 transition-colors">
                        <i class="ri-phone-line text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-1">Số điện thoại</h3>
                        <p class="text-blue-700">1900 6789</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4 contact-info-item bg-blue-50 hover:bg-blue-100 p-4 rounded-lg transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 hover:bg-blue-700 transition-colors">
                        <i class="ri-mail-line text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-1">Email</h3>
                        <p class="text-blue-700">info@dienlanhkv.vn</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4 contact-info-item bg-blue-50 hover:bg-blue-100 p-4 rounded-lg transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 hover:bg-blue-700 transition-colors">
                        <i class="ri-time-line text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-1">Thời gian làm việc</h3>
                        <p class="text-blue-700">Từ thứ 2 - 7: 8h30 - 19h30<br>Chủ nhật: 8h30 - 12h</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-blue-50 rounded-lg shadow-lg p-8 border border-blue-200">
            <h3 class="text-xl font-bold text-blue-900 mb-6">Đăng ký NHẬN KHUYẾN MÃI</h3>
            
            <form method="POST" action="/project/index.php?act=contact_submit" class="space-y-6">
                <div>
                    <label for="service" class="block text-sm font-medium text-gray-700 mb-2">Chọn dịch vụ *</label>
                    <select name="service" id="service" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Chọn dịch vụ</option>
                        <option value="thi-cong-ong-dong">Thi công ống đồng</option>
                        <option value="thi-cong-may-lanh">Thi công máy lạnh</option>
                        <option value="thi-cong-may-lanh-am-tran">Thi công máy lạnh âm trần</option>
                        <option value="thi-cong-may-lanh-giau-tran">Thi công máy lạnh giấu trần</option>
                        <option value="thi-cong-may-lanh-trung-tam">Thi công máy lạnh trung tâm</option>
                        <option value="lap-dat-may-lanh">Lắp đặt máy lạnh</option>
                        <option value="bao-tri-may-lanh">Bảo trì máy lạnh</option>
                        <option value="sua-chua-may-lanh">Sửa chữa máy lạnh</option>
                    </select>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Họ và tên *</label>
                    <input type="text" name="name" id="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Nhập họ và tên">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại *</label>
                    <input type="tel" name="phone" id="phone" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Nhập số điện thoại">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Nhập email">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                    <input type="text" name="address" id="address" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Nhập địa chỉ">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Nội dung tin nhắn</label>
                    <textarea name="message" id="message" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Nhập nội dung tin nhắn"></textarea>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                    Gửi tin nhắn
                </button>
            </form>
        </div>
    </div>

    <!-- Map Section -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-16">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Bản đồ</h3>
        <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden relative">
            <!-- Google Maps iframe trực tiếp -->
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.484!2d106.7500!3d10.8500!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTDCsDUxJzAwLjAiTiAxMDbCsDQ1JzAwLjAiRQ!5e0!3m2!1svi!2s!4v1234567890"
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"
                title="Điện Lạnh KV - Google Maps">
            </iframe>
            
            <!-- Overlay với thông tin -->
            <div class="absolute top-2 left-2 bg-white bg-opacity-90 px-3 py-2 rounded-lg shadow-md z-10">
                <p class="text-sm font-semibold text-gray-800">Điện Lạnh KV</p>
                <p class="text-xs text-gray-600">163/50/7, Đặng Văn Bi, Thủ Đức</p>
            </div>
            
            <!-- Nút mở Google Maps -->
            <div class="absolute top-2 right-2 z-10">
                <a href="https://maps.google.com/?q=163/50/7,+Đặng+Văn+Bi,+Thủ+Đức" target="_blank" 
                   class="inline-flex items-center bg-white bg-opacity-90 hover:bg-opacity-100 px-3 py-2 rounded-lg shadow-md text-sm text-primary hover:text-primary-dark transition-colors">
                    <i class="ri-external-link-line mr-1"></i>
                    Mở Google Maps
                </a>
            </div>
        </div>
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-500">163/50/7, Đặng Văn Bi, Thủ Đức</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-2">
                <a href="https://maps.google.com/?q=163/50/7,+Đặng+Văn+Bi,+Thủ+Đức" target="_blank" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                    <i class="ri-external-link-line mr-1"></i>
                    Xem trên Google Maps
                </a>
                <a href="https://maps.google.com/directions?daddr=163/50/7,+Đặng+Văn+Bi,+Thủ+Đức" target="_blank" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                    <i class="ri-route-line mr-1"></i>
                    Chỉ đường
                </a>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-customer-service-line text-primary text-2xl"></i>
            </div>
            <h4 class="font-semibold text-gray-900 mb-2">Hỗ trợ 24/7</h4>
            <p class="text-gray-600 text-sm">Đội ngũ chuyên viên tư vấn luôn sẵn sàng hỗ trợ bạn mọi lúc</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-tools-line text-primary text-2xl"></i>
            </div>
            <h4 class="font-semibold text-gray-900 mb-2">Dịch vụ chuyên nghiệp</h4>
            <p class="text-gray-600 text-sm">Đội ngũ kỹ thuật viên có kinh nghiệm và chuyên môn cao</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-shield-check-line text-primary text-2xl"></i>
            </div>
            <h4 class="font-semibold text-gray-900 mb-2">Bảo hành uy tín</h4>
            <p class="text-gray-600 text-sm">Chế độ bảo hành chính hãng với thời gian dài hạn</p>
        </div>
    </div>
</div>

<style>
/* Custom styles for contact page */
.contact-form input:focus,
.contact-form select:focus,
.contact-form textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.contact-info-item:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .contact-details {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .contact-details > div {
        margin-bottom: 1rem;
    }
}
</style>';

renderPage("Liên hệ - Điện Lạnh KV", $content);
?> 