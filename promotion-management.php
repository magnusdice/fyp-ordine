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

        <div class="my-2 p-2 rounded-md bg-primary text-neutral">
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
          <!-- <a href="promo-add-page.php" class="btn btn-primary">Add Promotion</a> -->
          <a href="#my_modal_8" class="btn btn-primary">Add Promotion</a>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="table">
          <!-- head -->
          <thead>
            <tr>
              <th>No</th>
              <th>Promotion Name</th>
              <th>Date Start</th>
              <th>Date End</th>
              <th>Discount %</th>
              <th>Description</th>
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
                $promotion_search_sql = "SELECT p.promotion_id, p.promotionName, p.dateStart, p.dateEnd, p.discount, p.description ,p.restaurant_id
                                    FROM promotion p
                                    WHERE p.restaurant_id = $restaurant_id AND CONCAT(p.promotionName)
                                    LIKE '%$filtervalues%'";
                $promotion_search_sql_results = mysqli_query($mysqli, $promotion_search_sql);
                if (mysqli_num_rows($promotion_search_sql_results) > 0) {
                  foreach ($promotion_search_sql_results as $row) {
            ?>
                    <tr class="hover">
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $row["promotionName"]; ?></td>
                      <td><?php echo $row["dateStart"]; ?></td>
                      <td><?php echo $row["dateEnd"]; ?></td>
                      <td><?php echo $row["discount"]; ?></td>
                      <td><?php echo $row["description"]; ?></td>
                      <th>
                        <a href="#my_modal_7?promotion_id=<?php echo $row["promotion_id"] ?>" class="btn btn-xs btn-primary my-2">Edit</a>
                        <div class="modal" role="dialog" id="my_modal_7?promotion_id=<?php echo $row["promotion_id"] ?>">
                          <div class="modal-box">
                            <div class="flex justify-center items-center my-5">
                              <div class="form-control w-full max-w-xs">
                                <form action="CRUD-item-functions.php" method="POST">
                                  <input name="promotion_id" id="promotion_id" type="hidden" value="<?php echo $row['promotion_id'] ?>">

                                  <label for="promotionName" class="label">Promotion's Name:</label>
                                  <input value="<?php echo $row['promotionName'] ?>" required name="promotionName" id="promotionName" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

                                  <label for="dateStart" class="label">Date Start:</label>
                                  <input value="<?php echo $row['dateStart'] ?>" required name="dateStart" id="dateStart" type="date" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

                                  <label for="dateEnd" class="label">Date Ends:</label>
                                  <input value="<?php echo $row['dateEnd'] ?>" required name="dateEnd" id="dateEnd" type="date" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

                                  <label for="discount" class="label">Discount %:</label>
                                  <input value="<?php echo $row['discount'] ?>" required name="discount" id="discount" type="number" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

                                  <label for="description" class="label">Description:</label>
                                  <textarea class="textarea textarea-bordered textarea-lg w-full max-w-xs" placeholder="Type here" required name="description" id="description"><?php echo $row['description'] ?></textarea>

                                  <div class="mx-1 my-5 text-right">
                                    <input type="submit" value="Save" class="btn btn-success" name="update-promo-button">
                                    <a href="#" class="btn btn-error">Cancel</a>
                                  </div>
                                </form>
                              </div>
                            </div>

                          </div>
                        </div>
                        <form action="CRUD-item-functions.php" method="POST">
                          <input type="hidden" name="promotion_id" value="<?php echo $row["promotion_id"]; ?>">
                          <input type="hidden" name="restaurant_id" value="<?php echo $row["restaurant_id"]; ?>">
                          <button type="submit" name="delete-promo-data" class="btn btn-error btn-xs">Delete</button>
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
                $promotion_select_sql = "SELECT p.promotion_id, p.promotionName, p.dateStart, p.dateEnd, p.discount, p.description ,p.restaurant_id
                                    FROM promotion p
                                    WHERE p.restaurant_id = $restaurant_id
                                    ORDER BY p.promotion_id DESC";
                $rows = mysqli_query($mysqli, $promotion_select_sql);
                if (mysqli_num_rows($rows) > 0) {
                  foreach ($rows as $row) {
                  ?>
                    <tr class="hover">
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $row["promotionName"]; ?></td>
                      <td><?php echo $row["dateStart"]; ?></td>
                      <td><?php echo $row["dateEnd"]; ?></td>
                      <td><?php echo $row["discount"]; ?></td>
                      <td><?php echo $row["description"]; ?></td>
                      <th>

                        <a href="#my_modal_7?promotion_id=<?php echo $row["promotion_id"] ?>" class="btn btn-xs btn-primary my-2">Edit</a>
                        <div class="modal" role="dialog" id="my_modal_7?promotion_id=<?php echo $row["promotion_id"] ?>">
                          <div class="modal-box">
                            <div class="flex justify-center items-center my-5">
                              <div class="form-control w-full max-w-xs font-normal">
                                <form action="CRUD-item-functions.php" method="POST">
                                  <input name="promotion_id" id="promotion_id" type="hidden" value="<?php echo $row['promotion_id'] ?>">
                                  <label for="promotionName" class="label max-w-xs">Promotion's Name:</label>
                                  <input value="<?php echo $row['promotionName'] ?>" required name="promotionName" id="promotionName" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

                                  <label for="dateStart" class="label">Date Start:</label>
                                  <input value="<?php echo $row['dateStart'] ?>" required name="dateStart" id="dateStart" type="date" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

                                  <label for="dateEnd" class="label">Date Ends:</label>
                                  <input value="<?php echo $row['dateEnd'] ?>" required name="dateEnd" id="dateEnd" type="date" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

                                  <label for="discount" class="label">Discount %:</label>
                                  <input value="<?php echo $row['discount'] ?>" required name="discount" id="discount" type="number" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

                                  <label for="description" class="label">Description:</label>
                                  <textarea class="textarea textarea-bordered textarea-lg w-full max-w-xs" placeholder="Type here" required name="description" id="description"><?php echo $row['description'] ?></textarea>

                                  <div class="mx-1 my-5 text-left">
                                    <input type="submit" value="Save" class="btn btn-success" name="update-promo-button">
                                    <a href="#" class="btn btn-error">Cancel</a>
                                  </div>
                                </form>
                              </div>
                            </div>

                          </div>
                        </div>
                        <form action="CRUD-item-functions.php" method="POST">
                          <input type="hidden" name="promotion_id" value="<?php echo $row["promotion_id"]; ?>">
                          <input type="hidden" name="restaurant_id" value="<?php echo $row["restaurant_id"]; ?>">
                          <button type="submit" name="delete-promo-data" class="btn btn-error btn-xs">Delete</button>
                        </form>
                      </th>
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

  <div class="modal" role="dialog" id="my_modal_8">
    <div class="modal-box">
      <div class="flex justify-center items-center my-5">
        <div class="form-control w-full max-w-xs">
          <form action="CRUD-item-functions.php" method="POST">

            <label for="promotionName" class="label">Promotion's Name:</label>
            <input required name="promotionName" id="promotionName" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

            <label for="dateStart" class="label">Date Start:</label>
            <input required name="dateStart" id="dateStart" type="date" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

            <label for="dateEnd" class="label">Date Ends:</label>
            <input required name="dateEnd" id="dateEnd" type="date" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

            <label for="discount" class="label">Discount %:</label>
            <input required name="discount" id="discount" type="number" placeholder="Type here" class="input input-bordered w-full max-w-xs" />

            <label for="description" class="label">Description:</label>
            <textarea class="textarea textarea-bordered textarea-lg w-full max-w-xs" placeholder="Type here" required name="description" id="description"></textarea>


            <div class="mx-1 my-5 text-right">
              <input type="submit" value="Add" class="btn btn-success" name="add-promo-button">
              <a href="#" class="btn btn-error">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


</body>

</html>