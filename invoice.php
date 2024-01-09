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
  <title>Menu Management</title>
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

        <div class="my-2 p-2 rounded-md bg-primary text-neutral">
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
        <div></div>
        <form method="GET" action="">
          <div class="join">
            <div>
              <input name="search" type="text" value="<?php if (isset($_GET['search'])) {
                                                        echo $_GET['search'];
                                                      } ?>" class="input input-bordered w-80 join-item" placeholder="Invoice ID" />
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
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="table">
          <!-- head -->
          <thead>
            <tr>
              <th>Invoice ID</th>
              <th>Order No</th>
              <th>Payment Type</th>
              <th>Payment Time</th>
              <th>Payment Date</th>
              <th>Total Price</th>
              <th>Amount Paid</th>
              <td>Feedback</td>
              <th>Action</th>
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

            $invoice_select_sql = isset($_GET['search'])
              ? "SELECT * FROM invoice WHERE restaurant_id = $restaurant_id AND CONCAT(invoice_id) LIKE '%{$filtervalues}%'"
              : "SELECT * FROM invoice WHERE restaurant_id = $restaurant_id ";

            if (!empty($filter_start_date) && !empty($filter_end_date)) {
              $invoice_select_sql .= " AND payment_date BETWEEN '$filter_start_date' AND '$filter_end_date' ";
            }
            $invoice_select_sql .= " ORDER BY payment_date DESC LIMIT 15";
            $invoice_rows = mysqli_query($mysqli, $invoice_select_sql);
            if (mysqli_num_rows($invoice_rows) > 0) {
              foreach ($invoice_rows as $invoice) {
                $payment_date = $invoice['payment_date'];
                $order_id = $invoice['order_id'];
                $invoice_id = $invoice['invoice_id'];
                $payment_time = $invoice['payment_time'];
                $payment_method = $invoice['payment_method'];
                $after_discount = $invoice['after_discount'];
                $amount_paid = $invoice['amount_paid'];
                $feedback = $invoice['feedback'];
            ?>
                <tr class="hover">
                  <td><?= $invoice_id ?></td>
                  <td><?= $order_id ?></td>
                  <td><?= $payment_method ?></td>
                  <td><?= date("h:i a", strtotime($payment_time))  ?></td>
                  <td><?= date('d F Y', strtotime($payment_date)) ?></td>
                  <td>RM <?= number_format($after_discount, 2) ?></td>
                  <td>RM <?= number_format($amount_paid, 2) ?></td>
                  <td>
                    <?php if ($feedback == null) : ?>
                      <p>No feedback given</p>
                    <?php else : ?>
                      <?= $feedback ?>
                    <?php endif; ?>
                  </td>
                  <td>
                    <iframe id="frame" src="generate-receipt.php?order_id=<?= $order_id ?>" style="width:0; border:0; height:0;" frameborder="0"></iframe>
                    <button class="btn btn-primary btn-xs" id="btn-print">Print</button>
                  </td>
                </tr>
              <?php
              }
            } else {
              ?>
              <tr>
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
  <script>
    const printBtn = document.getElementById('btn-print');

    printBtn.addEventListener('click', function() {
      let wspFrame = document.getElementById('frame').contentWindow;
      wspFrame.focus();
      wspFrame.print();
    });
  </script>
</body>

</html>