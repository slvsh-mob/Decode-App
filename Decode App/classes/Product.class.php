<?php
class Product{

    //database connection
    private $conn;
    private $table_name = "products";

    //object properties
    public $id;
    public $pid;
    public $supplier;
    public $description;
    public $category_id;
    public $created;

    public function __construct($db){
        $this->conn = $db;
    }

    //create product
    function create(){
        $query = "INSERT INTO " . $this->table_name . " (pid, supplier, description, category_id, created) VALUES (:a, :b, :c, :d, :e)";
        $stmt = $this->conn->prepare($query);

        //posted values
        $this->pid = htmlspecialchars(strip_tags($this->pid));
        $this->supplier = htmlspecialchars(strip_tags($this->supplier));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        //create created timestamp
        $this->created = date('Y-m-d H:i:s');

        //bind values
        $stmt->bindParam(':a', $this->pid);
        $stmt->bindParam(':b', $this->supplier);
        $stmt->bindParam(':c', $this->description);
        $stmt->bindParam(':d', $this->category_id);
        $stmt->bindParam(':e', $this->created);

        if($stmt->execute()){
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

        $this->pid = $row['pid'];
        $this->supplier = $row['supplier'];
        $this->description = $row['description'];
        $this->category_id = $row['category_id'];
        $this->created = $row['created'];
    }

    function readAll($from_record_num, $records_per_page){
        $query = "SELECT id, pid, supplier, description, category_id, created FROM " . $this->table_name . " ORDER BY id ASC LIMIT {$from_record_num}, {$records_per_page}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    function readList(){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $output = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $temp = array();
            $temp['id'] = $id;
            $temp['pid'] = $pid;
            $temp['supplier'] = $supplier;
            $temp['description'] = $description;
            $temp['category_id'] = $category_id;
            $output[] = $temp;
        }
        return $output;
    }


    function update(){
        $text = '';
        $holder = array();
        if(isset($this->pid)){
            $text .= " SET pid = :a";
            $holder[] = 1;
        }
        if(isset($this->supplier)){
            $text .= ", supplier = :b";
            $holder[] = 2;
        }
        if(isset($this->description)){
            $text .= ", description = :c";
            $holder[] = 3;
        }
        $query = "UPDATE " . $this->table_name . $text . " WHERE id = :e";

        $stmt = $this->conn->prepare($query);

         foreach ($holder as $row){
            switch ($row){
                case 1:
                    $this->pid = htmlspecialchars(strip_tags($this->pid));
                    $stmt->bindParam(':a', $this->pid);
                    break;
                case 2:
                    $this->supplier = htmlspecialchars(strip_tags($this->supplier));
                    $stmt->bindParam(':b', $this->supplier);
                    break;
                case 3:
                    $this->description = htmlspecialchars(strip_tags($this->description));
                    $stmt->bindParam(':c', $this->description);
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

    public function search($search_term, $from_record_num, $records_per_page){
        //select query
        $query = "SELECT c.name as category_name, p.id, p.pid, p.supplier, p.description, p.created FROM " . $this->table_name . " p LEFT JOIN categories c ON p.category_id = c.code WHERE p.pid LIKE ? OR p.description LIKE ? ORDER BY p.id ASC LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);
        $stmt->bindParam(2, $search_term);
        $stmt->bindParam(3, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(4, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

    public function countAll_BySearch($search_term){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->name_name . " p LEFT JOIN categories c on p.category_id = c.code WHERE p.pid LIKE :a";

        $stmt = $this->conn->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(":a", $search_term);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    public function NamebyPID($pid){
        $query = "SELECT description FROM " . $this->table_name . " WHERE pid = :a";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':a', $pid);
        $stmt->execute();
        $name = $stmt->fetchColumn();
        return $name;
    }

    function readToArray(){
        $query = "SELECT * FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $id[] = $row['id'];
            $pid[] = $row['pid'];
            $supplier[] = $row['supplier'];
            $description[] = $row['description'];
            $category_id[] = $row['category_id'];
        }
        $output = array('id' => $id, 'pid' => $pid, 'supplier' => $supplier, 'description' => $description, 'category_id' => $category_id);
        return $output;
    }

    function addProduct($value){
        $query = "INSERT INTO " . $this->table_name . " (pid, supplier, description, category_id, created) VALUES (:a, :b, :c, :d, :e)";

        $stmt = $this->conn->prepare($query);

        $this->pid = htmlspecialchars(strip_tags($value['pid']));
        $this->supplier = htmlspecialchars(strip_tags($value['supplier_name']));
        $this->description = htmlspecialchars(strip_tags($value['description']));
        $this->category_id = $value['category_id'];
        $this->created = date('Y-m-d H:i:s');

        $stmt->bindParam(':a', $this->pid);
        $stmt->bindParam(':b', $this->supplier);
        $stmt->bindParam(':c', $this->description);
        $stmt->bindParam(':d', $this->category_id);
        $stmt->bindParam(':e', $this->created);

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