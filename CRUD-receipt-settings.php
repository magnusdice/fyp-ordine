<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
$user_id = $_SESSION['user_id'];

if (isset($_POST['submit-settings'])) {
    $restaurant_id = getRestaurantId($user_id, $mysqli);

    if ($restaurant_id === NULL) {
        echo "<script> alert('Restaurant not found for this user'); </script>";
    } else {
        $user_id = $_SESSION['user_id'];
        $paperwidth = $_POST['paperwidth'];
        $custom_msg = $_POST['custom_msg'];
        $name_settings = isset($_POST['name_settings']) ? 1 : 0;
        $address_settings = isset($_POST['address_settings']) ? 1 : 0;
        $logo_settings = isset($_POST['logo_settings']) ? 1 : 0;
        $custom_message_settings = isset($_POST['custom_message_settings']) ? 1 : 0;

        $existingSettings = $mysqli->query("SELECT * FROM receipt_settings WHERE restaurant_id = $restaurant_id");
        if ($existingSettings->num_rows > 0) {
            //update
            $stmt = $mysqli->prepare("UPDATE receipt_settings SET name_settings=?, address_settings=?, custom_msg=?, logo_settings=?, custom_message_settings=?, paperwidth=? WHERE restaurant_id = ?");
            $stmt->bind_param("iisiiii", $name_settings, $address_settings, $custom_msg, $logo_settings, $custom_message_settings, $paperwidth, $restaurant_id);
            $stmt->execute();
            $stmt->close();
            echo
            "<script> 
                alert('Settings has been saved!');
                document.location.href = 'receipt-management.php';
            </script>";
            //$mysqli->query("UPDATE receipt_settings SET name_settings=$name_settings,address_settings=$address_settings,custom_msg=$custom_msg, logo_settings=$logo_settings, custom_message_settings=$custom_message_settings, paperwidth=$paperwidth WHERE restaurant_id = $restaurant_id");
        } else {
            $stmt = $mysqli->prepare("INSERT INTO receipt_settings (name_settings, address_settings, custom_msg, restaurant_id, logo_settings, custom_message_settings, paperwidth) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisiiii", $name_settings, $address_settings, $custom_msg, $restaurant_id, $logo_settings, $custom_message_settings, $paperwidth);
            $stmt->execute();
            $stmt->close();
            echo
            "<script> 
                alert('Settings has been saved!');
                document.location.href = 'receipt-management.php';
            </script>";
            //insert
            //$mysqli->query("INSERT INTO receipt_settings (name_settings,address_settings,custom_msg,restaurant_id, logo_settings, custom_message_settings, paperwidth) VALUES ($name_settings,$address_settings,$custom_msg,$restaurant_id, $logo_settings, $custom_message_settings, $paperwidth)");

        }
    }
}
