<?php
$host = 'localhost';
$db = 'Great_American';
$user = 'Lou';
$pass = 'GAFNJ001';

$dsn = "mysql:host=$host;dbname=$db;";
$pdo = new PDO($dsn, $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
