<?php
class Perp_Inventory{
    private $conn;
    private $table_name = 'perp_inventory';

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

    function distinct(){
        $prods = array();
        $query = "SELECT DISTINCT Product_Code FROM " . $this->table_name . " WHERE Receiving_ID = '{$this->receiving_id}'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $prods[] = $row['Product_Code'];
        }

        $array = array();
        foreach ($prods as $row){
            $query_1 = "SELECT ROUND(SUM(Weight)) FROM " . $this->table_name . " WHERE Product_Code = :a AND Receiving_ID = '{$this->receiving_id}'";
            $query_2 = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE Product_Code = :a AND Receiving_ID = '{$this->receiving_id}'";
            $query_3 = "SELECT description FROM products WHERE pid = :a";
            $stmt_1 = $this->conn->prepare($query_1);
            $stmt_2 = $this->conn->prepare($query_2);
            $stmt_3 = $this->conn->prepare($query_3);
            $stmt_1->bindParam(':a', $row);
            $stmt_2->bindParam(':a', $row);
            $stmt_3->bindParam(':a', $row);
            $stmt_1->execute();
            $stmt_2->execute();
            $stmt_3->execute();
            $weight = $stmt_1->fetchColumn();
            $count = $stmt_2->fetchColumn();
            $description = $stmt_3->fetchColumn();
            $temp = array();
            $temp['prod'] = $row;
            $temp['weight'] = $weight;
            $temp['count'] = $count;
            $temp['description'] = $description;
            $array[] = $temp;
        } 
        return $array;
    }

    function distincts($values){
        $prods = array();
        $finals = array();
        $built = array();
        $built['prods'] = array();
        $built['descriptions'] = array();
        $built['weights'] = array();
        $built['counts'] = array();
        foreach ($values as $row){
            $query = "SELECT DISTINCT Product_Code FROM " . $this->table_name . " WHERE Receiving_ID = '{$this->receiving_id}'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $prods[] = $row['Product_Code'];
            }
            $array = array();
            foreach ($prods as $row){
                $query_1 = "SELECT ROUND(SUM(Weight)) FROM " . $this->table_name . " WHERE Product_Code = :a AND Receiving_ID = '{$this->receiving_id}'";
                $query_2 = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE Product_Code = :a AND Receiving_ID = '{$this->receiving_id}'";
                $query_3 = "SELECT description FROM products WHERE pid = :a";
                $stmt_1 = $this->conn->prepare($query_1);
                $stmt_2 = $this->conn->prepare($query_2);
                $stmt_3 = $this->conn->prepare($query_3);
                $stmt_1->bindParam(':a', $row);
                $stmt_2->bindParam(':a', $row);
                $stmt_3->bindParam(':a', $row);
                $stmt_1->execute();
                $stmt_2->execute();
                $stmt_3->execute();
                $weight = $stmt_1->fetchColumn();
                $count = $stmt_2->fetchColumn();
                $description = $stmt_3->fetchColumn();
                $temp = array();
                $temp['prod'] = $row;
                $temp['weight'] = $weight;
                $temp['count'] = $count;
                $temp['description'] = $description;
                $array[] = $temp;
                //validationn
                 if(in_array($row, $built['prods']) == false){
                    array_push($built['prods'], $row);
                    array_push($built['weights'], $weight);
                    array_push($built['counts'], $count);
                    array_push($built['descriptions'], $description);
                }
                else{
                    $index = array_search($row, $built['prods']);
                    $tmp_weight = int_val($built['weights'][$index]);
                    $tmp_count = int_val($built['counts'][$index]);
                    $weight_fin = $tmp_weight + int_val($weight); 
                    $count_fin = $tmp_count + int_val($count);
                    $built['weights'][$index] = $weight_fin;
                    $built['counts'][$index] = $count_fin;
                } 
                
            }
        $finals[] = $array;    
        }
        return $built;
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

    function dumpValues(){
        $query = "SELECT barcode FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

}

?>