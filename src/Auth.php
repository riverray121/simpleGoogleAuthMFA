<?php
require __DIR__ . '/../vendor/autoload.php';
use RobThree\Auth\TwoFactorAuth;

class Auth {
    private $tfa;
    private $db;

    public function __construct() {
        $this->tfa = new TwoFactorAuth('SES-MFA');
        $this->db = new SQLite3(__DIR__ . '/../mfa.db');
    }

    public function register($username, $password) {
        $secret = $this->tfa->createSecret();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO users (username, password, secret) VALUES (:username, :password, :secret)");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);
        $stmt->bindValue(':secret', $secret, SQLITE3_TEXT);
        $stmt->execute();

        return $secret;
    }

    public function login($username, $password, $code) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        if (!$result) {
            return false;
        }

        $user = new User($result['id'], $result['username'], $result['password'], $result['secret']);

        if (password_verify($password, $user->getPassword()) && $this->tfa->verifyCode($user->getSecret(), $code)) {
            return true;
        }

        return false;
    }

    public function getQRCode($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        if (!$result) {
            return null;
        }

        return $this->tfa->getQRCodeImageAsDataUri($username, $result['secret']);
    }
}
