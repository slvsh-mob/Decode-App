<?php
class Category{
    //connection properties
    private $conn;
    private $table_name = "categories";

    //object properties
    public $id;
    public $name;
    public $code;
    public $created;

    public function __construct($db){
        $this->conn = $db;
    }

    function read(){
        $query = "SELECT id, name FROM " . $this->table_name . " ORDER BY name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    function readAll(){
        $query = "SELECT * FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $id[] = $row['id'];
            $name[] = $row['name'];
            $code[] = $row['code'];
            $created[] = $row['created'];
        }
        $output = array('id' => $id, 'name' => $name, 'code' => $code, 'created' => $created);
        return $output;
    }
    
    function readName(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE code = {$this->code} LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['name'];
        $this->id = $row['id'];
        $this->code = $row['code'];
    }

    function readToArray(){
        $query = "SELECT * FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $output = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $temp = [];
            $temp['id'] = $row['id'];
            $temp['name'] = $row['name'];
            $temp['code'] = $row['code'];
            $temp['created'] = $row['created'];
            $output[] = $temp;
        }

        return $output;
    }



}

?>