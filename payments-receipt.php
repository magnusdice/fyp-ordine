<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
$user_id = $_SESSION['user_id'];
$restaurant_id = getRestaurantId($user_id, $mysqli);
$user_type = getUserType($restaurant_id, $mysqli, $user_id);
$restaurant_logo = getRestaurantlogo($restaurant_id, $mysqli);

if (isset($_GET['table_id'])) {
  $table_id = $_GET['table_id'];
  $table_query = "SELECT table_no FROM tables WHERE table_id = $table_id AND restaurant_id = $restaurant_id";
  $table_result = mysqli_query($mysqli, $table_query);
  if ($table_result && mysqli_num_rows($table_result) > 0) {
    $table_row = mysqli_fetch_assoc($table_result);
    $table_number = $table_row['table_no'];
    // Now you can use $table_number to display the table number in your page.
  } else {
    // Handle the case where the table_id doesn't exist or is not associated with the restaurant.
    echo "Table ID is invalid or not associated with the restaurant.";
  }
} else {
  echo "Table id missing";
}
if (isset($_GET['order_id'])) {
  // Retrieve the order_id value
  $order_id = $_GET['order_id'];

  // Use $order_id as needed in your code
} else {
  // Handle the case when order_id is not present in the URL
  echo "Order ID not found in the URL";
}

$invoice_query = "SELECT amount_paid, after_discount, payment_method, payment_time, payment_date FROM invoice WHERE order_id = $order_id";
$invoice_result = mysqli_query($mysqli, $invoice_query);

// Check if the invoice exists
if ($invoice_result && mysqli_num_rows($invoice_result) > 0) {
  $invoice_data = mysqli_fetch_assoc($invoice_result);
  $amount_paid = $invoice_data['amount_paid'];
  $after_discount = $invoice_data['after_discount'];
  $payment_method = $invoice_data['payment_method'];
  $payment_time = $invoice_data['payment_time'];
  $payment_date = $invoice_data['payment_date'];
  $changes = $after_discount - $amount_paid;
} else {
  // Handle the case where the invoice doesn't exist.
  echo "Invoice not found for the given order.";
}

$restaurant_name = getRestaurantName($restaurant_id, $mysqli);
$restaurant_address = getRestaurantAdd($restaurant_id, $mysqli);

$settings_query = "SELECT * FROM receipt_settings WHERE restaurant_id = $restaurant_id";
$settings_result = mysqli_query($mysqli, $settings_query);

if ($settings_result) {
  $settings = mysqli_fetch_assoc($settings_result);


  $display_name_settings = (bool) $settings['name_settings'];
  $display_address_settings = (bool) $settings['address_settings'];
  $display_logo_settings = (bool) $settings['logo_settings'];
  $display_custom_message_settings = (bool) $settings['custom_message_settings'];
}

?>
<!DOCTYPE html>
<html lang="en" data-theme="cupcake">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <title>Payments</title>
  <link rel="stylesheet" href="styles/output.css" />
  <link rel="stylesheet" type="text/css" href="styles/receipt.css" />
</head>

<body>
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
              <a href="payment-table.php"><svg class="fill-current h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24">
                  <path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z"></path>
                </svg></a>
            </li>
            <li>
              <a href="home-staff-page.php"><svg xmlns="http://www.w3.org/2000/svg" class="text-white h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg></a>
            </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
  <!-- header end -->
  <div class="flex flex-row h-screen">
    <!-- sidebar -->
    <div class="flex flex-col bg-gray-100 justify-between w-96 py-4 px-2 border-r-2 flex-none">
      <div class="order-sidebar h-screen p-2 shadow-lg" id="order-sidebar">
        <h2 class="bg-white rounded-lg p-2 text-lg font-bold mb-2 shadow-sm">Order ID <?= $order_id ?></h2>
        <h2 class="bg-white rounded-lg p-2 text-lg font-bold mb-2 shadow-sm"><?php echo "Table Number: ", $table_number ?></h2>
        <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
          <table class="table">
            <!-- head -->
            <thead>
              <tr>
                <th></th>
                <th>Ordered Items</th>
                <th>Quantity</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              $total = getTotal($order_id, $mysqli);
              $restaurant_id = getRestaurantId($user_id, $mysqli);
              if ($restaurant_id === null) {
                echo "<script> alert('Restaurant not found for this user'); </script>";
              } else {
                $order_select = "SELECT oi.*,i.item_name FROM order_item oi INNER JOIN item i ON oi.item_id=i.item_id WHERE oi.order_id = '$order_id'";
                $order_result = mysqli_query($mysqli, $order_select);
                foreach ($order_result as $row) :
                  $item_name = $row['item_name'];
                  $quantity = $row['quantity'];
                  $subtotal = $row['subtotal'];
              ?>
                  <tr>
                    <th><?= $i++ ?></th>
                    <td><?= $item_name ?></td>
                    <td>x<?= $quantity ?></td>
                    <td>RM <?= number_format($subtotal, 2) ?></td>
                  </tr>
              <?php endforeach;
              } ?>
              <tr>
                <td colspan="2"></td>
                <td>Total Price</td>
                <td>RM <?= number_format($total, 2) ?></td>
              </tr>
              <tr>
                <td colspan="2"></td>
                <td>Discounted Price</td>
                <td>RM <?= number_format($after_discount, 2) ?></td>
              </tr>
              <tr>
                <td colspan="2"></td>
                <td>Paid Amount</td>
                <td>RM <?= number_format($amount_paid, 2) ?></td>
              </tr>
              <tr>
                <td colspan="2"></td>
                <td>Changes</td>
                <td>RM <?= number_format($changes, 2) ?></td>
              </tr>

            </tbody>
          </table>
          <div class="flex justify-end my-10">
            <button onclick="window.print();" class="btn btn-primary mx-2">Print</button>
            <a href="home-staff-page.php" class="btn btn-primary">Home</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Content  -->
    <main class="w-full overflow-x-hidden">
      <div class="flex justify-center items-center my-5">
        <ul class="steps steps-vertical lg:steps-horizontal flex-1 justify-between">
          <li class="step step-primary">Choose Table</li>
          <li class="step step-primary">Pay</li>
          <li class="step step-primary">Print Receipt</li>
        </ul>
      </div>
      <div class="flex justify-center items-center">
        <div>
          <style>
            @media print {

              /* Hide other element */
              body * {
                visibility: hidden;
              }

              /* Display Print Container */
              .print-container,
              .print-container * {
                visibility: visible;
              }

              .print-container {
                position: absolute;
                left: 0px;
                top: 0px;
              }
            }
          </style>
          <div id="editor" class="print-container" data-theme="light">
            <div style="width: <?= $settings['paperwidth'] ?>;">
              <?php if ($display_logo_settings) : ?>
                <div id="restaurantLogo" class="restaurantLogo">
                  <div class="logo-container">
                    <img width="100" src="upload-restaurant-logo/<?= $restaurant_logo ?>" alt="Logo">
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <?php if ($display_name_settings) : ?>
              <h2 id="restaurantName" class="restaurant-name"><?= $restaurant_name ?></h2>
            <?php endif; ?>

            <?php if ($display_address_settings) : ?>
              <p id="restaurantAddress" class="address"><?= $restaurant_address ?></p>
            <?php endif; ?>

            <table class="table-style">
              <thead>
                <tr>
                  <th>Q</th>
                  <th>Order Items</th>
                  <th>RM</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $order_select = "SELECT oi.*, i.item_name FROM order_item oi INNER JOIN item i ON oi.item_id = i.item_id WHERE oi.order_id = '$order_id'";
                $order_result = mysqli_query($mysqli, $order_select);
                foreach ($order_result as $row) :
                  $item_name = $row['item_name'];
                  $quantity = $row['quantity'];
                  $subtotal = $row['subtotal'];
                ?>
                  <tr>
                    <th><?= $quantity ?></th>
                    <th><?= $item_name ?></th>
                    <th><?= number_format($subtotal, 2) ?></th>
                  </tr>
                <?php endforeach; ?>
                <tr>
                  <td colspan="1"></td>
                  <td>Total</td>
                  <td>RM <?= number_format($total, 2) ?></td>
                </tr>
                <tr>
                  <td colspan="1"></td>
                  <td>Discounted Price</td>
                  <td>RM <?= number_format($after_discount, 2) ?></td>
                </tr>

                <tr>
                  <td colspan="1"></td>
                  <td>Paid Amount</td>
                  <td>RM <?= number_format($amount_paid, 2) ?></td>
                </tr>
                <tr>
                  <td colspan="1"></td>
                  <td>Changes</td>
                  <td>RM <?= number_format($changes, 2) ?></td>
                </tr>
              </tbody>
            </table>
            <p class="pay-method"><?= $payment_method ?></p>
            <?php if ($display_custom_message_settings) : ?>
              <p id="customMsg" class="custom-msg"><?= $settings['custom_msg'] ?></p>
            <?php endif; ?>
            <p class="paymentdate"><?= $payment_date ?> <?= $payment_time ?></p>
          </div>
        </div>
      </div>
    </main>
    <!-- end of content  -->
  </div>
  <script>
    const discountSelect = document.querySelector("#discountSelect");
    const originalTotalDisplay = document.getElementById("originalTotal");
    const discountedTotalDisplay = document.getElementById("discountedTotal");

    function updateDiscountedTotal() {
      const selectedOption = discountSelect.options[discountSelect.selectedIndex];
      //const selectedDiscount = selectedOption.dataset.discount;
      const selectedDiscount = selectedOption.getAttribute("data-discount");

      // Check if a discount is selected
      if (selectedDiscount) {
        const total = <?= $total ?>; // Get the original total from PHP
        const discountedTotal = total - (total * selectedDiscount / 100);
        document.getElementById("discountedAmount").value = discountedTotal.toFixed(2);
        originalTotalDisplay.style.display = "none";
        discountedTotalDisplay.style.display = "block";
        discountedTotalDisplay.querySelector('.text-5xl').textContent = `RM ${discountedTotal.toFixed(2)}`;
      } else {
        document.getElementById("discountedAmount").value = <?= $total ?>;
        originalTotalDisplay.style.display = "block";
        discountedTotalDisplay.style.display = "none";

      }
    }
    // Event listener for the discount select element
    discountSelect.addEventListener("change", updateDiscountedTotal);

    // Initial update based on the default selected option
    updateDiscountedTotal();
  </script>
</body>

</html>