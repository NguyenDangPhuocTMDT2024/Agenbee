<?php

class ShopInfo extends Database
{
    private $tableName = 'shop_info';

    public function __construct()
    {
        parent::__construct();
    }

    public function getShopInfoById($userId)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE user_id = :user_id LIMIT 1";
        $params = [
            'user_id' => $userId,
        ];
        return $this->getOne($sql, $params);
    }
    public function updateShopInfoByUserId($userId, $data)
    {
        $fields = [];
        $params = ['user_id' => $userId];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[$key] = $value;
        }
        $fieldsStr = implode(', ', $fields);
        $sql = "UPDATE {$this->tableName} SET $fieldsStr WHERE user_id = :user_id";
        return $this->update($sql, $params);
    }
}

?>