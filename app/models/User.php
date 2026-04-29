<?php

class User extends Database
{
    private $tableName = 'users';
    private $tokenTable = 'login_session';

    public function __construct()
    {
        parent::__construct();
    }
    //ham lay toan bo thong tin user
    public function getAllUsers()
    {
        $sql = "SELECT * FROM " . $this->tableName . " ORDER BY role ASC";
        $result = $this->getAll($sql);
        return $result;
    }
    //ham lay thong tin 1 user bang email
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE email = :email";
        $params = ['email' => $email];
        $result = $this->getOne($sql, $params);
        return $result;
    }
    //ham lay thong tin 1 user bang ID
    public function getUserByID($id)
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE id = :id";
        $params = ['id' => $id];
        $result = $this->getOne($sql, $params);
        return $result;
    }
    //ham lay thong tin phien lam viec user bang token
    public function getSessionByToken($token)
    {
        $sql = "SELECT * FROM " . $this->tokenTable . " WHERE token = :token";
        $params = ['token' => $token];
        $result = $this->getOne($sql, $params);
        return $result;
    }
    //ham them phien dang nhap vao bang login_token
    public function createLoginSession($data)
    {
        $sql = "INSERT INTO " . $this->tokenTable . " (user_id, token, created_at, updated_at) VALUES (:user_id, :token, :created_at, :updated_at)";
        $params = [
            'user_id' => isset($data['user_id']) ? $data['user_id'] : null,
            'token' => isset($data['token']) ? $data['token'] : null,
            'created_at' => isset($data['created_at']) ? $data['created_at'] : null,
            'updated_at' => isset($data['updated_at']) ? $data['updated_at'] : null
        ];
        return $this->insert($sql, $params);
    }
    //ham dem so luong user bang email
    public function countUsersByEmail($email)
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE email = :email";
        $params = ['email' => $email];
        $result = $this->count($sql, $params);
        return $result;
    }
    //ham tao user moi
    public function createUser($data)
    {
        $params = [
            'name' => isset($data['name']) ? $data['name'] : null,
            'email' => isset($data['email']) ? $data['email'] : null,
            'password' => isset($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null,
            'phone' => isset($data['phone']) ? $data['phone'] : null,
            'role' => isset($data['role']) ? $data['role'] : null,
            'status' => isset($data['status']) ? $data['status'] : null,
            'active_token' => isset($data['active_token']) ? $data['active_token'] : null,
            'created_at' => isset($data['created_at']) ? $data['created_at'] : null
        ];
        $sql = "INSERT INTO " . $this->tableName . " (name, email, password, phone, active_token, role, status, created_at) VALUES (:name, :email, :password, :phone, :active_token, :role, :status, :created_at)";
        return $this->insert($sql, $params);
    }
    //ham cap nhat login_token cho user
    public function updateLoginSessionByID($data)
    {
        $sql = "UPDATE " . $this->tokenTable . " SET token = :token, updated_at = :updated_at WHERE user_id = :user_id";
        $params = [
            'token' => isset($data['token']) ? $data['token'] : null,
            'updated_at' => isset($data['updated_at']) ? $data['updated_at'] : null,
            'user_id' => isset($data['user_id']) ? $data['user_id'] : null,
        ];
        return $this->update($sql, $params);
    }
    //ham thay doi forgot_token khi user quen mat khau bang email
    public function updateForgotTokenByEmail($data)
    {
        $sql = "UPDATE " . $this->tableName . " SET forgot_token = :forgot_token, updated_at = :updated_at WHERE email = :email";
        $params = [
            'forgot_token' => isset($data['forgot_token']) ? $data['forgot_token'] : null,
            'email' => isset($data['email']) ? $data['email'] : null,
            'updated_at' => isset($data['updated_at']) ? $data['updated_at'] : null
        ];
        return $this->update($sql, $params);
    }
    //ham thay doi active_token khi user kich hoat tai khoan bang id
    public function updateActiveTokenByID($data)
    {
        $sql = "UPDATE " . $this->tableName . " SET active_token = :active_token, updated_at = :updated_at, status = :status WHERE id = :id";
        $params = [
            'active_token' => isset($data['active_token']) ? $data['active_token'] : null,
            'id' => isset($data['id']) ? $data['id'] : null,
            'updated_at' => isset($data['updated_at']) ? $data['updated_at'] : null,
            'status' => isset($data['status']) ? $data['status'] : null
        ];
        return $this->update($sql, $params);
    }
    //ham lay thong tin user bang forgot_token
    public function getUserByForgotToken($token)
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE forgot_token = :forgot_token";
        $params = ['forgot_token' => $token];
        $result = $this->getOne($sql, $params);
        return $result;
    }
    //ham lay thong tin user bang active_token
    public function getUserByActiveToken($token)
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE active_token = :active_token";
        $params = ['active_token' => $token];
        $result = $this->getOne($sql, $params);
        return $result;
    }
    //ham cap nhat mat khau moi cho user
    public function updatePasswordByID($data)
    {
        $sql = "UPDATE " . $this->tableName . " SET password = :password, forgot_token = :forgot_token, updated_at = :updated_at WHERE id = :id";
        $params = [
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'forgot_token' => isset($data['forgot_token']) ? $data['forgot_token'] : null,
            'updated_at' => isset($data['updated_at']) ? $data['updated_at'] : null,
            'id' => isset($data['id']) ? $data['id'] : null
        ];
        return $this->update($sql, $params);
    }
    //hàm xóa user bằng id
    public function deleteUserByID($id){
        $sql = "DELETE FROM ".$this->tableName. " WHERE id = :id";
        $params = ['id' => $id];
        return $this->delete($sql,$params);
    }

    public function updateUserByID($data, $userID){
        $sql = "UPDATE " . $this->tableName . " SET name = :name, email = :email, phone = :phone, updated_at = :updated_at WHERE id = :id";
        $params = [
            'name' => isset($data['name']) ? $data['name'] : null,
            'email' => isset($data['email']) ? $data['email'] : null,
            'phone' => isset($data['phone']) ? $data['phone'] : null,
            'updated_at' => date('Y-m-d H:i:s'),
            'id' => $userID
        ];
        return $this->update($sql, $params);
    }
    public function updateUserStatusByID($id, $status){
        $sql = "UPDATE " . $this->tableName . " SET status = :status, updated_at = :updated_at WHERE id = :id";
        $params = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
            'id' => $id
        ];
        return $this->update($sql, $params);
    }
}
