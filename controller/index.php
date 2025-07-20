<?php 
include_once __DIR__ . '/controller.php';
include_once __DIR__ . '/../helpers/jwt_helper.php';
include_once __DIR__ . '/../model/user.php';

if(isset($_REQUEST['act'])){
    $act= $_REQUEST['act'];
    switch($act){
        case 'login':
            include "../view/login.php";
            break;
        case 'register':
            include "../view/register.php";
            break;
        case 'xl_login':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $password = $_POST['password'];
                
                $user = new User();
                $result = $user->login($username, $password);
                
                if ($result['success']) {
                    // Tạo JWT token
                    $token = JWT::generateToken([
                        'user_id' => $result['user']['id_user'],
                        'username' => $result['user']['username'],
                        'position' => $result['user']['position']
                    ]);
                    
                    // Lưu token vào cookie
                    setcookie('auth_token', $token, time() + 3600, '/');
                    
                    header('Location: index.php?success=Đăng nhập thành công');
                } else {
                    header('Location: index.php?act=login&error=' . urlencode($result['message']));
                }
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
                
                // Validation
                if ($password !== $confirm_password) {
                    header('Location: index.php?act=register&error=' . urlencode('Mật khẩu xác nhận không khớp'));
                    exit();
                }
                
                if (strlen($password) < 6) {
                    header('Location: index.php?act=register&error=' . urlencode('Mật khẩu phải có ít nhất 6 ký tự'));
                    exit();
                }
                
                $user = new User();
                $result = $user->register($username, $password, $fullname, $email, $phone, $address);
                
                if ($result['success']) {
                    header('Location: index.php?act=login&success=' . urlencode($result['message']));
                } else {
                    header('Location: index.php?act=register&error=' . urlencode($result['message']));
                }
            }
            break;
        case 'profile':
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                header('Location: index.php?act=login');
                exit();
            }
            include "../view/profile.php";
            break;
        case 'logout':
            setcookie('auth_token', '', time() - 3600, '/');
            header('Location: index.php?success=Đăng xuất thành công');
            break;
        case 'danhmuc':
            $controller = new controller();
            $danhmuc = $controller->hienthidm();
            include "../view/danhmuc.php";
            break;
        case 'xl_themDM':
            if ($_SERVER["REQUEST_METHOD"] == "POST") { 
                $name = $_POST['name'];
                $dm = new danhmuc();
                $dm->setName($name);
                $controller = new controller();
                $controller->themdm($dm);
                $danhmuc = $controller->hienthidm();
                include "../view/danhmuc.php";
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
                include "../view/danhmuc.php";
            }
            break;
    }

}else{
    include "../view/home.php";
}