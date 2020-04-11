<?php
class Supplier_E_List{
    private $conn;
    private $table_name = "email_list_suppliers";

    public $id;
    public $name;
    public $email_address;
    public $status;
    public $created;

    public function __construct($db){
        $this->conn = $db;
    }

    function create(){
        $query = "INSERT INTO " . $this->table_name . " (name, email_address, status, created) VALUES (:a, :b, :c, :d)";

        $stmt = $this->conn->prepare($query);

        $this->created = date('Y-m-d H:i:s');

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email_address = htmlspecialchars(strip_tags($this->email_address));

        $stmt->bindParam(':a', $this->name);
        $stmt->bindParam(':b', $this->email_address);
        $stmt->bindParam(':c', $this->status);
        $stmt->bindParam(':d', $this->created);

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
        if(isset($this->email_address)){
            $text .= ", email_address = :b";
            $holder[] = 2;
        }
        if(isset($this->status)){
            $text .= ", status = :c";
            $holder[] = 3;
        }
        $query = "UPDATE " . $this->table_name . $text . " WHERE id = :e";

        $stmt = $this->conn->prepare($query);

         foreach ($holder as $row){
            switch ($row){
                case 1:
                    $this->pid = htmlspecialchars(strip_tags($this->name));
                    $stmt->bindParam(':a', $this->name);
                    break;
                case 2:
                    $this->supplier = htmlspecialchars(strip_tags($this->email_address));
                    $stmt->bindParam(':b', $this->email_address);
                    break;
                case 3:
                    $stmt->bindParam(':c', $this->status);
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
        $this->email_address = $row['email_address'];
        $this->status = $row['status'];
        $this->created = $row['created'];
    }

    function readAll($from_record_num, $records_per_page){
        $query = "SELECT id, name, email_address, status, created FROM " . $this->table_name . " ORDER BY id ASC LIMIT {$from_record_num}, {$records_per_page}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    function activeUsers(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE status = 1";
        $stmt = $this->conn->prepare($query);
        $emails = array();
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $emails[] = $row['email_address'];
        }
        return $emails;
    }


}

?>