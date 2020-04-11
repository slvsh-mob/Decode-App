<?php
$page_title = "Meat Inventory";
include_once "../static/layout_header.php";
include_once "../static/inventory_search_sidebar.php";

//instantiate database and objects
$database = new Database();
$db = $database->getConnection();

$inventory = new Inventory($db);
$output = $inventory->readToArray();

$total_rows = $inventory->countAll();
?>
    <div class="col s10 right">
        <div class="col s8 offset-s1" style='height: 15vh; font-family: bold;'>
            <h1>Protein Inventory</h1>
        </div>
        <div class="col s2">
            <h4 class="center" style="padding-top: 1em;">Page <i id='page_number'>#</i></h4>
        </div>
        <div class="row">
            <div class="col s10 offset-s1">
            <table class='centered striped' id='inventory_table' style='height: 80vh;'>
                <tr>
                    <th class='center-align'>ID</th>
                    <th class='center-align'>GTIN</th>
                    <th class='center-align'>Product_Code</th>
                    <th class='center-align'>Weight</th>
                    <th class='center-align'>Date</th>
                    <th class='center-align'>Serial ID</th>
                    <th class='center-align'>Received</th>
                    <th class='center-align'>Receiving ID</th>
                </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col s4 offset-s1">
                <div class="row center" id='qty_container'>
                    <p class='center'>Results per page</p>
                    <a id='btn_qty_5' href='javascript:lines_per_page(5)'>5</a>
                    <a id='btn_qty_10' href='javascript:lines_per_page(10)'>10</a>
                    <a id='btn_qty_25' href='javascript:lines_per_page(25)'>25</a>
                    <a id='btn_qty_50' href='javascript:lines_per_page(50)'>50</a>
                </div>
            </div>
            <div class="col s6">
                <div class="row center" id="nav_container">
                    <p>Page Navigation</p>
                    <a id="first" href="javascript:page(0)">First</a>
                    <a class="btn-small" id="previous" href="javascript:page(current_page - 1)">Previous</a>
                    <a class="btn-small" id="next" href="javascript:page(current_page + 1)">Next</a>
                    <a id="last" href="javascript:page(num_pages - 1)">Last</a>
                </div>
            </div>
        </div>     
    </div>

    <script type="text/javascript">
    input_array = <?php echo json_encode($output); ?>;
    console.log(input_array);

    var chunked_data = [];
    var num_pages = 0;
    var row_per_page = 25;
    var current_page = 0;
    var page_counter = document.getElementById('page_number');
    var heading_length = 0;
    var table_name = document.getElementById('inventory_table');

    function drawTable(){
        current_chunk = Object.values(chunked_data)[current_page];
        current_chunk_length = current_chunk[0].length;
        //console.log(current_chunk_length);

        num_rows = table_name.rows.length;
        //Clear Table
        if(num_rows > 1){
            for(z = 0; z < (num_rows - 1); z++){
                table_name.deleteRow(1);
            }
        }

        //Fill Table
        for(k = 0; k < current_chunk_length; k++){
            temp_row = table_name.insertRow();
            for(l = 0; l < heading_length; l++){
                temp_cell = temp_row.insertCell(l);
                temp_cell.innerHTML = Object.values(current_chunk)[l][k];
            }
        }
        page_counter.innerHTML = current_page + 1;
        buttons();
    }

    function chunking(input_data){
        var num_indexes = Object.keys(input_data).length;
        var num_rows = Object.values(input_data)[0].length;
        //console.log(num_rows);
        //console.log(num_indexes);

        heading_length = num_indexes;

        var num_chunks = Math.ceil(num_rows / row_per_page);
        num_pages = num_chunks;
        //console.log('Num Pages: ' + num_pages);

        var chunks = new Array();

        var pointer = 0;
        for(i = 0; i < num_chunks; i++){
            pointer_end = pointer + row_per_page;
            //create the chunk
            var temp = new Array();
            for(j = 0; j < heading_length; j++){
                temp[j] = Object.values(input_data)[j].slice(pointer, pointer_end);
            }
            chunks[i] = temp;
            pointer += row_per_page;
        } 
        chunked_data = chunks;
        //console.log(chunked_data);
    }


    function lines_per_page(quantity){
        switch (quantity){
            case 5:
                row_per_page = 5;
                chunking(input_array);
                break;
            case 10:
                row_per_page = 10;
                chunking(input_array);
                break;
            case 25:
                row_per_page = 25;
                chunking(input_array);
                break;
            case 50:
                row_per_page = 50;
                chunking(input_array);
                break;
        }
        current_page = 0;
        drawTable();
    }

    function page(page_number){
        switch (true){
            case (page_number < 0):
                current_page = 0;
                break;
            case (page_number > (num_pages - 1)):
                current_page = num_pages - 1;
                break;
            case ((0 <= page_number < num_pages)):
                current_page = page_number;
                break;
        }
        drawTable();
    }

    function buttons(){
        btn_qty_5 = document.getElementById('btn_qty_5');
        btn_qty_5.className = 'btn-small';
        btn_qty_10 = document.getElementById('btn_qty_10');
        btn_qty_10.className = 'btn-small';
        btn_qty_25 = document.getElementById('btn_qty_25');
        btn_qty_25.className = 'btn-small';
        btn_qty_50 = document.getElementById('btn_qty_50');
        btn_qty_50.className = 'btn-small';

        btn_first = document.getElementById('first');
        btn_first.className = 'btn-small';
        btn_last = document.getElementById('last');
        btn_last.className = 'btn-small';

        if (current_page == 0){
            btn_first.className += ' red';
        }
        if (current_page == (num_pages - 1)){
            btn_last.className += ' red';
        }
        if (row_per_page == 5){
            btn_qty_5.className += ' red';
        }
        if (row_per_page == 10){
            btn_qty_10.className += ' red';
        }
        if (row_per_page == 25){
            btn_qty_25.className += ' red';
        }
        if (row_per_page == 50){
            btn_qty_50.className += ' red';
        }
    }


    chunking(input_array);
    drawTable();

    function searchPID(input_data, search_term){
        var index = new Array();
        var pointer = 0;
        var temp_codes = Object.values(input_data)[2];
        value_0 = new Array();
        value_1 = new Array();
        value_2 = new Array();
        value_3 = new Array();
        value_4 = new Array();
        value_5 = new Array();
        value_6 = new Array();
        value_7 = new Array();
        //console.log(temp_codes);
        temp = temp_codes.indexOf(search_term, pointer)
        if(temp == -1){
            index['message'] = 'No Results Found';
        }else{
            while(temp != -1){
            value_0.push(Object.values(input_data)[0][temp]);
            value_1.push(Object.values(input_data)[1][temp]);
            value_2.push(Object.values(input_data)[2][temp]);
            value_3.push(Object.values(input_data)[3][temp]);
            value_4.push(Object.values(input_data)[4][temp]);
            value_5.push(Object.values(input_data)[5][temp]);
            value_6.push(Object.values(input_data)[6][temp]);
            value_7.push(Object.values(input_data)[7][temp]);
            index.push(temp);
            //console.log(temp);
            pointer = temp + 1;
            //console.log(pointer)
            temp = temp_codes.indexOf(search_term, pointer)
            }
            output = new Array(value_0, value_1, value_2, value_3, value_4, value_5, value_6, value_7);
        }
        chunking(output);
        drawTable();
    }

    searchPID(input_array, '05409');

</script>
    
<?php
include_once "../static/layout_footer.php";
?>
