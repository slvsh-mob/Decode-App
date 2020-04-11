<?php

if($total_rows>0){
    echo "<h1>Cases received in " . $perp_inv->receiving_id . "</h1>";
    echo "<table class='centered striped'>";
        echo "<tr>";
            echo "<th class='center-align'>ID</th>";
            echo "<th class='center-align'>GTIN</th>";
            echo "<th class='center-align'>Product_Code</th>";
            echo "<th class='center-align'>Weight</th>";
            echo "<th class='center-align'>Date</th>";
            echo "<th class='center-align'>Serial ID</th>";
            echo "<th class='center-align'>Received</th>";
            echo "<th class='center-align'>Receiving ID</th>";
        echo "<tr>";
    while ($row = $stmt['stmt']->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$gtin}</td>";
            echo "<td>{$product_code}</td>";
            echo "<td>{$weight}</td>";
            echo "<td>{$pack_date}</td>";
            echo "<td>{$serial_id}</td>";
            echo "<td>{$received}</td>";
            echo "<td>{$receiving_id}</td>";
        echo "</tr>";
    }
    echo "</table>";

    $page_url = "receiving_details.php?";
    $total_rows = $perp_inv->countAll();

    //paging buttons here
    include_once 'paging.php';
}
else{
    echo "<div class='alert alert-info'>No Products Found.</div>";
}
?>