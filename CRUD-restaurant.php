<?php
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
if (isset($_POST['restaurant-detail'])) {
    session_start();
    $restaurant_name = $_POST["restaurant_name"];
    $restaurant_address = $_POST["restaurant_address"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];
    $new_image = $_FILES['restaurant_logo']['name'];
    $old_image = $_POST['restaurant_logo_old'];
    $user_id = $_SESSION['user_id'];
    $restaurant_id = getRestaurantId($user_id, $mysqli);

    if ($restaurant_id === null) {
        echo "<script> alert('Restaurant not found for this user'); </script>";
    } else {
        if ($new_image != '') {
            $filesize = $_FILES['restaurant_logo']['size'];
            $tmpName = $_FILES['restaurant_logo']['tmp_name'];

            //validation
            $validImageExtension = ['jpg', 'jpeg', 'png'];
            $imageExtension = explode('.', $new_image);
            //strtolower make lowcases
            $imageExtension = strtolower(end($imageExtension));
            //update image
            if (!in_array($imageExtension, $validImageExtension)) {
                echo "<script> alert('Invalid Image Extension. Only jpg, jpeg and png is allowed'); </script>";
            } else if ($filesize > 1000000) {
                echo "<script> alert('Image size is too large'); </script>";
            } else {
                $update_filename = uniqid();
                $update_filename .= '.' . $imageExtension;
                move_uploaded_file($tmpName, 'upload-restaurant-logo/' . $update_filename);
                unlink("upload-restaurant-logo/" . $old_image);
            }
        } else {
            //use old image
            $update_filename = $old_image;
        }

        $update_image_query = "UPDATE restaurant SET restaurant_name='$restaurant_name',
                                                restaurant_address='$restaurant_address',
                                                start_time='$start_time',
                                                end_time='$end_time',
                                                restaurant_logo='$update_filename'
                                                WHERE restaurant_id='$restaurant_id'";
        $update_query_run = mysqli_query($mysqli, $update_image_query);
        echo "<script> alert('Restaurant data has been updated!'); document.location.href = 'restaurant-details.php'; </script>";
    }
}
