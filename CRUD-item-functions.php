<?php
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
if (isset($_POST['add-item-button'])) {
    session_start();
    $category_id = $_POST['category_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $user_id = $_SESSION['user_id'];

    $restaurant_id = getRestaurantId($user_id, $mysqli);

    if ($restaurant_id === null) {
        echo "<script> alert('Restaurant not found for this user'); </script>";
    } else {
        //item dupe
        $item_check_sql = "SELECT item_name FROM item WHERE item_name = '$item_name' AND restaurant_id = $restaurant_id";
        $item_result = $mysqli->query($item_check_sql);
        if ($item_result->num_rows > 0) {
            echo "<script> alert('This item already exist'); </script>";
        } else {
            if ($_FILES['item_image']['error'] === 4) {
                //if user didnt put the image
                echo "<script> alert('Image doesn't exist'); </script>";
            } else {
                $filename = $_FILES['item_image']['name'];
                $filesize = $_FILES['item_image']['size'];
                $tmpName = $_FILES['item_image']['tmp_name'];

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
                    move_uploaded_file($tmpName, 'upload-image-item/' . $newImageName);
                    $item_insert_sql = "INSERT INTO item(item_name,item_price,item_image,category_id,restaurant_id)
                    VALUES ('$item_name','$item_price','$newImageName','$category_id','$restaurant_id')";
                    $insert_query_run = mysqli_query($mysqli, $item_insert_sql);
                    //DATA SUCCESS
                    echo
                    "<script> 
                        alert('This item added');
                        document.location.href = 'menu-management.php';
                    </script>";
                }
            }
        }
    }
}

if (isset($_POST['update-item-button'])) {

    session_start();
    $item_id = $_POST['item_id'];
    $category_id = $_POST['category_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $new_image = $_FILES['item_image']['name'];
    $old_image = $_POST['item_image_old'];
    $user_id = $_SESSION['user_id'];

    $restaurant_id = getRestaurantId($user_id, $mysqli);

    if ($restaurant_id === null) {
        echo "<script> alert('Restaurant not found for this user'); </script>";
    } else {
        if ($new_image != '') {
            $filesize = $_FILES['item_image']['size'];
            $tmpName = $_FILES['item_image']['tmp_name'];

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
                move_uploaded_file($tmpName, 'upload-image-item/' . $update_filename);
                unlink("upload-image-item/" . $old_image);
            }
        } else {
            //use old image
            $update_filename = $old_image;
        }

        $update_image_query = "UPDATE item SET item_name='$item_name',
                                                item_price='$item_price',
                                                category_id='$category_id',
                                                item_image='$update_filename',
                                                restaurant_id='$restaurant_id' WHERE item_id='$item_id'";
        $update_query_run = mysqli_query($mysqli, $update_image_query);
        echo "<script> alert('Item has been updated!'); document.location.href = 'menu-management.php'; </script>";
    }
}



if (isset($_POST['add-category-button'])) {

    $category_name = $_POST['category_name'];
    session_start();
    $user_id = $_SESSION['user_id'];
    $restaurant_id = getRestaurantId($user_id, $mysqli);

    if ($restaurant_id === null) {
        echo "restaurant not found";
        //echo "<script> alert('Restaurant not found for this user'); </script>";
    } else {
        //category duplication
        $category_check_sql = "SELECT category_name FROM category WHERE category_name = '$category_name' AND restaurant_id = $restaurant_id";
        $category_result = $mysqli->query($category_check_sql);
        if ($category_result->num_rows > 0) {
            echo "<script> alert('Category already existed!'); document.location.href = 'cat-management.php'; </script>";
        } else {
            $category_insert_sql = "INSERT INTO category(category_name,restaurant_id) VALUES ('$category_name','$restaurant_id')";
            $insert_query_run = mysqli_query($mysqli, $category_insert_sql);
            //DATA SUCCESS
            header("Location:cat-management.php");
        }
    }
}

if (isset($_POST['update-category-button'])) {

    session_start();
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    $user_id = $_SESSION['user_id'];

    $restaurant_id = getRestaurantId($user_id, $mysqli);

    if ($restaurant_id === null) {
        echo "<script> alert('Restaurant not found for this user'); </script>";
    } else {
        

        $update_category_query = "UPDATE category SET category_name='$category_name',
                                                restaurant_id='$restaurant_id' WHERE category_id='$category_id' AND restaurant_id = '$restaurant_id'";
        $update_query_run = mysqli_query($mysqli, $update_category_query);
        echo "<script> alert('Category has been updated!'); document.location.href = 'cat-management.php'; </script>";
    }
}


if (isset($_POST['delete-category-data'])) {

    session_start();
    $user_id = $_SESSION['user_id'];
    $category_id = $_POST['category_id'];
    $delete_cat_query = "DELETE FROM category WHERE category_id = '$category_id'";
    $delete_cat_query_run = mysqli_query($mysqli, $delete_cat_query);

    if ($delete_image_query_run) {
        echo "Category deleted!";
        header("Location: cat-management.php");
    } else {
        echo "Category failed to delete, maybe theres item that is linked with this category";
        header("Location: cat-management.php");
    }
}



if (isset($_POST['delete-item-data'])) {

    session_start();
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['item_id'];
    $item_image = $_POST['item_image'];
    $delete_image_query = "DELETE FROM item WHERE item_id = '$item_id'";
    $delete_image_query_run = mysqli_query($mysqli, $delete_image_query);

    if ($delete_image_query_run) {
        unlink("upload-image-item/" . $item_image);
        echo "Item deleted!";
        header("Location: menu-management.php");
    } else {
        echo "Item failed to delete";
        header("Location: menu-management.php");
    }
}


if (isset($_POST['add-promo-button'])) {
    session_start();
    $user_id = $_SESSION['user_id'];
    $restaurant_id = getRestaurantId($user_id, $mysqli);

    $category_name = $_POST['category_name'];
    $promotionName = $_POST['promotionName'];
    $dateStart = $_POST['dateStart'];
    $dateEnd = $_POST['dateEnd'];
    $discount = $_POST['discount'];
    $description = $_POST['description'];

    

    if ($restaurant_id === null) {
        echo "restaurant not found";
        //echo "<script> alert('Restaurant not found for this user'); </script>";
    } else {
        //category duplication
        $promo_check_sql = "SELECT promotionName FROM promotion WHERE promotionName = '$promotionName' AND restaurant_id = $restaurant_id";
        $promo_result = $mysqli->query($promo_check_sql);
        if ($promo_result->num_rows > 0) {
            echo "<script> alert('Promotion already existed!'); document.location.href = 'promotion-management.php'; </script>";
        } else {
            $promo_insert_sql = "INSERT INTO promotion(promotionName,dateStart,dateEnd,discount,description,restaurant_id) VALUES ('$promotionName','$dateStart','$dateEnd','$discount','$description','$restaurant_id')";
            $insert_query_run = mysqli_query($mysqli, $promo_insert_sql);
            //DATA SUCCESS
            echo "<script> alert('Promotion added successfully!'); document.location.href = 'promotion-management.php'; </script>";
        }
    }
}

if (isset($_POST['delete-promo-data'])) {

    session_start();
    $user_id = $_SESSION['user_id'];
    $restaurant_id = getRestaurantId($user_id, $mysqli);
    $promotion_id = $_POST['promotion_id'];
    $delete_promo_query = "DELETE FROM promotion WHERE promotion_id = '$promotion_id' AND restaurant_id = '$restaurant_id'";
    $delete_promo_query_run = mysqli_query($mysqli, $delete_promo_query);

    if ($delete_promo_query_run) {
        echo "Item deleted!";
        header("Location: promotion-management.php");
    } else {
        echo "Item failed to delete";
        header("Location: promotion-management.php");
    }
}

if (isset($_POST['update-promo-button'])) {

    session_start();
    $user_id = $_SESSION['user_id'];
    $promotion_id = $_POST['promotion_id'];
    $restaurant_id = getRestaurantId($user_id, $mysqli);
    $promotionName = $_POST['promotionName'];
    $dateStart = $_POST['dateStart'];
    $dateEnd = $_POST['dateEnd'];
    $discount = $_POST['discount'];
    $description = $_POST['description'];

    if ($restaurant_id === null) {
        echo "<script> alert('Restaurant not found for this user'); </script>";
    } else {
        $update_promo_query = "UPDATE promotion SET promotionName='$promotionName',
                                                dateStart='$dateStart',
                                                dateEnd='$dateEnd',
                                                discount='$discount',
                                                description='$description',
                                                restaurant_id='$restaurant_id' WHERE promotion_id='$promotion_id'";
        $update_query_run = mysqli_query($mysqli, $update_promo_query);
        echo "<script> alert('Promotion has been updated!'); document.location.href = 'promotion-management.php'; </script>";

       
    }
}
