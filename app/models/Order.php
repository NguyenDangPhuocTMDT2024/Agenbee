<?php
class Order extends Database {
    private $tableName = 'orders';
    private $orderItems = 'order_items';
    private $setupInfo = 'setup_info';
    private $user = 'users';
    
    public function __construct() {
        parent::__construct();
    }

    public function getAllOrders() {
        $sql = "SELECT o.*, u.name FROM ".$this->tableName." o LEFT JOIN ".$this->user." u ON o.user_id = u.id";
        $result = $this->getAll($sql);
        return $result;
    }

}