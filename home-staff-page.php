<?php
session_start();
include 'functions.php';
if (isset($_SESSION["user_id"])) {
  $mysqli = require __DIR__ . "/database-connection.php";
  $sql = "SELECT * FROM user
              WHERE user_id = {$_SESSION["user_id"]}";
  $result = $mysqli->query($sql);
  //GET ASSOCIATIVE ARRAY
  $user = $result->fetch_assoc();
}
$user_id = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <title>Main Page</title>
  <link rel="stylesheet" href="styles/output.css" />
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Get the current date and time
      var currentDate = new Date();
      var currentYear = currentDate.getFullYear();
      var currentMonth = ('0' + (currentDate.getMonth() + 1)).slice(-2);
      var currentDay = ('0' + currentDate.getDate()).slice(-2);
      var currentHours = currentDate.getHours();
      var ampm = currentHours >= 12 ? 'PM' : 'AM';
      currentHours = currentHours % 12;
      currentHours = currentHours ? currentHours : 12;
      var currentMinutes = ('0' + currentDate.getMinutes()).slice(-2);

      // Format the date in the desired format (YYYY-MM-DD)
      var formattedDate = currentYear + '-' + currentMonth + '-' + currentDay;

      // Format the time in the desired format (HH:MM)
      var formattedTime = currentHours + ':' + currentMinutes + ' ' + ampm;

      // Set the values in the input fields
      document.querySelector('input[name="date"]').value = formattedDate;
      document.querySelector('input[name="timeNow"]').value = formattedTime;
    });
  </script>
</head>

<body class="font-poppins bg-neutral">
  <?php if (isset($user)) :
    $restaurant_id = getRestaurantId($user_id, $mysqli);
    $restaurant_name = getRestaurantName($restaurant_id, $mysqli);
    $fullName = getEmployeeName($user_id, $restaurant_id, $mysqli);
    $empImage = getEmployeeImage($user_id, $restaurant_id, $mysqli);
    $employee_id = getEmployeeID($user_id, $restaurant_id, $mysqli);
    $startStatus = getStartStatus($employee_id, $mysqli);
    $endStatus = getEndStatus($employee_id, $mysqli);
  ?>

    <!-- header -->
    <?php include 'header-staff.php'; ?>
    <!-- header end -->
    <div class="flex flex-row h-screen">
      <!-- sidebar -->
      <div class="flex flex-col bg-gray-100 justify-between w-64 py-4 px-2 border-r-2 flex-none">
        <div class="flex flex-col flex-auto">
          <div class="my-2 p-2 rounded-md bg-primary text-neutral">
            <div class="flex flex-row space-x-3">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
              </svg>
              <a href="home-staff-page.php" class="font-semibold text-neutral">Dashboard</a>
            </div>
          </div>
          <div class="my-2 p-2 text-primary rounded-md hover:bg-primary hover:text-neutral">
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

          <!-- <div class="my-2 p-2 text-primary rounded-md hover:bg-primary hover:text-neutral">
            <div class="flex flex-row space-x-3">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-clock" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M10.5 21h-4.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v3" />
                <path d="M16 3v4" />
                <path d="M8 3v4" />
                <path d="M4 11h10" />
                <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                <path d="M18 16.5v1.5l.5 .5" />
              </svg>
              <a href="attendance.php" class="font-semibold">Attendance</a>
            </div>
          </div> -->

        </div>
      </div>
      <!-- Dashboard  -->
      <div class="bg-slate-200 h-screen w-full overflow-y-auto">
        <div class="p-8">
          <div class="grid grid-cols-1 md:cols-2 lg:grid-cols-4 gap-4 lg:gap-8">
            <!-- barchart -->
            <div class="p-4 bg-white rounded-lg shadow-sm lg:col-span-3 inline-grid grid-cols-3 gap-4">
              <a class="font-bold bg-primary ease-in duration-300 hover:bg-violet-950 rounded-lg text-center hover:text-lg flex flex-col items-center justify-center text-white" href="take-orders-tables.php">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-bottombar-collapse" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M20 6v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2z" />
                  <path d="M20 15h-16" />
                  <path d="M14 8l-2 2l-2 -2" />
                </svg>
                Take Order
              </a>
              <a class="font-bold bg-primary ease-in duration-300 hover:bg-violet-950 rounded-lg text-center hover:text-lg flex flex-col items-center justify-center text-white" href="payment-table.php">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                  <path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                  <path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" />
                </svg>
                Payments
              </a>
              <a class="font-bold bg-primary ease-out duration-300 hover:bg-violet-950 rounded-lg text-center hover:text-lg flex flex-col items-center justify-center text-white" href="view-order.php">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-category" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M4 4h6v6h-6z" />
                  <path d="M14 4h6v6h-6z" />
                  <path d="M4 14h6v6h-6z" />
                  <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                </svg>
                View Orders
              </a>
            </div>
            <!-- barchartends -->

            <div class="rounded-lg shadow-sm bg-white">
              <div class="overflow-x-auto relative sm:rounded-lg">

                <div class="flex flex-col justify-center items-center">
                  <div class="card bg-neutral text-neutral-content">
                    <div class="card-body items-center text-center">
                      <h2 class="card-title">Attendance</h2>
                      <form action="CRUD-attendance.php" method="POST">
                        <input type="hidden" name="employee_id" value="<?= $employee_id ?>">
                        <input type="hidden" name="restaurant_id" value="<?= $restaurant_id ?>">
                        <p class="my-1">Date Today:<input name="date" disabled type="text" class="input input-ghost input-sm w-full text-center max-w-xs my-1"></p>
                        <p class="my-1">Time Now:<input name="timeNow" disabled type="text" class="input input-ghost input-sm w-full max-w-xs my-1 text-center" value=""></p>
                        <div class="card-actions justify-center">
                          <input name="clock_in" class="btn btn-success" type="submit" value="Clock-In" <?php echo checkClockInStatus($employee_id, $mysqli); ?>>
                          <input name="clock_out" class="btn btn-success" type="submit" value="Clock-Out" <?php echo checkClockOutStatus($employee_id, $mysqli); ?>>
                        </div>
                        <?php
                        if (hasTakenAttendance($employee_id, $mysqli)) {
                          echo '<div><p>You already taken your attendance!</p></div>';
                        } else {
                          echo '<div></div>';
                        }
                        ?>
                        <div>
                          <?php
                          if (hasTakenAttendance($employee_id, $mysqli)) {
                            echo '<p class="my-2 font-bold">Status</p>';
                            echo '<div class="badge badge-primary mx-1">' . $startStatus . '</div>';
                            echo '<div class="badge badge-primary mx-1"> ' . $endStatus . '</div>';
                          } else {
                            echo '<p class="my-2 font-bold">Status</p>';
                            echo '<div class="badge badge-primary mx-1">' . $startStatus . '</div>';
                          }
                          ?>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Tables -->


            <!-- Table -->
            <div class="rounded-lg shadow-sm bg-white md:col-span-2 lg:col-span-4">
              <div class="overflow-x-auto relative sm:rounded-lg">
                <table class="w-full text-sm text-center text-gray-500">
                  <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                      <th class="py-3 px-6">Order No</th>
                      <th class="py-3 px-6">Items</th>
                      <th class="py-3 px-6">Table</th>
                      <th class="py-3 px-6">Time Ordered</th>
                      <th class="py-3 px-6">Status</th>
                      <th class="py-3 px-6">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $order_select_sql = "SELECT * FROM orders WHERE restaurant_id = $restaurant_id ORDER BY order_date DESC";
                    $order_rows = mysqli_query($mysqli, $order_select_sql);
                    $today = date("Y-m-d");
                    $items_found = false;
                    foreach ($order_rows as $order) {
                      $order_date = $order["order_date"];
                      if (date("Y-m-d", strtotime($order_date)) == $today) {
                        $items_found = true;
                        $order_id = $order["order_id"];
                        $table_id = $order["table_id"];
                        $order_time = $order["order_time"];
                        $order_status = $order["order_status"];
                        $table_no = getTableNo($table_id, $restaurant_id, $mysqli);

                    ?>
                        <tr class="hover">
                          <th scope="row" class="py-7 px-6 font-medium text-gray-900 whitespace-nowrap"><?= $order_id ?></th>
                          <td><?php
                              //order_items
                              $order_item_select_sql = "SELECT oi.*,i.item_name FROM order_item oi INNER JOIN item i ON oi.item_id = i.item_id WHERE oi.order_id = $order_id";
                              $order_item_rows = mysqli_query($mysqli, $order_item_select_sql);
                              ?>
                            <table class="table shadow-sm rounded-lg">
                              <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                  <th>Name</th>
                                  <th>Quantity</th>
                                  <th>Remarks</th>
                                  <th>Order Status</th>
                                </tr>
                              </thead>

                              <?php foreach ($order_item_rows as $order_item) : ?>
                                <tr>
                                  <td class="w-52"><?= $order_item['item_name'] ?></td>
                                  <td class="w-52"><?= $order_item['quantity'] ?></td>
                                  <td class="w-52"><?= $order_item['remarks'] ?></td>
                                  <td>
                                    <form action="CRUD-order.php" method="POST">
                                      <input type="hidden" name="orderItemID" value="<?= $order_item['orderItemID'] ?>">
                                      <?php if ($order_item['order_item_status'] === 'completed') : ?>
                                        <!-- <button class="btn btn-primary btn-xs" type="button" disabled>Completed</button> -->
                                        <div class="badge badge-success">Completed</div>
                                      <?php else : ?>
                                        <button class="btn btn-warning btn-xs" type="submit" name="order-status-btn">Pending</button>
                                      <?php endif; ?>
                                    </form>
                                  </td>
                                </tr>

                              <?php endforeach; ?>
                            </table>
                          </td>
                          <td class="py-4 px-6"><?= $table_no ?></td>
                          <td class="py-4 px-6">
                            <div>
                              <?= date("h:ia", strtotime($order_time)) ?>
                            </div>
                            <div>
                              <?= date('d F Y', strtotime($order_date)) ?>
                            </div>
                          </td>
                          <td class="py-4 px-6"><?php if ($order_status == 0) : ?>
                              <div class="badge badge-error gap-2">Unpaid</div>
                            <?php else : ?>
                              <div class="badge badge-success gap-2">Paid</div>
                            <?php endif; ?>
                          </td>
                          <td class="py-4 px-6">
                            <form action="CRUD-order.php" method="POST">
                              <input type="hidden" name="order_id" value="<?= $order_id ?>">
                              <button type="submit" name="delete-order-data" class="btn btn-error btn-xs my-2">Cancel</button>
                            </form>
                          </td>
                        </tr>
                      <?php
                      }
                    }
                    if (!$items_found) {
                      ?>
                      <tr>
                        <td colspan="7" class="text-center py-4 px-6">No items ordered today</td>
                      </tr>
                    <?php
                    }
                    ?>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- Footer  -->
          <div class="pt-5 flex justify-center">
            <p class="text-gray-400">Ordine</p>
          </div>
        </div>
      </div>
      <!-- end of Dashboard  -->
    </div>
    <!-- end of component -->
  <?php else : ?>
    <div class="hero min-h-screen bg-base-200">
      <div class="hero-content text-center">
        <div class="max-w-md">
          <h1 class="text-5xl font-bold">SESSION DOESNT MATCH!</h1>
          <p class="py-6">Please login again.</p>
          <a class="btn btn-primary" href="login.php">Log in</a>
          <a class="btn btn-primary" href="register.php">Get Started</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
</body>

</html>

<?php
function checkClockInStatus($employee_id, $mysqli)
{
  $checkClockInQuery = "SELECT attendance_id FROM attendance WHERE employee_id = $employee_id AND date = CURDATE()";
  $checkClockInResult = mysqli_query($mysqli, $checkClockInQuery);

  if (mysqli_num_rows($checkClockInResult) > 0) {
    return 'disabled';
  } else {
    return '';
  }
}

function checkClockOutStatus($employee_id, $mysqli)
{
  $checkClockOutQuery = "SELECT attendance_id FROM attendance WHERE employee_id = $employee_id AND date = CURDATE() AND clock_out IS NOT NULL";
  $checkClockOutResult = mysqli_query($mysqli, $checkClockOutQuery);

  if (mysqli_num_rows($checkClockOutResult) > 0) {
    return 'disabled';
  } else {
    return '';
  }
}

function hasTakenAttendance($employee_id, $mysqli)
{
  $checkAttendanceQuery = "SELECT attendance_id FROM attendance WHERE employee_id = $employee_id AND date = CURDATE() AND clock_in IS NOT NULL AND clock_out IS NOT NULL";
  $checkAttendanceResult = mysqli_query($mysqli, $checkAttendanceQuery);

  return mysqli_num_rows($checkAttendanceResult) > 0;
}
?>