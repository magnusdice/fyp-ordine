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

if (isset($_POST['add_to_cart'])) {
  if (isset($_SESSION['cart'])) {
    $session_array_id = array_column($_SESSION['cart'], "item_id");

    if (!in_array($_GET['item_id'], $session_array_id)) {
      $session_array = array(
        'item_id' => $_GET['item_id'],
        "item_name" => $_POST['item_name'],
        "item_price" => $_POST['item_price'],
        "remarks" => empty($_POST['remarks']) ? 'None' : $_POST['remarks'],
        "quantity" => $_POST['quantity'],
        "table_id" => $_POST['table_id']
      );

      $_SESSION['cart'][] = $session_array;
    }
  } else {
    $session_array = array(
      'item_id' => $_GET['item_id'],
      "item_name" => $_POST['item_name'],
      "item_price" => $_POST['item_price'],
      "remarks" => empty($_POST['remarks']) ? 'None' : $_POST['remarks'],
      "quantity" => $_POST['quantity'],
      "table_id" => $_POST['table_id']
    );

    $_SESSION['cart'][] = $session_array;
  }
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
  <title>Take Orders</title>
  <link rel="stylesheet" href="styles/output.css" />
</head>

<body class="font-poppins bg-neutral">
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
              <a href="take-orders-tables.php"><svg class="fill-current text-white h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24">
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
      <div class="order-sidebar h-screen p-4" id="order-sidebar">
        <h2 class="bg-white rounded-lg px-2 py-2 text-lg font-bold mb-4 shadow-sm">Orders</h2>
        <h2 class="bg-white rounded-lg py-2 px-2 shadow-sm text-lg mb-4"><?php echo "Table Number: ", $table_number ?></h2>
        <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
          <?php
          //var_dump($_SESSION['cart']);
          $i = 0;
          $total = 0;
          $output = "";
          $output .= "
                <table class='table'>
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Name</th>
                  <th>Remarks</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Sub Total</th>
                  <th>Action</th>
                </tr>
                <thead>
                ";
          if (!empty($_SESSION['cart'])) {
            $output .= "
          <tbody>";
            foreach ($_SESSION['cart'] as $key => $value) {
              $i++;
              $output .= "
                  <tr>
                    <td>" . $i . "</td>
                    <td>" . $value['item_name'] . "</td>
                    <td>".$value['remarks']."</td>
                    <td>RM " . $value['item_price'] . "</td>
                    <td>" . $value['quantity'] . "</td>
                    <td>RM " . number_format($value['item_price'] * $value['quantity'], 2) . "</td>
                    <td>
                      <a class='btn btn-error btn-sm' href='take-orders-order.php?table_id=$table_id&action=remove&item_id=" . $value['item_id'] . "'>Remove</a>
                    </td>
                    </tr>
                  ";
              $total = $total + $value['quantity'] * $value['item_price'];
            }
            $output .= "
                  <tr>
                    <td colspan='3'></td>
                    <td></b>Total Price</b></td>
                    <td>RM " . number_format($total, 2) . "</td>
                    <td><a class='btn btn-error btn-sm' href='take-orders-order.php?table_id=$table_id&action=clearall'>Remove All</a></td>
                  </tr>
                  </tbody>
                  ";
          }
          if (empty($_SESSION['cart'])){
            $output ="<table class='table'>
            <thead>
            <tr>
              <th>No.</th>
              <th>Name</th>
              <th>Remarks</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Sub Total</th>
              <th>Action</th>
            </tr>
            <thead>";
          }

          $output .= "</table>";
          echo $output;
          ?>
          <?php
          if (isset($_GET['action'])) {
            if ($_GET['action'] == "clearall") {
              unset($_SESSION['cart']);
            }
            if ($_GET['action'] == "remove") {
              foreach ($_SESSION['cart'] as $key => $value) {
                if ($value['item_id'] == $_GET['item_id']) {
                  unset($_SESSION['cart'][$key]);
                }
              }
            }
          }
          ?>
        </div>
        <div>
          <form action="CRUD-order.php" method="POST">
            <input type="hidden" name="total_amount" value="<?php echo $total ?>">
            <input type="hidden" name="table_id" value="<?php echo $table_id ?>">
            <input type="hidden" name="restaurant_id" value="<?php echo $restaurant_id ?>">
            <input type="submit" class="btn btn-primary my-2" value="Confirm Order" name="confirm-order">
          </form>
        </div>
      </div>
    </div>
    <!-- Content  -->
    <main class="w-full overflow-x-hidden">
      <div class="flex justify-center items-center my-5">
        <ul class="steps steps-vertical lg:steps-horizontal flex-1 justify-between">
          <li class="step step-primary">Choose Table</li>
          <li class="step step-primary">Order</li>
        </ul>
      </div>
      <div class="flex justify-center px-6 sticky top-0 z-10 bg-neutral border-b-2">
        <div class="flex justify-center items-center my-5 join">
          <?php
          if ($restaurant_id === null) {
            echo "<script> alert('Restaurant not found for this user'); </script>";
          } else {
            $category_select_sql = "SELECT category_id, category_name FROM category WHERE restaurant_id = $restaurant_id ORDER BY category_id desc";
            $rows = mysqli_query($mysqli, $category_select_sql);
          ?>
            <?php
            foreach ($rows as $row) :
            ?>
              <button class="join-item btn btn-primary <?php if ($defaultCategory === $row["category_id"]) echo "btn-active"; ?>" data-category-id="<?php echo $row["category_id"] ?>"><?php echo $row["category_name"] ?></button>
          <?php endforeach;
          } ?>
        </div>
      </div>
      <div class="mx-auto ml-2 flex flex-wrap items-center justify-center gap-4">
        <?php
        if ($restaurant_id === null) {
          echo "<script> alert('Restaurant not found for this user'); </script>";
        } else {
          //i. is a common practice. I is an alias or short for table name
          $item_select_sql = "SELECT i.item_id, i.item_name,c.category_id, c.category_name, i.item_price, i.item_image 
                FROM item i
                LEFT JOIN category c ON i.category_id = c.category_id
                WHERE i.restaurant_id = $restaurant_id
                ORDER BY i.item_id DESC";
          $rows = mysqli_query($mysqli, $item_select_sql);

          while ($row = mysqli_fetch_array($rows)) { ?>
            <form action="take-orders-order.php?table_id=<?= $table_id ?>&item_id=<?= $row['item_id'] ?>" method="POST">
              <div class="card w-60 bg-base-100 shadow-sm my-4" data-category-id="<?php echo $row["category_id"] ?>">
                <figure><img class="object-cover w-60 h-40" src="upload-image-item/<?= $row["item_image"] ?>" alt="Item" /></figure>
                <div class="card-body">
                  <p><?= $row["item_name"]; ?></p>
                  <p>Price: RM <?= $row["item_price"]; ?></p>
                  <div class="card-actions justify-end">
                    <input type="hidden" name="item_id" value="<?= $row["item_id"]; ?>" />
                    <input type="hidden" name="table_id" value="<?= $table_id; ?>" />
                    <input type="hidden" name="item_name" value="<?= $row["item_name"]; ?>" />
                    <input type="hidden" name="item_price" value="<?= $row["item_price"]; ?>" />
                    <input type="number" class="input input-bordered input-sm w-full max-w-xs" name="quantity" placeholder="Quantity" min="1" required>
                    <input type="text" class="input input-bordered input-sm w-full max-w-xs" name="remarks" placeholder="Remarks">
                    <input type="submit" class="btn btn-primary btn-sm" name="add_to_cart" value="Order">
                  </div>
                </div>
              </div>
            </form>
          <?php
          }
          ?>
        <?php
        } ?>
      </div>
    </main>
    <!-- end of content  -->
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const buttons = document.querySelectorAll(".join-item");
      const itemsContainer = document.getElementById("items-container");

      buttons.forEach(function(button) {
        button.addEventListener("click", function() {
          const categoryId = button.getAttribute("data-category-id");
          filterItemsByCategory(categoryId);
          // Remove the 'btn-active' class from all buttons
          buttons.forEach(function(btn) {
            btn.classList.remove("btn-active");
          });
          // Add the 'btn-active' class to the clicked button
          button.classList.add("btn-active");
        });
      });

      function filterItemsByCategory(categoryId) {
        const allItems = document.querySelectorAll(".card");

        allItems.forEach(function(item) {
          const itemCategoryId = item.getAttribute("data-category-id");
          if (categoryId === itemCategoryId || categoryId === "all") {
            item.style.display = "block";
          } else {
            item.style.display = "none";
          }
        });
      }

      // Initial display of all items
      filterItemsByCategory("all");
    });
  </script>
</body>


</html>