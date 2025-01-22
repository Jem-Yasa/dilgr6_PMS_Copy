<?php
session_start();
include '../db/connect.php';
include '../actions/functions.php';

$emp_id = $_SESSION['emp_id'];
if (!isset($_SESSION['is_login']) || !isset($_SESSION['emp_id'])) {
  header('Location: ../index.php');
} else {
  if(!is_pms_user($emp_id, $conn)){
    header('Location: error404.html');  
  } 
}
$check = is_pms_user($emp_id, $conn);
$role = $check ? $check['pms_role'] : '';
$office = $check ? $check['pms_office'] : '';

if($role !== 'admin'){
  header('Location: error404.html');  
} 

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>DILG Region 6 PMS</title>
  <link href="../assets/img/dilg_logo.png" rel="icon">
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>|
  <link href="../assets/css/style.css" rel="stylesheet">
  <!-- <link href="../../assets/css/portal.css" rel="stylesheet"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
  <!-- ======= Header ======= -->
  <?php
  include("header.php");
  ?>

  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
          <a class="nav-link collapsed" href="dashboard.php">
          <i class="bi bi-grid"></i>
            <span>Dashboard</span>
          </a>
        </li>
    <li class="nav-item">
          <a class="nav-link collapsed" href="purchase_order.php">
            <i class="bi bi-box-arrow-right"></i>
            <span>Purchase Order</span>
          </a>
        </li>
       
        <?php
        if($role == 'admin'){
          ?>
            <li class="nav-item">
            <a class="nav-link active" href="app_settings.php">
            <i class="ri-settings-2-line"></i>
            <span>Application Settings</span>
            </a>
          <?php
        }
        ?>
       
        <!-- End F.A.Q Page Nav -->
    </ul>
  </aside><!-- End Sidebar-->


  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Application Settings</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item active">Application Settings</li>
        </ol>
      </nav>
    </div>


    <section class="section dashboard">
      <div class="align-items-center justify-content-between; ">


        <p style="font-weight:600; font-size:1rem; padding-top:10px">PMS USER

          <span style="float:right; margin-right:20px;">
            <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-outline-dark rounded-pill">
            <i class="ri-add-line"></i> User</button>
          </span>
        </p>


        <ul id="myUL" style=" list-style-type: none; padding: 0;margin-top:30px;">
          <?php
          $sql = mysqli_query($conn, "SELECT * FROM tbl_pms_user INNER JOIN tbl_employee ON tbl_pms_user.emp_id
   = tbl_employee.emp_id");

          if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_array($sql)) {
              $fullname = $row['first_name'] . ' ' .  $row['middle_name'] . ' ' . $row['last_name'];
              $image = $row['pic_emp_data'];
              $hrmis_role = $row['role'];
              $hrmis_role_office = $row['office_prov'];
              $procurement_user_id = $row['procurement_user_id'];
          ?>
              <li>
                <a style="display:none" href="#"><?php echo $fullname; ?></a>
                <div class="card mb-4">
                  <div class="row g-0">
                    <div class="col-md-2">
                      <a class="" href="#" data-bs-toggle="dropdown">
                        <?php
                        if ($image == '' || $image == null) {
                        ?>
                          <img src="../assets/img/no-photo.jpg" alt="user profile" class="rounded-circle" style="margin-left: 30%; width:80px; margin-top:11px">
                        <?php
                        } else {
                          echo '<img src="data:image;base64,' . base64_encode($image) . '" alt="user profile" class="rounded-circle" style="margin-left: 30%; width:70px; margin-top:11px">';
                        }
                        ?>


                      </a>
                    </div>
                    <div class="col-md-8">
                      <div class="card-body">
                        <h5 class="card-title"><?php echo $fullname; ?></h5>
                        <p class="card-text" style="margin-top:-20px; color:darkgoldenrod; font-weight:600; font-size:0.9rem"> <i class="bi bi-bookmark-star" style="color:darkgoldenrod;"></i> <?php echo $hrmis_role; ?>

                        <span class="badge rounded-pill bg-secondary text-light" style="margin-left:20px"><?php echo $hrmis_role_office; ?></span>
                        </p>
                      </div>
                    </div>
                    <?php
                    if ($hrmis_role != 'super admin') {
                    ?>
                      <div class="col-md-2 pt-4 pb-2">
                        <!-- <button data-bs-toggle="modal" data-bs-target="#exampleModal2" class="btn float-right" style="color:white; padding: 5px 20px 5px 20px; background-color:#ff9b44; border-radius: 30px 0px 30px 30px;" type="submit" title="Search">Edit</button> -->
                        <button onclick="romoveUser('<?php echo $procurement_user_id; ?>')" class="btn btn-outline-danger btn-sm" style="float:right; margin-right:20px; padding: 5px 20px 5px 20px;  border-radius: 30px 0px 30px 30px;" type="submit" title="Search">Remove</button>
                      </div>
                    <?php
                    }
                    ?>

                  </div>
                </div>
              </li>
          <?php
            }
          }
          ?>


        </ul>

      </div>

    </section>
  </main>




  <?php
  include("footer.php");
  ?>


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src=../assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.min.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/js/main.js"></script>


  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add PMS User </h5>
          <button class="btn-close" data-bs-dismiss="modal">
            <span aria-hidden="true"></span>
          </button>
        </div>
        <form style="padding: 20px 20px" action="../actions/add_pms_user.php" method="POST">
          <div class="row mb-3">
            <label for="inputText" class="col-sm-3 col-form-label">User</label>
            <div class="col-sm-9">
              <select class="form-select" aria-label="Default select example" name="emp_id">
                <option selected>Select user</option>
                <?php
                $sql = mysqli_query($conn, "SELECT * FROM tbl_employee ORDER BY last_name");
                while ($row = mysqli_fetch_array($sql)) {
                  $fullname =  $row['last_name'] . ', ' . $row['first_name'] . ' ' .  $row['middle_name'];;
                  $image = $row['pic_emp'];
                ?>
                  <option value="<?php echo $row['emp_id']; ?>">
                    <?php echo $fullname; ?></option>
                <?php
                }
                ?>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Admin type</label>
            <div class="col-sm-9">
              <select class="form-select" aria-label="Default select example" name="pms_role">
                <option default selected >Select role</option>
                <option value="admin">Administrator</option>
                <option value="sub-admin">Sub Administrator</option>
                <option value="coa">COA Personnel</option>
                <option value="encoder">Encoder</option>
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <label for="inputEmail" class="col-sm-3 col-form-label">Role Description</label>
            <div class="col-sm-9">
              <textarea name="remarks" class="form-control" style="height: 100px"></textarea>
            </div>
          </div>

          <div class="row mb-3">
            <label for="inputText" class="col-sm-3 col-form-label">Office</label>
            <div class="col-sm-9">
              <select class="form-select" aria-label="Default select example" name="office">
                <option selected>Select Office</option>
                <option value="REGIONAL OFFICE">REGIONAL OFFICE</option>
                <option value="AKLAN">AKLAN</option>
                <option value="CAPIZ">CAPIZ</option>
                <option value="ILOILO PROVINCE">ILOILO PROVINCE</option>
                <option value="ILOILO CITY">ILOILO CITY</option>
                <option value="NEGROS OCCIDENTAL">NEGROS OCCIDENTAL</option>
                <option value="BACOLOD">BACOLOD</option>
                <option value="GUIMARAS">GUIMARAS</option>
                <option value="ANTIQUE">ANTIQUE</option>
              </select>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" style="border-radius: 30px 0px 30px 30px; width:120px; font-weight:500;" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            <button type="submit" style="border-radius: 30px 0px 30px 30px; width:120px; font-weight:500;" class="btn btn-primary">Submit</button>
          </div>
        </form><!-- End General Form Elements -->
      </div>
    </div>
  </div>






  <!-- Modal -->
  <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit User Admin Role </h5>
          <button class="btn-close" data-bs-dismiss="modal">
            <span aria-hidden="true"></span>
          </button>
        </div>
        <form style="padding: 20px 20px" action="../actions/add_hrmis_admin.php" method="POST">
          <div class="row mb-3">
            <label for="inputText" class="col-sm-3 col-form-label">Select User</label>
            <div class="col-sm-9">
              <select class="form-select" aria-label="Default select example" name="emp_id">
                <option selected>Select user</option>
                <?php
                $sql = mysqli_query($conn, "SELECT * FROM tbl_employee ORDER BY last_name");
                while ($row = mysqli_fetch_array($sql)) {
                  $fullname =  $row['last_name'] . ', ' . $row['first_name'] . ' ' .  $row['middle_name'];;
                  $image = $row['pic_emp'];
                ?>
                  <option value="<?php echo $row['emp_id']; ?>">
                    <?php echo $fullname; ?></option>
                <?php
                }
                ?>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Select Role</label>
            <div class="col-sm-9">
              <select class="form-select" aria-label="Default select example" name="hrmis_role">
                <option value="personnel admin">Personnel Administrator</option>
                <option value="accounting admin">Accounting Administrator</option>
                <option value="super admin">Super Administrator</option>
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <label for="inputEmail" class="col-sm-3 col-form-label">Remarks</label>
            <div class="col-sm-9">
              <textarea name="remarks" class="form-control" style="height: 100px"></textarea>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" style="border-radius: 30px 0px 30px 30px; width:120px; font-weight:500;" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            <button type="submit" style="border-radius: 30px 0px 30px 30px; width:120px; font-weight:500;" class="btn btn-primary">Submit</button>
          </div>
        </form><!-- End General Form Elements -->
      </div>
    </div>
  </div>




  <script>
    function romoveUser(id) {
      swal({
        title: "Remove PMS User",
        text: "Are you sure you want to remove this admin?",
        icon: 'warning',
        buttons: [
          'CANCEL',
          'REMOVE'
        ],
      }).then(function(isConfirm) {
        if (isConfirm) {
          window.location.href = '../actions/remove_pms_user.php?procurement_user_id=' + id;
        }
      });
    }
  </script>

</body>

</html>