<?php
ini_set('display_errors', "1");


if (empty($_POST["restaurant_name"])) {
    die("Restaurant name is required!");
}

if (empty($_POST["restaurant_address"])) {
    die("Address is required!");
}

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

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database-connection.php";

//INSERT DATA
$email = $_POST["email"];
$restaurant_name = $_POST["restaurant_name"];
$restaurant_address = $_POST["restaurant_address"];
$user_type = "admin";
$start_time = $_POST["start_time"];
$end_time = $_POST["end_time"];

//EMAIL CHECK DUPLICATION
$email_check_sql = "SELECT email FROM user WHERE email = '$email'";
$result = $mysqli->query($email_check_sql);
////////

if ($result->num_rows > 0) {
    echo "This email already exist!";
} else {
    if ($_FILES['restaurant_logo']['error'] === 4) {
        //if user didnt put the image
        echo "<script> alert('Image doesn't exist'); </script>";
    } else {
        $filename = $_FILES['restaurant_logo']['name'];
        $filesize = $_FILES['restaurant_logo']['size'];
        $tmpName = $_FILES['restaurant_logo']['tmp_name'];

        //validation
        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $filename);
        //strtolower make lowcases
        $imageExtension = strtolower(end($imageExtension));
        if (!in_array($imageExtension, $validImageExtension)) {
            echo "<script> alert('Invalid Image Extension. Only jpg, jpeg and png is allowed'); </script>";
        } else if ($filesize > 1000000) {
            echo "<script> alert('Image size is too large'); </script>";
        } else {
            $newImageName = uniqid();
            $newImageName .= '.' . $imageExtension;

            //move_uploaded_file($tmpName, 'img/' . $newImageName);
            move_uploaded_file($tmpName, 'upload-restaurant-logo/' . $newImageName);
            $restaurant_insert_sql = "INSERT INTO restaurant(restaurant_name, restaurant_address,restaurant_logo,start_time,end_time)
            VALUES ('$restaurant_name', '$restaurant_address','$newImageName','$start_time','$endtime')";
            if ($mysqli->query($restaurant_insert_sql) === TRUE) {
                $restaurant_id = $mysqli->insert_id;
                $user_insert_sql = "INSERT INTO user (email,password_hash,user_type,restaurant_id) 
                VALUES ('$email','$password_hash','$user_type','$restaurant_id')";
                if ($mysqli->query($user_insert_sql) === TRUE) {
                    header("Location: login.php");
                    exit;
                } else {
                    echo "Error: " . $user_insert_sql . "<br>" . $mysqli->error;
                    exit;
                }
                echo "Error: " . $restaurant_insert_sql . "<br>" . $mysqli->error;
                exit;
            }
        }
    }
}
