<?php
    echo "<h2 class='center-align'>Summary</h2>";
    echo "<table class='centered striped'>";
        echo "<tr>";
            echo "<th class='center-align'>Product Code</th>";
            echo "<th class='center-align'>Description</th>";
            echo "<th class='center-align'>Count</th>";
            echo "<th class='center-align'>Weight</th>";
        echo "<tr>";
    for ($i = 0; $i < count($return); $i++){
    echo "<tr>";
        echo "<td>" . $return[$i]['prod'] . "</td>";
        echo "<td>" . $return[$i]['description'] . "</td>";
        echo "<td>" . $return[$i]['count'] . "</td>";
        echo "<td>" . $return[$i]['weight'] . "</td>";
    echo "</tr>";
    }
    echo "</table>";
?>