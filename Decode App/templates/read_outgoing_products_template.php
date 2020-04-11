<?php
// search form
echo "<form role='search' action='search.php'>";
    echo "<div class='input-group col-md-3 pull-left margin-right-1em'>";
        $search_value=isset($search_term) ? "value='{$search_term}'" : "";
        echo "<input type='text' class='form-control' placeholder='Type product name or description...' name='s' id='srch-term' required {$search_value} />";
        echo "<div class='input-group-btn'>";
            echo "<button class='waves-effect waves-light btn' type='submit'>Search</button>";
        echo "</div>";
    echo "</div>";
echo "</form>";
 
// create product button
echo "<hr></hr>";
echo "<div class='right-button-margin'>";
    echo "<a href='create_product.php' class='waves-effect waves-light btn'>Create Product</a>";
echo "</div>";

if($total_rows>0){
        echo "<table class='table table-hover table-responsive table-bordered'>";
            echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Product ID</th>";
                echo "<th>Description</th>";
                echo "<th>Category</th>";
                echo "<th>Created</th>";
                echo "<th>Actions</th>";
            echo "<tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $category->code = $category_id;
            $category->readName();

            echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$product_id}</td>";
                echo "<td>{$description}</td>";
                echo "<td>{$category->name}</td>";
                echo "<td>{$created}</td>";

                echo "<td>";
                    echo "<a href='read_one.php?id={$id}' class='waves-effect waves-light btn'>Read</a>

                        <a href='update_product.php?id={$id}' class='waves-effect waves-light btn'>Edit</a>

                        <a class='waves-effect waves-light btn' onclick='deleteID($id)'>Delete</a>";
                echo "</td>";
            echo "</tr>";
        }
        echo "</table>";

        //the page where this paging is used
        $page_url = "all_outgoing_products.php?";

        //count all products in the database to calculate total pages
        $total_rows = $outgoing_product->countAll();

        //paging buttons here
        include_once 'paging.php';
    }
    else{
        echo "<div class='alert alert-info'>No Products Found.</div>";
    }
    ?>