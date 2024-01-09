<div class="navbar bg-primary border-b-2">
  <div class="flex-1">
    <h1 class="mx-2 text-center text-xl text-white font-bold">ORDINE</h1>
  </div>

  <div class="flex justify-end flex-1 px-2">
    <div class="flex items-stretch">
      <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="btn btn-ghost rounded-btn text-white">
          <img class="w-10 h-10 rounded-full" src="upload-image-employee/<?php echo $empImage ?>" alt="Staff's Image" />
          <?php echo $fullName ?>
        </div>
        <ul tabindex="0" class="menu dropdown-content z-[1] p-2 shadow bg-base-100 rounded-box w-52 mt-4">
          <li><a href="employee-details.php">Change Details</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>