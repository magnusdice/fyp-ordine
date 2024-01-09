<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
if (isset($_SESSION["user_id"])) {

  $sql = "SELECT * FROM user
              WHERE user_id = {$_SESSION["user_id"]}";
  $result = $mysqli->query($sql);
  //GET ASSOCIATIVE ARRAY
  $user = $result->fetch_assoc();
}
$user_id = $_SESSION["user_id"];
$restaurant_id = getRestaurantId($user_id, $mysqli);
$restaurant_name = getRestaurantName($restaurant_id, $mysqli);
$restaurant_logo = getRestaurantlogo($restaurant_id, $mysqli);

//Stats This Months
$firstDayOfMonth = date('Y-m-01');
$lastDayOfMonth = date('Y-m-t');
$totalSalesQuery = "SELECT SUM(amount_paid) AS totalSales FROM invoice
                   WHERE restaurant_id = $restaurant_id
                   AND payment_date BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth'";

$totalSalesResult = $mysqli->query($totalSalesQuery);
$totalSalesRow = $totalSalesResult->fetch_assoc();
$totalSales = $totalSalesRow['totalSales'];

//Stats Last Month
$firstDayOfLastMonth = date('Y-m-d', strtotime('first day of last month'));
$lastDayOfLastMonth = date('Y-m-d', strtotime('last day of last month'));
$totalSalesLastMonthQuery = "SELECT SUM(amount_paid) AS totalSalesLastMonth FROM invoice
                             WHERE restaurant_id = $restaurant_id
                             AND payment_date BETWEEN '$firstDayOfLastMonth' AND '$lastDayOfLastMonth'";

$totalSalesLastMonthResult = $mysqli->query($totalSalesLastMonthQuery);
$totalSalesLastMonthRow = $totalSalesLastMonthResult->fetch_assoc();
$totalSalesLastMonth = $totalSalesLastMonthRow['totalSalesLastMonth'];

//Changes Last Month and This Month
$percentageChange = 0;
$differenceInSales = 0;

if ($totalSalesLastMonth > 0) {
  $differenceInSales = $totalSales - $totalSalesLastMonth;
  $percentageChange = (($totalSales - $totalSalesLastMonth) / $totalSalesLastMonth) * 100;
}

//Total Sales Today
$todayDate = date('Y-m-d');
$todaySalesQuery = "SELECT SUM(amount_paid) AS todaySales FROM invoice
                   WHERE restaurant_id = $restaurant_id
                   AND payment_date = '$todayDate'";

$todaySalesResult = $mysqli->query($todaySalesQuery);
$todaySalesRow = $todaySalesResult->fetch_assoc();
$todaySales = $todaySalesRow['todaySales'];

// Yesterday
$yesterdayDate = date('Y-m-d', strtotime('-1 day'));

// SQL query to get yesterday's sales
$yesterdaySalesQuery = "SELECT SUM(amount_paid) AS yesterdaySales FROM invoice
                       WHERE restaurant_id = $restaurant_id
                       AND payment_date = '$yesterdayDate'";

$yesterdaySalesResult = $mysqli->query($yesterdaySalesQuery);
$yesterdaySalesRow = $yesterdaySalesResult->fetch_assoc();
$yesterdaySales = $yesterdaySalesRow['yesterdaySales'];

// Changes yesterday and today
$percentageChangeDaily = 0;
$differenceInSalesDaily = 0;
if ($yesterdaySales > 0) {
  $differenceInSalesDaily = $todaySales - $yesterdaySales;
  $percentageChangeDaily = (($todaySales - $yesterdaySales) / $yesterdaySales) * 100;
}

// Changes yesteday and the day before yesterday
$dayBeforeYesterdayDate = date('Y-m-d', strtotime('-2 days'));
$dayBeforeYesterdaySalesQuery = "SELECT SUM(amount_paid) AS dayBeforeYesterdaySales FROM invoice
                                 WHERE restaurant_id = $restaurant_id
                                 AND payment_date = '$dayBeforeYesterdayDate'";

$dayBeforeYesterdaySalesResult = $mysqli->query($dayBeforeYesterdaySalesQuery);
$dayBeforeYesterdaySalesRow = $dayBeforeYesterdaySalesResult->fetch_assoc();
$dayBeforeYesterdaySales = $dayBeforeYesterdaySalesRow['dayBeforeYesterdaySales'];
$percentageChangeYesterday = 0;
$differenceInSalesYesterday = 0;

if ($dayBeforeYesterdaySales > 0) {
  $differenceInSalesYesterday = $yesterdaySales - $dayBeforeYesterdaySales;
  $percentageChangeYesterday = (($yesterdaySales - $dayBeforeYesterdaySales) / $dayBeforeYesterdaySales) * 100;
}

//Changes last month and the last last month

$firstDayOfMonthBeforeLast = date('Y-m-d', strtotime('first day of last month', strtotime('-1 month')));
$lastDayOfMonthBeforeLast = date('Y-m-d', strtotime('last day of last month', strtotime('-1 month')));

$monthBeforeLastSalesQuery = "SELECT SUM(amount_paid) AS monthBeforeLastSales FROM invoice
                              WHERE restaurant_id = $restaurant_id
                              AND payment_date BETWEEN '$firstDayOfMonthBeforeLast' AND '$lastDayOfMonthBeforeLast'";

$monthBeforeLastSalesResult = $mysqli->query($monthBeforeLastSalesQuery);
$monthBeforeLastSalesRow = $monthBeforeLastSalesResult->fetch_assoc();
$monthBeforeLastSales = $monthBeforeLastSalesRow['monthBeforeLastSales'];
$percentageChangeLastMonth = 0;
$differenceInSalesLastMonth = 0;

if ($monthBeforeLastSales > 0) {
  $differenceInSalesLastMonth = $totalSalesLastMonth - $monthBeforeLastSales;
  $percentageChangeLastMonth = (($totalSalesLastMonth - $monthBeforeLastSales) / $monthBeforeLastSales) * 100;
}

//MOST ORDRED THIS MONTH
$mostOrderedItemQuery = "SELECT i.item_name, SUM(oi.quantity) AS totalOrders
                        FROM order_item oi
                        INNER JOIN item i ON oi.item_id = i.item_id
                        INNER JOIN orders o ON oi.order_id = o.order_id
                        WHERE o.restaurant_id = $restaurant_id
                        AND oi.restaurant_id = $restaurant_id
                        AND o.order_date BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth'
                        GROUP BY i.item_id
                        ORDER BY totalOrders DESC
                        LIMIT 1";

$mostOrderedItemResult = $mysqli->query($mostOrderedItemQuery);
$mostOrderedItemRow = $mostOrderedItemResult->fetch_assoc();

//YEARLY SALES
$firstDayOfYear = date('Y-01-01');
$lastDayOfYear = date('Y-12-t');

// SQL query to get the yearly sales
$yearlySalesQuery = "SELECT SUM(amount_paid) AS yearlySales FROM invoice
                     WHERE restaurant_id = $restaurant_id
                     AND payment_date BETWEEN '$firstDayOfYear' AND '$lastDayOfYear'";

$yearlySalesResult = $mysqli->query($yearlySalesQuery);
$yearlySalesRow = $yearlySalesResult->fetch_assoc();
$yearlySales = $yearlySalesRow['yearlySales'];

// Calculate the first and last day of the last year
$firstDayOfLastYear = date('Y-01-01', strtotime('-1 year'));
$lastDayOfLastYear = date('Y-12-t', strtotime('-1 year'));

// SQL query to get the sales from last year
$lastYearSalesQuery = "SELECT SUM(amount_paid) AS lastYearSales FROM invoice
                       WHERE restaurant_id = $restaurant_id
                       AND payment_date BETWEEN '$firstDayOfLastYear' AND '$lastDayOfLastYear'";

$lastYearSalesResult = $mysqli->query($lastYearSalesQuery);
$lastYearSalesRow = $lastYearSalesResult->fetch_assoc();
$lastYearSales = $lastYearSalesRow['lastYearSales'];

// Calculate the percentage increase or decrease and the difference in sales for the yearly and last year
$percentageChangeYearly = 0;
$differenceInSalesYearly = 0;

// Check if there are sales data for both the yearly and last year
if ($lastYearSales > 0) {
  $differenceInSalesYearly = $yearlySales - $lastYearSales;
  $percentageChangeYearly = (($yearlySales - $lastYearSales) / $lastYearSales) * 100;
}

// Fetch the top 5 ordered items
$topItemsQuery = "SELECT i.item_image, i.item_name, SUM(oi.quantity) AS totalOrders
                  FROM order_item oi
                  INNER JOIN item i ON oi.item_id = i.item_id
                  WHERE oi.restaurant_id = $restaurant_id
                  GROUP BY i.item_id
                  ORDER BY totalOrders DESC
                  LIMIT 5";

$topItemsResult = $mysqli->query($topItemsQuery);

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
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body class="font-poppins bg-neutral">
  <?php
  $allMonths = array(
    '01' => 'January',
    '02' => 'February',
    '03' => 'March',
    '04' => 'April',
    '05' => 'May',
    '06' => 'June',
    '07' => 'July',
    '08' => 'August',
    '09' => 'September',
    '10' => 'October',
    '11' => 'November',
    '12' => 'December'
  );
  //GRAPH FETCH//
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedYear = isset($_POST['selectedYear']) ? $_POST['selectedYear'] : date('Y');
  } else {
    // Set the default selected year to the current year if not set through POST
    $selectedYear = date('Y');
  }

  try {
    $sql = "SELECT 
      DATE_FORMAT(payment_date, '%m') AS month_num,
      SUM(amount_paid) AS totalSales
      FROM invoice
      WHERE restaurant_id = $restaurant_id
      AND YEAR(payment_date) = $selectedYear
      GROUP BY DATE_FORMAT(payment_date, '%m')";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
      $monthlySalesData = array();
      while ($row = $result->fetch_assoc()) {
        $monthNum = $row['month_num'];
        $monthName = $allMonths[$monthNum];
        $monthlySalesData[$monthName] = $row['totalSales'];
      }
      // Fill in sales data for months with no sales
      foreach ($allMonths as $monthNum => $monthName) {
        $monthYear = date('Y') . '-' . $monthNum;
        if (!isset($monthlySalesData[$monthName])) {
          // If no sales for the month, set total sales to 0
          $monthlySalesData[$monthName] = 0;
        }
      }
      //ksort($monthlySalesData);

      uksort($monthlySalesData, function ($a, $b) use ($allMonths) {
        $aNum = array_search($a, $allMonths);
        $bNum = array_search($b, $allMonths);
        return $aNum - $bNum;
      });
      unset($result);
    } else {
      echo "No records matching the query";
    }
  } catch (PDOException $e) {
    die("Error: Could not able to execute $sql. " . $e->getMessage());
  }


  ?>

  <?php if (isset($user)) : ?>
    <!-- header -->
    <?php include 'header-admin.php'; ?>
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
              <a href="home-admin-page.php" class="font-semibold">Dashboard</a>
            </div>
          </div>
          <div class="my-2 p-2 text-primary rounded-md hover:bg-primary hover:text-neutral">
            <div class="flex flex-row space-x-3">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
              </svg>
              <a href="menu-management.php" class="font-semibold">Manage Menu</a>
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
              <a href="cat-management.php" class="font-semibold">Manage Category</a>
            </div>
          </div>
          <div class="my-2 p-2 text-primary rounded-md hover:bg-primary hover:text-neutral">
            <div class="flex flex-row space-x-3">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-table">
                <path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"></path>
              </svg>
              <a href="table-management.php" class="font-semibold">Manage Table</a>
            </div>
          </div>

          <div class="my-2 p-2 text-primary rounded-md hover:bg-primary hover:text-neutral">
            <div class="flex flex-row space-x-3">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shield" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />
              </svg>
              <a href="admin-management.php" class="font-semibold">Manage Admin</a>
            </div>
          </div>

          <div class="my-2 p-2 text-primary rounded-md hover:bg-primary hover:text-neutral">
            <div class="flex flex-row space-x-3">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
              </svg>
              <a href="emp-management.php" class="font-semibold">Manage Staff</a>
            </div>
          </div>

          <div class="my-2 p-2 text-primary rounded-md hover:bg-primary hover:text-neutral">
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
              <a href="attendance-admin-view.php" class="font-semibold">Manage Attendance</a>
            </div>
          </div>

          <div class="my-2 p-2 text-primary rounded-md hover:bg-primary hover:text-neutral">
            <div class="flex flex-row space-x-3">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-discount" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M9 15l6 -6" />
                <circle cx="9.5" cy="9.5" r=".5" fill="currentColor" />
                <circle cx="14.5" cy="14.5" r=".5" fill="currentColor" />
                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
              </svg>
              <a href="promotion-management.php" class="font-semibold">Manage Promotion</a>
            </div>
          </div>

          <div class="my-2 p-2 text-primary rounded-md hover:bg-primary hover:text-neutral">
            <div class="flex flex-row space-x-3">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-receipt" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2" />
              </svg>
              <a href="receipt-management.php" class="font-semibold">Customize Receipt</a>
            </div>
          </div>
        </div>
      </div>
      <!-- Dashboard  -->
      <!-- <main class="grid min-h-full bg-white px-6 py-24 sm:py-32 lg:px-8">
      </main> -->
      <!-- stats -->
      <div class="bg-slate-200 h-screen w-full overflow-y-auto">
        <div class="p-8">
          <div class="grid grid-cols-1 md:cols-2 lg:grid-cols-4 gap-4 lg:gap-8">

            <div class="p-4 bg-white rounded-lg flex items-center h-32 shadow-sm">
              <div class="w-3/5 flex justify-start">
                <ul>
                  <li class="font-bold text-gray-400 upp">Today's Sales</li>
                  <li class="font-extrabold text-slate-800 text-xl">RM <?php echo number_format($todaySales, 2); ?></li>
                  <li><span class="text-gray-400 text-sm">Yesterday's Sales</span></li>
                  <?php if ($percentageChangeDaily != 0) : ?>
                    <div class="stat-desc font-bold <?php echo ($differenceInSalesDaily < 0) ? 'text-error' : 'text-success'; ?>">
                      <?php echo ($differenceInSalesDaily < 0) ? '↘︎' : '↗︎'; ?>
                      RM
                      <?php echo abs($differenceInSalesDaily) . ' (' . number_format($percentageChangeDaily, 2) . '%)'; ?>
                    </div>
                  <?php else : ?>
                    <div class="stat-desc">No change</div>
                  <?php endif; ?>
                </ul>
              </div>
              <div class="w-2/5 flex justify-end">
                <?php if ($percentageChangeDaily != 0) : ?>
                  <div class="rounded-full p-2 <?php echo ($differenceInSalesDaily < 0) ? 'bg-rose-500' : 'bg-success'; ?>">
                    <?php echo ($differenceInSalesDaily < 0) ? '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-down-right " width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7l10 10" /><path d="M17 8l0 9l-9 0" /></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-up-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 7l-10 10" /><path d="M8 7l9 0l0 9" /></svg>'; ?>
                  <?php else : ?>
                    =
                  <?php endif; ?>
                  </div>
              </div>
            </div>

            <div class="p-4 bg-white rounded-lg flex items-center h-32 shadow-sm">
              <div class="w-3/5 flex justify-start">
                <ul>
                  <li class="font-bold text-gray-400">Yesterday's Sales</li>
                  <li class="font-extrabold text-slate-800 text-xl">RM <?php echo number_format($yesterdaySales, 2); ?></li>
                  <li></li>
                </ul>
              </div>

            </div>

            <div class="p-4 bg-white rounded-lg flex items-center h-32 shadow-sm">
              <div class="w-3/5 flex justify-start">
                <ul>
                  <li class="font-bold text-gray-400"><?php echo date('F'); ?> Sales</li>
                  <li class="font-extrabold text-slate-800 text-xl">RM <?php echo number_format($totalSales, 2); ?></li>
                  <li><span class="text-gray-400 text-sm">Last Month</span></li>
                  <?php if ($percentageChange != 0) : ?>
                    <div class="stat-desc font-bold <?php echo ($differenceInSales < 0) ? 'text-error' : 'text-success'; ?>">
                      <?php echo ($differenceInSales < 0) ? '↘︎' : '↗︎'; ?>
                      RM
                      <?php echo abs($differenceInSales) . ' (' . number_format($percentageChange, 2) . '%)'; ?>
                    </div>
                  <?php else : ?>
                    <div class="stat-desc">No change</div>
                  <?php endif; ?>
                </ul>
              </div>
              <div class="w-2/5 flex justify-end">
                <?php if ($percentageChange != 0) : ?>
                  <div class="rounded-full p-2 <?php echo ($differenceInSales < 0) ? 'bg-rose-500' : 'bg-success'; ?>">
                    <?php echo ($differenceInSales < 0) ? '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-down-right " width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7l10 10" /><path d="M17 8l0 9l-9 0" /></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-up-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 7l-10 10" /><path d="M8 7l9 0l0 9" /></svg>'; ?>
                  <?php else : ?>
                    =
                  <?php endif; ?>
                  </div>
              </div>
            </div>

            <div class="p-4 bg-white rounded-lg flex items-center h-32 shadow-sm">
              <div class="w-3/5 flex justify-start">
                <ul>
                  <li class="font-bold text-gray-400">Annual Sales</li>
                  <li class="font-extrabold text-slate-800 text-xl">RM <?php echo number_format($yearlySales, 2); ?></li>
                  <li><span class="text-sm text-gray-400">Last Year Sales: </span><span class="text-gray-400"></span></li>
                  <li class="text-sm text-gray-400">RM <?php echo number_format($lastYearSales, 2); ?></li>
                </ul>
              </div>
              <div class="w-2/5 flex justify-end">
                <?php if ($percentageChangeYearly != 0) : ?>
                  <div class="rounded-full p-2 <?php echo ($differenceInSalesYearly < 0) ? 'bg-rose-500' : 'bg-success'; ?>">
                    <?php echo ($differenceInSalesYearly < 0) ? '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-down-right " width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7l10 10" /><path d="M17 8l0 9l-9 0" /></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-up-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 7l-10 10" /><path d="M8 7l9 0l0 9" /></svg>'; ?>
                  <?php else : ?>
                    =
                  <?php endif; ?>
                  </div>
              </div>
            </div>

            <!-- barchart -->
            <div class="p-4 bg-white rounded-lg shadow-sm lg:col-span-3">
              <div class="rounded-lg">
                <div class="flex-1">Bar Chart</div>
                <div class="rounded-md">
                  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="selectedYear">Select Year:</label>
                    <?php
                    // Assuming you have a database connection established
                    $query = "SELECT DISTINCT YEAR(payment_date) AS year FROM invoice";
                    $result = mysqli_query($mysqli, $query);

                    if ($result) {
                      echo '<select class="select-sm cursor-pointer select select-bordered" name="selectedYear" id="selectedYear" onchange="this.form.submit();">';
                      while ($row = mysqli_fetch_assoc($result)) {
                        $year = $row['year'];
                        echo "<option value='$year'";
                        if (isset($selectedYear) && $selectedYear == $year) {
                          echo "selected";
                        }
                        echo ">$year</option>";
                      }
                      echo '</select>';

                      // Free result set
                      mysqli_free_result($result);
                    } else {
                      echo "Error: " . mysqli_error($mysqli);
                    }
                    ?>
                  </form>
                </div>
                <canvas class="p-10" id="chartBar"></canvas>
              </div>
            </div>
            <!-- barchartends -->

            <div class="rounded-lg shadow-sm bg-white">
              <div class="overflow-x-auto relative sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                  <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                      <th class="py-3 px-6">Top 5 Ordered Items</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Loop through the top 5 ordered items and populate the table rows
                    while ($row = $topItemsResult->fetch_assoc()) {
                    ?>
                      <tr class="bg-white border-b items-center">
                        <th scope="row" class="py-7 px-6 font-medium text-gray-900 whitespace-nowrap">
                          <div class="flex items-center gap-3">
                            <div class="avatar">
                              <div class="mask mask-squircle w-12 h-12">
                                <img src="upload-image-item/<?php echo $row['item_image'] ?>" alt="N/A" />
                              </div>
                            </div>
                            <div>
                              <div class=""><?php echo $row['item_name']; ?></div>
                            </div>
                          </div>
                        </th>
                      </tr>
                    <?php
                    }
                    // Close the result set
                    mysqli_free_result($topItemsResult);
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- Tables -->
            <div class="rounded-lg shadow-sm bg-white md:col-span-2 lg:col-span-4">
              <?php
              $todayDate = date('Y-m-d');
              $attendance_select_sql = "SELECT a.*, e.firstName, e.lastName, e.empImage, e.empRoles FROM attendance a INNER JOIN employee e ON a.employee_id = e.employee_id WHERE a.restaurant_id = $restaurant_id AND date = '$todayDate' ORDER BY a.date DESC";
              $attendance_rows = mysqli_query($mysqli, $attendance_select_sql);
              $grouped_attendances = [];
              ?>
              <div class="overflow-x-auto relative sm:rounded-lg">
                <div>
                  <?php
                  if (mysqli_num_rows($attendance_rows) > 0) {
                    foreach ($attendance_rows as $attendance) {
                      $attendance_date = $attendance['date'];
                      $grouped_attendances[$attendance_date][] = $attendance;
                    }
                    foreach ($grouped_attendances as $date => $attendances) {
                  ?>
                      <p class="text-lg font-bold my-3 mx-3">Date: <?= date('d F Y', strtotime($date)) ?></p>
                      <table class="w-full text-sm text-left text-gray-500">
                        <!-- head -->
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                          <tr>
                            <th class="py-3 px-6">No</th>
                            <th class="py-3 px-6">Employee Name</th>
                            <th class="py-3 px-6">Clock In</th>
                            <th class="py-3 px-6">Clock Out</th>
                            <th class="py-3 px-6">Status</th>
                          </tr>
                        </thead>
                        <?php
                        $i = 1;
                        ?>
                        <tbody>
                          <?php
                          foreach ($attendances as $row) {
                            $fullName = $row["firstName"] . ' ' . $row["lastName"];
                            $clock_in = $row['clock_in'];
                            $clock_out = $row['clock_out'];
                            $start_status = $row['start_status'];
                            $end_status = $row['end_status'];
                          ?>
                            <tr class="hover">
                              <th scope="row" class="py-7 px-6 font-medium text-gray-900 whitespace-nowrap"><?= $i++; ?></th>
                              <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                  <div class="avatar">
                                    <div class="mask mask-squircle w-12 h-12">
                                      <img src="upload-image-employee/<?php echo $row["empImage"] ?>" alt="Staff's Image" />
                                    </div>
                                  </div>
                                  <div>
                                    <div class="font-bold"><?php echo $fullName; ?></div>
                                    <div class="text-sm opacity-50"><?php echo $row["empRoles"]; ?></div>
                                  </div>
                                </div>
                              </td>
                              <td class="py-4 px-6"><?= date("h:i a", strtotime($clock_in)) ?></td>
                              <td class="py-4 px-6"><?= date("h:i a", strtotime($clock_out)) ?></td>
                              <td class="py-4 px-6">
                                <div class="badge badge-primary gap-2"><?= $start_status ?></div>
                                <div class="badge badge-primary gap-2"><?= $end_status ?></div>
                              </td>
                            </tr>
                          <?php
                          }
                          ?>
                        </tbody>

                      </table>
                </div>
              <?php }
                  } else {
              ?>
              <p class="text-lg my-3 mx-3">No Records Found</p>
            <?php
                  }
            ?>
              </div>
            </div>
          </div>
          <!-- Footer  -->
          <div class="pt-5 flex justify-center">
            <p class="text-gray-400">Ordine</p>
          </div>
        </div>
      </div>
      <!-- stats ends -->
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

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    //Setup block
    const sales = <?php echo json_encode(array_values($monthlySalesData)); ?>;
    var months = <?php echo json_encode(array_keys($monthlySalesData)); ?>;
    var yearLabel = "Sales of Year <?php echo $selectedYear ?>";

    const data = {
      labels: months,
      datasets: [{
        label: yearLabel,
        data: sales,
        borderWidth: 1,
        backgroundColor: '#d8b4fe'
      }]
    };
    // Config Block
    const config = {
      type: 'bar',
      data,
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    };
    // Render Block
    const chartBar = new Chart(
      document.getElementById('chartBar'),
      config
    );

    // Add this script to trigger form submission when the select box value changes
    document.getElementById('selectedYear').addEventListener('change', function() {
      document.getElementById('chartContainer').querySelector('form').submit();
    });
  </script>
</body>

</html>