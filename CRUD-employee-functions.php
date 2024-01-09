<?php
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
if (isset($_POST['add-emp-button'])) {

    session_start();
    $user_id = $_SESSION['user_id'];
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $empRoles = $_POST['empRoles'];
    $empDate = $_POST['empDate'];
    $empSalary = $_POST['empSalary'];
    $empNo = $_POST['empNo'];
    $user_type = "staff";

    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $restaurant_id = getRestaurantId($user_id, $mysqli);

    if ($restaurant_id === null) {
        echo "<script> alert('Restaurant not found for this user'); </script>";
    } else {
        //email dupe
        $email_check_sql = "SELECT email FROM user WHERE email='$email' AND restaurant_id = '$restaurant_id'";
        $email_result = $mysqli->query($email_check_sql);

        if ($email_result->num_rows > 0) {
            echo
            "<script> 
                alert('This email already exist');
                document.location.href = 'emp-management.php';
            </script>";
        } else {
            //employee dupe
            $emp_check_sql = "SELECT firstName, lastName FROM employee WHERE firstName = '$firstName' AND lastName = '$lastName' AND restaurant_id = $restaurant_id";
            $emp_result = $mysqli->query($emp_check_sql);
            if ($emp_result->num_rows > 0) {
                echo
                "<script> 
                alert('This employee already exist');
                document.location.href = 'emp-management.php';
            </script>";
            } else {
                if ($_FILES['empImage']['error'] === 4) {
                    //if user didnt put the image
                    echo "<script> alert('Image doesn't exist'); </script>";
                } else {
                    $filename = $_FILES['empImage']['name'];
                    $filesize = $_FILES['empImage']['size'];
                    $tmpName = $_FILES['empImage']['tmp_name'];

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
                        $user_insert_sql = "INSERT INTO user(email,password_hash,user_type,restaurant_id)
                            VALUES ('$email','$password_hash','$user_type','$restaurant_id')";
                        if ($mysqli->query($user_insert_sql) === TRUE) {
                            $new_user_id = $mysqli->insert_id;
                            $newImageName = uniqid();
                            $newImageName .= '.' . $imageExtension;

                            //move_uploaded_file($tmpName, 'img/' . $newImageName);
                            move_uploaded_file($tmpName, 'upload-image-employee/' . $newImageName);
                            $emp_insert_sql = "INSERT INTO employee(firstName,lastName,empRoles,empDate,empSalary,empNo,empImage,restaurant_id,user_id)
                                VALUES ('$firstName','$lastName','$empRoles','$empDate','$empSalary','$empNo','$newImageName','$restaurant_id','$new_user_id')";
                            if ($mysqli->query($emp_insert_sql) === TRUE) {
                                echo
                                "<script> 
                                alert('This employee added');
                                document.location.href = 'emp-management.php';
                                </script>";
                            } else {
                                echo "Error: " . $emp_insert_sql . "<br>" . $mysqli->error;
                                exit;
                                //error
                            }
                            //$insert_query_run = mysqli_query($mysqli, $emp_insert_sql);
                            //DATA SUCCESS

                        } else {
                            echo "Error: " . $user_insert_sql . "<br>" . $mysqli->error;
                            exit;
                            //error
                        }
                    }
                }
            }
        }
    }
}

if (isset($_POST['update-emp-staff-button'])) {

    session_start();
    $employee_id = $_POST['employee_id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $empNo = $_POST['empNo'];
    $new_image = $_FILES['empImage']['name'];
    $old_image = $_POST['emp_image_old'];
    $new_email = $_POST['newEmail'];
    if ($new_image != '') {
        $filesize = $_FILES['empImage']['size'];
        $tmpName = $_FILES['empImage']['tmp_name'];

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
            move_uploaded_file($tmpName, 'upload-image-employee/' . $update_filename);
            unlink("upload-image-employee/" . $old_image);
        }
    } else {
        //use old image
        $update_filename = $old_image;
    }

    $update_image_query = "UPDATE employee SET firstName='$firstName',
                                            lastName='$lastName',
                                            empImage='$update_filename',
                                            empNo='$empNo'
                                            WHERE employee_id='$employee_id'";

    $update_user_query = "UPDATE user SET email='$new_email' WHERE user_id=(SELECT user_id FROM employee WHERE employee_id='$employee_id')";
    $update_query_run = mysqli_query($mysqli, $update_image_query);
    $update_user_query_run = mysqli_query($mysqli, $update_user_query);
    if ($update_query_run && $update_user_query_run) {
        echo "<script> alert('Employee Info has been updated!'); document.location.href = 'employee-details.php'; </script>";
    } else {
        echo "<script> alert('Failed to update employee info.'); </script>";
    }
}


if (isset($_POST['update-emp-button'])) {

    session_start();
    $employee_id = $_POST['employee_id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $empRoles = $_POST['empRoles'];
    $empDate = $_POST['empDate'];
    $empSalary = $_POST['empSalary'];
    $empNo = $_POST['empNo'];
    $new_image = $_FILES['empImage']['name'];
    $old_image = $_POST['emp_image_old'];
    $new_email = $_POST['newEmail'];
    if ($new_image != '') {
        $filesize = $_FILES['empImage']['size'];
        $tmpName = $_FILES['empImage']['tmp_name'];

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
            move_uploaded_file($tmpName, 'upload-image-employee/' . $update_filename);
            unlink("upload-image-employee/" . $old_image);
        }
    } else {
        //use old image
        $update_filename = $old_image;
    }

    $update_image_query = "UPDATE employee SET firstName='$firstName',
                                            lastName='$lastName',
                                            empRoles='$empRoles',
                                            empImage='$update_filename',
                                            empDate='$empDate',
                                            empNo='$empNo',
                                            empRoles='$empRoles'
                                            WHERE employee_id='$employee_id'";

    $update_user_query = "UPDATE user SET email='$new_email' WHERE user_id=(SELECT user_id FROM employee WHERE employee_id='$employee_id')";
    $update_query_run = mysqli_query($mysqli, $update_image_query);
    $update_user_query_run = mysqli_query($mysqli, $update_user_query);
    if ($update_query_run && $update_user_query_run) {
        echo "<script> alert('Employee Info has been updated!'); document.location.href = 'emp-management.php'; </script>";
    } else {
        echo "<script> alert('Failed to update employee info.'); </script>";
    }
}

if (isset($_POST['delete-emp-data'])) {

    session_start();
    $user_id = $_POST['user_id'];
    $employee_id = $_POST['employee_id'];
    $empImage = $_POST['empImage'];
    $email = $_POST['email'];
    $delete_image_query = "DELETE FROM employee WHERE employee_id = '$employee_id'";
    $delete_image_query_run = mysqli_query($mysqli, $delete_image_query);

    if ($delete_image_query_run) {
        $delete_user_query = "DELETE FROM user WHERE user_id = '$user_id'";
        $delete_user_query_run = mysqli_query($mysqli, $delete_user_query);
        if ($delete_user_query_run) {
            unlink("upload-image-employee/" . $empImage);
            echo "<script> alert('Employee has been deleted!'); </script>";
            header("Location: emp-management.php");
        } else {
            echo "<script> alert('Employee deleted, but there was an issue deleting the user account.'); </script>";
            header("Location: emp-management.php");
        }
    } else {
        echo "<script> alert('Employee failed to delete'); </script>";
        header("Location: emp-management.php");
    }
}

if (isset($_POST['password-change'])) {
    session_start();
    $employee_id = $_POST['employee_id'];
    $user_id = $_POST['user_id'];
    $sql_user = "SELECT * FROM user WHERE user_id = $user_id";
    $result_user = $mysqli->query($sql_user);
    $user = $result_user->fetch_assoc();

    if ($user) {
        if (password_verify($_POST['old_password'], $user["password_hash"])) {
            if ($_POST['new_password'] !== $_POST['confirm_password']) {
                echo "<script> alert('Password dont match!'); </script>";
            } else {
                $password_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $update_query = "UPDATE user SET password_hash = '$password_hash'
                                            WHERE user_id = $user_id";
                if ($mysqli->query($update_query) === TRUE) {
                    echo "<script> alert('Password Changed!'); document.location.href = 'emp-management.php'; </script>";
                } else {
                    echo "<script> alert('Error: . $update_query . <br> . $mysqli->error'); document.location.href = 'emp-management.php'; </script>";
                }
            }
        } else {
            echo "<script> alert('Wrong Password!'); document.location.href = 'emp-management.php'; </script>";
        }
    }
}

if (isset($_POST['password-change-staff'])) {
    session_start();
    $user_id = $_POST['user_id'];
    $sql_user = "SELECT * FROM user WHERE user_id = $user_id";
    $result_user = $mysqli->query($sql_user);
    $user = $result_user->fetch_assoc();

    if ($user) {
        if (password_verify($_POST['old_password'], $user["password_hash"])) {
            if ($_POST['new_password'] !== $_POST['confirm_password']) {
                echo "<script> alert('Password dont match!'); </script>";
            } else {
                $password_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $update_query = "UPDATE user SET password_hash = '$password_hash'
                                            WHERE user_id = $user_id";
                if ($mysqli->query($update_query) === TRUE) {
                    echo "<script> alert('Password Changed!'); document.location.href = 'employee-details.php'; </script>";
                } else {
                    echo "<script> alert('Error: . $update_query . <br> . $mysqli->error'); document.location.href = 'employee-details.php'; </script>";
                }
            }
        } else {
            echo "<script> alert('Wrong Password!'); document.location.href = 'employee-details.php'; </script>";
        }
    }
}
