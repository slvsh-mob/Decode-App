<?php
class Producer{
    private $conn;
    private $table_name = 'producers';

    public $id;
    public $name;
    public $status;
    public $created;

    public function __construct($db){
        $this->conn = $db;
    }

    function create(){
        $query = "INSERT INTO " . $this->table_name . " (name, status, created) VALUES (:a, :b, :c)";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->created = date('Y-m-d H:i:s');

        $stmt->bindParam(':a', $this->name);
        $stmt->bindParam(':b', $this->status);
        $stmt->bindParam(':c', $this->created);

       if($stmt->execute()){
           return true;
       }else{
           return false;
       }
    }

    function update(){
        $text = '';
        $holder = array();
        if(isset($this->name)){
            $text .= " SET name = :a";
            $holder[] = 1;
        }
        if(isset($this->status)){
            $text .= ", status = :b";
            $holder[] = 2;
        }
        $query = "UPDATE " . $this->table_name . $text . " WHERE id = :e";

        $stmt = $this->conn->prepare($query);

         foreach ($holder as $row){
            switch ($row){
                case 1:
                    $this->pid = htmlspecialchars(strip_tags($this->name));
                    $stmt->bindParam(':a', $this->pid);
                    break;
                case 2:
                    $this->supplier = htmlspecialchars(strip_tags($this->status));
                    $stmt->bindParam(':b', $this->supplier);
                    break;
            }
        }

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':e', $this->id);

        //execute the query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE id = {$this->id}";

        $stmt = $this->conn->prepare($query);

        if($result = $stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    function countAll(){
        $query = "SELECT id FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();

        return $num;
    }

    function readOne(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :a LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":a", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['name'];
        $this->status = $row['status'];
        $this->created = $row['created'];
    }

    function readAll($from_record_num, $records_per_page){
        $query = "SELECT id, name, status, created FROM " . $this->table_name . " ORDER BY id ASC LIMIT {$from_record_num}, {$records_per_page}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

}
?>