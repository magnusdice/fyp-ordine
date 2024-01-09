<?php
$mysqli = require __DIR__ . "/database-connection.php";
$employee_id = $_POST['employee_id'];
$restaurant_id = $_POST['restaurant_id'];

if (isset($_POST['clock_in'])) {
    /*
    $checkClockOutQuery = "SELECT attendance_id FROM attendance WHERE employee_id = $employee_id AND date = CURDATE()";
    $checkClockOutResult = mysqli_query($mysqli, $checkClockOutQuery);

    if (mysqli_num_rows($checkClockOutResult) > 0) {
        echo "<script> alert('Attendance already taken for today!'); document.location.href = 'home-staff-page.php'; </script>";
    } else {
        $businessStartTime = getBusinessStartTime($restaurant_id, $mysqli);
        $currentTimeStamp = time();
        echo "<script> alert('".$currentTimeStamp." yah ".$businessStartTime."'); document.location.href = 'home-staff-page.php'; </script>";
        echo $currentTimeStamp;
        if ($currentTimeStamp < $businessStartTime - 300) {
            $startStatus = "Early In";
        } elseif ($currentTimeStamp >= $businessStartTime - 300 && $currentTimeStamp <= $businessStartTime + 300) {
            $startStatus = "On Time";
        } else {
            $startStatus = "Late In";
        }

        $clockInQuery = "INSERT INTO attendance (employee_id,clock_in,clock_out,restaurant_id,date,start_status) VALUES ($employee_id, NOW(),NULL, $restaurant_id, NOW(),'$startStatus')";
        if ($mysqli->query($clockInQuery) === TRUE) {
            echo "<script> alert('Attendance Taken!'); document.location.href = 'home-staff-page.php'; </script>";
            exit;
        } else {
            echo "Error: " . $clockInQuery . "<br>" . $mysqli->error;
        }
    }
    */

    $checkClockOutQuery = "SELECT attendance_id FROM attendance WHERE employee_id = $employee_id AND date = CURDATE()";
    $checkClockOutResult = mysqli_query($mysqli, $checkClockOutQuery);
    if (mysqli_num_rows($checkClockOutResult) > 0) {
        echo "<script> alert('Attendance already taken for today!'); document.location.href = 'home-staff-page.php'; </script>";
    } else {
        // Process clock in
        $employee_id = $_POST['employee_id'];
        $restaurant_id = $_POST['restaurant_id'];
        $current_time = date("Y-m-d H:i:s");

        // Fetch business start time from the 'restaurant' table
        $start_time_query = "SELECT start_time FROM restaurant WHERE restaurant_id = $restaurant_id";
        $result = $mysqli->query($start_time_query);
        $row = $result->fetch_assoc();
        $business_start_time = $row['start_time'];

        // Compare current time with business start time to determine status
        if ($current_time < $business_start_time) {
            $status = "Early In";
        } elseif ($current_time >= $business_start_time && $current_time <= "23:59:59") {
            $status = "Late In";
        } else {
            $status = "On Time";
        }

        // Insert the clock-in record into the 'attendance' table
        $insert_query = "INSERT INTO attendance (employee_id, clock_in, restaurant_id, date,start_status) 
                     VALUES ('$employee_id', '$current_time', '$restaurant_id', CURDATE(), '$status')";
        if ($mysqli->query($insert_query) === TRUE) {
            echo "<script> alert('Attendance Taken!'); document.location.href = 'home-staff-page.php'; </script>";
            exit;
        } else {
            echo "Error: " . $clockInQuery . "<br>" . $mysqli->error;
        }
    }
}

if (isset($_POST['clock_out'])) {
    // // If the employee already clocked in
    // $checkClockInQuery = "SELECT attendance_id FROM attendance WHERE employee_id = $employee_id AND date = CURDATE() AND clock_out IS NULL";
    // $checkClockInResult = mysqli_query($mysqli, $checkClockInQuery);

    // if (mysqli_num_rows($checkClockInResult) > 0) {
    //     $businessStartTime = getBusinessStartTime($restaurant_id, $mysqli);
    //     $businessEndTime = getBusinessEndTime($restaurant_id, $mysqli);
    //     $currentTimeStamp = time();
    //     $nextDayStartTime = strtotime(date('Y-m-d', strtotime('+1 day', $businessStartTime)) . ' ' . date('H:i:s', $businessStartTime));

    //     if ($currentTimeStamp < $businessEndTime - 300) {
    //         $endStatus = "Early Out";
    //     } elseif ($currentTimeStamp >= $businessEndTime - 300 && $currentTimeStamp <= $businessEndTime + 300) {
    //         $endStatus = "On Time";
    //     } else {
    //         $endStatus = "Late Out";
    //     }

    //     $clockOutQuery = "UPDATE attendance SET clock_out = CURRENT_TIMESTAMP, end_status = '$endStatus' WHERE employee_id = $employee_id AND clock_out IS NULL";
    //     if ($mysqli->query($clockOutQuery) === TRUE) {
    //         echo "<script> alert('Clock-Out!'); document.location.href = 'home-staff-page.php'; </script>";
    //         exit;
    //     } else {
    //         echo "Error: " . $clockInQuery . "<br>" . $mysqli->error;
    //     }
    // } else {
    //     echo "<script> alert('You haven't clocked in today'); document.location.href = 'home-staff-page.php'; </script>";
    //     exit;
    // }

    // Process clock out
    $checkClockInQuery = "SELECT attendance_id FROM attendance WHERE employee_id = $employee_id AND date = CURDATE() AND clock_out IS NULL";
    $checkClockInResult = mysqli_query($mysqli, $checkClockInQuery);
    if (mysqli_num_rows($checkClockInResult) > 0) {
        $employee_id = $_POST['employee_id'];
        $restaurant_id = $_POST['restaurant_id'];
        $current_time = date("Y-m-d H:i:s");

        // Fetch business end time from the 'restaurant' table
        $end_time_query = "SELECT end_time FROM restaurant WHERE restaurant_id = $restaurant_id";
        $result = $mysqli->query($end_time_query);
        $row = $result->fetch_assoc();
        $business_end_time = $row['end_time'];

        // Compare current time with business end time to determine status
        if ($current_time < $business_end_time) {
            $status = "Early Out";
        } elseif ($current_time >= $business_end_time && $current_time <= "23:59:59") {
            $status = "Late Out";
        } else {
            $status = "On Time";
        }

        // Update the clock-out record in the 'attendance' table
        $update_query = "UPDATE attendance SET clock_out = '$current_time', end_status ='$status' 
                         WHERE employee_id = '$employee_id' AND restaurant_id = '$restaurant_id' AND date = CURDATE()";
        //$mysqli->query($update_query);
        if ($mysqli->query($update_query) === TRUE) {
            echo "<script> alert('Clock-Out!'); document.location.href = 'home-staff-page.php'; </script>";
            exit;
        } else {
            echo "Error: " . $clockInQuery . "<br>" . $mysqli->error;
        }

        // echo "Clock Out Successful. Status: $status";
    } else {
        echo "<script> alert('You haven't clocked in today'); document.location.href = 'home-staff-page.php'; </script>";
        exit;
    }
}

function getBusinessStartTime($restaurant_id, $mysqli)
{
    $startTimeQuery = "SELECT start_time FROM restaurant WHERE restaurant_id = $restaurant_id";
    $startTimeResult = mysqli_query($mysqli, $startTimeQuery);
    $startTimeData = mysqli_fetch_assoc($startTimeResult);
    return strtotime($startTimeData['start_time']);
}

function getBusinessEndTime($restaurant_id, $mysqli)
{
    $endTimeQuery = "SELECT end_time FROM restaurant WHERE restaurant_id = $restaurant_id";
    $endTimeResult = mysqli_query($mysqli, $endTimeQuery);
    $endTimeData = mysqli_fetch_assoc($endTimeResult);
    return strtotime($endTimeData['end_time']);
}
