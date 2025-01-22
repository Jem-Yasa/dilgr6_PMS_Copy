<?php
$emp_id = $_SESSION['emp_id'];
$emp_arr = display_user($emp_id, $conn) ? display_user($emp_id, $conn) : '';

?>

<header id="header" class="header fixed-top d-flex align-items-center" 
style=" background: #a64bf4;
  background: -webkit-linear-gradient(right, #21d4fd, #b721ff, #21d4fd, #b721ff);
  background: -o-linear-gradient(right, #21d4fd, #b721ff, #21d4fd, #b721ff);
  background: -moz-linear-gradient(right, #21d4fd, #b721ff, #21d4fd, #b721ff);
  background: linear-gradient(right, #21d4fd, #b721ff, #21d4fd, #b721ff);">
  <div class="d-flex align-items-center justify-content-between">
    <a href="dashboard.php" class="logo d-flex align-items-center">
      <img src="../assets/img/dilg_logo.png" alt="">
      <span class="d-none d-sm-block" style="font-size:1.1rem; font-weight:bold">DILG R6 PMS</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->


  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle " href="#">
          <i class="bi bi-search"></i>
        </a>
      </li><!-- End Search Icon-->
      <li class="nav-item dropdown">
       
      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <?php
          if ($emp_arr['pic_emp_data'] == '' || $emp_arr['pic_emp_data'] == null) {
          ?>
            <img src="../assets/img/no-photo.jpg" class="rounded-circle">
          <?php
          } else {
            echo '<img src="data:image;base64,' . base64_encode($emp_arr['pic_emp_data']) . '" class="rounded-circle">';
          }
          ?>
          <span class="d-none d-md-block dropdown-toggle ps-2">
            <?php echo $emp_arr['first_name']; ?>
          </span>
        </a><!-- End Profile Iamge Icon -->
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6> <?php echo $emp_arr['first_name'] . ' ' . $emp_arr['last_name']; ?></h6>

          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="../view/profile.php?emp_id=<?php echo $emp_id; ?>">
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="#">
              <i class="bi bi-gear"></i>
              <span>Account Settings</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a onclick="logout()" class="dropdown-item d-flex align-items-center" href="#">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>
        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->
    </ul>
  </nav><!-- End Icons Navigation -->
</header><!-- End Header -->

<script>
  function logout() {
    swal({
      text: "Are you sure you want to sign out?",
      icon: "warning",
      buttons: [
        'CANCEL',
        'SIGN OUT'
      ],
    }).then(function(isConfirm) {
      if (isConfirm) {
        window.location.href = "../actions/logout.php";
      }
    });
  }
</script>