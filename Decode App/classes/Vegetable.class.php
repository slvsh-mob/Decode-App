<?php
class Vegetable{
    private $conn;
    private $table_name = 'vegetables';
    private $table_name_2 = 'vegetable_shipments';
    public $table_name_3 = 'vegetable_types';

    public $id;
    public $vegetable;
    public $type;
    public $amount;
    public $supplier;
    public $created;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $query = "INSERT INTO " . $this->table_name_2 . " (vegetable, type, amount, supplier, created) VALUES (:a, :b, :c, :d, :e)";
        $stmt = $this->conn->prepare($query);
        $this->created = date("y-m-d H:i:s");
        $stmt->bindParam(":a", $this->vegetable);
        $stmt->bindParam(":b", $this->type);
        $stmt->bindParam(":c", $this->amount);
        $stmt->bindParam(":d", $this->supplier);
        $stmt->bindParam(":e", $this->created);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function readAll($from_record_num, $records_per_page){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC LIMIT {$from_record_num}, {$records_per_page}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    } 

    public function getInvStats(){
        $query = "SELECT ROUND(SUM(Amount)) FROM " . $this->table_name . " WHERE type = :a";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":a", $this->type);
        $stmt->execute();
        $output = $stmt->fetchColumn();
        return $output;
    }

    function read(){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    function readTypes(){
        $query = "SELECT * FROM vegetable_types";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $output = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $output[$row['id']] = $row['name'];
        }
        return $output;
    }

    function readSuppliers(){
        $query = "SELECT * FROM vegetable_suppliers";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $output = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $output[$row['id']] = $row['name'];
        }
        return $output;
    }

    function readInventory(){
        $query = "SELECT * FROM vegetable_inventory";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function readType($type){
        $query = "SELECT name FROM vegetable_types WHERE id = " . $type . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $response = $stmt->fetch(PDO::FETCH_ASSOC);
        $name = $response['name'];
        return $name;
    }

    function readAmount($type){
        $query = "SELECT amount from vegetable_inventory WHERE type = " . $type . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $output = $stmt->fetch(PDO::FETCH_ASSOC);
        $amount = $output['amount'];
        return $amount;
    }

    function updateAmount($type, $amount){
        $query = "UPDATE vegetable_inventory SET amount = " . $amount . " WHERE type = " . $type . "";
        $stmt = $this->conn->prepare($query);
        if($stmt->execute()){
            $message = "Sucessfully updated vegetable inventory weight";
        }else{
            $message = "Something went wrong, please try again";
        }
        return $message;
    }

    function addRecRecord($value){
        $query = "INSERT INTO vegetable_receiving (supplier, vegetable, amount, created) VALUES (:a, :b, :c, :d)";
        $stmt = $this->conn->prepare($query);
        $temp_supplier = $value['supplier'];
        $temp_vegetable = $value['vegetable'];
        $temp_amt = $value['amount'];
        $temp_created = date('H:m:s d/m/Y');

        $temp_amt = htmlspecialchars(strip_tags($temp_amt));

        $stmt->bindParam(':a', $temp_supplier);
        $stmt->bindParam(':b', $temp_vegetable);
        $stmt->bindParam(':c', $temp_amt);
        $stmt->bindParam(':d', $temp_created);

        $output = [];
        if($stmt->execute()){
            $output['message'] = "Successful";
            $last_id = $this->conn->lastInsertId();
            $output['insert_id'] = $last_id;
        }else{
            $output['message'] = "Failure";
            $output['insert_id'] = "null";
        }
        return $output;
    }


    function readToArray(){
        $query = "SELECT vegetable_types.name as vname, vegetable_inventory.amount as amount, vegetable_inventory.updated as updated FROM vegetable_inventory INNER JOIN vegetable_types ON vegetable_inventory.type = vegetable_types.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $vegetable[] = $row['vname'];
            $amount[] = $row['amount'];
            $updated[] = $row['updated'];
        }
        $output = array('vegetable' => $vegetable, 'amount' => $amount, 'updated' => $updated);
        return $output;
    }

    function readRecToArray(){
        $query = "SELECT vegetable_receiving.id as vid, vegetable_suppliers.name as vsupplier, vegetable_types.name as vtype, vegetable_receiving.amount as vamount, vegetable_receiving.created as vcreated FROM vegetable_receiving INNER JOIN vegetable_suppliers ON vegetable_suppliers.id = vegetable_receiving.supplier INNER JOIN vegetable_types ON vegetable_types.id = vegetable_receiving.vegetable";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $id[] = $row['vid'];
            $supplier[] = $row['vsupplier'];
            $vegetable[] = $row['vtype'];
            $amount[] = $row['vamount'];
            $created[] = $row['vcreated'];
        }
        $output = array('id' => $id, 'supplier' => $supplier, 'vegetable' => $vegetable, 'amount' => $amount, 'created' => $created);
        return $output;

    }

}
?>