<?php
class Reviewer extends Database {
    private $db;
    private $tableName = 'reviewers';
    
    public function __construct() {
        parent::__construct();
        $this->db = $this;
    }
    public function getAllReviewers() {
        $sql = "SELECT * FROM " . $this->tableName;
        $result = $this->getAll($sql);
        return $result;
    }
    public function getReviewerByEmail($email) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE email = :email";
        $params = ['email' => $email];
        $result = $this->getOne($sql, $params);
        return $result;
    }
}