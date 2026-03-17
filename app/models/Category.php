<?php

class Category extends Database {
    private $db;
    
    public function __construct() {
        parent::__construct();
    }
    public function getAllCategories() {
        $sql = "SELECT * FROM CATEGORIES";
        $result = $this->getAll($sql);
        return $result;
    }
}