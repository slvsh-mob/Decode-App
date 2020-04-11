<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Header: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization');

$query = "INSERT INTO inventory (barcode, gtin, product_code, weight, date, serial_id, received, receiving_id)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $this->conn->prepare($query);
try{
    $this->conn->beginTransaction();
    foreach ($data as $row){
        $stmt->execute($row);
    }
    $pdo->commit();
}catch (Exception $e){
    $this->conn->rollback();
    throw $e;
}
}

?>