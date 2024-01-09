<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
$user_id = $_SESSION['user_id'];
$restaurant_id = getRestaurantId($user_id, $mysqli);
$user_type = getUserType($restaurant_id, $mysqli, $user_id);

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

$order_id = getOrderIdByTable($table_id, $mysqli);

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
              <a href="payment-table.php"><svg class="text-white fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24">
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

            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- Content  -->
    <main class="w-full overflow-x-hidden">
      <div class="flex justify-center items-center my-5">
        <ul class="steps steps-vertical lg:steps-horizontal flex-1 justify-between">
          <li class="step step-primary">Choose Table</li>
          <li class="step step-primary">Pay</li>
          <li class="step">Print Receipt</li>
        </ul>
      </div>
      <div class="flex flex-col justify-center items-center my-5">
        <div>
          <div id="originalTotal">
            <p class="text-3xl font-bold">Total Price:</p>
            <p class="text-5xl font-black">RM <?= number_format($total, 2) ?></p>
          </div>
          <div id="discountedTotal" style="display: none;">
            <p class="text-3xl font-bold">Total Price (after discount):</p>
            <p class="text-5xl font-black">RM <?= number_format($total, 2) ?></p>
          </div>

        </div>
        <div>
          <form action="CRUD-order.php" method="POST">
            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <input type="hidden" name="table_id" value="<?= $table_id ?>">
            <input type="hidden" name="restaurant_id" value="<?= $restaurant_id ?>">
            <div>
              <label class="label" for="amount_paid">Amount Paid</label>
              <input step=".01" required type="number" name="amount_paid" placeholder="0.00" class="input input-bordered input-lg w-full max-w-xs" />
            </div>
            <div>
              <label class="label" for="feedback">Feedback</label>
              <textarea class="textarea textarea-bordered textarea-md w-full max-w-xs" name="feedback" placeholder="Feedback"></textarea>
            </div>
            <div>
              <label class="label" for="amount_paid">Discount:</label>
              <select id="discountSelect" class="select select-bordered w-full max-w-xs">
                <option disabled selected>Choose Discount</option>
                <?php
                $discount_query = "SELECT * FROM promotion WHERE restaurant_id = '$restaurant_id'";
                $discount_query_result = $mysqli->query($discount_query);
                if ($discount_query_result->num_rows > 0) {
                  while ($row = $discount_query_result->fetch_assoc()) {
                    $promotion_id = $row['promotion_id'];
                    $promotionName = $row['promotionName'];
                    $discount = $row['discount'];
                    echo "<option value= '$promotion_id' data-discount='$discount'>$promotionName $discount%</option>";
                  }
                }
                ?>
                <option value="1">No Discount</option>
              </select>
            </div>
            <div>
              <input type="hidden" id="discountedAmount" name="after_discount" value="<?= $total ?>">
            </div>

            <div class="my-2">
              <input type="submit" value="Pay with QR" name="payQR" class="btn btn-secondary">
              <input type="submit" value="Pay with Card" name="payCard" class="btn btn-secondary">
              <input type="submit" value="Pay with Cash" name="payCash" class="btn btn-secondary">
            </div>
          </form>
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