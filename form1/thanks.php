<?php
require_once __DIR__ . '/includes/config.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>送信完了 - Speedix</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/style.css">
</head>
<body>
    <div class="container">
        <div class="thanks-container">
            <div class="success-icon">✓</div>
            <h1>送信完了</h1>
            <div class="thanks-message">
                <p>ヒアリングフォームの内容を送信しました。<br>
                担当者より受け取り確認のご連絡をいたします。<br>
                ご入金がお済みでない方はご対応をお願いいたします。</p>
            </div>
            <div class="form-actions">
                <a href="<?php echo BASE_URL; ?>/index.php" class="btn-back">フォームに戻る</a>
            </div>
        </div>
    </div>
</body>
</html>
