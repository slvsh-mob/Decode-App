<?php
$page_title = "Add Supplier";
include_once "../static/layout_header.php";
include_once "../static/admin_sidebar.php";

//instantiate database and objects
$database = new Database();
$db = $database->getConnection();

$suppliers = new Supplier($db);
?>


<div class="col s9">
    <div class="container">
        <!--Title Section-->
        <div class="row">

        <?php
        if(isset($_POST['submit'])) 
        { 
            $supplier_name = $_POST['supplier_name'];
            $created = date('h:m:s d/m/Y');

            //Sanitize the input strings
            $supplier_name = htmlspecialchars(strip_tags($supplier_name));

            //Echo back the input values
            echo "<p class='center'>API Response:</p>";
            echo "<p class='center'>Supplier Name: " . $supplier_name . "</p>";
            echo "<p class='center'>Time Created: " . $created . "</p>";

            $return = $suppliers->addSupplier($supplier_name);
            $display_message = $return['message'];
            $display_id = $return['insert_id'];
            if($display_id !== "null"){
                echo "<p class='center'>Record was a: <b>" . $display_message . "</b></p>";
                echo "<p class='center'>Insert ID: <b>" . $display_id . "</b></p>";
            }else{
                echo "<p class='center'>Record was a: <b>" . $display_message . "</b></p>";
            }
        }
        ?>


            <h1 class="col s10 offset-s2">Add New Supplier</h1>
        </div>

        <!--Create new form for supplier name-->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <!--Label for new supplier-->
        <div class="row" style="margin-top: 3em;">
            <p class="col s8 offset-s2" style="font-size: 25px; font-family: sans-serif;">Supplier Name:</p>
        </div>
        <!--Input for new supplier-->
        <div class="row">
            <input type="text" id="supplier_name" name="supplier_name" class="col s7 offset-s2">
        </div>
        <!--Buttons section-->
        <div class="row" style="margin-top: 5em;">
            <div class="container">
                <input type="submit" name="submit" value="Submit Form" class="col s5 btn-large">
                <input type="reset" class="col s5 offset-s1 btn-large"> 
            </div>
        </div>
        <!--Close form-->
        </form>
    </div>
</div>



<?php
include_once "../static/layout_footer.php";
?>
