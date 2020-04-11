<?php
class Used{
    private $conn;
    private $table_name = "used_inventory";

    public $id;
    public $barcode;
    public $supplier;
    public $product_code;
    public $weight;
    public $serial;
    public $receiving_id;
    public $created;
    public $removed;

    public function __construct($db){
        $this->conn = $db;
    }

    function create(){
        $query = "INSERT INTO " . $this->table_name . " (barcode, supplier, product_code, weight, serial, receiving_id, created, removed) 
        VALUES (:barcode, :supplier, :product_code, :weight, :serial, :receiving_id, :created, :removed)";

        $stmt = $this->conn->prepare($query);

        $this->removed = date("Y-m-d H:i:s");

        $this->barcode = htmlspecialchars(strip_tags($this->barcode));
        $this->supplier = htmlspecialchars(strip_tags($this->supplier));
        $this->product_code = htmlspecialchars(strip_tags($this->product_code));
        $this->weight = htmlspecialchars(strip_tags($this->weight));
        $this->serial = htmlspecialchars(strip_tags($this->serial));
        $this->receiving_id = htmlspecialchars(strip_tags($this->receiving_id));
        $this->created = htmlspecialchars(strip_tags($this->created));
        

        $stmt->bindParam(":barcode", $this->barcode);
        $stmt->bindParam(":supplier", $this->supplier);
        $stmt->bindParam(":product_code", $this->product_code);
        $stmt->bindParam(":weight", $this->weight);
        $stmt->bindParam(":serial", $this->serial);
        $stmt->bindParam(":receiving_id", $this->receiving_id);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":removed", $this->removed);

        if($result = $stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        if($result = $stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    function update(){
        $query = "UPDATE " . $this->table_name . "SET barcode=:barcode, supplier=:supplier, product_code=:product_code, weight=:weight, serial=:serial, receiving_id=:receiving_id, created=:created, removed=:removed WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->barcode = htmlspecialchars(strip_tags($this->barcode));
        $this->supplier = htmlspecialchars(strip_tags($this->supplier));
        $this->product_code = htmlspecialchars(strip_tags($this->product_code));
        $this->weight = htmlspecialchars(strip_tags($this->weight));
        $this->serial = htmlspecialchars(strip_tags($this->serial));
        $this->receiving_id = htmlspecialchars(strip_tags($this->receiving_id));
        $this->created = htmlspecialchars(strip_tags($this->created));
        $this->removed = htmlspecialchars(strip_tags($this->removed));
        

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":barcode", $this->barcode);
        $stmt->bindParam(":supplier", $this->supplier);
        $stmt->bindParam(":product_code", $this->product_code);
        $stmt->bindParam(":weight", $this->weight);
        $stmt->bindParam(":serial", $this->serial);
        $stmt->bindParam(":receiving_id", $this->receiving_id);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":removed", $this->removed);

        //execute the query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function search($search_term, $from_record_num, $records_per_page){
        //select query
        $query = "SELECT * FROM " . $this->table_name . " WHERE product_code LIKE ? OR supplier LIKE ? OR receiving_id LIKE ? ORDER BY id ASC LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);
        $stmt->bindParam(2, $search_term);
        $stmt->bindParam(3, $search_term);
        $stmt->bindParam(4, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(5, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

    public function countAll_BySearch($search_term){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->name_name . " WHERE product_code LIKE ? OR supplier LIKE ? OR receiving_id LIKE ? ";

        $stmt = $this->conn->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);
        $stmt->bindParam(2, $search_term);
        $stmt->bindParam(3, $search_term);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }
}

?>