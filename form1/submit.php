<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/validate.php';
require_once __DIR__ . '/includes/mailer.php';
require_once __DIR__ . '/includes/logger.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

if (isset($_SESSION['last_submit_time']) && (time() - $_SESSION['last_submit_time']) < 10) {
    Logger::warning('多重送信を検知');
    header('Location: ' . BASE_URL . '/index.php?error=duplicate');
    exit;
}

try {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::validateToken($csrfToken)) {
        Logger::warning('CSRFトークン検証失敗');
        throw new Exception('不正なリクエストです');
    }

    $honeypot = $_POST['honeypot'] ?? '';
    $validator = new Validator();
    
    if (!$validator->validateHoneypot($honeypot)) {
        Logger::warning('スパム検知（honeypot）');
        throw new Exception('不正な送信を検知しました');
    }

    // セクション1
    $company_name = trim($_POST['company_name'] ?? '');
    $contact_person_name = trim($_POST['contact_person_name'] ?? '');
    $contact_email = trim($_POST['contact_email'] ?? '');
    $customer_support_email = trim($_POST['customer_support_email'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $company_url = trim($_POST['company_url'] ?? '');

    // セクション2
    $document_title = trim($_POST['document_title'] ?? '');
    $document_purposes = $_POST['document_purposes'] ?? [];
    $document_purpose_other = trim($_POST['document_purpose_other'] ?? '');

    // セクション3
    $target_audience_type = trim($_POST['target_audience_type'] ?? '');
    $target_audience_roles = $_POST['target_audience_roles'] ?? [];
    $target_audience_concerns = $_POST['target_audience_concerns'] ?? [];

    // セクション4
    $post_request_flow = $_POST['post_request_flow'] ?? [];

    // セクション5
    $form_fields_confirmed = $_POST['form_fields_confirmed'] ?? '';
    $lp_notes_confirmed = $_POST['lp_notes_confirmed'] ?? '';

   // セクション6
$document_file_url = trim($_POST['document_file_url'] ?? '');

// アップロードファイル（任意）
$uploadedFile = $_FILES['document_file'] ?? null;




    // セクション7
    $additional_requests = trim($_POST['additional_requests'] ?? '');

    // バリデーション
    $validator->validateRequired($company_name, '会社名・屋号');
    $validator->validateMaxLength($company_name, 100, '会社名・屋号');
    
    $validator->validateRequired($contact_person_name, 'ご担当者名');
    $validator->validateMaxLength($contact_person_name, 100, 'ご担当者名');
    
    $validator->validateEmail($contact_email, 'メールアドレス');
    
    if (!empty($customer_support_email)) {
        $validator->validateEmail($customer_support_email, 'お客さま窓口用メールアドレス');
    }
    
    $validator->validateRequired($phone_number, 'お電話番号');
    $validator->validateMaxLength($phone_number, 50, 'お電話番号');
    
    $validator->validateMaxLength($company_url, 500, '会社情報のURL');
    
    $validator->validateRequired($document_title, '資料のタイトル');
    $validator->validateMaxLength($document_title, 200, '資料のタイトル');
    
    $validator->validateArrayNotEmpty($document_purposes, '資料の主な目的');
    
    $validator->validateMaxLength($document_purpose_other, 50, 'その他の目的');
    
    $validator->validateCheckbox($form_fields_confirmed, '資料請求フォームの項目についての確認');
    $validator->validateCheckbox($lp_notes_confirmed, 'LP上の注意書きについての確認');
    
    
    $validator->validateMaxLength($additional_requests, 5000, 'ご希望・その他ご要望');

$hasUrl = !empty($document_file_url);
$hasFile = ($uploadedFile && isset($uploadedFile['error']) && $uploadedFile['error'] === UPLOAD_ERR_OK);

if (!$hasUrl && !$hasFile) {
    $validator->addError('添付資料は「URL入力」または「ファイルアップロード」のどちらかで提出してください');
}

// URLがある場合の長さチェック
if ($hasUrl) {
    $validator->validateMaxLength($document_file_url, 500, '資料ファイルのURL');
}


    if ($validator->hasErrors()) {
        $errors = implode('<br>', $validator->getErrors());
        Logger::info('バリデーションエラー');
        header('Location: ' . BASE_URL . '/index.php?error=' . urlencode($errors));
        exit;
    }

    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';

// ===== アップロードファイルを一時保存（管理者メール添付用）=====
$attachmentPath = null;
$attachmentName = null;

$hasFile = ($uploadedFile && isset($uploadedFile['error']) && $uploadedFile['error'] === UPLOAD_ERR_OK);

if ($hasFile) {
    // 例：最大 10MB 制限（必要なら調整）
    $maxSize = 10 * 1024 * 1024;
    if ($uploadedFile['size'] > $maxSize) {
        throw new Exception('添付ファイルが大きすぎます（最大10MB）');
    }

    // 拡張子チェック（必要なら調整）
    $allowedExt = ['pdf','doc','docx','ppt','pptx','xls','xlsx','zip','png','jpg','jpeg'];
    $origName = $uploadedFile['name'] ?? 'attachment';
    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        throw new Exception('添付ファイル形式が許可されていません');
    }

    // 一時ディレクトリ（サーバのtmpを利用）
    $tmpDir = sys_get_temp_dir();
    $safeName = preg_replace('/[^\w\.\-]+/u', '_', $origName);
    $tmpName = 'speedix_upload_' . bin2hex(random_bytes(8)) . '_' . $safeName;
    $dest = rtrim($tmpDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $tmpName;

    if (!move_uploaded_file($uploadedFile['tmp_name'], $dest)) {
        throw new Exception('添付ファイルの一時保存に失敗しました');
    }

    $attachmentPath = $dest;
    $attachmentName = $origName;
}


    // 配列をJSON形式で保存
    $document_purposes_json = json_encode($document_purposes, JSON_UNESCAPED_UNICODE);
    $target_audience_roles_json = !empty($target_audience_roles) ? json_encode($target_audience_roles, JSON_UNESCAPED_UNICODE) : null;
    $target_audience_concerns_json = !empty($target_audience_concerns) ? json_encode($target_audience_concerns, JSON_UNESCAPED_UNICODE) : null;
    $post_request_flow_json = !empty($post_request_flow) ? json_encode($post_request_flow, JSON_UNESCAPED_UNICODE) : null;

    // データベース保存用データ
    $dbData = [
        ':company_name' => $company_name,
        ':contact_person_name' => $contact_person_name,
        ':contact_email' => $contact_email,
        ':customer_support_email' => $customer_support_email ?: null,
        ':phone_number' => $phone_number,
        ':company_url' => $company_url ?: null,
        ':document_title' => $document_title,
        ':document_purposes' => $document_purposes_json,
        ':document_purpose_other' => $document_purpose_other ?: null,
        ':target_audience_type' => $target_audience_type ?: null,
        ':target_audience_roles' => $target_audience_roles_json,
        ':target_audience_concerns' => $target_audience_concerns_json,
        ':post_request_flow' => $post_request_flow_json,
        ':form_fields_confirmed' => $form_fields_confirmed === '1' ? 1 : 0,
        ':lp_notes_confirmed' => $lp_notes_confirmed === '1' ? 1 : 0,
        ':document_file_url' => $document_file_url ?: null,
        ':additional_requests' => $additional_requests ?: null,
        ':user_agent' => $userAgent,
        ':ip' => $ip
    ];

    $db = Database::getInstance();
    $insertId = $db->insert($dbData);

    if (!$insertId) {
        throw new Exception('データの保存に失敗しました');
    }

    // メール送信用データ
    $mailData = [
        'company_name' => $company_name,
        'contact_person_name' => $contact_person_name,
        'contact_email' => $contact_email,
        'customer_support_email' => $customer_support_email,
        'phone_number' => $phone_number,
        'company_url' => $company_url,
        'document_title' => $document_title,
        'document_purposes_text' => implode("\n", array_map(function($p) { return '・' . $p; }, $document_purposes)),
        'document_purpose_other' => $document_purpose_other,
        'target_audience_type' => $target_audience_type,
        'target_audience_roles_text' => !empty($target_audience_roles) ? implode("\n", array_map(function($r) { return '・' . $r; }, $target_audience_roles)) : '',
        'target_audience_concerns_text' => !empty($target_audience_concerns) ? implode("\n", array_map(function($c) { return '・' . $c; }, $target_audience_concerns)) : '',
        'post_request_flow_text' => !empty($post_request_flow) ? implode("\n", array_map(function($f) { return '・' . $f; }, $post_request_flow)) : '',
           'document_file_url' => $document_file_url,
        'additional_requests' => $additional_requests,
        'attachment_path' => $attachmentPath,
        'attachment_name' => $attachmentName,

    ];

    $adminMailResult = Mailer::sendAdminNotification($mailData);
    $customerMailResult = Mailer::sendCustomerConfirmation($mailData);
// ===== 一時ファイル削除 =====
if (!empty($attachmentPath) && file_exists($attachmentPath)) {
    @unlink($attachmentPath);
}


    if (!$adminMailResult || !$customerMailResult) {
        Logger::warning('メール送信に一部失敗しましたが、DB保存は成功');
    }

    $_SESSION['last_submit_time'] = time();

    header('Location: ' . BASE_URL . '/thanks.php');
    exit;

} catch (Exception $e) {
    Logger::error('送信処理エラー: ' . $e->getMessage());

    // ★テスト用：原因をURLに載せる（本番では戻す）
    header('Location: ' . BASE_URL . '/index.php?error=' . urlencode($e->getMessage()));
    exit;
}

