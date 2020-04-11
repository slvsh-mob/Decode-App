<?php
class SKU{
    private $conn;
    private $table_name = "sku_table";

    public $id;
    public $sku;
    public $supplier;
    public $description;
    public $weight;
    public $protein;

    public function __construct($db){
        $this->conn = $db;
    }

    function readAll(){
        $query = "SELECT * FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $id[] = $row['id'];
            $sku[] = $row['SKU'];
            $supplier[] = $row['Supplier'];
            $description[] = $row['Description'];
            $weight[] = $row['Weight'];
            $protein[] = $row['Protein'];
        }

        $output = array('id' => $id, 'sku' => $sku, 'supplier' => $supplier, 'description' => $description, 'weight' => $weight, 'protein' => $protein);
        return $output;
    }
}

?>