<?php

class Category extends Database {
    private $tableName = 'categories';
    public function __construct() {
        parent::__construct();
    }
    public function getAllCategories() {
        $sql = "SELECT * FROM CATEGORIES";
        $result = $this->getAll($sql);
        return $result;
    }
    //thêm cate
    public function createCategory($data){
        $sql = "INSERT INTO ".$this->tableName." (name, description, created_at) VALUES (:name, :description, :created_at)";
        return $this->insert($sql,$data);
    }
}