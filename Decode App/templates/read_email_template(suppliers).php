<?php
// create product button
echo "<hr></hr>";
echo "<div class='right-button-margin'>";
    echo "<a href='create_email(supplier).php' class='waves-effect waves-light btn'>Create Email User</a>";
echo "</div>";

if($total_rows>0){
        echo "<table class='table table-hover table-responsive table-bordered'>";
            echo "<tr>";
                echo "<th>Name</th>";
                echo "<th>Email</th>";
                echo "<th>Status</th>";
                echo "<th>Created</th>";
                echo "<th>Actions</th>";
            echo "<tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            echo "<tr>";
                echo "<td>{$name}</td>";
                echo "<td>{$email_address}</td>";
                echo "<td>{$status}</td>";
                echo "<td>{$created}</td>";

                echo "<td>";
                    echo "<a href='read_one(email).php?id={$id}' class='waves-effect waves-light btn'>Read</a>

                        <a href='update_email.php?id={$id}' class='waves-effect waves-light btn'>Edit</a>

                        <a class='waves-effect waves-light btn' onclick='deleteID_Email($id)'>Delete</a>";
                echo "</td>";
            echo "</tr>";
        }
        echo "</table>";

        //the page where this paging is used
        $page_url = "email_list_suppliers.php?";

        //count all products in the database to calculate total pages
        $total_rows = $emails->countAll();

        //paging buttons here
        include_once 'paging.php';
    }
    else{
        echo "<div class='alert alert-info'>No Emails Found.</div>";
    }
    ?>