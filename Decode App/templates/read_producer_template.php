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
    echo "<a href='create_producer.php' class='waves-effect waves-light btn'>Create Producer</a>";
echo "</div>";

if($total_rows>0){
    echo "<table class='table table-hover table-responsive table-bordered'>";
        echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Status</th>";
            echo "<th>Created</th>";
            echo "<th>Actions</th>";
        echo "<tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$name}</td>";
            echo "<td>{$status}</td>";
            echo "<td>{$created}</td>";

            echo "<td>";
                echo "<a href='read_one(producer).php?id={$id}' class='waves-effect waves-light btn'>Read</a>

                    <a href='update_producer.php?id={$id}' class='waves-effect waves-light btn'>Edit</a>

                    <a class='waves-effect waves-light btn' onclick='deleteID_Producer($id)'>Delete</a>";
            echo "</td>";
        echo "</tr>";
    }
    echo "</table>";

    //the page where this paging is used
    $page_url = "all_producers.php?";

    //count all products in the database to calculate total pages
    $total_rows = $producer->countAll();

    //paging buttons here
    include_once 'paging.php';
}
else{
    echo "<div class='alert alert-info'>No Producers Found.</div>";
}
?>