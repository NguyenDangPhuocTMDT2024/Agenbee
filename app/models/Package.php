<?php

class Package extends Database
{
    private $tableName = 'packages';
    private $categoryTable = 'categories';

    public function __construct()
    {
        parent::__construct();
    }
    //lấy tất cả gói
    public function getAllPackages()
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->tableName} p LEFT JOIN {$this->categoryTable} c 
                ON p.category_id = c.id 
                ORDER BY category_id ASC";
        $result = $this->getAll($sql);
        return $result;
    }
    //thêm gói
    public function createPackages($data)
    {
        $sql = "INSERT INTO " . $this->tableName . "(name, avatar, description, price, category_id, hidden, created_at) 
                VALUES (:name, :avatar, :description, :price, :category, :hidden, :created_at)";
        return $this->insert($sql, $data);
    }
    //lấy gói theo id
    public function getPackagesByID($id) 
    {
        $sql = "SELECT * FROM ".$this->tableName." WHERE id = :id";
        $param = ['id' => $id];
        return $this->getOne($sql,$param);
    }
    //cập nhật gói theo id
    public function updatePackageByID($data,$id){
        $sql = "UPDATE ".$this->tableName. 
        " SET name = :name, avatar = :avatar, description = :description, price = :price, category_id = :category, hidden = :hidden, updated_at = :updated_at
        WHERE id = :id";
        $data['id'] = $id;
        return $this->update($sql,$data);
    }
    //xóa gói theo id
    public function deletePackageByID($id){
        $sql = "DELETE FROM ".$this->tableName." WHERE id = :id";
        $param = ['id' => $id];
        return $this->delete($sql, $param);
    }
}
