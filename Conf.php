<?php 
$hostDB='127.0.0.1';
$nameDB='gestiontareas';
$userDB='root';
$passDB="";

$dns="mysql:host=$hostDB;dbname=$nameDB;";
$pdo=new PDO($dns,$userDB,$passDB);
?>