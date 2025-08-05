<?php
// Layout template cho tất cả các trang
function renderPage($title = "Điện Lạnh KV", $content = "") {
    include __DIR__ . '/header.php';
    ?>
    <main class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4">
            <?php echo $content; ?>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <img src="/project/view/image/logodienlanh.png" alt="Điện Lạnh KV" style="width:40px;">
                    <p class="text-gray-400 mb-4">
                        Chuyên cung cấp và sửa chữa các thiết bị điện lạnh chất lượng cao
                        với dịch vụ tận tâm.
                    </p>
                    <div class="flex space-x-4">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <i class="ri-facebook-fill text-blue-500 text-lg"></i>
                        </div>
                        <div class="w-8 h-8 flex items-center justify-center">
                            <i class="ri-youtube-fill text-red-500 text-lg"></i>
                        </div>
                        <div class="w-8 h-8 flex items-center justify-center">
                            <i class="ri-instagram-fill text-pink-500 text-lg"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Sản Phẩm</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>
                            <a href="#" class="hover:text-white transition-colors">Máy lạnh</a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-white transition-colors">Tủ lạnh</a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-white transition-colors">Máy giặt</a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-white transition-colors">Phụ kiện</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Dịch Vụ</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>
                            <a href="#" class="hover:text-white transition-colors">Sửa chữa</a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-white transition-colors">Bảo trì</a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-white transition-colors">Lắp đặt</a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-white transition-colors">Bảo hành</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Liên Hệ</h4>
                    <div class="space-y-3 text-gray-400">
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 flex items-center justify-center">
                                <i class="ri-map-pin-line text-sm"></i>
                            </div>
                            <span class="text-sm">163/50/7, Đặng Văn Bi, Thủ Đức</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 flex items-center justify-center">
                                <i class="ri-phone-line text-sm"></i>
                            </div>
                            <span class="text-sm">84+ 346868288</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 flex items-center justify-center">
                                <i class="ri-mail-line text-sm"></i>
                            </div>
                            <span class="text-sm">info@dienlanhkv.vn</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Điện Lạnh KV Thủ Đức . Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>
    </body>
    </html>
    <?php
}
?> 