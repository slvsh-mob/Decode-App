<?php

if($total_rows>0){
    echo "<h1>Receiving Records Table</h1>";
    echo "<table class='centered striped'>";
        echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Supplier</th>";
            echo "<th>Expected Quantity</th>";
            echo "<th>Expected Weight</th>";
            echo "<th>Actual Quantity</th>";
            echo "<th>Actual Weight</th>";
            echo "<th>Receiving ID</th>";
            echo "<th>Created</th>";
            echo "<th>Actions</th>";
        echo "<tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$supplier}</td>";
            echo "<td>{$exp_count}</td>";
            echo "<td>{$exp_weight}</td>";
            echo "<td>{$act_count}</td>";
            echo "<td>{$act_weight}</td>";
            echo "<td>{$receiving_id}</td>";
            echo "<td>{$created}</td>";

            echo "<td>";
                echo "<a href='update_product.php?id={$id}' class='btn'>Edit</a>

                    <a delete-id='{$id}' class='btn'>Delete</a>

                    <a href='receiving_details.php?id={$id}' class='btn'>Details</a>";
            echo "</td>";
        echo "</tr>";
    }
    echo "</table>";

    $page_url = "receiving_summary.php?";
    //$total_rows = $receiving->countAll();

    //paging buttons here
    include_once '../config/paging.php';
}
else{
    echo "<div class='alert alert-info'>No Products Found.</div>";
}
?>