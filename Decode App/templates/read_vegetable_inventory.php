<?php
        echo "<table class='table table-responsive table-bordered'>";
            echo "<tr>";
                echo "<th>Vegetable</th>";
                echo "<th>Amount</th>";
                echo "<th>Last Updated</th>";
            echo "<tr>";
        while ($row = $temp->fetch(PDO::FETCH_ASSOC)){
            $type = $row['type'];
            $amount = $row['amount'];
            $updated = $row['updated'];
            $name = $vegetable->readType($type);

            echo "<tr>";
                echo "<td>" . $name . "</td>";
                echo "<td>" . $amount . "</td>";
                echo "<td>" . $updated . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    ?>