<?php
$is_invalid = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $mysqli = require __DIR__ . "/database-connection.php";

  //real_escape_string() is to avoid any sql injections attack
  $sql = sprintf(
    "SELECT * FROM user 
                  WHERE email = '%s'",
    $mysqli->real_escape_string($_POST["email"])
  );

  //EXECUTE QUERY
  $result = $mysqli->query($sql);

  //GET DATA (fetch_assoc())
  $user = $result->fetch_assoc();

  if ($user) {
    //CHECK PASSWORD_HASHED
    if (password_verify($_POST["password"], $user["password_hash"])) {
      session_start();


      session_regenerate_id(); //Session Attack Avoid
      $_SESSION["user_id"] = $user["user_id"]; #2
      $_SESSION["user_type"] = $user["user_type"];

      if ($user["user_type"] === "admin") {
        header("Location: home-admin-page.php");
        exit;
      }
      if ($user["user_type"] === "staff") {
        header("Location:home-staff-page.php");
        exit;
      }
      if ($user["user_type"] === "super_admin") {
        header("Location: home-super-admin.php");
        exit;
      }
    }
  }
  $is_invalid = true;

  //var_dump($user); // TEST
}

?>


<!DOCTYPE html>
<html lang="en" data-theme="cupcake">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ordine - Login Page</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="styles/output.css" />
  <!-- Just-Validate.dev -->
  <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
  <script src="/scripts/validation-register.js" defer></script>
</head>

<body class="font-poppins">
  <div class="hero min-h-screen bg-base-200" style="background-image: url(example/pexels-ella-olsson-1640773.jpg)">
    <div class="hero-overlay bg-opacity-60"></div>
    <div class="hero-content flex-col lg:flex-row-reverse">
      <div class="ml-10 text-center lg:text-left">
        <h1 class="text-center text-5xl font-bold">Ordine</h1>
        <p class="py-6 text-center">POS System design for Small F&B Businesses</p>
        <p class="text-center text-xs">Don't have an account? <a class="label-text-alt link link-primary text-xs" href="register.php">Sign Up</a></p>
      </div>
      <div class="card flex-shrink-0 w-full max-w-sm shadow-2xl bg-white my-10">
        <form method="POST" class="card-body">
          <div>
            <label class="text-3xl font-bold label">Sign In</label>
          </div>
          <div class="form-control">
            <label for="email" class="label">
              <span class="label-text">Email</span>
            </label>
            <input value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" name="email" id="email" type="email" placeholder="Email" class="input input-bordered" required />
            <!-- leave email cred in the form as default -->
            <!-- htmlspecialchars() to avoid email leaks -->
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Password</span>
            </label>
            <input name="password" id="password" type="password" placeholder="Password" class="input input-bordered" required />
            <label class="label">
              <a href="forgot-password.php" class="label-text-alt link link-hover">Forgot password?</a>
            </label>
          </div>
          <div class="form-control mt-6">
            <input type="submit" value="Sign in" class="btn btn-primary">
          </div>
          <div>
            <?php if ($is_invalid) : ?>
              <em>Invalid Login</em>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>