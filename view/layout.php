<?php
// Layout template cho tất cả các trang
function renderPage($title = "Myweb", $content = "") {
    include "header.php";
    ?>
    <main class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4">
            <?php echo $content; ?>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Về chúng tôi</h3>
                    <p class="text-gray-300 text-sm">Công ty chuyên cung cấp các sản phẩm và dịch vụ chất lượng cao.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liên kết</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-300 hover:text-white">Trang chủ</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Sản phẩm</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Dịch vụ</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Liên hệ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Hỗ trợ</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-300 hover:text-white">FAQ</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Hướng dẫn</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Bảo hành</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liên hệ</h3>
                    <div class="space-y-2 text-sm text-gray-300">
                        <p>Hotline: 1900 6789</p>
                        <p>Email: info@myweb.com</p>
                        <p>Địa chỉ: 123 Đường ABC, Quận XYZ</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-300">
                <p>&copy; 2024 Myweb. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>
    </body>
    </html>
    <?php
}
?> 