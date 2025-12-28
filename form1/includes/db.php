require_once __DIR__ . '/logger.php';

<?php
/**
 * データベース接続クラス
 */
class Database {
    private static $instance = null;
    private $pdo = null;

    private function __construct() {
        try {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                DB_HOST,
                DB_NAME
            );
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            Logger::error('DB接続エラー: ' . $e->getMessage());
            throw new Exception('データベース接続に失敗しました');
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function insert($data) {
        try {
            $sql = "INSERT INTO speedix_hearing 
                    (company_name, contact_person_name, contact_email, customer_support_email, 
                     phone_number, company_url, document_title, document_purposes, 
                     document_purpose_other, target_audience_type, target_audience_roles, 
                     target_audience_concerns, post_request_flow, form_fields_confirmed, 
                     lp_notes_confirmed, document_file_url, additional_requests, user_agent, ip) 
                    VALUES 
                    (:company_name, :contact_person_name, :contact_email, :customer_support_email, 
                     :phone_number, :company_url, :document_title, :document_purposes, 
                     :document_purpose_other, :target_audience_type, :target_audience_roles, 
                     :target_audience_concerns, :post_request_flow, :form_fields_confirmed, 
                     :lp_notes_confirmed, :document_file_url, :additional_requests, :user_agent, :ip)";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($data);
            
            if ($result) {
                Logger::info('DB保存成功 - Email: ' . substr($data[':contact_email'], 0, 3) . '***');
                return $this->pdo->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            Logger::error('DB保存エラー: ' . $e->getMessage());
            throw new Exception('データの保存に失敗しました');
        }
    }
}
