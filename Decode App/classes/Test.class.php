<?php
class Test {

    private $name;
    private $number;

    function __construct(){
        $this->name = 'william';
        $this->number = 98;
    }

function aloud(){
    return 'Hello ' . $this->name . ' You are visitor #' . $this->number; 
}

}
?>