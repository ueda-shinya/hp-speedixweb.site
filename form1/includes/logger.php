<?php
/**
 * ログ出力クラス
 */
class Logger {
    
    public static function info($message) {
        self::write('INFO', $message);
    }

    public static function error($message) {
        self::write('ERROR', $message);
    }

    public static function warning($message) {
        self::write('WARNING', $message);
    }

    private static function write($level, $message) {
        if (!LOG_ENABLED) {
            return;
        }

        $logDir = dirname(LOG_FILE);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $timestamp = date('Y-m-d H:i:s');
        $logMessage = sprintf(
            "[%s] [%s] %s\n",
            $timestamp,
            $level,
            $message
        );

        file_put_contents(LOG_FILE, $logMessage, FILE_APPEND | LOCK_EX);
    }
}
