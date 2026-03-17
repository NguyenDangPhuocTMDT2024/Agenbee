<?php
class Payment extends Database {
    private $db;
    private $tableName = 'payment';
    public function __construct() {
        parent::__construct();
    }
}