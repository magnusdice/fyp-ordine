<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user_id = $_SESSION['user_id'];
    $json_canvasData = file_get_contents('php://input');
    $canvasData = json_decode($json_canvasData);
    $restaurant_id = getRestaurantId($user_id, $mysqli);
    $width = $canvasData->width;
    $height = $canvasData->height;
    error_log("Canvas Width: " . $width);
    error_log("Canvas Height: " . $height);

    // Check if a record for this restaurant already exists
    $existingLayout = $mysqli->query("SELECT * FROM layout WHERE restaurant_id = $restaurant_id");
    if ($existingLayout->num_rows > 0) {
        // Update the existing layout record
        $mysqli->query("UPDATE layout SET width = $width, height = $height WHERE restaurant_id = $restaurant_id");
        //echo "Canvas properties updated successfully!";
        echo json_encode(["message" => "Canvas properties updated successfully!"]);
    } else {
        // Insert a new layout record
        $mysqli->query("INSERT INTO layout (restaurant_id, width, height) VALUES ($restaurant_id, $width, $height)");
        echo "Canvas properties saved successfully!";
        echo json_encode(["message" => "Canvas properties saved successfully!"]);
    }
    error_reporting(E_ALL);
ini_set('display_errors', 1);
}
