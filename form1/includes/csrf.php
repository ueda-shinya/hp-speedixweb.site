<?php
/**
 * CSRF対策クラス
 */
class CSRF {
    public static function generateToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = bin2hex(random_bytes(32));
        $_SESSION[CSRF_TOKEN_NAME] = $token;
        $_SESSION[CSRF_TOKEN_NAME . '_time'] = time();
        
        return $token;
    }

    public static function validateToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[CSRF_TOKEN_NAME]) || !isset($_SESSION[CSRF_TOKEN_NAME . '_time'])) {
            return false;
        }

        if (time() - $_SESSION[CSRF_TOKEN_NAME . '_time'] > CSRF_TOKEN_EXPIRES) {
            return false;
        }

        $isValid = hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
        
        if ($isValid) {
            unset($_SESSION[CSRF_TOKEN_NAME]);
            unset($_SESSION[CSRF_TOKEN_NAME . '_time']);
        }
        
        return $isValid;
    }
}
