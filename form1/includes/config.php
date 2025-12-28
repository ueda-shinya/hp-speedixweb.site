<?php


// データベース接続情報
define('DB_HOST', 'localhost');
define('DB_NAME', 'yisweb_speedixform');  // 例: speedix_db
define('DB_USER', 'yisweb_formuser');  // 例: speedix_user
define('DB_PASS', 'ACl2wWu?bpXR');  // 例: your_password_here

// メール設定（固定）
define('ADMIN_EMAIL', 'info@speedixweb.site');
define('FROM_EMAIL', 'info@speedixweb.site');

// サイト設定（固定）
define('BASE_URL', 'https://speedixweb.site/form1');

// セキュリティ設定
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_EXPIRES', 3600); // 1時間

// ログ設定
define('LOG_FILE', __DIR__ . '/../logs/app.log');
define('LOG_ENABLED', true);
