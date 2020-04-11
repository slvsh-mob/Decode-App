<?php
class Inventory{
    private $conn;
    private $table_name = 'inventory';

    public $id;
    public $barcode;
    public $gtin;
    public $product_code;
    public $weight;
    public $date;
    public $serial_id;
    public $received;
    public $receiving_id;

    public function __construct($db){
        $this->conn = $db;
    }

    function readOne(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :a";
        $stmt =$this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':a', $this->id);
        $stmt->execute();
        return $stmt;
    }

    function readAll($from_record_num, $records_per_page){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC LIMIT {$from_record_num}, {$records_per_page}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    function readToArray(){
        $query = "SELECT * FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $id[] = $row['id'];
            $gtin[] = $row['gtin'];
            $pid[] = $row['product_code'];
            $weight[] = $row['weight'];
            $date[] = $row['pack_date'];
            $serial[] = $row['serial_id'];
            $received[] = $row['received'];
            $receiving_id[] = $row['receiving_id'];
        }
        $output = array('id' => $id, 'gtin' => $gtin, 'pid' => $pid, 'weight' => $weight, 'date' => $date, 'serial' => $serial, 'received' => $received, 'receiving_id' => $receiving_id);
        return $output;
    }

    function countAll(){
        $query = "SELECT COUNT(*) FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $num = $stmt->fetchColumn();
        return $num;
    }

    function rowCount(){
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE Receiving_ID = :a";

        $stmt = $this->conn->prepare($query);
        $this->receiving_id = htmlspecialchars(strip_tags($this->receiving_id));
        $this->receiving_id = quote($this->receiving_id);
        $stmt->bindParam(':a', $this->receiving_id);
        $stmt->execute();
        $num = $stmt->fetchColumn();

        return $num;
    }

    function readRecID(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE Receiving_ID = '{$this->receiving_id}'";
        $query2 = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE Receiving_ID = '{$this->receiving_id}'";

        $stmt = $this->conn->prepare($query);
        $stmt1 = $this->conn->prepare($query2);
        $stmt->execute();
        $stmt1->execute();
        $num = $stmt1->fetchColumn();

        $array = array();
        $array['stmt'] = $stmt;
        $array['count'] = $num;
        return $array;
    }

    function searchPID($product_code){
        $query = "SELECT * FROM " . $this->table_name . " WHERE product_code = " . $product_code . "";
        $stmt = $this->conn->prepare($query);
        //$product_code = htmlspecialchars(strip_tags($product_code));
        //$stmt->bindParam(":a", $product_code);
        $stmt->execute();
        return $stmt;
    }

    function rowCountPID($product_code){
        $query = "SELECT COUNT(product_code) FROM " . $this->table_name . " WHERE product_code = " . $product_code . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $num = $stmt->fetchColumn();
        return $num;
    }

    function searchDESC($description){
        $query = "SELECT pid FROM products WHERE description LIKE '%" . $description . "%'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $product_code = $stmt->fetchColumn();
        $query_2 = "SELECT * FROM " . $this->table_name . " WHERE product_code = " . $product_code . "";
        $stmt_2 = $this->conn->prepare($query_2);
        $stmt_2->execute();

        $output = array();
        $output['stmt'] = $stmt_2;
        $output['prod_code'] = $product_code;
        return $output;
    }

    function dumpValues(){
        $query = "SELECT * FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getProducts(){
        $query = "SELECT supplier, description FROM products ORDER BY pid DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $descriptions = [];
        $suppliers = [];
        $product_codes = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $descriptions[] = $row['description'];
            $suppliers[] = $row['supplier'];
        }
        $output = array('descriptions' => $descriptions, 'suppliers' => $suppliers);
        return $output;
    }


    function summary(){
        $query = "SELECT SUM(weight) as sum_weight, product_code FROM inventory GROUP BY product_code";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $product_code = [];
        $sum_weight = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $temp_weight = round(floatval($row['sum_weight']), 2);
            if(is_null($temp_weight)){
                $product_code[] = $row['product_code'];
                $sum_weight[] = 0;
            }else{
                $product_code[] = $row['product_code'];
                $sum_weight[] = round(floatval($row['sum_weight']), 2);
            }
        }
        $products = $this->getProducts();
        $output = array('product_code' => $product_code, 'supplier' => $products['suppliers'], 'description' => $products['descriptions'], 'sum_weight' => $sum_weight);
        return $output;
    }

}

?>