<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
$user_id = $_SESSION['user_id'];
$restaurant_id = getRestaurantId($user_id,$mysqli);

if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}

// Perform a SELECT query to retrieve data from the database
$sql = "SELECT * FROM tables WHERE restaurant_id = $restaurant_id";
$result = mysqli_query($mysqli, $sql);

if (mysqli_num_rows($result) > 0) {
    $data = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    // Return the data as JSON
    echo json_encode($data);
} else {
    echo "No data found";
}

mysqli_close($mysqli);

?>