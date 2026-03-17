<?php
class Requirement extends Database {
    private $db;
    private $tableName = 'requirement';
    public function __construct() {
        parent::__construct();
    }
}