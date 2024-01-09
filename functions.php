<?php
function getTotal($order_id, $mysqli)
{
    $total_query = "SELECT total_amount FROM orders WHERE order_id='$order_id'";
    $total_result = $mysqli->query($total_query);
    if ($total_result->num_rows > 0) {
        $row = $total_result->fetch_assoc();
        return $row['total_amount'];
    } else {
        return null;
    }
}
function getOrderIdByTable($table_id, $mysqli)
{
    $order_status = 0;
    $order_query = "SELECT order_id FROM orders WHERE table_id ='$table_id' AND order_status = '$order_status'";
    $order_result = $mysqli->query($order_query);
    if ($order_result->num_rows > 0) {
        $row = $order_result->fetch_assoc();
        return $row['order_id'];
    } else {
        return null;
    }
}
function getItemName($item_id, $mysqli, $restaurant_id)
{
    $item_query = "SELECT item_name FROM item WHERE restaurant_id = '$restaurant_id'";
    $item_result = $mysqli->query($item_query);
    if ($item_result->num_rows > 0) {
        $row = $item_result->fetch_assoc();
        return $row['item_name'];
    } else {
        return null;
    }
}
function getRestaurantId($user_id, $mysqli)
{
    $restaurant_query = "SELECT restaurant_id FROM user WHERE user_id = '$user_id'";
    $restaurant_result = $mysqli->query($restaurant_query);

    if ($restaurant_result->num_rows > 0) {
        $row = $restaurant_result->fetch_assoc();
        return $row['restaurant_id'];
    } else {
        return null;
    }
}

function getTableNo($table_id, $restaurant_id, $mysqli)
{
    $query = "SELECT table_no FROM tables WHERE table_id = $table_id AND restaurant_id = '$restaurant_id'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['table_no'];
    } else {
        return null;
    }
}


function getEmployeeImage($user_id, $restaurant_id, $mysqli)
{
    $emp_image_query = "SELECT * FROM employee WHERE restaurant_id = $restaurant_id AND user_id = $user_id";
    $emp_image_query_result = $mysqli->query($emp_image_query);
    if ($emp_image_query_result->num_rows > 0) {
        $row = $emp_image_query_result->fetch_assoc();
        return $row['empImage'];
    } else {
        return null;
    }
}

function getEmployeeID($user_id, $restaurant_id, $mysqli)
{
    $emp_query = "SELECT employee_id FROM employee WHERE restaurant_id = $restaurant_id AND user_id = $user_id";
    $result = $mysqli->query($emp_query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['employee_id'];
    } else {
        return null;
    }
}

function getEmployeeName($user_id, $restaurant_id, $mysqli)
{
    $emp_query = "SELECT firstName, lastName FROM employee WHERE restaurant_id = $restaurant_id AND user_id = $user_id";
    $result = $mysqli->query($emp_query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fullName = $row["firstName"] . ' ' . $row["lastName"];
        return $fullName;
    } else {
        return null;
    }
}

function getRestaurantName($restaurant_id, $mysqli)
{
    $restaurant_name_query = "SELECT restaurant_name FROM restaurant WHERE restaurant_id = '$restaurant_id'";
    $restaurant_name_query_result = $mysqli->query($restaurant_name_query);

    if ($restaurant_name_query_result->num_rows > 0) {
        $row = $restaurant_name_query_result->fetch_assoc();
        return $row['restaurant_name'];
    } else {
        return null;
    }
}

function getRestaurantAdd($restaurant_id, $mysqli)
{
    $restaurant_add_query = "SELECT restaurant_address FROM restaurant WHERE restaurant_id = '$restaurant_id'";
    $restaurant_add_query_result = $mysqli->query($restaurant_add_query);

    if ($restaurant_add_query_result->num_rows > 0) {
        $row = $restaurant_add_query_result->fetch_assoc();
        return $row['restaurant_address'];
    } else {
        return null;
    }
}

function getRestaurantlogo($restaurant_id, $mysqli)
{
    $restaurant_logo_query = "SELECT restaurant_logo FROM restaurant WHERE restaurant_id = '$restaurant_id'";
    $restaurant_logo_query_result = $mysqli->query($restaurant_logo_query);

    if ($restaurant_logo_query_result->num_rows > 0) {
        $row = $restaurant_logo_query_result->fetch_assoc();
        return $row['restaurant_logo'];
    } else {
        return null;
    }
}

function getLayoutId($restaurant_id, $mysqli)
{
    $layout_query = "SELECT layout_id FROM layout WHERE restaurant_id = '$restaurant_id'";
    $layout_query_result = $mysqli->query($layout_query);

    if ($layout_query_result->num_rows > 0) {
        $row = $layout_query_result->fetch_assoc();
        return $row['layout_id'];
    } else {
        return null;
    }
}

function getReadOnlyLayoutData($mysqli)
{
    $layout_select_query = "SELECT * FROM restuarant_layout WHERE read_only =1";
    $layout_stmt = $mysqli->query($layout_select_query);

    return $layout_stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserType($restaurant_id, $mysqli, $user_id)
{
    $user_query = "SELECT user_type FROM user WHERE restaurant_id = '$restaurant_id' AND user_id = '$user_id'";
    $user_result = $mysqli->query($user_query);

    if ($user_result->num_rows > 0) {
        $row = $user_result->fetch_assoc();
        return $row['user_type'];
    } else {
        return null;
    }
}

// Function to get start status
function getStartStatus($employee_id, $mysqli)
{
    $startStatusQuery = "SELECT start_status FROM attendance WHERE employee_id = $employee_id AND date = CURDATE()";
    $startStatusResult = mysqli_query($mysqli, $startStatusQuery);

    if ($startStatusResult && mysqli_num_rows($startStatusResult) > 0) {
        $startStatusData = mysqli_fetch_assoc($startStatusResult);
        return $startStatusData['start_status'];
    }

    return "Not Taken";
}

// Function to get end status
function getEndStatus($employee_id, $mysqli)
{
    $endStatusQuery = "SELECT end_status FROM attendance WHERE employee_id = $employee_id AND date = CURDATE()";
    $endStatusResult = mysqli_query($mysqli, $endStatusQuery);

    if ($endStatusResult && mysqli_num_rows($endStatusResult) > 0) {
        $endStatusData = mysqli_fetch_assoc($endStatusResult);
        return $endStatusData['end_status'];
    }

    return "Not Taken";
}

function getUserID($employee_id, $mysqli)
{
    $sql = "SELECT user_id FROM employee WHERE employee_id = $employee_id";
    $result = mysqli_query($mysqli, $sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['user_id'];
    } else {
        return null;
    }
}
