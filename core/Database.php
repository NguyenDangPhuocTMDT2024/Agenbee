<?php

class Database{
    public $host = servername;
    public $username = username;
    public $password = password;
    public $database = database;
    public $conn;
    
    public function __construct(){
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    public function insert($query, $params = []){
        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt->execute($params)) {
                return $this->conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            die("Insert failed: " . $e->getMessage());
        }
    }
    public function update($query, $params = []){
        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt->execute($params)) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            die("Update failed: " . $e->getMessage());
        }
    }
    public function delete($query, $params = []){
        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt->execute($params)) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            die("Delete failed: " . $e->getMessage());
        }
    }
    public function getAll($query, $params = []){
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  
            return $data;
        } catch (PDOException $e) {
            die("Select failed: " . $e->getMessage());
        }
    }
    public function getOne($query, $params = []){
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);  
            return $data;
        } catch (PDOException $e) {
            die("Select failed: " . $e->getMessage());
        }
    }
    public function count($query, $params = []){
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die("Count failed: " . $e->getMessage());
        }
    }
}
?>