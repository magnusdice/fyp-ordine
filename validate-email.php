<?php
$mysqli = require __DIR__ . "/database-connection.php";
$sql = sprintf("SELECT * FROM user WHERE email = '%s'", $mysqli->real_escape_string($_GET["email"]));
$result = $mysqli->query($sql);
$is_available = $result ->num_rows === 0;
//To send the data to js
header("Content-Type: application/json");
echo json_encode(["available" => $is_available]);
?>