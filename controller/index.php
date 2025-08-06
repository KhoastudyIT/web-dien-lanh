<?php 
session_start();
include_once __DIR__ . '/controller.php';
include_once __DIR__ . '/../helpers/jwt_helper.php';
include_once __DIR__ . '/../model/user.php';
include_once __DIR__ . '/../model/cart.php';
include_once __DIR__ . '/../model/sanpham.php';

if(isset($_REQUEST['act'])){
    $act= $_REQUEST['act'];
    switch($act){
        case 'login':
            include "../view/pages/login.php";
            break;
        case 'register':
            include "../view/pages/register.php";
            break;
        case 'xl_login':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $password = $_POST['password'];
                
                $user = new User();
                $result = $user->login($username, $password);
                file_put_contents(__DIR__ . '/../debug_login.txt', print_r($result, true));
                
                if ($result['success']) {
                    // Tạo JWT token
                    $token = JWT::generateToken([
                        'user_id' => $result['user']['id_user'],
                        'username' => $result['user']['username'],
                        'position' => $result['user']['position']
                    ]);
                    
                    // Lưu token vào cookie
                    setcookie('auth_token', $token, time() + 3600, '/');
                    
                    header('Location: /project/controller/index.php?success=Đăng nhập thành công');
                } else {
                    header('Location: /project/controller/index.php?act=login&error=' . urlencode($result['message']));
                }
            } else {
                header('Location: /project/controller/index.php?act=login');
                exit();
            }
            break;
        case 'xl_register':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $fullname = $_POST['fullname'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];
                
                // Kiểm tra dữ liệu đầu vào
                if ($password !== $confirm_password) {
                    header('Location: /project/controller/index.php?act=register&error=' . urlencode('Mật khẩu xác nhận không khớp'));
                    exit();
                }
                
                if (strlen($password) < 6) {
                    header('Location: /project/controller/index.php?act=register&error=' . urlencode('Mật khẩu phải có ít nhất 6 ký tự'));
                    exit();
                }
                
                $user = new User();
                $result = $user->register($username, $password, $fullname, $email, $phone, $address);
                file_put_contents(__DIR__ . '/../debug_register.txt', print_r($result, true));
                
                if ($result['success']) {
                    header('Location: /project/controller/index.php?act=login&success=' . urlencode($result['message']));
                } else {
                    header('Location: /project/controller/index.php?act=register&error=' . urlencode($result['message']));
                }
            } else {
                header('Location: /project/controller/index.php?act=register');
                exit();
            }
            break;
        case 'profile':
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                header('Location: /project/controller/index.php?act=login');
                exit();
            }
            include "../view/pages/profile.php";
            break;
        case 'logout':
            // Xóa tất cả cookie liên quan đến authentication
            setcookie('auth_token', '', time() - 3600, '/');
            setcookie('auth_token', '', time() - 3600, '/project');
            
            // Xóa session nếu có
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_destroy();
            }
            
            // Xóa tất cả biến session
            $_SESSION = array();
            
            // Xóa giỏ hàng nếu có
            if (class_exists('Cart')) {
                $cart = new Cart();
                $cart->clear();
            }
            
            header('Location: /project/controller/index.php?success=Đăng xuất thành công');
            exit();
            break;
        case 'danhmuc':
            $controller = new controller();
            $danhmuc = $controller->hienthidm();
            $brands = $controller->getAllBrands();
            // Lấy sản phẩm nổi bật cho từng danh mục
            foreach ($danhmuc as &$dm) {
                $dm['featured_products'] = $controller->getFeaturedProductsByCategory($dm['id']);
            }
            unset($dm);
            include "../view/pages/danhmuc.php";
            break;
        case 'gioithieu':
            include "../view/pages/gioithieu.php";
            break;
        case 'xl_themDM':
            if ($_SERVER["REQUEST_METHOD"] == "POST") { 
                $name = $_POST['name'];
                $dm = new danhmuc();
                $dm->setName($name);
                $controller = new controller();
                $controller->themdm($dm);
                $danhmuc = $controller->hienthidm();
                include "../view/pages/danhmuc.php";
            }
            break;
        case 'xoadm':
            if (isset($_GET['id_dm'])) {
                $id_dm = $_GET['id_dm'];
                $dm = new danhmuc();
                $dm->setId($id_dm);
                $controller = new controller();
                $controller->xoadm($dm);
                $danhmuc = $controller->hienthidm();
                include "../view/pages/danhmuc.php";
            }
            break;
        case 'sanpham':
            include "../view/pages/sanpham.php";
            break;
        case 'chitiet':
            include "../view/pages/chitiet.php";
            break;
        case 'add_to_cart':
            // Kiểm tra đăng nhập trước khi thêm vào giỏ hàng
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng'));
                exit();
            }
            
            $cart = new Cart();
            $sanpham = new sanpham();
            if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];
                $product = $sanpham->getSanphamById($id);
                if ($product) {
                    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
                    $cart->add($product, $quantity);
                }
            }
            header('Location: /project/controller/index.php?act=cart');
            break;
        case 'cart':
            // Kiểm tra đăng nhập trước khi xem giỏ hàng
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để xem giỏ hàng'));
                exit();
            }
            
            include "../view/pages/cart.php";
            break;
        case 'update_cart':
            // Kiểm tra đăng nhập trước khi cập nhật giỏ hàng
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để cập nhật giỏ hàng'));
                exit();
            }
            
            $cart = new Cart();
            if (isset($_POST['quantities'])) {
                foreach ($_POST['quantities'] as $id => $quantity) {
                    $cart->update($id, (int)$quantity);
                }
            }
            header('Location: /project/controller/index.php?act=cart');
            break;
        case 'remove_from_cart':
            // Kiểm tra đăng nhập trước khi xóa sản phẩm
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để quản lý giỏ hàng'));
                exit();
            }
            
            $cart = new Cart();
            if (isset($_GET['id'])) {
                $cart->remove($_GET['id']);
            }
            header('Location: /project/controller/index.php?act=cart');
            break;
        case 'clear_cart':
            // Kiểm tra đăng nhập trước khi xóa toàn bộ giỏ hàng
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để quản lý giỏ hàng'));
                exit();
            }
            
            $cart = new Cart();
            $cart->clear();
            header('Location: /project/controller/index.php?act=cart');
            break;
        case 'checkout':
            // Kiểm tra đăng nhập trước khi thanh toán
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để thanh toán'));
                exit();
            }
            
            include "../view/pages/checkout.php";
            break;
        case 'process_checkout':
            // Debug logging
            error_log("process_checkout: Starting order processing");
            
            // Kiểm tra đăng nhập
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                error_log("process_checkout: User not authenticated");
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để thanh toán'));
                exit();
            }
            
            error_log("process_checkout: User authenticated - " . $currentUser['username']);
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                error_log("process_checkout: POST request received");
                error_log("process_checkout: POST data: " . print_r($_POST, true));
                // Validate dữ liệu
                $ten_nguoi_nhan = $_POST['ten_nguoi_nhan'] ?? '';
                $sdt_nguoi_nhan = $_POST['sdt_nguoi_nhan'] ?? '';
                $dia_chi_giao = $_POST['dia_chi_giao'] ?? '';
                $ghi_chu = $_POST['ghi_chu'] ?? '';
                $phuong_thuc_thanh_toan = $_POST['phuong_thuc_thanh_toan'] ?? 'Tiền mặt';
                
                if (empty($ten_nguoi_nhan) || empty($sdt_nguoi_nhan) || empty($dia_chi_giao)) {
                    error_log("process_checkout: Validation failed - missing required fields");
                    header('Location: /project/controller/index.php?act=checkout&error=' . urlencode('Vui lòng điền đầy đủ thông tin bắt buộc'));
                    exit();
                }
                
                // Kiểm tra giỏ hàng
                $cart = new Cart();
                $cart_items = $cart->getCart();
                $total = $cart->getTotal();
                
                error_log("process_checkout: Cart items count: " . count($cart_items));
                error_log("process_checkout: Cart total: " . $total);
                
                if (empty($cart_items)) {
                    error_log("process_checkout: Cart is empty");
                    header('Location: /project/controller/index.php?act=cart&error=' . urlencode('Giỏ hàng trống, vui lòng thêm sản phẩm'));
                    exit();
                }
                
                try {
                    error_log("process_checkout: Starting order creation");
                    include_once "../model/donhang.php";
                    $donhang = new DonHang();
                    $shippingInfo = [
                        'ten_nguoi_nhan' => $ten_nguoi_nhan,
                        'sdt_nguoi_nhan' => $sdt_nguoi_nhan,
                        'dia_chi_giao' => $dia_chi_giao,
                        'ghi_chu' => $ghi_chu,
                        'phuong_thuc_thanh_toan' => $phuong_thuc_thanh_toan
                    ];
                    
                    error_log("process_checkout: Shipping info: " . print_r($shippingInfo, true));
                    
                    $orderId = $donhang->createOrder($currentUser['id_user'], $total, $shippingInfo);
                    
                    error_log("process_checkout: Order created successfully with ID: " . $orderId);
                    
                    header('Location: /project/controller/index.php?act=order_success&id=' . $orderId);
                    exit();
                    
                } catch (Exception $e) {
                    error_log("process_checkout: Error creating order: " . $e->getMessage());
                    header('Location: /project/controller/index.php?act=checkout&error=' . urlencode('Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage()));
                    exit();
                }
            } else {
                header('Location: /project/controller/index.php?act=checkout');
                exit();
            }
            break;
        case 'order_success':
            // Kiểm tra đăng nhập
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để xem đơn hàng'));
                exit();
            }
            
            $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if (!$orderId) {
                header('Location: /project/controller/index.php?act=cart');
                exit();
            }
            
            include "../view/pages/order_success.php";
            break;
        case 'my_orders':
            // Kiểm tra đăng nhập
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để xem đơn hàng'));
                exit();
            }
            
            include "../view/pages/my_orders.php";
            break;
        case 'order_detail':
            // Kiểm tra đăng nhập
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Vui lòng đăng nhập để xem đơn hàng'));
                exit();
            }
            
            include "../view/pages/order_detail.php";
            break;
        case 'admin_orders':
            // Kiểm tra quyền admin
            $currentUser = getCurrentUser();
            if (!$currentUser || $currentUser['position'] !== 'admin') {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang này'));
                exit();
            }
            
            // Xử lý cập nhật trạng thái đơn hàng
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
                include_once "../model/donhang.php";
                $donHang = new DonHang();
                
                $orderId = (int)$_POST['order_id'];
                $status = $_POST['status'];
                
                $paymentStatus = $_POST['payment_status'] ?? '';
                
                // Cập nhật trạng thái đơn hàng
                if ($donHang->updateOrderStatus($orderId, $status)) {
                    // Cập nhật trạng thái thanh toán nếu có
                    if (!empty($paymentStatus)) {
                        $donHang->updatePaymentStatus($orderId, $paymentStatus);
                    }
                    header('Location: /project/controller/index.php?act=admin_orders&success=' . urlencode('Cập nhật trạng thái đơn hàng thành công!'));
                } else {
                    header('Location: /project/controller/index.php?act=admin_orders&error=' . urlencode('Có lỗi xảy ra khi cập nhật trạng thái!'));
                }
                exit();
            }
            
            include "../view/pages/admin_orders.php";
            break;
        case 'admin_order_detail':
            // Kiểm tra quyền admin
            $currentUser = getCurrentUser();
            if (!$currentUser || $currentUser['position'] !== 'admin') {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang này'));
                exit();
            }
            
            include "../view/pages/admin_order_detail.php";
            break;

        case 'cancel_order':
            // Kiểm tra đăng nhập
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
                exit();
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $orderId = (int)($_POST['order_id'] ?? 0);
                
                if ($orderId > 0) {
                    include_once "../model/donhang.php";
                    $donHang = new DonHang();
                    
                    // Kiểm tra xem đơn hàng có thuộc về user này không
                    $order = $donHang->getOrderDetails($orderId);
                    if ($order && $order['id_user'] == $currentUser['id_user']) {
                        // Chỉ cho phép hủy đơn hàng ở trạng thái "Chờ xác nhận"
                        if ($order['trangthai'] === 'Chờ xác nhận') {
                            if ($donHang->updateOrderStatus($orderId, 'Đã hủy', $currentUser['id_user'], 'Khách hàng hủy đơn hàng')) {
                                header('Content-Type: application/json');
                                echo json_encode(['success' => true, 'message' => 'Hủy đơn hàng thành công']);
                            } else {
                                header('Content-Type: application/json');
                                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi hủy đơn hàng']);
                            }
                        } else {
                            header('Content-Type: application/json');
                            echo json_encode(['success' => false, 'message' => 'Không thể hủy đơn hàng ở trạng thái này']);
                        }
                    } else {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại hoặc không thuộc về bạn']);
                    }
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'ID đơn hàng không hợp lệ']);
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
            }
            exit();
            break;
        case 'admin_product_management':
            // Kiểm tra quyền admin
            $currentUser = getCurrentUser();
            if (!$currentUser || $currentUser['position'] !== 'admin') {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang này'));
                exit();
            }
            
            include "../view/pages/admin_product_management.php";
            break;
        case 'admin_add_product':
            // Kiểm tra quyền admin
            $currentUser = getCurrentUser();
            if (!$currentUser || $currentUser['position'] !== 'admin') {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang này'));
                exit();
            }
            
            include "../view/pages/admin_add_product.php";
            break;
        case 'admin_edit_product':
            // Kiểm tra quyền admin
            $currentUser = getCurrentUser();
            if (!$currentUser || $currentUser['position'] !== 'admin') {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang này'));
                exit();
            }
            
            include "../view/pages/admin_edit_product.php";
            break;
        case 'admin_category_management':
            // Kiểm tra quyền admin
            $currentUser = getCurrentUser();
            if (!$currentUser || $currentUser['position'] !== 'admin') {
                header('Location: /project/controller/index.php?act=login&error=' . urlencode('Bạn không có quyền truy cập trang này'));
                exit();
            }
            
            include "../view/pages/admin_category_management.php";
            break;
        case 'lienhe':
            include "../view/pages/lienhe.php";
            break;

        case 'wishlist':
            include "../view/pages/wishlist.php";
            break;
        case 'contact_submit':
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Xử lý form liên hệ
                    $service = $_POST['service'] ?? '';
                    $name = $_POST['name'] ?? '';
                    $phone = $_POST['phone'] ?? '';
                    $email = $_POST['email'] ?? '';
                    $address = $_POST['address'] ?? '';
                    $message = $_POST['message'] ?? '';
                    
                    // Validate dữ liệu
                    if (empty($name) || empty($phone) || empty($service)) {
                        header('Location: /project/controller/index.php?act=lienhe&error=' . urlencode('Vui lòng điền đầy đủ thông tin bắt buộc'));
                        exit();
                    }
                    
                    // Có thể lưu vào database hoặc gửi email ở đây
                    // Hiện tại chỉ hiển thị thông báo thành công
                    header('Location: /project/controller/index.php?act=lienhe&success=' . urlencode('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.'));
                    exit();
                } else {
                    header('Location: /project/controller/index.php?act=lienhe');
                    exit();
                }
                break;
            case 'admin':
                include "../view/pages/admin_complete.php";
                break;
    }

}else{
    include "../view/pages/home.php";
}