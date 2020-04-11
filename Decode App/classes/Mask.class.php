<?php
class Mask{
    private $conn;
    private $table_name = "masks";

    public $id;
    public $barcode;
    public $gtin_loc;
    public $prod_loc;
    public $weight_loc;
    public $weight_len;
    public $date_loc;
    public $date_len;
    public $serial_loc;
    public $created;
    public $prod_code;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $w_length = 6;
        $this->weight_len = $w_length;
        $d_length = 6;
        $this->date_len = $d_length;
        $result = array();

         //run thru gtin
        $gtin_locs = $this->gtin();
        //run thru weight
        $weight_locs = $this->weight();
        //run thru date
        $result = $this->decode();
        var_dump($result);
        //run thru serial
        $serial_locs = $this->serial();
        //run thru product code
        $prod_location = $this->findProd();

         if($gtin_locs[0] != 100){
            $num = count($gtin_locs);
            if($num > 1){
                $this->gtin_loc = $gtin_locs[0];
            }else{
                $this->gtin_loc = $gtin_locs[0];
            }
        }
        if($serial_locs[0] != 100){
            $num = count($serial_locs);
            if($num > 1){
                $this->serial_loc = $serial_locs[0];
            }else{
                $this->serial_loc = $serial_locs[0];
            }
        }

        if($weight_locs['3101'][0] !== false){
            $this->weight_loc = ($weight_locs['3101'][0] + 4);
            $temp = substr($this->barcode, $this->weight_loc, $w_length);
            $temp = floatval($temp);
            $result['weight'] = $this->convertWeight($temp, 0);
        }
        if($weight_locs['3102'][0] !== false){
            $this->weight_loc = ($weight_locs['3102'][0] + 4);
            $temp = substr($this->barcode, $this->weight_loc, $w_length);
            $temp = floatval($temp);
            $result['weight'] = $this->convertWeight($temp, 1);
        }
        if($weight_locs['3201'][0] !== false){
            $this->weight_loc = ($weight_locs['3201'][0] + 4);
            $temp = substr($this->barcode, $this->weight_loc, $w_length);
            $temp = floatval($temp);
            $result['weight'] = $this->convertWeight($temp, 2);
        }
        if($weight_locs['3202'][0] !== false){
            $this->weight_loc = ($weight_locs['3202'][0] + 4);
            $temp = substr($this->barcode, $this->weight_loc, $w_length);
            $temp = floatval($temp);
            $result['weight'] = $this->convertWeight($temp, 3);
        }
         if($prod_location != 100){
            $this->prod_loc = $prod_location;
        }
        else{
            $this->prod_loc = "00000";
        } 
        $result['prod_code'] = $this->prod_code;
        $result['gtin_loc'] = $this->gtin_loc;
        $result['prod_loc'] = $this->prod_loc;
        $result['weight_loc'] = $this->weight_loc;
        $result['weight_len'] = $this->weight_len;
        $result['date_loc'] = $this->date_loc;
        $result['date_len'] = $this->date_len;
        $result['serial_loc'] = $this->serial_loc;
        return $result;
        
        //$query = "INSERT INTO " . $this->table_name . " (gtin_loc, prod_loc, weight_loc, date_loc, serial_loc, created) VALUES (:a, :b, :c, :d, :e, :f)";
        //$stmt = $this->conn->prepare($query);
        //$this->created = date('Y-m-d H:i:s');
        //$stmt->bindParam(':a', $this->gtin_loc);
        //$stmt->bindParam(':b', $this->prod_loc);
        //$stmt->bindParam(':c', $this->weight_loc);
        //$stmt->bindParam(':d', $this->date_loc);
        //$stmt->bindParam(':e', $this->serial_loc);
        //$stmt->bindParam(':f', $this->created);
        //$stmt->execute();
    }

    private function decode(){
        $length = strlen($this->barcode);
        for ($k = 0; $k < 2; $k++){
            switch ($k){
                case 0:
                    for ($i = 0; $i < ($length - 1); $i++){
                        $temp = substr($this->barcode, $i, 2);
                        if($temp === '11'){
                            $output_1[] = $i;
                            $date_1[] = substr($this->barcode, ($i + 2), 6);
                        }
                    }
                    break;
                case 1:
                    for ($i = 0; $i < ($length - 1); $i++){
                        $temp = substr($this->barcode, $i, 2);
                        if($temp === '13'){
                            $output_2[] = $i;
                            $date_2[] = substr($this->barcode, ($i + 2), 6);
                        }
                    }
                    break;
                case 2:
                    for ($i = 0; $i < ($length - 1); $i++){
                        $temp = substr($this->barcode, $i, 2);
                        if($temp === '15'){
                            $output_3[] = $i;
                            $date_3[] = substr($this->barcode, ($i + 2), 6);
                        }
                    }
                    break;

            }
        }
        
        foreach ($date_1 as $line_a){
            $temp = $this->validateDate($line_a);
            echo $line_a;
            var_dump($temp);
            echo "<br>";
        }
        foreach ($date_2 as $line_b){
            $temp = $this->validateDate($line_b);
            echo $line_b;
            var_dump($temp);
            echo "<br>";

        }
        foreach ($date_3 as $line_c){
            $temp = $this->validateDate($line_c);
            echo $line_c;
            var_dump($temp);
            echo "<br>";

        }

        $outlet = array();
        $outlet['11'] = $output_1;
        $outlet['date_11'] = $date_1;
        $outlet['13'] = $output_2;
        $outlet['date_13'] = $date_2;
        $outlet['15'] = $output_3;
        $outlet['date_15'] = $date_3;
        return $outlet;
    }

    private function validateDate($date_string){
        $finals = array();
        $formats = array();
        $k = 0;
        $j = 0;
        while ($j < 4){
            switch ($j){
                case 0:
                    $date = DateTime::createFromFormat('dmy', $date_string);
                    $finals[$k] = $date;
                    if($date !== false){
                        $finals[$k] = $date;
                        $formats[$k] = 'dmy';
                        $k++;   
                    }
                    break;
                case 1:
                    $date = DateTime::createFromFormat('mdy', $date_string);
                    if($date !== false){
                        $finals[$k] = $date_string;
                        $formats[$k] = 'dmy';
                        $k++;
                    }
                    break;
                case 2:
                    $date = DateTime::createFromFormat('ymd', $date_string);
                    $finals[$k] = $date;
                    if($date !== false){
                        $finals[$k] = $date_string;
                        $formats[$k] = 'dmy';
                        $k++;
                    }
                    break;
                case 3:
                    $date = DateTime::createFromFormat('ydm', $date_string);
                    $finals[$k] = $date;
                    if($date !== false){
                        $finals[$k] = $date_string;
                        $formats[$k] = 'dmy';
                        $k++;
                    }
                    break;
                $j++;
                }
        }
        $outlet = array();
        $outlet['dates'] = $finals;
        $outlet['formats'] = $formats;
    return $outlet;
    }

    private function weight(){
        //find where weight isnt working, possible at the !== false claims
        $weight_1 = "3101";
        $weight_2 = "3102";
        $weight_3 = "3201";
        $weight_4 = "3202";
        $output_1 = array();
        $output_2 = array();
        $output_3 = array();
        $output_4 = array();
        $offset = 0;

        $temp_1 = strpos($this->barcode, $weight_1);
        $temp_2 = strpos($this->barcode, $weight_2);
        $temp_3 = strpos($this->barcode, $weight_3);
        $temp_4 = strpos($this->barcode, $weight_4);

        if ($temp_1 >= 0){
            $num_1 = substr_count($this->barcode, $weight_1);
            if($num_1 > 1){
                for ($i = 0; $i < $num_1; $i++){
                    $output_1[$i] = strpos($this->barcode, $weight_1, $offset);
                    $offset = ($output_1[$i] + 1);
                }
            }
            else{
                $output_1[] = strpos($this->barcode, $weight_1);
            }
        }else{
            $output_1[] = 100;
        }
        if ($temp_2 >= 0){
            $num_2 = substr_count($this->barcode, $weight_2);
            if($num_2 > 1){
                for ($i = 0; $i < $num_2; $i++){
                    $output_2[$i] = strpos($this->barcode, $weight_2, $offset);
                    $offset = ($output_2[$i] + 1);
                }
            }
            else{
                $output_2[] = strpos($this->barcode, $weight_2);
            }
        }else{
            $output_2[] = 100;
        }
        if ($temp_3 >= 0){
            $num_3 = substr_count($this->barcode, $weight_3);
            if($num_3 > 1){
                for ($i = 0; $i < $num_3; $i++){
                    $output_3[$i] = strpos($this->barcode, $weight_3, $offset);
                    $offset = ($output_3[$i] + 1);
                }
            }
            else{
                $output_3[] = strpos($this->barcode, $weight_3);
            }
        }else{
            $output_3[] = 100;
        }
        if ($temp_4 >= 0){
            $num_4 = substr_count($this->barcode, $weight_4);
            if($num_4 > 1){
                for ($i = 0; $i < $num_4; $i++){
                    $output_4[$i] = strpos($this->barcode, $weight_4, $offset);
                    $offset = ($output_4[$i] + 1);
                }
            }
            else{
                $output_4[] = strpos($this->barcode, $weight_4);
            }
        }else{
            $output_4[] = 100;
        }
        $outlet = array();
        $outlet['3101'] = $output_1;
        $outlet['3102'] = $output_2;
        $outlet['3201'] = $output_3;
        $outlet['3202'] = $output_4;
        return $outlet;
    }

    function gtin(){
        $gtin = '01';
        $output = array();

        if(strpos($this->barcode, $gtin) !== false){
            $num = substr_count($this->barcode, $gtin);
            if ($num > 1){
                for ($i = 0; $i < $num; $i++){
                    $output[$i] = strpos($this->barcode, $gtin, $offset);
                    $offset = ($output[$i] + 1);
                }
            }else{
                $output[] = strpos($this->barcode, $gtin);
            }
        }
        else{
            $output[] = 100;
        }
        return $output;
    }

    function serial(){
        $serial = "21";
        $output = array();

        if(strpos($this->barcode, $serial) !== false){
            $num = substr_count($this->barcode, $serial);
            if($num > 1){
                for ($i = 0; $i < $num; $i++){
                    $output[$i] = strpos($this->barcode, $serial, $offset);
                    $offset = ($output[$i] + 1);
                }
            }else{
                $output[] = strpos($this->barcode, $serial);
            }
        }else{
            $output[] = 100;
        }
        return $output;
    }

    function ageRange($date, $type){
        //take in a date and determine if it is within 1 year old 
        //case 0: mm-dd-yy
        //case 1: dd-mm-yy
        //case 2: yy-mm-dd
        //case 3: yy-dd-mm
        $temp = strtotime($date);
        switch ($type){
            case 0:
                $today = date('m-d-y');
                if($temp > $today->modify('-1 year')){
                    $check = 1;
                }else{
                    $check = 0;
                }
                break;
            case 1:
                $today = date('d-m-y');
                if($temp > $today->modify('-1 year')){
                    $check = 1;
                }else{
                    $check = 0;
                }
                break;
            case 2:
                $today = date('y-m-d');
                if($temp > $today->modify('-1 year')){
                    $check = 1;
                }else{
                    $check = 0;
                }
                break;
            case 3:
                $today = date('y-d-m');
                if($temp > $today->modify('-1 year')){
                    $check = 1;
                }else{
                    $check = 0;
                }
                break;
        }
        return $check;
    }

    function howOld($date, $type){
        //take in a date and determine how old it is in [d, m, y]
        $output = array();
        switch ($type){
            case 0:
                $today = date('m-d-y');
                $timestamp = $today->diff($date);
                $output['y'] = $timestamp->y;
                $output['m'] = $timestamp->m;
                $output['d'] = $timestamp->d;
                break;
            case 1:
                $today = date('d-m-y');
                $timestamp = $today->diff($date);
                $output['y'] = $timestamp->y;
                $output['m'] = $timestamp->m;
                $output['d'] = $timestamp->d;
                break;
            case 2:
                $today = date('y-m-d');
                $timestamp = $today->diff($date);
                $output['y'] = $timestamp->y;
                $output['m'] = $timestamp->m;
                $output['d'] = $timestamp->d;
                break;
            case 3:
                $today = date('y-d-m');
                $timestamp = $today->diff($date);
                $output['y'] = $timestamp->y;
                $output['m'] = $timestamp->m;
                $output['d'] = $timestamp->d;
                break;
        }
        return $output;
    }

    private function convertWeight($weight, $type){
        switch ($type){
            case 0:
                //3101
                $mod = 10;
                $weight = $weight / $mod;
                break;
            case 1:
                //3102
                $mod = 100;
                $weight = $weight / $mod;
                break;
            case 2:
                //3201
                $mod = 10;
                $weight = $weight / $mod;
                break;
            case 3:
                //3202
                $mod = 100;
                $weight = $weight / $mod;
                break;
        }
        return $weight;
    }

    private function findProd(){
        $num = strpos($this->barcode, $this->prod_code);
        if ($num >= 0){
            $prod_loc = $num;
        }else{
            $prod_loc = 100;
        }
        return $prod_loc;
    }
}
?>