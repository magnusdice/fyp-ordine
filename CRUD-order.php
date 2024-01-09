<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";

if (isset($_POST['order-status-btn'])){
    $order_item_id = $_POST["orderItemID"];
    $update_sql = "UPDATE order_item SET order_item_status = 'completed' WHERE orderItemID = $order_item_id";
    mysqli_query($mysqli,$update_sql);
    header("Location: view-order.php");
    exit();
}


if (isset($_POST['confirm-order'])) {
    $cart = $_SESSION['cart'];

    $total = $_POST['total_amount'];
    $restaurant_id = $_POST['restaurant_id'];
    $table_id = $_POST['table_id'];
    $order_status = 0;
    //0 = Unpaid 1 = Paid

    $insert_order_query = "INSERT INTO orders(order_date,total_amount,table_id,restaurant_id,order_status,order_time) VALUES (NOW(),$total,$table_id,$restaurant_id,$order_status,NOW())";
    //$insert_order_query_run = mysqli_query($mysqli,$insert_order_query);
    if ($mysqli->query($insert_order_query) === TRUE) {
        $order_id = $mysqli->insert_id;
        if (is_array($cart)) {
            //print_r($cart);
            foreach ($cart as $row) {
                $is_inserted = false;
                $item_id = mysqli_real_escape_string($mysqli, $row['item_id']);
                $item_price = mysqli_real_escape_string($mysqli, $row['item_price']);
                $quantity = mysqli_real_escape_string($mysqli, $row['quantity']);
                $remarks = mysqli_real_escape_string($mysqli,$row['remarks']);
                $order_item_status = "uncomplete";
                $subtotal = $item_price * $quantity;
                $insert_order_items_query = "INSERT INTO order_item (quantity,subtotal,item_id,order_id,remarks,restaurant_id,order_item_status) 
                VALUES ('" . $quantity . "','" . $subtotal . "','" . $item_id . "','" . $order_id . "','" . $remarks . "','" . $restaurant_id . "','" . $order_item_status . "')";
                if (mysqli_query($mysqli, $insert_order_items_query)) {
                    $is_inserted = true;
                }
            }
            if ($is_inserted) {
                $table_status = 'unavailable';
                echo "<script> alert('Order has been confirmed!'); document.location.href = 'view-order.php'; </script>";
                $update_table_query = "UPDATE tables SET table_status ='$table_status' WHERE table_id = '$table_id'";
                $update_query_run = mysqli_query($mysqli, $update_table_query);
                unset($_SESSION['cart']);
            } else {
                echo "<script> alert('Error'); document.location.href = 'home-staff-page.php'; </script>";
            }
        } else {
            echo "<script> alert('Error in array_cart'); document.location.href = 'home-staff-page.php'; </script>";
        }
    } else {
        echo "<script> alert('failed to create order_id'); document.location.href = 'home-staff-page.php'; </script>";
    }
}

if (isset($_POST['delete-order-data'])) {
    session_start();
    $user_id = $_SESSION['user_id'];
    $order_id = $_POST['order_id'];

    $delete_oi_query = "DELETE FROM order_item WHERE order_id = '$order_id'";
    $delete_oi_query_run = mysqli_query($mysqli, $delete_oi_query);

    if ($delete_oi_query_run) {
        $delete_order_query = "DELETE FROM orders WHERE order_id = '$order_id'";
        $delete_run = mysqli_query($mysqli, $delete_order_query);
        if ($delete_run) {
            echo "Order deleted!";
            header("Location: view-order.php");
        }
    } else {
        echo "Order failed to delete";
        header("Location: view-order.php");
    }
}

if (isset($_POST['payQR'])) {
    session_start();
    $amount_paid = $_POST['amount_paid'];
    $payment_method = 'Pay with QR';
    $order_id = $_POST['order_id'];
    $restaurant_id = $_POST['restaurant_id'];
    $after_discount = $_POST['after_discount'];
    $table_id = $_POST['table_id'];
    $feedback = $_POST['feedback'];

    // $insert_invoice_query = "INSERT INTO invoice(amount_paid,payment_method,payment_date,order_id,restaurant_id,after_discount,table_id,payment_time) 
    // VALUES ($amount_paid,$payment_method,NOW(),$order_id,$restaurant_id,$after_discount,$table_id,NOW())";

    $insert_invoice_query = "INSERT INTO invoice (amount_paid, payment_method, payment_date, order_id, restaurant_id, after_discount, payment_time, feedback) 
    VALUES (?, ?, NOW(), ?, ?, ?, NOW(),?)";

    $stmt = $mysqli->prepare($insert_invoice_query);
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("dsiids", $amount_paid, $payment_method, $order_id, $restaurant_id, $after_discount,$feedback);

        // Execute the statement
        if ($stmt->execute()) {
            $table_status = 'available';
            $update_table_query = "UPDATE tables SET table_status ='$table_status' WHERE table_id = '$table_id'";
            $update_query_run = mysqli_query($mysqli, $update_table_query);
            if ($update_query_run) {
                $order_status = 1;
                $update_paid_status = "UPDATE orders SET order_status ='$order_status' WHERE order_id = '$order_id'";
                $update_paid_run = mysqli_query($mysqli, $update_paid_status);
                echo "<script> alert('Order has been paid!');</script>";
                header("Location: payments-receipt.php?table_id=" . $table_id . "&order_id=" . $order_id);
            } else {
                echo "Failed to update the order status";
                header("Location: home-staff-page.php");
            }
        } else {
            echo "Failed to pay";
            header("Location: payment-table.php");
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle the case where the statement preparation failed
        echo "Failed to prepare statement";
    }
}

if (isset($_POST['payCard'])) {
    session_start();
    $amount_paid = $_POST['amount_paid'];
    $payment_method = 'Pay with Card';
    $order_id = $_POST['order_id'];
    $restaurant_id = $_POST['restaurant_id'];
    $after_discount = $_POST['after_discount'];
    $table_id = $_POST['table_id'];
    $feedback = $_POST['feedback'];

    // $insert_invoice_query = "INSERT INTO invoice(amount_paid,payment_method,payment_date,order_id,restaurant_id,after_discount,table_id,payment_time) 
    // VALUES ($amount_paid,$payment_method,NOW(),$order_id,$restaurant_id,$after_discount,$table_id,NOW())";

    $insert_invoice_query = "INSERT INTO invoice (amount_paid, payment_method, payment_date, order_id, restaurant_id, after_discount, payment_time, feedback) 
    VALUES (?, ?, NOW(), ?, ?, ?, NOW(),?)";

    $stmt = $mysqli->prepare($insert_invoice_query);
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("dsiids", $amount_paid, $payment_method, $order_id, $restaurant_id, $after_discount,$feedback);

        // Execute the statement
        if ($stmt->execute()) {
            $table_status = 'available';
            $update_table_query = "UPDATE tables SET table_status ='$table_status' WHERE table_id = '$table_id'";
            $update_query_run = mysqli_query($mysqli, $update_table_query);
            if ($update_query_run) {
                $order_status = 1;
                $update_paid_status = "UPDATE orders SET order_status ='$order_status' WHERE order_id = '$order_id'";
                $update_paid_run = mysqli_query($mysqli, $update_paid_status);
                echo "<script> alert('Order has been paid!');</script>";
                header("Location: payments-receipt.php?table_id=" . $table_id . "&order_id=" . $order_id);
            } else {
                echo "Failed to update the order status";
                header("Location: home-staff-page.php");
            }
        } else {
            echo "Failed to pay";
            header("Location: payment-table.php");
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle the case where the statement preparation failed
        echo "Failed to prepare statement";
    }
}


if (isset($_POST['payCash'])) {
    session_start();
    $amount_paid = $_POST['amount_paid'];
    $payment_method = 'Pay with Cash';
    $order_id = $_POST['order_id'];
    $restaurant_id = $_POST['restaurant_id'];
    $after_discount = $_POST['after_discount'];
    $table_id = $_POST['table_id'];
    $feedback = $_POST['feedback'];

    // $insert_invoice_query = "INSERT INTO invoice(amount_paid,payment_method,payment_date,order_id,restaurant_id,after_discount,table_id,payment_time) 
    // VALUES ($amount_paid,$payment_method,NOW(),$order_id,$restaurant_id,$after_discount,$table_id,NOW())";

    $insert_invoice_query = "INSERT INTO invoice (amount_paid, payment_method, payment_date, order_id, restaurant_id, after_discount, payment_time, feedback) 
    VALUES (?, ?, NOW(), ?, ?, ?, NOW(),?)";

    $stmt = $mysqli->prepare($insert_invoice_query);
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("dsiids", $amount_paid, $payment_method, $order_id, $restaurant_id, $after_discount,$feedback);

        // Execute the statement
        if ($stmt->execute()) {
            $table_status = 'available';
            $update_table_query = "UPDATE tables SET table_status ='$table_status' WHERE table_id = '$table_id'";
            $update_query_run = mysqli_query($mysqli, $update_table_query);
            if ($update_query_run) {
                $order_status = 1;
                $update_paid_status = "UPDATE orders SET order_status ='$order_status' WHERE order_id = '$order_id'";
                $update_paid_run = mysqli_query($mysqli, $update_paid_status);
                echo "<script> alert('Order has been paid!');</script>";
                header("Location: payments-receipt.php?table_id=" . $table_id . "&order_id=" . $order_id);
            } else {
                echo "Failed to update the order status";
                header("Location: home-staff-page.php");
            }
        } else {
            echo "Failed to pay";
            header("Location: payment-table.php");
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle the case where the statement preparation failed
        echo "Failed to prepare statement";
    }
}