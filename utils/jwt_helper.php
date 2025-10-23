<?php
class JWTHelper {
    private static $secret_key = "IT_Sahayata_Secret_Key_2025_Secure";
    private static $encrypt_method = "AES-256-CBC";
    private static $secret_iv = "IT_Sahayata_IV_Key";

    public static function generateToken($user_id, $role) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode([
            'user_id' => $user_id,
            'role' => $role,
            'exp' => time() + (30 * 24 * 60 * 60) // 30 days
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secret_key, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function validateToken($token) {
        if (empty($token)) return false;

        $tokenParts = explode('.', $token);
        if (count($tokenParts) != 3) return false;

        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secret_key, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        if ($base64UrlSignature !== $signatureProvided) return false;

        $payloadData = json_decode($payload, true);
        if (time() > $payloadData['exp']) return false;

        return $payloadData;
    }

    public static function getBearerToken() {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $matches = [];
            preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches);
            return $matches[1] ?? null;
        }
        return null;
    }
}
?>
