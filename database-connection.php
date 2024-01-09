<?php

$host="localhost";
$dbname="ordine";
$username="root";
$password="";

$mysqli= new mysqli($host,$username,$password,$dbname);

//CONNECTION TO DATABASE
if($mysqli->connect_errno){
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;