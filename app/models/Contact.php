<?php

class Contact extends Database
{
    private $tableName = 'contacts';
    public function __construct()
    {
        parent::__construct();
    }
    public function insertContact($data)
    {
        $sql = "INSERT INTO {$this->tableName} (name, phone, shop_status, budget_range, message, status, created_at) VALUES (:name, :phone, :shop_status, :budget_range, :message, :status, :created_at)";
        $params = [
            ':name' => $data['name'],
            ':phone' => $data['phone'],
            ':shop_status' => $data['shop_status'],
            ':budget_range' => $data['budget_range'],
            ':message' => $data['message'],
            ':status' => $data['status'],
            ':created_at' => $data['created_at']
        ];
        return $this->insert($sql, $params);
    }
}

?>