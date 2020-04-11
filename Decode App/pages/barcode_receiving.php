<?php
  $page_title = "Barcode Receiving";
  include_once "../static/layout_header.php";

  $database = new Database();
  $db = $database->getConnection();

  //$suppliers = new Supplier($db);
  $formula = new Formula($db);
  $out = $formula->readAll();

  $product = new Product($db);
  $products = $product->readList();

  //New SKUS section
  $sku = new SKU($db);
  $skus = $sku->readAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title><?php echo $page_title; ?></title>
 
    <!--Material Theme-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <!-- our custom CSS -->
    <link rel="stylesheet" type="text/css" href="../libs/css/custom.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>
    <style>
        #options{
            min-height: 20em;
        }
        .p_sec_heading{
            font-weight: bold;
            font-size: 15pt;
            margin-left: 1em;
        }
        .prod_table{
            width: 70%;
            margin-left: auto;
            margin-right: auto;
        }
        .prod_info_table{
            margin-top: 3em;
            margin-left: 3em;
        }
        .prod_count_table{
            margin-top: 3em;
            margin-right: 3em;
        }
        #prod_sec{
            min-height: 25em;
        }
    </style>
    </head>
<body>

<script>
//Search thru all SKUS and return index if it matches, or 'null' if it does not match
skus = <?php echo json_encode($skus); ?>;
function findSKU(barcode){
    var k = 0;
    var sku_index = 'null';
    for (s of skus){
        temp_s = s['sku'];
        result = barcode.match(temp_s);
        if (result !== null){
            sku_index = k;
            break;
        }
        k++;
    }
    return sku_index;
}
</script>

<script type="text/javascript">
masks = <?php echo json_encode($out); ?>;
var common_codes = [];
function findMask(barcode){
    var i = 0;
    var index = '';
    for (x of masks){
        temp = x['mask'];
        var num = x['length'];
        var re = new RegExp(temp, 'g');
        res = barcode.match(re);
        if (res !== null){
            if (num == barcode.length){
                index = i;
                common_codes[] = re;
                break;
            }
        }else{
            index = 'null';
        }
        if (i == masks.length){
            index = 'null';
        }
        i++;
    }
    return index;
}
</script>


<script type="text/javascript">
var values = [];
(function () {
    function contentLoaded () {
        var chars = [];

        window.addEventListener('keypress', function (e) {
            if (e.keyCode !== 13) {
                    chars.push(e.key);
                    }
            }, false);

        window.addEventListener('keyup', function (e) {
            if (e.keyCode === 13) {
                chars = chars.join('');
                value_sku = findSKU(chars);
                if (value_sku == 'null'){
                    value = findMask(chars);
                    decode(chars, value);
                }else{
                    sku_decode(chars, value_sku);
                }
                chars = [];
                }
            }, false);
    }
    window.addEventListener('DOMContentLoaded', contentLoaded, false);
}());
</script>


<!-- <script type="text/javascript">
$(document).ready(function(){
  window.Cases_Expected = prompt("How many cases expected", "Cases");
  window.Weight_Expected = prompt("How much weight is expected", "Weight (lbs)");
  var Expected_Quantity = document.getElementById("exp_cases");
  var Expected_Weight = document.getElementById("exp_weight");

  Expected_Quantity.innerHTML = Cases_Expected;
  Expected_Weight.innerHTML = Weight_Expected;
});
</script> -->

<div class="row">
    <div class="col s5">
        <h3 class="center">Count</h3>
        <table style="width: 60%; margin-left: auto; margin-right: auto">
            <tr>
                <th>Expected Cases</th>
                <td><span id="exp_cases">#</span></td>
            </tr>
            <tr>
                <th>Actual Cases</th>
                <td><span id="act_cases">0</span></td>
            </tr>
        </table>
    </div>
    <div class="col s5">
        <h3 class="center">Weight</h3>
        <table style="width: 60%; margin-left: auto; margin-right: auto">
            <tr>
                <th>Expected Weight</th>
                <td><span id="exp_weight">#</span></td>
            </tr>
            <tr>
                <th>Actual Weight</th>
                <td><span id="act_weight">0</span></td>
            </tr>
        </table>
    </div>
    <div class="col s2 grey lighten-2" id="options">
    <h3 class="center">Options</h3>
    <br>
        <button class="col s10 offset-s1 btn">Manual Add</button>
        <br>
        <br>
        <a class="col s10 offset-s1 btn" onclick="submitAll()">Add Product</a>
    </div>
</div>


<div class="row">
    <div class="col s6 grey lighten-4" style="height: 500px">
        <h3 class="center">Last Scanned</h3>
        <table style="width: 80%; margin-left: auto; margin-right: auto" id="last_scanned_table">
            <tr>
                <th>Product Code</th>
                <th>Product</th>
                <th>Weight</th>
                <th>Date</th>
            </tr>
            <tr>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
            </tr>
        </table>
    </div>
    <div class="col s6 grey lighten-2" style="height: 500px">
        <h3 class="center">Summary Table</h3>
        <table style="width: 80%; margin-left: auto; margin-right: auto" id="summary_table">
            <tr>
                <th>Product Code</th>
                <th>Product</th>
                <th>Count</th>
                <th>Weight</th>
            </tr>
        </table>
    </div>
</div>

<?php
include_once "../static/layout_footer.php";
?>

<script type="text/javascript">
//this will create table rows for the summary table
var summary_table = document.getElementById('summary_table');
function createLine(current){
    var last_row = summary_table.rows.length;
    line = summary_table.insertRow(last_row);
    cell1 = line.insertCell(0).innerHTML = current['prod'];
    cell1 = line.insertCell(1).innerHTML = current['description'];
    cell2 = line.insertCell(2).innerHTML = 1;
    cell3 = line.insertCell(3).innerHTML = current['weight'];
}
</script>

<script type="text/javascript">
function updateLine(prod, type, value){
    var final_row = summary_table.rows.length;
    for (i = 1; i < final_row; i++){
        temp_pid = summary_table.rows[i].cells[0].innerHTML;
        if (temp_pid == prod){
            index = i;
        }
    }
    if (type == 0){
        //weight
        summary_table.rows[index].cells[3].innerHTML = value;
    }
    if (type == 1){
        //count
        summary_table.rows[index].cells[2].innerHTML = value;
    }
}
</script>

<script type="text/javascript">
//load all products from database
var products = <?php echo json_encode($products); ?>;
var summary_table = document.getElementById('summary_table');
var last_table = document.getElementById('last_scanned_table');

function decode(barcode, index){
    //gather start location and length [gtin, prod, weight, date]
    var g_s = masks[index]['gtin_start'];
    var g_l = masks[index]['gtin_length']
    var p_s = masks[index]['prod_start'];
    var p_l = masks[index]['prod_length'];
    var w_s = masks[index]['weight_start'];
    w_s = parseInt(w_s) + 4;
    var w_mod = masks[index]['weight_mod'];
    var d_s = window.masks[index]['date_start'];
    d_s = parseInt(d_s) + 2;
    var s_s = masks[index]['serial_start'];
    s_s = parseInt(s_s) + 2;
    var s_l = masks[index]['serial_length'];


    //create empty variables for [gtin, prod, weight, date]
    var gtin = '';
    var prod = '';
    var date = '';
    var weight = '';
    var description = '';
    var supplier = '';
    var serial = '';

    //decode barcode according to mask start locations [gtin, product code]
    gtin = barcode.substr(g_s, g_l);
    prod = barcode.substr(p_s, p_l);
    //decode weight according to mask start locations
    weight_raw = barcode.substr(w_s, 6);
    weight = parseFloat(weight_raw)/w_mod;
    //decode date according to mask start locations & form into mm/dd/yy
    date_raw = barcode.substr(d_s, 6);
    day = date_raw.substr(4, 2);
    month = date_raw.substr(2, 2);
    year = date_raw.substr(0, 2);
    date = month + "/" + day + "/" + year;
    //decode serial
    serial = barcode.substr(s_s, s_l);

    //using product code determine supplier & product description
    products.forEach(function(iteration){
        if (iteration['pid'] == prod){
            description = iteration['description'];
            supplier = iteration['supplier'];
        }
    });

    //Update Last Scanned Table
    last_table.rows[1].cells[0].innerHTML = prod;
    last_table.rows[1].cells[1].innerHTML = description;
    last_table.rows[1].cells[2].innerHTML = weight;
    last_table.rows[1].cells[3].innerHTML = date;

    //Update Current array with current values
    current = [];
    current['gtin'] = gtin;
    current['prod'] = prod;
    current['description'] = description;
    current['supplier'] = supplier;
    current['weight'] = weight;
    current['date'] = date;
    current['serial'] = serial;

    addElement(current);
}
</script>

<script>
//parse skus formula list and update summary and and last scanned table
function sku_decode(barcode, index){
    var sku_weight = parseFloat(skus[index]['weight']);
    var sku_description = skus[index]['description'];
    var sku_supplier = skus[index]['supplier'];

    current = [];
    current['gtin'] = '';
    current['prod'] = barcode;
    current['description'] = sku_description;
    current['supplier'] = sku_supplier;
    current['weight'] = sku_weight;
    current['date'] = '';
    current['serial'] = '';

    //Update Last Scanned Table
    //--ADD CALL TO UPDATE --//
    last_table.rows[1].cells[0].innerHTML = barcode;
    last_table.rows[1].cells[1].innerHTML = sku_description;
    last_table.rows[1].cells[2].innerHTML = sku_weight;
    last_table.rows[1].cells[3].innerHTML = '';

    addElement(current);
}

</script>

<script>
//define unique product code array
var unique_prod = [];
//define unique gtin array
var unique_gtin = [];
//define unique count array
var unique_count = {};
//define unique weight array
var unique_weight = {};
//define unique description array
var unique_description = {};
//define unique supplier array
var unique_supplier = {};
//define each weight object
var each_weight = {};
//define scans object
var scans = [];

function addElement(current){
    //a = gtin, b = prod, c = weight, d = date
    a = current['gtin'];
    b = current['prod'];
    c = current['weight'];
    d = current['date'];
    e = current['description'];
    f = current['supplier'];
    g = current['serial']

    //setup scans array, this holds db information about every scan
    scans.push({
        gtin: a,
        prod: b,
        weight: c,
        date: d,
        serial: g
    });
    //scans.push(temp);
    console.log(scans);

    //check for unique prods & add to array
    check_p = unique_prod.includes(b);
    if (check_p != true){
        //add product code to unique_prod array
        unique_prod.push(b);
        newProductSection(current);
        //add product code to unique count array with value 1
        var c_pair = {[b] : 0}
        unique_count = {...unique_count, ...c_pair};
        //add product code to unique weight array with value 0
        var w_pair = {[b] : 0}
        unique_weight = {...unique_weight, ...w_pair};
        //add description to unique description array
        var d_pair = {[b] : e}
        unique_description = {...unique_description, ...d_pair};
        //add supplier to unique supplier array
        var s_pair = {[c] : f}
        unique_supplier = {...unique_supplier, ...s_pair};
        //add product code to each_weight array
        var e_w_pair = {[b] : [c]}
        each_weight = {...each_weight, ...e_w_pair};
        //call create summary line function createLine()
        createLine(current);
        //call count function
        count_fxn(b, 1);
        //call weight function
        weight_fxn(c, 1, b);
    }else{
        //add weight to each weight array
        holder = each_weight[b];
        holder.push(c);
        each_weight[b] = holder;
        //call count function
        count_fxn(b, 1);
        //call weight function
        weight_fxn(c, 1, b);
        //update product section
        updateProductSection(current)
    }

    //check for unique box suppliers and add to array
    check_g = unique_gtin.includes(a);
    if (check_g != true){
        unique_gtin.push(a);
        //could do something with number of box suppliers
    }
}
</script>

<script>
var total_weight = 0;
var weight_counter = document.getElementById('act_weight');
function weight_fxn(weight, type, prod){
    if (type == 1){
        intra = unique_weight[prod] + weight;
        intra = Math.round(intra * 100)/100;
        unique_weight[prod] = intra;

        updateLine(prod, 0, unique_weight[prod]);
        intra_2 = total_weight + weight;
        intra_2 = Math.round(intra_2 * 100)/100;
        total_weight = intra_2;
        weight_counter.innerHTML = total_weight;
    }
    if (type == 0){
        unique_weight[prod] -= weight;
        total_weight -= weight;
        weight_counter.innerHTML = total_weight;
    }
}
</script>

<script>
var total_count = 0;
var quantity_counter = document.getElementById('act_cases');
function count_fxn(product_code, type){
    //count function will be called to count the number of each product code as well as track total boxes
    if (type == 1){
        unique_count[product_code] += 1;
        updateLine(product_code, 1, unique_count[product_code]);
        total_count += 1;
        quantity_counter.innerHTML = total_count;
    }
    if (type == 0){
        unique_count[product_code] -= 1;
        total_count -= 1;
        quantity_counter.innerHTML = total_count;
    }
}
</script>

<script type="text/javascript">
function newProductSection(current){
    //name each div after the product code 
    var div_row = document.createElement( 'div' );
    var div_body = document.createElement( 'div' );
    div_row.className = 'row';
    div_body.className = current['prod'];
    part_0 = "<div class='col s12 indigo lighten-5' id='prod_sec'>";
    part_1 = "<div class='row'><div class='col s3'><table class='prod_info_table'><tr><th>Product Code</th><td>" + current['prod'] + "</td></tr><tr><th>Description</th><td>" + current['description'] +"</td></tr><tr><th>Supplier</th><td>" + current['supplier'] + "</td></tr></table></div><div class='col s3 offset-s5'><table class='prod_count_table'><tr><th>Quantity</th><td>1</td></tr><tr><th>Weight</th><td>" + current['weight'] + "</td></tr></table></div></div>";
    part_2 = "<div class='row'><div class='prod_table'><table id=" + current['prod'] + "><tr><th>#</th><th>Weight</th></tr></table></div></div></div>";
    div_body.innerHTML = part_0 + part_1 + part_2;
    //div_body.innerHTML = "<p class='p_sec_heading'>Product</p>";
    div_row.appendChild(div_body);
    document.body.appendChild(div_row);
    updateProductSection(current);
}
</script>

<script type="text/javascript">
function updateProductSection(current){
/*     var temp_prod = current['prod'];
    var temp_count = unique_count[temp_prod];
    var temp_length = temp_table.rows.length;
    var current_row = temp_length;

    if (temp_count % 5 == 0){
        var tempCell = current_row.insertCell()
        next_row = temp_table.insertRow(temp_length);
    }else */


    var temp_table = document.getElementById(current['prod']);
    var temp_length = temp_table.rows.length;
    var temp_row = temp_table.insertRow(temp_length);
        var cellA = temp_row.insertCell(0);
        var cellB = temp_row.insertCell(1);
        cellA.innerHTML = temp_length;
        cellB.innerHTML = current['weight'];
}
</script>

<script>
function printScreen(){
    var doc = new jsPDF();

}
</script>


<script type="text/javascript">
function submitAll(){
    var input = scans;
    console.log(input);
  $.ajax({  
      url:"DB_Add.php",  
      method:"POST",
      data:{
        'scans':scans
          },  
      success: function(response){ 
            console.log(response)
      } 
  });

}



/* function submitAll(){
    var input = scans;
console.log(input);
     $.ajax({
        url: "DB_Add.php",
        method: "POST",
        data:{'scans':input},
        success: function(response){
            console.log(response);
        },
        error: function(e){
            console.log('fail');
        }
    })
} */
</script>