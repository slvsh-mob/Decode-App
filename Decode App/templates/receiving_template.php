<?php
// search form
 echo "<form role='search' action='search.php'>";
    echo "<div class='input-group col-md-3 pull-left margin-right-1em'>";
        $search_value=isset($search_term) ? "value='{$search_term}'" : "";
        echo "<input type='text' class='form-control' placeholder='Type Receiving ID or description...' name='s' id='srch-term' required {$search_value} />";
        echo "<div class='input-group-btn'>";
            echo "<button class='btn btn-primary' type='submit'><i class='glyphicon glyphicon-search'></i></button>";
        echo "</div>";
    echo "</div>";
echo "</form>";

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

    //the page where this paging is used
    $page_url = "read_receiving.php";
    //$page_url = "date_search.php";

    //count all products in the database to calculate total pages
    $total_rows = $receiving->countAll();

    //paging buttons here
    include_once 'paging.php';
}
else{
    echo "<div class='alert alert-info'>No Products Found.</div>";
}
?>