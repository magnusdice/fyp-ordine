<?php

ini_set('display_errors', "1");
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
$mysqli = require __DIR__ . "/database-connection.php";

//EMAIL CHECK DUPLICATION
$email_check_sql = "SELECT email FROM user WHERE email = '$email'";
$result = $mysqli->query($email_check_sql);
////////

if (isset($_POST['add-admin-button'])) {
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        die("Valid email is required!");
    }

    if (strlen($_POST["password"]) < 8) {
        die("Password must be at least 8 characters");
    }

    if (!preg_match("/[a-z]/i", $_POST["password"])) {
        die("Password must be at least one letter");
    }

    if (!preg_match("/[0-9]/", $_POST["password"])) {
        die("Password must be at least one number");
    }

    if ($_POST["password"] !== $_POST["confirm-pass"]) {
        die("Password don't match");
    }
    $email = $_POST["email"];
    $user_type = "admin";
    $restaurant_id = $_POST['restaurant_id'];
    //EMAIL CHECK DUPLICATION
    $email_check_sql = "SELECT email FROM user WHERE email = '$email'";
    $result = $mysqli->query($email_check_sql);
    ////////
    if ($result->num_rows > 0) {
        echo "This email already exist!";
    } else {
        $user_insert_sql = "INSERT INTO user (email,password_hash,user_type,restaurant_id) 
        VALUES ('$email','$password_hash','$user_type','$restaurant_id')";
        if ($mysqli->query($user_insert_sql) === TRUE) {
            header("Location: admin-management.php");
            exit;
        } else {
            echo "Error: " . $user_insert_sql . "<br>" . $mysqli->error;
            exit;
        }
    }
}

if (isset($_POST['delete-admin-data'])) {
    session_start();
    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $delete_image_query = "DELETE FROM user WHERE user_id = '$user_id'";
    $delete_image_query_run = mysqli_query($mysqli, $delete_image_query);

    if ($delete_image_query_run) {
        echo "<script> alert('Admin has been deleted!'); </script>";
        header("Location: admin-management.php");
    } else {
        echo "<script> alert('Admin failed to delete'); </script>";
        header("Location: admin-management.php");
    }
}
