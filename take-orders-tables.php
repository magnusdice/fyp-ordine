<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
$user_id = $_SESSION['user_id'];
$restaurant_id = getRestaurantId($user_id, $mysqli);
$restaurant_name = getRestaurantName($restaurant_id, $mysqli);
$restaurant_logo = getRestaurantlogo($restaurant_id, $mysqli);
$fullName = getEmployeeName($user_id, $restaurant_id, $mysqli);
$empImage = getEmployeeImage($user_id, $restaurant_id, $mysqli);
$employee_id = getEmployeeID($user_id, $restaurant_id, $mysqli);
$user_type = getUserType($restaurant_id, $mysqli, $user_id);
?>
<!DOCTYPE html>
<html lang="en" data-theme="cupcake">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <title>Take Order</title>
  <link rel="stylesheet" href="styles/output.css" />
</head>

<body class="font-poppins bg-neutral">
  <!-- Header  -->
  <?php include 'header-staff.php'; ?>
  <!-- header end -->
  <div class="flex flex-row h-screen">
    <!-- sidebar -->
    <div class="flex flex-col bg-gray-100 justify-between w-64 py-4 px-2 border-r-2 flex-none">
      <div class="flex flex-col flex-auto">
        <div class="my-2 p-2 text-primary rounded-md hover:bg-primary hover:text-neutral">
          <div class="flex flex-row space-x-3">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2">
              <line x1="18" y1="20" x2="18" y2="10"></line>
              <line x1="12" y1="20" x2="12" y2="4"></line>
              <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>
            <a href="home-staff-page.php" class="font-semibold">Dashboard</a>
          </div>
        </div>
        <div class="my-2 p-2 rounded-md bg-primary text-neutral">
          <div class="flex flex-row space-x-3">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-bottombar-collapse" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M20 6v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2z" />
              <path d="M20 15h-16" />
              <path d="M14 8l-2 2l-2 -2" />
            </svg>
            <a href="take-orders-tables.php" class="font-semibold">Take Order</a>
          </div>
        </div>
        <div class="my-2 p-2 text-primary rounded-md hover:bg-primary hover:text-neutral">
          <div class="flex flex-row space-x-3">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-category" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M4 4h6v6h-6z" />
              <path d="M14 4h6v6h-6z" />
              <path d="M4 14h6v6h-6z" />
              <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
            </svg>
            <a href="view-order.php" class="font-semibold">View Orders</a>
          </div>
        </div>
        <div class="my-2 p-2 text-primary rounded-md hover:bg-primary hover:text-neutral">
          <div class="flex flex-row space-x-3">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
              <path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
              <path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" />
            </svg>
            <a href="payment-table.php" class="font-semibold">Payments</a>
          </div>
        </div>

        <div class="my-2 p-2 text-primary rounded-md hover:bg-primary hover:text-neutral">
          <div class="flex flex-row space-x-3">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-receipt" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2" />
            </svg>
            <a href="invoice.php" class="font-semibold">View Invoice</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Content  -->
    <main class="w-full overflow-x-hidden">
      <div class="flex justify-center items-center my-5">
        <ul class="steps steps-vertical lg:steps-horizontal flex-1 justify-between">
          <li class="step step-primary">Choose Table</li>
          <li class="step">Order</li>
        </ul>
      </div>

      <div class="flex justify-center items-center my-5">
        <div class="col-sm-2 col-sm-offset-3 form-group">
          <input type="hidden" id="width" class="form-control" />
        </div>
        <div class="col-sm-2 form-group">
          <input type="hidden" id="height" class="form-control" />
        </div>
        <canvas id="canvas"></canvas>
      </div>
    </main>
    <!-- end of content  -->
  </div>
  <script src="./lib/fabric.min.js"></script>
  <script src="./scripts/take-order.js"></script>
</body>

</html>