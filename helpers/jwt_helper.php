<?php
class JWT {
    private static $secret_key = "your_secret_key_here_change_this_in_production";
    private static $algorithm = 'HS256';

    public static function generateToken($payload, $expiry = 3600) {
        $header = json_encode(['typ' => 'JWT', 'alg' => self::$algorithm]);
        $payload['iat'] = time();
        $payload['exp'] = time() + $expiry;
        $payload = json_encode($payload);

        $base64Header = self::base64url_encode($header);
        $base64Payload = self::base64url_encode($payload);

        $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, self::$secret_key, true);
        $base64Signature = self::base64url_encode($signature);

        return $base64Header . "." . $base64Payload . "." . $base64Signature;
    }

    public static function verifyToken($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        $header = $parts[0];
        $payload = $parts[1];
        $signature = $parts[2];

        $expectedSignature = self::base64url_encode(
            hash_hmac('sha256', $header . "." . $payload, self::$secret_key, true)
        );

        if (!hash_equals($signature, $expectedSignature)) {
            return false;
        }

        $payloadData = json_decode(self::base64url_decode($payload), true);

        if (!$payloadData || $payloadData['exp'] < time()) {
            return false;
        }

        return $payloadData;
    }

    public static function getPayload($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        return json_decode(self::base64url_decode($parts[1]), true);
    }

    private static function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64url_decode($data) {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }
}

function requireAuth() {
    $token = $_COOKIE['auth_token'] ?? null;

    if (!$token) {
        header('Location: index.php?act=login');
        exit();
    }

    $payload = JWT::verifyToken($token);
    if (!$payload) {
        setcookie('auth_token', '', time() - 3600, '/');
        header('Location: index.php?act=login');
        exit();
    }

    return $payload;
}

function getCurrentUser() {
    $token = $_COOKIE['auth_token'] ?? null;

    if (!$token) {
        return null;
    }

    $payload = JWT::verifyToken($token);
    if (!$payload) {
        return null;
    }

    $user = new User();
    return $user->getUserById($payload['user_id']);
} 