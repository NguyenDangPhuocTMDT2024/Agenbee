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
    public function getAllContacts()
    {
        $sql = "SELECT * FROM {$this->tableName} ORDER BY `status` ASC, created_at DESC";
        return $this->getAll($sql);
    }
    public function getContactByID($id)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = :id ORDER BY `status` ASC, created_at DESC";
        $params = [':id' => $id];
        return $this->getOne($sql, $params);
    }
    public function updateContactStatusById($id, $status)
    {
        $sql = "UPDATE {$this->tableName} SET `status` = :status, `updated_at` = :updated_at WHERE id = :id";
        $params = [
            'id' => $id,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        return $this->update($sql, $params);
    }
    public function deleteContactByID($id)
    {
        $sql = "DELETE FROM {$this->tableName} WHERE id = :id";
        $params = [':id' => $id];
        return $this->delete($sql, $params);
    }
}

?>
    