<?php
/**
 * Lớp JWT - Xử lý JSON Web Token
 * Dùng để tạo và xác thực token cho hệ thống đăng nhập
 */
class JWT {
    private static $secret_key = "your_secret_key_here_change_this_in_production"; // Khóa bí mật
    private static $algorithm = 'HS256'; // Thuật toán mã hóa

    /**
     * Tạo JWT token mới
     * @param array $payload Dữ liệu payload
     * @param int $expiry Thời gian hết hạn (giây)
     * @return string JWT token
     */
    public static function generateToken($payload, $expiry = 3600) {
        $header = json_encode(['typ' => 'JWT', 'alg' => self::$algorithm]);
        $payload['iat'] = time(); // Thời gian tạo
        $payload['exp'] = time() + $expiry; // Thời gian hết hạn
        $payload = json_encode($payload);

        $base64Header = self::base64url_encode($header);
        $base64Payload = self::base64url_encode($payload);

        // Tạo chữ ký
        $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, self::$secret_key, true);
        $base64Signature = self::base64url_encode($signature);

        return $base64Header . "." . $base64Payload . "." . $base64Signature;
    }

    /**
     * Xác thực JWT token
     * @param string $token JWT token cần xác thực
     * @return array|false Dữ liệu payload nếu hợp lệ, false nếu không hợp lệ
     */
    public static function verifyToken($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false; // Token không đúng định dạng
        }

        $header = $parts[0];
        $payload = $parts[1];
        $signature = $parts[2];

        // Tạo chữ ký mong đợi
        $expectedSignature = self::base64url_encode(
            hash_hmac('sha256', $header . "." . $payload, self::$secret_key, true)
        );

        // Kiểm tra chữ ký
        if (!hash_equals($signature, $expectedSignature)) {
            return false; // Chữ ký không khớp
        }

        $payloadData = json_decode(self::base64url_decode($payload), true);

        // Kiểm tra thời gian hết hạn
        if (!$payloadData || $payloadData['exp'] < time()) {
            return false; // Token đã hết hạn
        }

        return $payloadData;
    }

    /**
     * Lấy dữ liệu payload từ token
     * @param string $token JWT token
     * @return array|null Dữ liệu payload hoặc null nếu token không hợp lệ
     */
    public static function getPayload($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null; // Token không đúng định dạng
        }

        return json_decode(self::base64url_decode($parts[1]), true);
    }

    /**
     * Mã hóa base64url
     * @param string $data Dữ liệu cần mã hóa
     * @return string Dữ liệu đã mã hóa
     */
    private static function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Giải mã base64url
     * @param string $data Dữ liệu cần giải mã
     * @return string Dữ liệu đã giải mã
     */
    private static function base64url_decode($data) {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }
}

/**
 * Yêu cầu xác thực - Chuyển hướng về trang đăng nhập nếu chưa đăng nhập
 * @return array Dữ liệu payload của user
 */
function requireAuth() {
    $token = $_COOKIE['auth_token'] ?? null;

    if (!$token) {
        header('Location: index.php?act=login');
        exit();
    }

    $payload = JWT::verifyToken($token);
    if (!$payload) {
        // Xóa token không hợp lệ
        setcookie('auth_token', '', time() - 3600, '/');
        header('Location: index.php?act=login');
        exit();
    }

    return $payload;
}

/**
 * Lấy thông tin user hiện tại từ token
 * @return array|null Thông tin user hoặc null nếu chưa đăng nhập
 */
function getCurrentUser() {
    $token = $_COOKIE['auth_token'] ?? null;

    if (!$token) {
        return null; // Chưa đăng nhập
    }

    $payload = JWT::verifyToken($token);
    if (!$payload) {
        return null; // Token không hợp lệ
    }

    $user = new User();
    return $user->getUserById($payload['user_id']);
} 