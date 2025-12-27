<?php
/**
 * お問い合わせフォーム エントリーポイント
 */

// エラーレポートを有効にする（本番環境では無効化推奨）
error_reporting(E_ALL);
ini_set('display_errors', 0);

// パスの定義
define('TEMPLATEPATH', dirname(dirname(__FILE__)));
define('CONTACT_PATH', __DIR__);

// TransmitMail を読み込む
require_once CONTACT_PATH . '/lib/TransmitMail.php';

// TransmitMail のインスタンスを作成
$transmitMail = new TransmitMail();

// 設定ファイルを読み込む
$transmitMail->init(CONTACT_PATH . '/config/config.php');

// 実行
$transmitMail->run();

