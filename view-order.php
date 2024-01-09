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
?>
<!DOCTYPE html>
<html lang="en" data-theme="cupcake">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <title>View Order</title>
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
        <div class="my-2 p-2 rounded-md bg-primary text-neutral">
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
      <div class="flex justify-between py-3 px-6 sticky top-0 z-10 bg-neutral border-b-2">
        <div>
        </div>
        <form method="GET" action="">
          <div class="join">
            <div>
              <input name="search" type="text" value="<?php if (isset($_GET['search'])) {
                                                        echo $_GET['search'];
                                                      } ?>" class="input input-bordered w-80 join-item" placeholder="Order No" />
            </div>
            <div class="indicator">
              <button type="submit" class="btn btn-primary join-item">Search</button>
            </div>
          </div>
        </form>
        <form method="GET" action="">
          <div class="join">
            <div>
              <input name="start_date" type="date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" class="input input-bordered w-80 join-item" placeholder="Start Date" />
            </div>
            <div>
              <input name="end_date" type="date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" class="input input-bordered w-80 join-item" placeholder="End Date" />
            </div>
            <div>
              <button type="submit" class="btn btn-primary join-item">Filter by Date</button>
            </div>
          </div>
        </form>
        <div>
          <a href="take-orders-tables.php" class="btn btn-primary">Add Order</a>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="table w-full text-sm text-center text-gray-500">
          <!-- head -->
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
            $filtervalues = '';
            if (isset($_GET['search'])) {
              $filtervalues = $_GET['search'];
            }

            $filter_start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
            $filter_end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

            $order_select_sql = isset($_GET['search'])
              ? "SELECT * FROM orders WHERE restaurant_id = $restaurant_id AND CONCAT(order_id) LIKE '%{$filtervalues}%'"
              : "SELECT * FROM orders WHERE restaurant_id = $restaurant_id ";

            if (!empty($filter_start_date) && !empty($filter_end_date)) {
              $order_select_sql .= " AND order_date BETWEEN '$filter_start_date' AND '$filter_end_date' ";
            }

            $order_select_sql .= " ORDER BY order_date DESC";
            $order_rows = mysqli_query($mysqli, $order_select_sql);
            if (mysqli_num_rows($order_rows) > 0) {
              foreach ($order_rows as $order) {
                $order_date = $order["order_date"];
                $order_id = $order["order_id"];
                $table_id = $order["table_id"];
                $order_time = $order["order_time"];
                $order_status = $order["order_status"];
                $table_no = getTableNo($table_id, $restaurant_id, $mysqli);
            ?>
                <tr>
                  <td><?= $order_id ?></td>
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
            } else {
              ?>
              <tr>
                <!-- <td colspan="7" class="text-center">All orders has been paid</td> -->
                <td colspan="9">No Records Found</td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </main>
    <!-- end of content  -->
  </div>
</body>

</html>