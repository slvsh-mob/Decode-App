<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include "../includes/autoloader.inc.php";
    include_once "../config/core.php";
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
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  
</head>
<body>
    <nav>
        <div class="nav-wrapper">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="../pages/receiving.php">Receiving</a></li>
            <li><a href="../pages/production.php">Production</a></li>
            <li><a href="../pages/inventory.php">Inventory</a></li>
            <li><a href="../pages/admin.php">Admin</a></li>
        </ul>
        </div>
    </nav>

<div class="row" style="margin-bottom: 0;">