<?php
class User {
    private $id;
    private $username;
    private $password;
    private $secret;

    public function __construct($id, $username, $password, $secret) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->secret = $secret;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getSecret() {
        return $this->secret;
    }
}