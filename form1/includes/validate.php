<?php
/**
 * バリデーションクラス
 */
class Validator {
    private $errors = [];

    public function validateRequired($value, $fieldName) {
        if (empty($value)) {
            $this->errors[] = $fieldName . 'を入力してください';
            return false;
        }
        return true;
    }

    public function validateEmail($email, $fieldName = 'メールアドレス') {
        if (empty($email)) {
            $this->errors[] = $fieldName . 'を入力してください';
            return false;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = '正しい' . $fieldName . 'を入力してください';
            return false;
        }
        if (strlen($email) > 255) {
            $this->errors[] = $fieldName . 'が長すぎます';
            return false;
        }
        return true;
    }

    public function validateMaxLength($value, $maxLength, $fieldName) {
        if (!empty($value) && mb_strlen($value) > $maxLength) {
            $this->errors[] = $fieldName . 'は' . $maxLength . '文字以内で入力してください';
            return false;
        }
        return true;
    }

    public function validateCheckbox($value, $fieldName) {
        if ($value !== '1') {
            $this->errors[] = $fieldName . 'に同意してください';
            return false;
        }
        return true;
    }

    public function validateHoneypot($honeypot) {
        return empty($honeypot);
    }

    public function validateArrayNotEmpty($array, $fieldName) {
        if (empty($array) || !is_array($array)) {
            $this->errors[] = $fieldName . 'を1つ以上選択してください';
            return false;
        }
        return true;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }
}
