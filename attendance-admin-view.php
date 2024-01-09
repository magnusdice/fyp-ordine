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
  <title>Menu Management</title>
  <link rel="stylesheet" href="styles/output.css" />
</head>

<body class="font-poppins bg-neutral">
  <!-- Header  -->
  <?php include 'header-admin.php'; ?>
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

        <div class="my-2 p-2 rounded-md bg-primary text-neutral">
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
    <!-- Content  -->
    <main class="w-full overflow-x-hidden">
      <div class="flex justify-between py-3 px-6 sticky top-0 z-10 bg-neutral border-b-2">
        <div></div>
        <div>
          <form method="GET" action="">
            <input name="selected_date" class="input" type="date" value="<?php echo $selected_date; ?>">
            <!-- ... Your existing form elements ... -->
            <button type="submit" class="btn btn-primary join-item">Search</button>
          </form>
        </div>
        <div></div>

      </div>
      <div class="overflow-x-auto">
        <?php
        if (isset($_GET['selected_date'])) {
          $selected_date = $_GET['selected_date'];
          $attendance_select_sql = "SELECT a.*, e.firstName, e.lastName, e.empImage, e.empRoles
          FROM attendance a
          INNER JOIN employee e ON a.employee_id = e.employee_id
          WHERE a.restaurant_id = $restaurant_id
          AND DATE(a.date) = '$selected_date'  -- Add this condition to filter by date
          ORDER BY date DESC";
        } else {
          $attendance_select_sql = "SELECT a.*, e.firstName, e.lastName, e.empImage, e.empRoles
          FROM attendance a
          INNER JOIN employee e ON a.employee_id = e.employee_id
          WHERE a.restaurant_id = $restaurant_id
          ORDER BY date DESC";
          //$selected_date = date('Y-m-d'); // Set a default date if no date is selected
        }
        $attendance_rows = mysqli_query($mysqli, $attendance_select_sql);
        $grouped_attendances = [];
        if (mysqli_num_rows($attendance_rows) > 0) {
        ?>
          <div>
            <?php
            foreach ($attendance_rows as $attendance) {
              $attendance_date = $attendance['date'];
              $grouped_attendances[$attendance_date][] = $attendance;
            }
            foreach ($grouped_attendances as $date => $attendances) {
            ?>
              <div>
                <p class="text-sm uppercase font-semibold my-3 mx-3">Date: <?= date('d F Y', strtotime($date)) ?></p>
                <hr>
                <table class="table table-fixed">
                  <!-- head -->
                  <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                      <th>Employee Name</th>
                      <th>Clock In</th>
                      <th>Clock Out</th>
                      <th>Status</th>
                      <th>Action</th>
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
                        <td>
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
                        <td><?= date("h:i a", strtotime($clock_in)) ?></td>
                        <td><?= date("h:i a", strtotime($clock_out)) ?></td>
                        <td>
                          <div class="badge badge-primary gap-2"><?= $start_status ?></div>
                          <div class="badge badge-primary gap-2"><?= $end_status ?></div>
                        </td>
                        <td>
                          <form action="CRUD-attendance.php" method="POST">
                            <input type="hidden" name="attendance_id" value="<?= $attendance_id ?>">
                            <button type="submit" name="delete-order-data" class="btn btn-error btn-xs my-2">Delete</button>
                          </form>
                        </td>
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            <?php }
            ?>
          </div>
        <?php
        } else {
        ?>
          <table class="table table-fixed">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <th>Employee Name</th>
                <th>Clock In</th>
                <th>Clock Out</th>
                <th>Status</th>
                <th>Action</th>
              </tr>

            </thead>
            <tbody>
              <tr>
                <td colspan="5" class="text-center">No Records Found</td>
              </tr>
            <?php
          }
            ?>
            </tbody>
          </table>
    </main>
    <!-- end of content  -->
  </div>
</body>

</html>