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
            ':shop_status' => $data['shopStatus'],
            ':budget_range' => $data['budgetRange'],
            ':message' => $data['message'],
            ':status' => $data['status'],
            ':created_at' => $data['createdAt']
        ];
        return $this->insert($sql, $params);
    }
}

?>