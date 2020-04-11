
function paging(input, chunk_size){
    id_list = input['id'];
    gtin_list = input['gtin'];
    pid_list = input['pid'];
    weight_list = input['weight'];
    date_list = input['date'];
    serial_list = input['serial'];
    received_list = input['received'];
    receiving_id_list = input['receiving_id'];

    id_chunked = [];
    gtin_chunked = [];
    pid_chunked = [];
    weight_chunked = [];
    date_chunked = [];
    serial_chunked = [];
    received_chunked = [];
    receiving_id_chunked = [];
    //calculate number of rows & initialize pointer
    length_set = id_list.length;
    num_chunks = Math.ceil(length_set / chunk_size);
    pointer = 0;
    //chunk arrays by specified size
    for(j = 0; j < num_chunks; j++){
        pointer_end = pointer + (chunk - 1);
        id_chunk = id_list.slice(pointer, pointer_end);
        gtin_chunk = gtin_list.slice(pointer, pointer_end);
        pid_chunk = pid_list.slice(pointer, pointer_end);
        weight_chunk = weight_list.slice(pointer, pointer_end);
        date_chunk = date_list.slice(pointer, pointer_end);
        serial_chunk = serial_list.slice(pointer, pointer_end);
        received_chunk = received_list.slice(pointer, pointer_end);
        receiving_id_chunk = receiving_id_list.slice(pointer, pointer_end);
        
        id_chunked.push(id_chunk);
        gtin_chunked.push(gtin_chunk);
        pid_chunked.push(pid_chunk);
        weight_chunked.push(weight_chunk);
        date_chunked.push(date_chunk);
        serial_chunked.push(serial_chunk);
        received_chunked.push(received_chunk);
        receiving_id_chunked.push(receiving_id_chunk);
        
        pointer = pointer + chunk;
    }
    //create output array of chunked arrays
    output = {ids: id_chunked, gtins: gtin_chunked, pids: pid_chunked, weights: weight_chunked, dates: date_chunked, serials: serial_chunked, receiving_times: received_chunked, receiving_ids: receiving_id_chunked};
    //feed array chunk into drawTable
    drawTable(output, index);
}




function drawTable(input, index){
    id_list = input['ids'][index];
    gtin_list = input['gtins'][index];
    pid_list = input['pids'][index];
    weight_list = input['weights'][index];
    date_list = input['dates'][index];
    serial_list = input['serials'][index];
    received_list = input['receiving_times'][index];
    receiving_id_list = input['receiving_ids'][index];

    for(i = 0; i < length.id_list; i++){
        row = table.insertRow();
        cell0 = row.insertCell(0);
        cell0.innerHTML = id_list[i];
        cell1 = row.insertCell(1);
        cell1.innerHTML = gtin_list[i]
        cell2 = row.insertCell(2);
        cell2.innerHTML = pid_list[i];
        cell3 = row.insertCell(3);
        cell3.innerHTML = weight_list[i];
        cell4 = row.insertCell(4);
        cell4.innerHTML = date_list[i];
        cell5 = row.insertCell(5);
        cell5.innerHTML = serial_list[i];
        cell6 = row.insertCell(6);
        cell6.innerHTML = received_list[i];
        cell7 = row.insertCell(7);
        cell7.innerHTML = receiving_id_list[i];
    }
}


function orderTable(order){
    if(order == 1){
        ids = ids.reverse();
        gtins = gtins.reverse();
        pids = pids.reverse();
        weights = weights.reverse();
        dates = dates.reverse();
        serials = serials.reverse();
        receiving_times = receiving_times.reverse();
        receiving_ids = receiving_ids.reverse();
        drawTable(ids, gtins, pids, weights, dates, serials, receiving_times, receiving_ids);
    }else{
        drawTable(ids, gtins, pids, weights, dates, serials, receiving_times, receiving_ids);
    }
} 

var shipment_table_btns = [
    ["View", "./shipment_details.php?id="],
    ["Edit", "./edit_shipment.php?id="],
    ["Delete", "./delete_shipment.php?id="]
]
var inventory_table_btns = [
    ["Delete", "./delete_item.php?id="]
]
var supplier_table_btns = [
    ["View", "./supplier_details.php?id="],
    ["Edit", "./edit_supplier.php?id="],
    ["Delete", "./delete_supplier.php?id="]
]


function addButtons(btns, id){
    for (i = 0; i < btns.length; i ++){
        btn = document.createElement("button");
        btn.className = "table_btns";
        btn.id = id;
        btn.innerHTML = btns[i][0];      
        btn.onClick('window.location.href = ' + btns[i][1] + id);
    }
}


var inventory_table = {
    columns: 7,
    headings: ["ID", "GTIN", "Product Code", "Weight", "Date", "Serial ID", "Receiving ID"],
}
var shipments_table = {
    columns: 9,
    headings: ["ID", "Supplier", "Expected Quantity", "Expected Weight", "Actual Quantity", "Actual Weight", "Receiving ID", "Created", "Actions"]
}


function drawTABLE(style, table_name, data){
    table = document.getElementById(table_name);
    temp_row = table.insertRow();
    for (i = 0; i < style['columns']; i ++){
        temp_cell = temp_row.insertCell(i);
        temp_cell.innerHTML = style["headings"][i];
    }

    table_rows = data[]
}





//WORKING CODE BELOW//

function searchFor(input, 'weight', '44.05'){
    id_list = input['id'];
    gtin_list = input['gtin'];
    pid_list = input['pid'];
    weight_list = input['weight'];
    date_list = input['date'];
    serial_list = input['serial'];
    received_list = input['received'];
    receiving_id_list = input['receiving_id'];

    switch (heading){
        case ('id'):
            var ids = input.filter(function(id){
                return input.id == term;
            })
            console.log(ids);
            break;
        case ('gtin'):
            var gtins = input.filter(function(gtin){
                return input.gtin == term;
            })
            console.log(gtins);
            break;
        case ('pid'):
            var pids = input.filter(function(pid){
                return input.pid == term;
            })
            console.log(pids);
            break;
        case ('weight'):
            var weights = input.filter(function(weight){
                return input.weight == term;
            })
            console.log(weights);
            break;
    }


}

function chunking(input_data, row_per_page){
    var num_indexes = Object.keys(input_data).length;
    var num_rows = input_data['product_code'].length;
    console.log(num_rows);
    console.log(num_indexes);


    var heading_length = 4;

    var num_chunks = Math.ceil(num_rows / row_per_page);

    var chunks = new Array();

    var pointer = 0;
    for(i = 0; i < num_chunks; i++){
        pointer_end = pointer + row_per_page;
        //create the chunk
        var temp = new Array();
        for(j = 0; j < heading_length; j++){
            temp[j] = Object.values(input_data)[j].slice(pointer, pointer_end);
        }
        temp['heading_length'] = heading_length;
        temp['row_per_page'] = row_per_page;
        temp['num_pages'] = num_chunks;
        chunks[i] = temp;
        pointer += row_per_page;
    } 
    return chunks;
}


function drawTable(chunked_data, current_page){
    current_chunk = Object.values(chunked_data)[current_page];
    heading_length = current_chunk['heading_length'];
    row_per_page = current_chunk['row_per_page'];

    //Update Page Number
    page_number = document.getElementById('page_number');
    page_number.innerHTML = current_page + 1;

    //Generate Table
    table = document.getElementById('inventory_table');
    num_rows = table.rows.length;
    //Clear Table
    if(num_rows > 1){
        for(z = 0; z < (num_rows - 1); z++){
            table.deleteRow(1);
        }
    }

    //Fill Table
    for(k = 0; k < row_per_page; k++){
        temp_row = table.insertRow();
        for(l = 0; l < heading_length; l++){
            temp_cell = temp_row.insertCell(l);
            temp_cell.innerHTML = Object.values(current_chunk)[l][k];
        }
    }
}

function setup(row_per_page, current_page){
    qty_container = document.getElementById('qty_container');
    qty_container.getElementsByTagName('a')[0].className = 'btn-small';
    qty_container.getElementsByTagName('a')[1].className = 'btn-small';
    qty_container.getElementsByTagName('a')[2].className = 'btn-small';
    qty_container.getElementsByTagName('a')[3].className = 'btn-small';

    nav_container = document.getElementById('nav_container');
    nav_container.getElementsByTagName('a')[0].className = 'btn-small';
    nav_container.getElementsByTagName('a')[1].className = 'btn-small';
    nav_container.getElementsByTagName('a')[2].className = 'btn-small';
    nav_container.getElementsByTagName('a')[3].className = 'btn-small';

    switch (row_per_page){
        case 5:
            document.getElementById('btn_qty_5').className += ' red';
            break;
        case 10:
            document.getElementById('btn_qty_10').className += ' red';
            break;
        case 25:
            document.getElementById('btn_qty_25').className += ' red';
            break;
        case 50:
            document.getElementById('btn_qty_50').className += ' red';
            break;
        case "all":
            document.getElementById('btn_qty_all').className += ' red';
    }

    chunks = chunking(input_array, row_per_page);
    var last_page = Object.values(chunks)[0]['num_pages'];
    console.log(last_page)
    drawTable(chunks, current_page);

    switch (current_page){
        case 0:
            nav_container.getElementsByTagName('a')[1].className += " disabled";
            nav_container.getElementsByTagName('a')[0].className += " red";
            nav_container.getElementsByTagName('a')[2].onClick = "javascript:setup(25, " + (current_page + 1) + ")";
            nav_container.getElementsByTagName('a')[3].onClick = setup(25, last_page);
            break;
/*         case (0 < current_page < last_page):
            nav_container.getElementsByTagName('a')[1].onClick = "setup(25, " + (current_page + 1) + ")";
            nav_container.getElementsByTagName('a')[0].onClick = "setup(25, " + (current_page - 1) + ")";
            break;
        case last_page:
            nav_container.getElementsByTagName('a')[2].className += ' disabled';
            nav_container.getElementsByTagName('a')[3].className += ' red';
            break; */
    }
    }

    setup(25, 0);