<?php
require_once __DIR__ . '/logger.php';

class Mailer {

    /**
     * 管理者へ通知メール（ファイル添付対応）
     */
    public static function sendAdminNotification($data, $uploadedFile = null) {
        $to = ADMIN_EMAIL;
        $subject = '【Speedix】ヒアリングフォーム送信がありました';

        // ===== 本文作成 =====
        $body  = "ヒアリングフォームから送信がありました。\n\n";

        // ■ 1. ご担当者さまについて
        $body .= "━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "■ 1. ご担当者さまについて\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "会社名・屋号: {$data['company_name']}\n";
        $body .= "ご担当者名: {$data['contact_person_name']}\n";
        $body .= "メールアドレス: {$data['contact_email']}\n";

        if (!empty($data['customer_support_email'])) {
            $body .= "お客さま窓口用メール: {$data['customer_support_email']}\n";
        }

        $body .= "電話番号: {$data['phone_number']}\n";

        if (!empty($data['company_url'])) {
            $body .= "会社URL: {$data['company_url']}\n";
        }

        // ■ 2. 資料について
        $body .= "\n━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "■ 2. 資料について\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "資料タイトル: {$data['document_title']}\n";
        $body .= "資料の主な目的:\n{$data['document_purposes_text']}\n";

        if (!empty($data['document_purpose_other'])) {
            $body .= "その他の目的: {$data['document_purpose_other']}\n";
        }

        // ■ 3. 資料を読む相手について
        $body .= "\n━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "■ 3. 資料を読む相手について\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━\n";

        if (!empty($data['target_audience_type'])) {
            $body .= "想定している読者区分: {$data['target_audience_type']}\n";
        } else {
            $body .= "想定している読者区分: 未指定\n";
        }

        if (!empty($data['target_audience_roles_text'])) {
            $body .= "主な立場:\n{$data['target_audience_roles_text']}\n";
        }

        if (!empty($data['target_audience_concerns_text'])) {
            $body .= "当てはまりそうな悩み・課題:\n{$data['target_audience_concerns_text']}\n";
        }

        // ■ 4. 資料請求後の流れ
        $body .= "\n━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "■ 4. 資料請求後の流れ\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━\n";

        if (!empty($data['post_request_flow_text'])) {
            $body .= "{$data['post_request_flow_text']}\n";
        } else {
            $body .= "特に指定なし\n";
        }

        // ■ 5. フォーム・LP記載内容の確認
        $body .= "\n━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "■ 5. フォーム・LP記載内容の確認\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "フォーム項目確認: 済\n";
        $body .= "LP注意書き確認: 済\n";

        // ■ 6. 添付資料
        $body .= "\n━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "■ 6. 添付資料\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━\n";

        if (!empty($data['document_file_url'])) {
            $body .= "資料URL: {$data['document_file_url']}\n";
        } else {
            $body .= "資料URL: 未入力\n";
        }

        // ■ 7. ご希望・その他
        if (!empty($data['additional_requests'])) {
            $body .= "\n━━━━━━━━━━━━━━━━━━━━\n";
            $body .= "■ 7. ご希望・その他ご要望\n";
            $body .= "━━━━━━━━━━━━━━━━━━━━\n";
            $body .= "{$data['additional_requests']}\n";
        }

        $body .= "\n送信日時: " . date('Y-m-d H:i:s') . "\n";

        // ===== メール送信（添付対応）=====
        mb_language('Japanese');
        mb_internal_encoding('UTF-8');

        $boundary = uniqid('boundary_');

        $headers = [
            'From: ' . FROM_EMAIL,
            'Reply-To: ' . $data['contact_email'],
            'MIME-Version: 1.0',
            "Content-Type: multipart/mixed; boundary=\"{$boundary}\""
        ];

        $message  = "--{$boundary}\r\n";
        $message .= "Content-Type: text/plain; charset=ISO-2022-JP\r\n";
        $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $message .= mb_convert_encoding($body, 'ISO-2022-JP-MS', 'UTF-8') . "\r\n";

        // 添付ファイル（管理者宛のみ）
        if ($uploadedFile && $uploadedFile['error'] === UPLOAD_ERR_OK) {
            $fileContent = chunk_split(base64_encode(file_get_contents($uploadedFile['tmp_name'])));
            $fileName = mb_encode_mimeheader($uploadedFile['name'], 'ISO-2022-JP-MS');

            $message .= "--{$boundary}\r\n";
            $message .= "Content-Type: application/octet-stream; name=\"{$fileName}\"\r\n";
            $message .= "Content-Transfer-Encoding: base64\r\n";
            $message .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n\r\n";
            $message .= $fileContent . "\r\n";
        }

        $message .= "--{$boundary}--";

        $result = mb_send_mail(
            $to,
            mb_convert_encoding($subject, 'ISO-2022-JP-MS', 'UTF-8'),
            $message,
            implode("\r\n", $headers)
        );

        Logger::info($result ? '管理者メール送信成功' : '管理者メール送信失敗');
        return $result;
    }

    /**
     * お客様へ自動返信（添付なし）
     */
    public static function sendCustomerConfirmation($data) {
        $to = $data['contact_email'];
        $subject = '【Speedix】送信内容の控えをお送りします';

        $body  = "{$data['contact_person_name']} 様\n\n";
        $body .= "ヒアリングフォームの送信ありがとうございます。\n";
        $body .= "担当者より折り返しご連絡いたします。\n\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "Speedix\n";
        $body .= BASE_URL . "\n";

        mb_language('Japanese');
        mb_internal_encoding('UTF-8');

        return mb_send_mail(
            $to,
            mb_convert_encoding($subject, 'ISO-2022-JP-MS', 'UTF-8'),
            mb_convert_encoding($body, 'ISO-2022-JP-MS', 'UTF-8'),
            'From: ' . FROM_EMAIL . "\r\nContent-Type: text/plain; charset=ISO-2022-JP"
        );
    }
}
