<?php
session_start();
include 'functions.php';
$mysqli = require __DIR__ . "/database-connection.php";
$user_id = $_SESSION['user_id'];
$restaurant_id = getRestaurantId($user_id, $mysqli);
$restaurant_name = getRestaurantName($restaurant_id, $mysqli);
if (isset($_SESSION["user_id"])) {

  $sql = "SELECT * FROM user
                WHERE user_id = {$_SESSION["user_id"]}";
  $result = $mysqli->query($sql);
  //GET ASSOCIATIVE ARRAY
  $user = $result->fetch_assoc();
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
</head>

<body class="font-poppins bg-white flex justify-center ">
  <?php if (isset($user)) : ?>
    <div class="container border border-black my-2 p-4">
      <div class="mx-auto justify-content">
        <div class="text-center">
          <h2 class="text-xl">Menu</h2>
          <hr>
          <h3 class="my-2"><?= $restaurant_name ?></h3>
        </div>
        <div class="grid grid-cols-2 gap-4 border border-black">
          <?php
          $category_sql = "SELECT * FROM category WHERE restaurant_id = $restaurant_id";
          $category_result = $mysqli->query($category_sql);
          if ($category_result->num_rows > 0) {
            while ($categoryRow = $category_result->fetch_assoc()) {
          ?>
              <div class="text-center m-4">
                <p class="uppercase font-semibold"><?= $categoryRow["category_name"] ?></p>
                <hr>
                <?php
                $categoryId = $categoryRow["category_id"];
                $itemQuery = "SELECT * FROM item WHERE category_id = $categoryId";
                $itemResult = $mysqli->query($itemQuery);
                ?>
                <table class="table-fixed table w-full text-sm text-center">
                  <tbody>
                    <?php
                    if ($itemResult->num_rows > 0) {
                      while ($itemRow = $itemResult->fetch_assoc()) {
                    ?>
                        <tr>
                          <th scope="row" class="uppercase py-1 px-6 font-medium text-gray-900 whitespace-nowrap"><?=$itemRow["item_name"]?></th>
                          <td class="px-6">RM <?= number_format($itemRow["item_price"],2)?></td>
                        </tr>
                    <?php
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
          <?php
            }
          }
          ?>

        </div>
        <footer class="pt-5 flex justify-center p-2">
          <p class="text-gray-400">Made by Ordine</p>
        </footer>
      </div>
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