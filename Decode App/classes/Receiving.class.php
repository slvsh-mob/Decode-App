<?php
class Receiving{

    //database connection
    private $conn;
    private $table_name = "receiving";

    //object properties
    public $id;
    public $supplier;
    public $exp_count;
    public $exp_weight;
    public $act_count;
    public $act_weight;
    public $receiving_id;
    public $created;

     public function __construct($db){
        $this->conn = $db;
    }

    function searchDate($date_start, $date_end){
        $output = array();
        $query = "SELECT * FROM " . $this->table_name . " WHERE created BETWEEN :a AND :b";
        $stmt = $this->conn->prepare($query);

        $date_start = htmlspecialchars(strip_tags($date_start)); 
        $date_end = htmlspecialchars(strip_tags($date_end));

        $stmt->bindValue(':a', $date_start);
        $stmt->bindValue(':b', $date_end);

        $stmt->execute();
        $temp = array();
        $rec_ids = array();
        while ($var = $stmt->fetch(PDO::FETCH_ASSOC)){
            $temp['id'] = $var['id'];
            $temp['supplier'] = $var['supplier'];
            $temp['exp_count'] = $var['exp_count'];
            $temp['exp_weight'] = $var['exp_weight'];
            $temp['act_count'] = $var['act_count'];
            $temp['act_weight'] = $var['act_weight'];
            $temp['receiving_id'] = $var['receiving_id'];
            $temp['created'] = $var['created'];
            $rec_ids[] = $var['receiving_id'];
            $output[] = $temp;
        }
        $output['receiving_ids'] = $rec_ids;
        return $output;
    }

    function num_searchDate($date_start, $date_end){
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE created BETWEEN :a AND :b";
        $stmt = $this->conn->prepare($query);

        $date_start = htmlspecialchars(strip_tags($date_start)); 
        $date_end = htmlspecialchars(strip_tags($date_end));

        $stmt->bindValue(':a', $date_start);
        $stmt->bindValue(':b', $date_end);

        $stmt->execute();
        $row = $stmt->fetchColumn();

        return $row;
    }

    function count(){
        $query = "SELECT COUNT(*) FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $num_receiving = $stmt->fetchColumn();
        return $num_receiving;
    }

    public function readPaging($from_record_num, $records_per_page){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created DESC LIMIT :a, :b";
        $from_record_num = htmlspecialchars(strip_tags($from_record_num));
        $records_per_page = htmlspecialchars(strip_tags($records_per_page));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":a", $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(":b", $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

    function readRecID(){
        $query = "SELECT * FROM perp_inventory WHERE Receiving_ID = :a";

        $stmt = $this->conn->prepare($query);
        $this->receiving_id = htmlspecialchars(strip_tags($this->receiving_id));
        $this->receiving_id = quote($this->receiving_id);
        $stmt->bindParam(':a', $this->receiving_id);
        $stmt->execute();

        return $stmt;
    }

    function getRecID($value){
        $query = "SELECT receiving_id FROM " . $this->table_name . " WHERE id = :a";
        $value = htmlspecialchars(strip_tags($value));
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":a", $value);
        $stmt->execute();
        $rec_id = $stmt->fetchColumn();
        return $rec_id;
    }

    function readByRecID($value){
        $temp = array();
        $query = "SELECT * FROM " . $this->table_name . " WHERE receiving_id = '{$value}'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $var = $stmt->fetch(PDO::FETCH_ASSOC);
        $temp['supplier'] = $var['supplier'];
        $temp['exp_count'] = $var['exp_count'];
        $temp['exp_weight'] = $var['exp_weight'];
        $temp['act_count'] = $var['act_count'];
        $temp['act_weight'] = $var['act_weight'];
        $temp['receiving_id'] = $var['receiving_id'];
        $temp['created'] = $var['created'];
        return $temp;
    }


/*     function create(){
        $query = "INSERT INTO " . $this->table_name . " (supplier, exp_count, exp_weight, act_count, act_weight, receiving_id, created) VALUES (:supplier, :exp_count, :exp_weight, :act_count, :act_weight, :receiving_id, :created)";
        $stmt = $this->conn->prepare($query);

        $this->supplier = htmlspecialchars(strip_tags($this->supplier));
        $this->exp_count = htmlspecialchars(strip_tags($this->exp_count)); 
        $this->exp_weight = htmlspecialchars(strip_tags($this->exp_weight));
        $this->act_count = htmlspecialchars(strip_tags($this->act_count));
        $this->act_weight = htmlspecialchars(strip_tags($this->act_weight));
        $this->receiving_id = htmlspecialchars(strip_tags($this->receiving_id));

        $this->created = date("Y-m-d H:i:s");

        $stmt->bindParam(":supplier", $this->supplier);
        $stmt->bindParam(":exp_count", $this->exp_count);
        $stmt->bindParam(":exp_weight", $this->exp_weight);
        $stmt->bindParam(":act_count", $this->act_count);
        $stmt->bindParam(":act_weight", $this->act_weight);
        $stmt->bindParam(":receiving_id", $this->receiving_id);
        $stmt->bindParam(":created", $this->created);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    } */

/*     function update(){
        $query = "UPDATE " . $this->table_name . " SET supplier = :supplier, exp_count = :exp_count, exp_weight = :exp_weight, act_count = :act_count, act_weight = :act_weight, receiving_id = :receiving_id, created = :created WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->supplier = htmlspecialchars(strip_tags($this->supplier));
        $this->exp_count = htmlspecialchars(strip_tags($this->exp_count)); 
        $this->exp_weight = htmlspecialchars(strip_tags($this->exp_weight));
        $this->act_count = htmlspecialchars(strip_tags($this->act_count));
        $this->act_weight = htmlspecialchars(strip_tags($this->act_weight));
        $this->created = htmlspecialchars(strip_tags($this->created));
        $this->receiving_id = htmlspecialchars(strip_tags($this->receiving_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":supplier", $this->supplier);
        $stmt->bindParam(":exp_count", $this->exp_count);
        $stmt->bindParam(":exp_weight", $this->exp_weight);
        $stmt->bindParam(":act_count", $this->act_count);
        $stmt->bindParam(":act_weight", $this->act_weight);
        $stmt->bindParam(":receiving_id", $this->receiving_id);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":id", $this->id);
    
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    } */

/*     function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":id", $this->id);

        if($result = $stmt->execute()){
            return true;
        }else{
            return false;
        }
    } */

     public function countAll(){
        $query = "SELECT COUNT(*) FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $num = $stmt->fetchColumn();

        return $num;
    } 

     function readOne(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->supplier = $row['supplier'];
        $this->exp_count = $row['exp_count'];
        $this->exp_weight = $row['exp_weight'];
        $this->act_count = $row['act_count'];
        $this->act_weight = $row['act_weight'];
        $this->receiving_id = $row['receiving_id'];
        $this->created = $row['created'];
    }

     function readAll($from_record_num, $records_per_page){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC LIMIT {$from_record_num}, {$records_per_page}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    } 

    /* public function search($search_term, $from_record_num, $records_per_page){
        //select query
        $query = "SELECT * FROM " . $this->table_name . " WHERE receiving_id LIKE ? ORDER BY id ASC LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);
        $stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    } */

    public function countAll_BySearch($search_term){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->name_name . " WHERE receiving_id LIKE ?";

        $stmt = $this->conn->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    function readToArray(){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $id[] = $row['id'];
            $receiving_id[] = $row['receiving_id'];
            $supplier[] = $row['supplier'];
            $exp_count[] = $row['exp_count'];
            $exp_weight[] = $row['exp_weight'];
            $act_count[] = $row['act_count'];
            $act_weight[] = $row['act_weight'];
            $created[] = $row['created'];
        }
        $output = array('id' => $id, 'supplier' => $supplier, 'exp_count' => $exp_count, 'exp_weight' => $exp_weight, 'act_count' => $act_count, 'act_weight' => $act_weight, 'receiving_id' => $receiving_id, 'created' => $created);
        return $output;
    }
}

?>