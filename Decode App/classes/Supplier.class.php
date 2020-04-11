<?php
class Supplier{
    private $conn;
    private $table_name = 'suppliers';

    public $id;
    public $gtin;
    public $name;
    public $status;
    public $created;

    public function __construct($db){
        $this->conn = $db;
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

    function readAll(){
        $query = "SELECT id, name, status, created FROM " . $this->table_name . " ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $id[] = $row['id'];
            $name[] = $row['name'];
            $status[] = $row['status'];
            $created[] = $row['created'];
        }
        $output = array('id' => $id, 'name' => $name, 'status' => $status, 'created' => $created);
        return $output;
    }

    function readGTIN($gtin){
        $query = "SELECT name FROM " . $this->table_name . " WHERE gtin = " . $gtin . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $output = $stmt->fetchColumn();
        return $output;
    }

    function readSuppliers(){
        $query = "SELECT id, name FROM " . $this->table_name . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $id = [];
        $name = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $id[] = $row['id'];
            $name[] = $row['name'];
        }
        $output = [];
        $output['id'] = $id;
        $output['name'] = $name;
        return $output;
    }

    function readToArray(){
        $query = "SELECT id, name FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $output = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $temp = [];
            $temp['id'] = $row['id'];
            $temp['name'] = $row['name'];
            $output[$row['id']] = $temp;
        }
        return $output;
    }


    function addSupplier($value){
        $query = "INSERT INTO " . $this->table_name . " (name, status, created, gtin) VALUES (:a, :b, :c, :d)";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($value));
        $this->status = 0;
        $this->created = date('Y-m-d H:i:s');
        $this->gtin = '0';

        $stmt->bindParam(':a', $this->name);
        $stmt->bindParam(':b', $this->status);
        $stmt->bindParam(':c', $this->created);
        $stmt->bindParam(':d', $this->gtin);


        $output = [];
        if($stmt->execute()){
            $last_id = $this->conn->lastInsertId();
            $output['message'] = "Success";
            $output['insert_id'] = $last_id;
            return $output;
        }else{
            $output['message'] = "Failure";
            $output['insert_id'] = "null";
            return $output;
        }
    }


}

?>