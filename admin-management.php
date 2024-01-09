<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
$user_id = $_SESSION['user_id'];
if (isset($_SESSION["user_id"])) {

  $sql = "SELECT * FROM user
              WHERE user_id = {$_SESSION["user_id"]}";
  $result = $mysqli->query($sql);
  //GET ASSOCIATIVE ARRAY
  $user = $result->fetch_assoc();
}
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
<?php if (isset($user)) : ?>
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

        <div class="my-2 p-2 rounded-md bg-primary text-neutral">
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
    <!-- Content  -->
    <main class="w-full overflow-x-hidden">
      <div class="flex justify-between py-3 px-6 sticky top-0 z-10 bg-neutral border-b-2">
        <div></div>
        <form method="GET" action="">
          <div class="join">
            <div>
              <input name="search" type="text" value="<?php if (isset($_GET['search'])) {
                                                        echo $_GET['search'];
                                                      } ?>" class="input input-bordered w-80 join-item" placeholder="Search" />
            </div>
            <div class="indicator">
              <button type="submit" class="btn btn-primary join-item">Search</button>
            </div>
          </div>
        </form>
        <div>
          <button class="btn btn-primary" onclick="my_modal_1.showModal()">Add Admin</button>
          <dialog id="my_modal_1" class="modal">
            <div class="modal-box w-auto">
              <h3 class="font-bold text-lg">Add Admin</h3>
              <form action="CRUD-admin-functions.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="restaurant_id" value="<?= $restaurant_id ?>">
                <label for="email" class="label">Email:</label>
                <input required name="email" id="email" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

                <label for="password" class="label">Password:</label>
                <input required name="password" id="password" type="password" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

                <label for="confirm-pass" class="label">Confirm Password:</label>
                <input required name="confirm-pass" id="confirm-pass" type="password" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

                <div class=" my-2 text-left">
                  <input type="submit" value="Add" class="btn btn-success" name="add-admin-button">
                  
              </form>
              
              </div>
              <div>
                <form method="dialog">
                  <!-- if there is a button in form, it will close the modal -->
                  <button class="btn">Close</button>
                </form>
              </div>
            </div>
          </dialog>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="table">
          <!-- head -->
          <thead>
            <tr>
              <th>No</th>
              <th>Admin's Email</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>

            <?php
            $i = 1;
            if ($restaurant_id === null) {
              echo "<script> alert('Restaurant not found for this user'); </script>";
            } else {
              if (isset($_GET['search'])) {
                $filtervalues = $_GET['search'];
                //i. is a common practice. I is an alias or short for table name
                $admin_search_sql = "SELECT u.user_id, u.email, u.password_hash, u.user_type, u.restaurant_id
                                    FROM user u
                                    WHERE u.restaurant_id = $restaurant_id AND u.user_type = 'admin' AND CONCAT(u.email)
                                    LIKE '%$filtervalues%'";
                $admin_search_sql_result = mysqli_query($mysqli, $admin_search_sql);
                if (mysqli_num_rows($admin_search_sql_result) > 0) {
                  foreach ($admin_search_sql_result as $row) {
            ?>
                    <tr class="hover">
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $row["email"]; ?></td>
                      <th>
                        <form class="form-control" action="CRUD-admin-functions.php" method="POST">
                          <input type="hidden" name="user_id" value="<?php echo $row["user_id"]; ?>">
                          <button type="submit" name="delete-admin-data" class="btn btn-error btn-xs" <?php echo ($row["user_id"] == $user_id) ? "disabled" : ""; ?>>Delete</button>
                        </form>
                      </th>
                    </tr>
                  <?php
                  }
                } else {
                  ?>
                  <tr>
                    <td colspan="4">No Record Found</td>
                  </tr>
                  <?php
                }
              } else {
                $admin_all_sql = "SELECT u.user_id, u.email, u.password_hash, u.user_type, u.restaurant_id
                                    FROM user u
                                    WHERE u.restaurant_id = $restaurant_id AND u.user_type = 'admin'";
                $admin_all_sql_result = mysqli_query($mysqli, $admin_all_sql);
                if (mysqli_num_rows($admin_all_sql_result) > 0) {
                  foreach ($admin_all_sql_result  as $row) {
                  ?>
                    <tr class="hover">
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $row["email"]; ?></td>
                      <td>
                        <form action="CRUD-admin-functions.php" method="POST">
                          <input type="hidden" name="user_id" value="<?php echo $row["user_id"]; ?>">
                          <button type="submit" name="delete-admin-data" class="btn btn-error btn-xs" <?php echo ($row["user_id"] == $user_id) ? "disabled" : ""; ?>>Delete</button>
                        </form>
                      </td>
                    </tr>
                  <?php
                  }
                } else {
                  ?>
                  <tr>
                    <td colspan="4">No Records Found</td>
                  </tr>
              <?php
                }
              }
              ?>
          </tbody>
        <?php
            } ?>
        </table>
      </div>
    </main>
    <!-- end of content  -->
  </div>
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