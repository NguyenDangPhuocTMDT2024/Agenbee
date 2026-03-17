<?php 
class Booking extends Database {
    private $db;
    private $tableName = 'booking';
    public function __construct() {
        parent::__construct();
    }
}