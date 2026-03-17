<?php
class Order extends Database {
    private $db;
    
    public function __construct() {
        parent::__construct();
        $this->db = $this;
    }

    public function getAllOrders() {
        $sql = "SELECT * FROM ORDERS";
        $result = $this->select($sql);
        return $result;
    }    
}