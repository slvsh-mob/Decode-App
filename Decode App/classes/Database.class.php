<?php
Class Database{
 private $host = "localhost";
 private $db_name = "Great_American";
 private $username = "Lou";
 private $password = "GAFNJ001";
 public $conn;

    // get the database connection
    public function getConnection(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}

?>