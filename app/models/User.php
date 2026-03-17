<?php

class User extends Database {
    private $tableName = 'users';
    private $tokenTable = 'login_session';
    
    public function __construct() {
        parent::__construct();
    }
    //ham lay toan bo thong tin user
    public function getAllUsers() {
        $sql = "SELECT * FROM ".$this->tableName;
        $result = $this->getAll($sql);
        return $result;
    }
    //ham lay thong tin 1 user bang email
    public function getUserByEmail($email) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE email = :email";
        $params = ['email' => $email];
        $result = $this->getOne($sql, $params);
        return $result;
    }
    //ham lay thong tin 1 user bang ID
    public function getUserByID($id) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE id = :id";
        $params = ['id' => $id];
        $result = $this->getOne($sql, $params);
        return $result;
    }
    //ham lay thong tin phien lam viec user bang token
    public function getSessionByToken($token) {
        $sql = "SELECT * FROM " . $this->tokenTable . " WHERE token = :token";
        $params = ['token' => $token];
        $result = $this->getOne($sql, $params);
        return $result;
    } 
    //ham them phien dang nhap vao bang login_token
    public function createLoginSession($userId, $token, $createdAt) {
        $sql = "INSERT INTO " . $this->tokenTable . " (user_id, token, created_at) VALUES (:user_id, :token, :created_at)";
        $params = ['user_id' => $userId, 'token' => $token, 'created_at' => $createdAt];
        return $this->insert($sql, $params);
    }
    //ham dem so luong user bang email
    public function countUsersByEmail($email) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE email = :email";
        $params = ['email' => $email];
        $result = $this->count($sql, $params);
        return $result;
    }
    //ham tao user moi
    public function createUser($name, $email, $password, $phone, $activeToken, $createdAt) {
        $sql = "INSERT INTO " . $this->tableName . " (name, email, password, phone, active_token, created_at) VALUES (:name, :email, :password, :phone, :active_token, :created_at)";
        $params = ['name' => $name, 'email' => $email, 'password' => $password, 'phone' => $phone, 'active_token' => $activeToken, 'created_at' => $createdAt];
        return $this->insert($sql, $params);
    }
    //ham cap nhat login_token cho user
    public function updateLoginSessionByID($userId, $token, $updatedAt) {
        $sql = "UPDATE " . $this->tokenTable . " SET token = :token, updated_at = :updated_at WHERE user_id = :user_id";
        $params = ['token' => $token, 'updated_at' => $updatedAt, 'user_id' => $userId];
        return $this->update($sql, $params);
    }
    //ham thay doi forgot_token khi user quen mat khau bang email
    public function updateForgotToken($email, $token, $updatedAt) {
        $sql = "UPDATE " . $this->tableName . " SET forgot_token = :forgot_token, updated_at = :updated_at WHERE email = :email";
        $params = ['forgot_token' => $token, 'email' => $email, 'updated_at' => $updatedAt];
        return $this->update($sql, $params);
    }
    //ham thay doi active_token khi user kich hoat tai khoan bang id
    public function updateActiveToken($userId, $token, $status, $updatedAt) {
        $sql = "UPDATE " . $this->tableName . " SET active_token = :active_token, updated_at = :updated_at, status = :status WHERE id = :id";
        $params = ['active_token' => $token, 'id' => $userId, 'updated_at' => $updatedAt, 'status' => $status];
        return $this->update($sql, $params);
    }
    //ham lay thong tin user bang forgot_token
    public function getUserByForgotToken($token) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE forgot_token = :forgot_token";
        $params = ['forgot_token' => $token];
        $result = $this->getOne($sql, $params);
        return $result;
    }
    //ham lay thong tin user bang active_token
    public function getUserByActiveToken($token) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE active_token = :active_token";
        $params = ['active_token' => $token];
        $result = $this->getOne($sql, $params);
        return $result;
    }
    //ham cap nhat mat khau moi cho user
    public function updatePasswordByID($userId, $password, $forgotToken, $updatedAt) {
        $sql = "UPDATE " . $this->tableName . " SET password = :password, forgot_token = :forgot_token, updated_at = :updated_at WHERE id = :id";
        $params = ['password' => password_hash($password, PASSWORD_DEFAULT), 'forgot_token' => $forgotToken, 'updated_at' => $updatedAt, 'id' => $userId];
        return $this->update($sql, $params);
    }
}