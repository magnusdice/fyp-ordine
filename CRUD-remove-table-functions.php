<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (isset($data['tableId'])) {
        $tableId = $data['tableId'];
        $user_id = $_SESSION['user_id'];
        $restaurant_id = getRestaurantId($user_id, $mysqli);

        // Check if the table belongs to the restaurant (additional security check)
        $stmt = $mysqli->prepare("SELECT * FROM tables WHERE table_id = ? AND restaurant_id = ?");
        $stmt->bind_param("ii", $tableId, $restaurant_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Table exists in the restaurant; you can now delete it
            $stmt = $mysqli->prepare("DELETE FROM tables WHERE table_id = ?");
            $stmt->bind_param("i", $tableId);
            if ($stmt->execute()) {
                echo "Table with ID $tableId removed from the database.";
            } else {
                echo "Error removing table from the database.";
            }
        } else {
            echo "Table with ID $tableId does not exist in your restaurant.";
        }
    } else {
        echo "Invalid data received.";
    }
}
?>