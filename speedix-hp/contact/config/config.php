<?php

/**
 * TransmitMail設定ファイル PHP版
 */

$config['email'] = 'info@speedixweb.site';
$config['subject'] = 'メールフォームからお問い合わせ';
$config['auto_reply_subject'] = 'Yis株式会社：お問い合わせありがとうございます';
$config['auto_reply_name'] = 'Yis株式会社';
$config['session'] = false;
$config['from_email'] = 'info@speedixweb.site';
$config['auto_reply_from_email'] = 'info@speedixweb.site';
$config['return_path'] = 'info@speedixweb.site';
$config['auto_reply_email_input_name'] = 'user_email'; // フォームのメールアドレスフィールド名

// TransmitMailのテンプレートパスを記述する
// input.html と confirm.html は使用しない（フォームは index.html に直接実装、確認画面をスキップ）
$config['tpl_input'] = CONTACT_PATH . '/form.html'; // エラー時は form.html を表示（フォームに戻る）
$config['tpl_confirm'] = TEMPLATEPATH . '/contact/finish.html'; // 使用しないが設定が必要
$config['tpl_finish'] = TEMPLATEPATH . '/contact/finish.html';
$config['tpl_error'] = CONTACT_PATH . '/form.html'; // エラー時も form.html を表示
$config['mail_body'] = TEMPLATEPATH . '/contact/config/mail_body.txt';
$config['mail_auto_reply_body'] = TEMPLATEPATH . '/contact/config/mail_auto_reply_body.txt';
$config['log_dir'] = TEMPLATEPATH . '/contact/log/';
$config['tmp_dir'] = TEMPLATEPATH . '/contact/tmp/';
