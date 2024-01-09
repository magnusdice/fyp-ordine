<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive the table data from the request
    $user_id = $_SESSION['user_id'];
    $json_tableData = file_get_contents('php://input');
    $tableData = json_decode($json_tableData, JSON_OBJECT_AS_ARRAY);
    $restaurant_id = getRestaurantId($user_id, $mysqli);
    $table_status = 'available';
    $layout_id = getLayoutId($restaurant_id, $mysqli);
    
    foreach ($tableData as $table) {
        $left = $table["left"];
        $top = $table["top"];
        $table_shape = $table["table_shape"];
        $width = $table["width"];
        $height = $table["height"];
        $number = $table["number"];
        $radius = $table["radius"];
        $table_id = $table["table_id"];

        $existingTable = $mysqli->query("SELECT * FROM tables WHERE table_id = $table_id AND restaurant_id = $restaurant_id");

        if ($existingTable->num_rows > 0) {
            // exist so update
            $mysqli->query("UPDATE tables SET table_x = $left, table_y = $top, width = $width, height = $height, radius= $radius WHERE table_id = $table_id");
        } else {
            //not exist so insert
            $table_insert_stmt = $mysqli->prepare("INSERT INTO tables (table_id,table_x, table_y, table_shape, width, height, table_no, radius, table_status, restaurant_id,layout_id) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)");
            $table_insert_stmt->bind_param("iiisiiiisii", $table_id, $left, $top, $table_shape, $width, $height, $number, $radius, $table_status, $restaurant_id, $layout_id);
            $table_insert_stmt->execute();
            
        }
    }
}
