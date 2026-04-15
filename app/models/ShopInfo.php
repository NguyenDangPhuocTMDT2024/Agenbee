<?php

class ShopInfo extends Database
{
    private $tableName = 'shop_info';

    public function __construct()
    {
        parent::__construct();
    }

    public function getByUserId($userId)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE user_id = :user_id LIMIT 1";
        $params = [
            'user_id' => $userId,
        ];
        return $this->getOne($sql, $params);
    }
}

?>