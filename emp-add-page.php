<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
$user_id = $_SESSION['user_id'];
$restaurant_id = getRestaurantId($user_id, $mysqli);
$restaurant_name = getRestaurantName($restaurant_id, $mysqli);
$restaurant_logo = getRestaurantlogo($restaurant_id, $mysqli);
?>
<!DOCTYPE html>
<html lang="en" data-theme="cupcake">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <title>Add Employee</title>
  <link rel="stylesheet" href="styles/output.css" />
</head>

<body class="font-poppins bg-neutral">
  <!-- Header  -->
  <div class="navbar bg-primary border-b-2">
    <div class="flex-1">
      <h1 class="mx-2 text-center text-xl text-white font-bold">ORDINE</h1>
    </div>
    <div class="flex justify-end flex-1 px-2">
      <div class="flex items-stretch">
        <div class="flex-none hidden lg:block">
          <ul class="menu menu-horizontal">
            <!-- Navbar menu content here -->
            <li>
              <a href="emp-management.php"><svg class="fill-current h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24">
                  <path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z"></path>
                </svg></a>
            </li>
            <li>
              <a href="home-admin-page.php"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg></a>
            </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
  <!-- header end -->
  <form action="CRUD-employee-functions.php" method="POST" enctype="multipart/form-data">
    <div class="flex flex-row h-screen">
      <!-- sidebar -->
      <div class="flex flex-col bg-white justify-between items-center w-1/2 py-4 px-2 border-r-2 flex-none">
        <div class="flex flex-col flex-auto">
          <label for="empImage" class="label">Upload Staff's Image</label>
          <input type="file" name="empImage" id="empImage" accept=".jpg,.jpeg,.png" required class="file-input file-input-bordered file-input-primary w-full max-w-xs" />
          <label class="label">
            <span class="label-text-alt">Supported formats: PNG, JPG, JPEG</span>
          </label>
          <label for="email" class="label">Employee's Email:</label>
          <input required name="email" id="email" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

          <label for="password" class="label">Employee's Password:</label>
          <input required name="password" id="password" type="password" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

          <label for="password" class="label">Employee's Confirm Password:</label>
          <input required name="confirm-pass" id="confirm-pass" type="password" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

        </div>
      </div>
      <!-- Content  -->
      <main class="flex flex-col bg-white justify-between items-center w-1/2 py-4 px-2 border-r-2 flex-none">
        <div class="overflow-x-auto">
          <label for="firstName" class="label">Staff's First Name:</label>
          <input required name="firstName" id="firstName" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

          <label for="lastName" class="label">Staff's Last Name:</label>
          <input required name="lastName" id="lastName" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

          <label for="empRoles" class="label">Staff's Position:</label>
          <input required name="empRoles" id="empRoles" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

          <label for="empDate" class="label">Employee's Date Joined:</label>
          <input required name="empDate" id="empDate" type="date" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

          <label for="empNo" class="label">Employee's Phone Number:</label>
          <input required name="empNo" id="empNo" type="number" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

          <label for="empSalary" class="label">Employee's Salary/Hour:</label>
          <input required name="empSalary" id="empSalary" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xs" />
          <div class="mx-1 my-5 text-left">
            <input type="submit" class="btn btn-success" value="Add" name="add-emp-button">
            <a class="btn btn-error" href="emp-management.php">Cancel</a>
          </div>

        </div>
      </main>
      <!-- end of content  -->
    </div>
  </form>

</body>

</html>