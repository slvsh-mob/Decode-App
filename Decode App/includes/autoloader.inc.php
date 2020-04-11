<?php
spl_autoload_register('LoaderFxn');

function LoaderFxn($class_name){
    $path_name = '../classes/';
    $extension = '.class.php';
    $full_path = $path_name . $class_name . $extension;

    include_once $full_path;
}
?>