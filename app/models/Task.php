<?php
class Task extends Database {
    private $db;
    private $tableName = 'tasks';
    
    public function __construct() {
        parent::__construct();
        $this->db = $this;
    }
    public function getAllTasks() {
        $sql = "SELECT * FROM " . $this->tableName;
        $result = $this->getAll($sql);
        return $result;
    }
}