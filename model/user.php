<?php
require_once 'database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = new database();
    }
    
    // Đăng ký tài khoản mới
    public function register($username, $password, $fullname, $email, $phone, $address) {
        try {
            $conn = $this->db->connection_database();
            
            // Kiểm tra username đã tồn tại chưa
            $stmt = $conn->prepare("SELECT id_user FROM taikhoan WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Tên đăng nhập đã tồn tại'];
            }
            
            // Kiểm tra email đã tồn tại chưa
            $stmt = $conn->prepare("SELECT id_user FROM taikhoan WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Email đã được sử dụng'];
            }
            
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Thêm tài khoản mới
            $stmt = $conn->prepare("INSERT INTO taikhoan (username, password, fullname, email, phone, address, position) VALUES (?, ?, ?, ?, ?, ?, '1')");
            $result = $stmt->execute([$username, $hashedPassword, $fullname, $email, $phone, $address]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Đăng ký thành công'];
            } else {
                return ['success' => false, 'message' => 'Đăng ký thất bại'];
            }
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()];
        }
    }
    
    // Đăng nhập
    public function login($username, $password) {
        try {
            $conn = $this->db->connection_database();
            
            $stmt = $conn->prepare("SELECT * FROM taikhoan WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                // Loại bỏ password khỏi dữ liệu trả về
                unset($user['password']);
                return ['success' => true, 'user' => $user];
            } else {
                return ['success' => false, 'message' => 'Tên đăng nhập hoặc mật khẩu không đúng'];
            }
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()];
        }
    }
    
    // Lấy thông tin user theo ID
    public function getUserById($id) {
        try {
            $conn = $this->db->connection_database();
            
            $stmt = $conn->prepare("SELECT id_user, username, fullname, email, phone, address, position FROM taikhoan WHERE id_user = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $user;
            
        } catch (PDOException $e) {
            return null;
        }
    }
    
    // Cập nhật thông tin user
    public function updateUser($id, $fullname, $email, $phone, $address) {
        try {
            $conn = $this->db->connection_database();
            
            $stmt = $conn->prepare("UPDATE taikhoan SET fullname = ?, email = ?, phone = ?, address = ? WHERE id_user = ?");
            $result = $stmt->execute([$fullname, $email, $phone, $address, $id]);
            
            return $result;
            
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Đổi mật khẩu
    public function changePassword($id, $oldPassword, $newPassword) {
        try {
            $conn = $this->db->connection_database();
            
            // Kiểm tra mật khẩu cũ
            $stmt = $conn->prepare("SELECT password FROM taikhoan WHERE id_user = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user || !password_verify($oldPassword, $user['password'])) {
                return ['success' => false, 'message' => 'Mật khẩu cũ không đúng'];
            }
            
            // Cập nhật mật khẩu mới
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE taikhoan SET password = ? WHERE id_user = ?");
            $result = $stmt->execute([$hashedNewPassword, $id]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Đổi mật khẩu thành công'];
            } else {
                return ['success' => false, 'message' => 'Đổi mật khẩu thất bại'];
            }
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()];
        }
    }
}
