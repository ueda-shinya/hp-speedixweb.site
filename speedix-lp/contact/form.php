<?php
/**
 * フォームページ（エラー時に表示）
 * TransmitMailのテンプレートとして使用
 */
// グローバル変数からエラーメッセージを取得（TransmitMailが設定）
$errorMessages = isset($global_errors) && is_array($global_errors) ? $global_errors : array();

// index.htmlを読み込んで表示
$indexPath = dirname(dirname(__FILE__)) . '/index.html';
if (file_exists($indexPath)) {
    $content = file_get_contents($indexPath);
    
    // エラーメッセージがある場合、JavaScriptで表示するためのスクリプトを追加
    if (!empty($errorMessages)) {
        $errorList = '';
        foreach ($errorMessages as $error) {
            $errorList .= '<li>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</li>';
        }
        
        $script = '<script>
            document.addEventListener("DOMContentLoaded", function() {
                const errorContainer = document.getElementById("formErrors");
                if (errorContainer) {
                    errorContainer.style.display = "block";
                    errorContainer.innerHTML = "<ul>' . addslashes($errorList) . '</ul>";
                    errorContainer.scrollIntoView({ behavior: "smooth", block: "nearest" });
                }
            });
        </script>';
        // </body>の前にスクリプトを挿入
        $content = str_replace('</body>', $script . '</body>', $content);
    }
    
    echo $content;
} else {
    // index.htmlが見つからない場合
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>エラー</title></head><body><p>フォームページが見つかりません。</p><p><a href="../">トップページへ戻る</a></p></body></html>';
}
?>
