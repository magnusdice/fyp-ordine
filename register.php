<!DOCTYPE html>
<html lang="en" data-theme="cupcake">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ordine - Register Page</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="styles/output.css" />
  <!-- Just-Validate.dev -->
  <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
  <script src="/scripts/validation-register.js" defer></script>
</head>

<body class="font-poppins">
  <div class="hero min-h-screen" style="background-image: url(example/pexels-ella-olsson-1640773.jpg)">
    <div class="hero-overlay bg-opacity-60"></div>
    <div class="hero-content flex-col lg:flex-row-reverse">
      <div class="ml-10 text-center lg:text-left">
        <h1 class="text-center text-5xl font-bold">Ordine</h1>
        <p class="pt-6 text-center">POS System design</p>
        <p class="text-center">for Small F&B Businesses</p>
        <p class="text-center text-xs">Already have account? <a class="label-text-alt link link-primary text-xs" href="login.php">Sign in</a></p>
      </div>
      <div class="card flex-shrink-0 w-full max-w-sm shadow-2xl bg-white my-10">
        <form method="POST" action="process-signup.php" id="register" enctype="multipart/form-data" class="card-body">
          <div>
            <label class="text-3xl font-bold label">Register Form</label>
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Email</span>
            </label>
            <input name="email" id="email" type="email" placeholder="Email" class="input input-bordered" required />
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Password</span>
            </label>
            <input name="password" id="password" type="password" placeholder="Password" class="input input-bordered" required />
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Confirm Password</span>
            </label>
            <input name="confirm-pass" id="confirm-pass" type="password" placeholder="Password" class="input input-bordered" required />
          </div>
          <div class="input-group">
            <input type="hidden" name="user_type" id="user_type" value="admin">
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text font-bold">Restaurant Details</span>
            </label>
            <label class="label">
              <span class="label-text">Restaurant Name</span>
            </label>
            <input type="text" name="restaurant_name" id="restaurant_name" placeholder="Type Here" class="input input-bordered" required />
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Restaurant Address</span>
            </label>
            <textarea class="textarea textarea-bordered" placeholder="Type Here" name="restaurant_address" id="restaurant_address" cols="37" rows="3" required></textarea>
          </div>
          <div class="form-control">
            <div class="form-control w-full max-w-xs">
              <label class="label">
                <span class="label-text">Restaurant Logo (Optional)</span>
              </label>
              <input type="file" name="restaurant_logo" id="restaurant_logo" accept=".jpg,.jpeg,.png" class="file-input file-input-bordered file-input-primary w-full max-w-xs" />
              <label class="label">
                <span class="label-text-alt">Supported formats: PNG, JPG, JPEG</span>
              </label>
            </div>
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text font-bold">Restaurant Working Time</span>
            </label>
            <label class="label">
              <span class="label-text">Start Working Time</span>
            </label>
            <input type="time" name="start_time" class="input input-bordered" required />
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">End Working Time</span>
            </label>
            <input type="time" name="end_time" class="input input-bordered" required />
          </div>
          <div class="form-control mt-6">
            <input type="submit" value="Sign Up" class="btn btn-primary">
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>