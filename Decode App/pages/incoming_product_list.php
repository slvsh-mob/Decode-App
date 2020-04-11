<?php
$page_title = "Incoming Product List";
include_once "../static/layout_header.php";
include_once "../static/admin_sidebar.php";

//instantiate database and objects
$database = new Database();
$db = $database->getConnection();

$products = new Product($db);
$output = $products->readToArray();
?>

    <div class="col s9 right">
        <div class="col s8 offset-s1" style='height: 15vh; font-family: bold;'>
        <h1>Incoming Product List</h1>
        </div>
        <div class="col s2">
        <h4 class="center" style="padding-top: 1em;">Page <i id='page_number'>#</i></h4>
        </div>
        <div class="row">
            <div class="col s10 offset-s1">
                <table class='centered striped' id='incoming_product_table' style='height: 80vh;'>
                    <tr>
                        <th class='center-align' width='10%'>ID</th>
                        <th class='center-align' width='15%'>Product Code</th>
                        <th class='center-align' width='25%'>Supplier</th>
                        <th class='center-align' width='50%'>Description</th>
                        <th class='center-align' width='10%'>Category ID</th>
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
</div>

<script type="text/javascript">
    input_array = <?php echo json_encode($output); ?>;

    var chunked_data = [];
    var num_pages = 0;
    var row_per_page = 25;
    var current_page = 0;
    var page_counter = document.getElementById('page_number');
    var heading_length = 0;
    var table_name = document.getElementById('incoming_product_table');

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
</script>

<?php
include_once "../static/layout_footer.php";
?>