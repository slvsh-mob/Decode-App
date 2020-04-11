<?php
if($total_rows>0){
        echo "<table class='table table-hover table-responsive table-bordered'>";
            echo "<tr>";
                echo "<th>id</th>";
                echo "<th>Vegetable</th>";
                echo "<th>Type</th>";
                echo "<th>Amount</th>";
                echo "<th>Supplier</th>";
            echo "<tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$vegetable}</td>";
                echo "<td>{$type}</td>";
                echo "<td>{$amount}</td>";
                echo "<td>{$supplier}</td>";
            echo "</tr>";
        }
        echo "</table>";

        //the page where this paging is used
        $page_url = "vegetable_shipments.php?";

        //count all products in the database to calculate total pages
        $total_rows = $vegetable->recCountAll();

        //paging buttons here
        include_once 'paging.php';
    }
    else{
        echo "<div class='alert alert-info'>No Shipments Found.</div>";
    }
    ?>