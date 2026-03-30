<?php

class Package extends Database
{
    private $tableName = 'packages';
    private $itemTable = 'package_items';
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
    public function getAddonPackages()
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->tableName} p LEFT JOIN {$this->categoryTable} c 
                ON p.category_id = c.id 
                WHERE p.category_id = '4'
                ORDER BY category_id ASC";
        $result = $this->getAll($sql);
        return $result;
    } 
    //lấy tất cả item của gói
    function getAllPackageItems()
    {
        $sql = "SELECT pi.*, p.name as addon_name FROM " . $this->itemTable . " pi 
                JOIN " . $this->tableName . " p ON pi.addon_id = p.id";
        return $this->getAll($sql);
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
    //thêm gói con vào gói chính
    public function createPackagesAddon($addon, $packageId, $quantity)
    {
        $sql = "INSERT INTO " . $this->itemTable . "(addon_id, combo_id, quantity) 
                VALUES (:addon_id, :combo_id, :quantity)";
        $param = [
            'addon_id' => $addon,
            'combo_id' => $packageId,
            'quantity' => $quantity
        ];
        return $this->insert($sql, $param);
    }
    //lấy các gói con theo id gói chính
    public function getAddonsByPackageID($packageId)
    {
        $sql = "SELECT pi.*, p.name as addon_name FROM " . $this->itemTable . " pi 
                JOIN " . $this->tableName . " p ON pi.addon_id = p.id 
                WHERE pi.combo_id = :packageId";
        $param = ['packageId' => $packageId];
        return $this->getAll($sql, $param);
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
