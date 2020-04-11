<?php
$page_title = "Add Outgoing Product";
include_once "../static/layout_header.php";
include_once "../static/admin_sidebar.php";

//instantiate database and objects
$database = new Database();
$db = $database->getConnection();

$categories = new Category($db);
$output = $categories->readToArray();

$outgoing_products = new Outgoing_Product($db);
?>


<div class="col s9">
    <div class="col s10 offset-s1">
        <!--Title Section-->
        <div class="row">

        <?php
            if(isset($_POST['submit'])) 
            { 
                $product_code = $_POST['product_code'];
                $product_name = $_POST['product_name'];
                $protein_type = $_POST['protein_type'];
                $created = date('h:m:s d/m/Y');

                //Sanitize the input strings
                $product_name = htmlspecialchars(strip_tags($product_name));

                //Echo back the input values
                echo "<p class='center'>Product Code: " . $product_code . "</p>";
                echo "<p class='center'>Product Name: " . $product_name . "</p>";
                echo "<p class='center'>Protein Type: " . $protein_type . "</p>";
                echo "<p class='center'>Time Created: " . $created . "</p>";

                $output_array = [];
                $output_array['product_code'] = $product_code;
                $output_array['description'] = $product_name;
                $output_array['category_id'] = $protein_type;
                $output_array['created'] = $created;
                //var_dump($output_array);

                $return = $outgoing_products->addProduct($output_array);
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
            <h1 class="col s10 offset-s1">Add New Outgoing Product</h1>
        </div>

        <!--Create new form for supplier name-->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <!--Label for new product code-->
        <div class="row" style="margin-top: 3em;">
            <p class="col s8 offset-s2" style="font-size: 25px; font-family: sans-serif;">Product Code:</p>
        </div>
        <!--Input for new product code-->
        <div class="row">
            <input type="text" id="product_code" name="product_code" class="col s7 offset-s2">
        </div>
        <!--Label for new product-->
        <div class="row" style="margin-top: 3em;">
            <p class="col s8 offset-s2" style="font-size: 25px; font-family: sans-serif;">Product Name:</p>
        </div>
        <!--Input for new product-->
        <div class="row">
            <input type="text" id="product_name" name="product_name" class="col s7 offset-s2">
        </div>
        <!--Label for protein type-->
        <div class="row" style="margin-top: 3em;">
            <p class="col s8 offset-s2" style="font-size: 25px; font-family: sans-serif;">Protein Type:</p>
        </div>
        <!--Input for protein type-->
        <div class="row">
            <select name="protein_type" id="protein_type" class="browser-default col s6 offset-s2">
            <?php
                foreach ($output as $value){
                    $temp_code = $value['code'];
                    $temp_name = $value['name'];
                    echo "<option value=" . $temp_code . ">" . $temp_name . "</option>";
                }
            ?>
            </select>
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
