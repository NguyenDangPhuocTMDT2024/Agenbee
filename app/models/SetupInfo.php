<?php
class SetupInfo extends Database {
    private $tableName = 'setup_info';
    private $productTable = 'shop_products'; 
    
    public function __construct() {
        parent::__construct();
    }
    public function getSetupInfoById($id) {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = :id";
        return $this->getOne($sql, ['id' => $id]);
    }
    public function getSetupInfoByOrderId($order_id) {
        $sql = "SELECT * FROM {$this->tableName} WHERE order_id = :order_id";
        return $this->getOne($sql, ['order_id' => $order_id]);
    }
    public function getProductsBySetupInfoId($setupInfoId) {
        $sql = "SELECT * FROM {$this->productTable} WHERE setup_id = :setup_info_id";
        return $this->getAll($sql, ['setup_info_id' => $setupInfoId]);
    }
    public function updateSetupInfoByUserId($userId, $data) {
        // $check = $this->getSetupInfoById($userId);
        // if(empty($check)) {
        //     $sql = "INSERT INTO {$this->tableName} (user_id, shop_name, platform, product_count) VALUES (:user_id, :shop_name, :platform, :product_count)";
        //     $params = [
        //         'user_id' => $userId,
        //         'shop_name' => $data['shop_name'],
        //         'platform' => $data['platform'],
        //         'product_count' => $data['product_count']
        //     ];
        //     return $this->insert($sql, $params);
        // } else {
        //     $fields = [];
        //     $params = ['user_id' => $userId];
        //     foreach ($data as $key => $value) {
        //         $fields[] = "$key = :$key";
        //         $params[$key] = $value;
        //     }
        //     $fieldsStr = implode(', ', $fields);
        //     $sql = "UPDATE {$this->tableName} SET $fieldsStr WHERE user_id = :user_id";
        //     return $this->update($sql, $params);
        // }
    }   
}