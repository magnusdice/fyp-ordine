<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
$user_id = $_SESSION['user_id'];
$restaurant_id = getRestaurantId($user_id, $mysqli);
$restaurant_name = getRestaurantName($restaurant_id, $mysqli);
$restaurant_logo = getRestaurantlogo($restaurant_id, $mysqli);
$restaurant_add = getRestaurantAdd($restaurant_id, $mysqli);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <title>Menu Management</title>
  <link rel="stylesheet" href="styles/output.css" />
  <link rel="stylesheet" type="text/css" href="styles/receipt.css" />
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

        <div class="my-2 p-2 rounded-md bg-primary text-neutral">
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
    <main class="flex w-full overflow-x-hidden">
      <div class="w-2/3">
        <div class="flex justify-center items-center">
          <form class="px-2 py-3 rounded-md" action="CRUD-receipt-settings.php" method="POST" enctype="multipart/form-data">
            <label for="paperwidth" class="label">
              <span class="label-text">Paper Width</span>
              <span class="label-text-alt">(mm)</span>
            </label>
            <input type="number" id="paperwidth" name="paperwidth" value="58" class="input input-bordered w-full max-w-xs">
            <label class="label">
              <span class="label-text-alt">The size depends on your printer, check the printer's manual</span>
            </label>
            <label for="logo_settings" class="label">Add Restaurant Logo</label>
            <input name="logo_settings" type="checkbox" class="toggle toggle-success" id="toggleRestaurantLogo" checked />

            <label for="name_settings" class="label">Add Restaurant Name</label>
            <input name="name_settings" type="checkbox" class="toggle toggle-success" id="toggleRestaurantName" checked />

            <label for="address_settings" class="label">Add Restaurant Address</label>
            <input name="address_settings" type="checkbox" class="toggle toggle-success" id="toggleRestaurantAddress" checked />

            <label for="custom_message_settings" class="label">Add Custom Message</label>
            <input name="custom_message_settings" type="checkbox" class="toggle toggle-success" id="toggleRestaurantCustomMessage" checked />
            <label for="custom_msg" class="label">Custom Message:</label>
            <textarea class="textarea textarea-bordered textarea-lg w-full max-w-xs" placeholder="Type here" required name="custom_msg" id="custom_msg"></textarea>
            <input type="submit" class="btn btn-primary" name="submit-settings" value="Save">
          </form>
        </div>
      </div>
      <div class="w-1/3 flex justify-center">
        <div>
          <p class="text-2xl font-bold my-3">The receipt will look like this!</p>
          <div id="content">
            <div id="editor" style="width: 48mm;" data-theme="light">
              <div id="restaurantLogo" class="restaurantLogo">
                <div class="logo-container">
                  <img src="upload-restaurant-logo/<?=$restaurant_logo?>" width="100" alt="Logo">

                </div>

              </div>
              <h2 id="restaurantName" class="restaurant-name"><?=$restaurant_name?></h2>
              <p id="restaurantAddress" class="address"><?=$restaurant_add?></p>
              <table class="table-style">
                <thead>
                  <tr>
                    <th>Q</th>
                    <th>Order Item</th>
                    <th>RM</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th>1</th>
                    <th>Item A</th>
                    <th>6.00</th>
                  </tr>
                  <tr>
                    <th>1</th>
                    <th>Item B</th>
                    <th>6.00</th>
                  </tr>
                  <tr>
                    <th>2</th>
                    <th>Item C</th>
                    <th>3.00</th>
                  </tr>
                  <tr>
                    <th>1</th>
                    <th>Item D</th>
                    <th>3.00</th>
                  </tr>
                  <tr>
                    <td colspan="1"></td>
                    <td>Total</td>
                    <td>RM 18</td>
                  </tr>
                  <tr>
                    <td colspan="1"></td>
                    <td>Discounted Price</td>
                    <td>RM 18</td>
                  </tr>

                  <tr>
                    <td colspan="1"></td>
                    <td>Paid Amount</td>
                    <td>RM 18</td>
                  </tr>
                  <tr>
                    <td colspan="1"></td>
                    <td>Changes</td>
                    <td>RM 0</td>
                  </tr>
                </tbody>
              </table>
              <p class="pay-method">Paid using QR</p>
              <p id="customMsg" class="custom-msg">Thank you! Come again!</p>
            </div>
          </div>
          <div class="flex justify-center my-5">
            <!-- <button class="btn btn-secondary" id="print" class="hidden-print">Test Print</button> -->
          </div>

        </div>
      </div>
    </main>
    <!-- end of content  -->
  </div>
  <script type="text/javascript">
    const printBtn = document.getElementById('print');

    printBtn.addEventListener('click', function() {
      print();
    });

    let widthInput = document.getElementById('paperwidth');
    let editor = document.getElementById('editor');
    let toggleRestaurantName = document.getElementById('toggleRestaurantName');
    let restaurantName = document.getElementById('restaurantName');
    let toggleRestaurantLogo = document.getElementById('toggleRestaurantLogo');
    let restaurantLogo = document.getElementById('restaurantLogo');
    let toggleRestaurantAddress = document.getElementById('toggleRestaurantAddress');
    let restaurantAddress = document.getElementById('restaurantAddress');
    let toggleRestaurantCustomMessage = document.getElementById('toggleRestaurantCustomMessage');
    let customMsg = document.getElementById('customMsg');

    function updateWidth(event) {
      editor.style["width"] = event.target.value + "mm";
    }
    widthInput.onchange = updateWidth;
    widthInput.onpaste = updateWidth;
    widthInput.onkeyup = updateWidth;

    function updateRestaurantNameState() {
      if (!toggleRestaurantName.checked) {
        restaurantName.style.display = 'none';
      } else {
        restaurantName.style.display = '';
      }
    }
    toggleRestaurantName.addEventListener('change', updateRestaurantNameState);
    updateRestaurantNameState();

    function updateRestaurantLogoState() {
      if (!toggleRestaurantLogo.checked) {
        restaurantLogo.style.display = 'none';
      } else {
        restaurantLogo.style.display = '';
      }
    }
    toggleRestaurantLogo.addEventListener('change', updateRestaurantLogoState);
    updateRestaurantLogoState();

    function updateRestaurantAddressState() {
      if (!toggleRestaurantAddress.checked) {
        restaurantAddress.style.display = 'none';
      } else {
        restaurantAddress.style.display = '';
      }
    }
    toggleRestaurantAddress.addEventListener('change', updateRestaurantAddressState);
    updateRestaurantAddressState();

    function updateRestaurantCustomMsg() {
      if (!toggleRestaurantCustomMessage.checked) {
        customMsg.style.display = 'none';
      } else {
        customMsg.style.display = '';
      }
    }
    toggleRestaurantCustomMessage.addEventListener('change', updateRestaurantCustomMsg);
    updateRestaurantCustomMsg();
  </script>
</body>

</html>