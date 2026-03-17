<?php
class SetupInfo extends Database {
    private $db;
    private $tableName = 'setup_info';
    
    public function __construct() {
        parent::__construct();
        $this->db = $this;
    }
    public function getSetupInfo() {
        $sql = "SELECT * FROM " . $this->tableName . " LIMIT 1";
        $result = $this->getOne($sql);
        return $result;
    }
}