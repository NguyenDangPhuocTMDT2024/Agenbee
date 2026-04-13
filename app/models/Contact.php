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
        $sql = "INSERT INTO {$this->tableName} (name, email, phone, message) VALUES (:name, :email, :phone, :message)";
        $params = [
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':message' => $data['message']
        ];
        return $this->insert($sql, $params);
    }
}

?>