<?php

if($total_rows>0){
    echo "<table class='table table-hover table-responsive table-bordered'>";
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
    for ($i = 0; $i < $total_rows; $i++){
        echo "<tr>";
            echo "<td>" . $stmt[$i]['id'] . "</td>";
            echo "<td>" . $stmt[$i]['supplier'] . "</td>";
            echo "<td>" . $stmt[$i]['exp_count'] . "</td>";
            echo "<td>" . $stmt[$i]['exp_weight'] . "</td>";
            echo "<td>" . $stmt[$i]['act_count'] . "</td>";
            echo "<td>" . $stmt[$i]['act_weight'] . "</td>";
            echo "<td>" . $stmt[$i]['receiving_id'] . "</td>";
            echo "<td>" . $stmt[$i]['created'] . "</td>";
            echo "<td>";
                echo "<a href='read_one.php?id={$id}' class='btn btn-primary left-margin'>
                    <span class='glyphicon glyphicon-lest'></span> Read
                    </a>

                    <a href='update_product.php?id={$id}' class='btn btn-info left-margin'>
                    <span class='glyphicon glyphicon-edit'></span> Edit
                    </a>

                    <a delete-id='{$id}' class='btn btn-danger delete-object'>
                    <span class='glyphicon glyphicon-remove'></span> Delete
                    </a>";
            echo "</td>";
        echo "</tr>";

    }
    echo "</table>";

}
else{
    echo "<div class='alert alert-info'>No Products Found.</div>";
}
?>