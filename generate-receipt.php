<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
$user_id = $_SESSION['user_id'];
$restaurant_id = getRestaurantId($user_id, $mysqli);
$restaurant_name = getRestaurantName($restaurant_id, $mysqli);
$restaurant_logo = getRestaurantlogo($restaurant_id, $mysqli);
if (isset($_SESSION["user_id"])) {

  $sql = "SELECT * FROM user
                WHERE user_id = {$_SESSION["user_id"]}";
  $result = $mysqli->query($sql);
  //GET ASSOCIATIVE ARRAY
  $user = $result->fetch_assoc();
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
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Menu</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="styles/output.css" />
  <link rel="stylesheet" type="text/css" href="styles/receipt.css" />
</head>

<body class="font-poppins bg-white flex justify-center ">
  <?php if (isset($user)) : ?>
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
                  $total = getTotal($order_id, $mysqli);
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