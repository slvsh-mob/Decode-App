<?php
class Formula{
    private $conn;
    private $table_name = 'formulas';

    public $id;
    public $mask;
    public $length;
    public $gtin_start;
    public $gtin_length;
    public $prod_start;
    public $prod_length;
    public $weight_start;
    public $weight_mod;
    public $date_start;
    public $serial_start;
    public $serial_length;
    public $addl_serial_start;
    public $addl_serial_length;

    public function __construct($db){
        $this->conn = $db;
    }

    public function readAll(){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $output = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $temp = array();
            $temp['mask'] = $mask;
            $temp['length'] = $length;
            $temp['gtin_start'] = $gtin_start;
            $temp['gtin_length'] = $gtin_length;
            $temp['prod_start'] = $prod_start;
            $temp['prod_length'] = $prod_length;
            $temp['weight_start'] = $weight_start;
            $temp['weight_mod'] = $weight_mod;
            $temp['date_start'] = $date_start;
            $temp['serial_start'] = $serial_start;
            $temp['serial_length'] = $serial_length;
            $temp['addl_serial_start'] = $addl_serial_start;
            $temp['addl_serial_length'] = $addl_serial_length;
            $output[] = $temp;
        }
        return $output;
    }

    function getMasks(){
        $query = "SELECT mask FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        while ($row = $stmt->fetchColumn()){
            $output[] = $row;
        }
        return $output;
    }


}

?>