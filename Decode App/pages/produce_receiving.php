<?php
$page_title = 'Vegetable: Receiving';
include_once "../static/layout_header.php";
include_once "../static/receiving_sidebar.php";

$database = new Database();
$db = $database->getConnection();

$vegetable = new Vegetable($db);

$types = $vegetable->readTypes();
$suppliers = $vegetable->readSuppliers();
?>

<div class="col s9">
    <div class="container">
        <!--Title Section-->
        <div class="row">

      <?php
        if(isset($_POST['submit'])) 
        { 
          $supplier_out = $_POST['V_Supplier'];
          $type_out = $_POST['V_Type'];
          $amount_out = $_POST['V_Weight'];
          $amount_out = floatval($amount_out);
          $current_amount = $vegetable->readAmount($type_out);
          $final_amount = $current_amount + $amount_out;

          echo "<p class='center'>Supplier: " . $supplier_out . "</p>";
          echo "<p class='center'>Vegetable Type: " . $type_out . "</p>";
          echo "<p class='center'>Amount Out: " . $amount_out . "</p>";



          $input_array = [];
          $input_array['supplier'] = $supplier_out; 
          $input_array['vegetable'] = $type_out;
          $input_array['amount'] = $amount_out;
          $temp = $vegetable->updateAmount($type_out, $final_amount);
          $return = $vegetable->addRecRecord($input_array);
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

      <h2 class="col s8 offset-s2">Receive Produce</h2>
    </div>

    <!--Create new form for supplier name-->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <!--Label for new supplier-->
        <div class="row" style="margin-top: 3em;">
            <p class="col s8 offset-s2" style="font-size: 25px; font-family: sans-serif;">Vegetable Supplier:</p>
        </div>
        <div class="row">
        <select class="browser-default col s6 offset-s2" name="V_Supplier" id="veg_supplier">
          <option selected="selected">Choose One</option>
          <?php
            foreach($suppliers as $key => $value){
              echo "<option value='" . $key . "'>" . $value . "</option>";
            }
          ?>
        </select>
        </div>
        <div class="row" style="margin-top: 3em;">
            <p class="col s8 offset-s2" style="font-size: 25px; font-family: sans-serif;">Vegetable Type:</p>
        </div>
        <div class="row">
        <select class="browser-default col s6 offset-s2" name="V_Type" id="veg_type">
            <option selected="selected">Choose One</option>
            <?php
              foreach($types as $key => $value){
                echo "<option value='" . $key . "'>" . $value . "</option>";
              }
            ?>
        </select>
        </div>
        <div class="row" style="margin-top: 3em;">
            <p class="col s8 offset-s2" style="font-size: 25px; font-family: sans-serif;">Incoming Weight (Lbs):</p>
        </div>
        <div class="row">
            <input type="number" step=".01" name="V_Weight" id="V_Weight" class="col s6 offset-s2">
        </div>

        <!--Buttons section-->
        <div class="row" style="margin-top: 5em;">
            <div class="container">
                <input type="submit" name="submit" value="Submit Form" class="col s5 btn-large">
                <input type="reset" class="col s5 offset-s1 btn-large"> 
            </div>
        </div>

    </form>
    </div>
  </div>
  </div>

<?php
include_once "../static/layout_footer.php";
?>

