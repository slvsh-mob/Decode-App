//GLOBALS
var input_array = <?php echo json_encode($output); ?>;
var chunked_data = [];
var num_pages = 0;
var row_per_page = 25;
var current_page = 0;
var heading_length = 0;
var table_name = document.getElementById('protein_summary_table');

function drawTable(){
    current_chunk = Object.values(chunked_data)[current_page];

    //Update Page Number
    //page_number = document.getElementById('page_number');
    //page_number.innerHTML = current_page + 1;

    //Generate Table
    //table = document.getElementById('inventory_table');
    num_rows = table_name.rows.length;
    //Clear Table
    if(num_rows > 1){
        for(z = 0; z < (num_rows - 1); z++){
            table.deleteRow(1);
        }
    }

    //Fill Table
    for(k = 0; k < row_per_page; k++){
        temp_row = table_name.insertRow();
        for(l = 0; l < heading_length; l++){
            temp_cell = temp_row.insertCell(l);
            temp_cell.innerHTML = Object.values(current_chunk)[l][k];
        }
    }
}

function chunking(input_data){
    var num_indexes = Object.keys(input_data).length;
    var num_rows = input_data[0].length;
    console.log(num_rows);
    console.log(num_indexes);

    heading_length = num_indexes;

    var num_chunks = Math.ceil(num_rows / row_per_page);
    num_pages = num_chunks;
    console.log('Num Pages: ' + num_pages);

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
    console.log(chunked_data);
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
}

function page(page_number){
    current_page = page_number;
    drawTable();
}

chunking(input_array);



//Search//
function searchPID(input_data, search_term){
    var i = 0;
    var index = new Array();
    var temp_codes = Object.values(input_data)['pid'];
    temp_codes.forEach(function(product_code){
        if(search_term == product_code){
            index.push(i);
        }
        i += 1
    })
    return index;
}