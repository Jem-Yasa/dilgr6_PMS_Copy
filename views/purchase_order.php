<?php
session_start();
include '../db/connect.php';
include '../actions/functions.php';
date_default_timezone_set('Asia/Manila');
$emp_id = $_SESSION['emp_id'];
$cur_year = date("Y");

$year = isset($_GET['year']) ?  $_GET['year'] : $cur_year;
$office_param = isset($_GET['office_param']) ?  $_GET['office_param'] : 'REGIONAL OFFICE';

if (!isset($_SESSION['is_login']) || !isset($_SESSION['emp_id'])) {
  header('Location: ../index.php');
} else {
  if (!is_pms_user($emp_id, $conn)) {
    header('Location: error404.html');
  }
}
$check = is_pms_user($emp_id, $conn);
$role = $check ? $check['pms_role'] : '';
$office = $check ? $check['pms_office'] : '';
$str1 = '';
$str2 = '';
$str3 = '';
$str4 = '';


if ($role !== 'encoder') {
  if ($role === 'coa') {
    $str1 = "SELECT * FROM tbl_pms_purchase_order WHERE with_resubmission=1 AND approved_res=1 AND year='$year'";
  } else {
    $str1 = "SELECT * FROM tbl_pms_purchase_order WHERE with_resubmission=1 AND status !='cancelled' AND approved_res=0 AND year='$year'";
  }

  $str2 = "SELECT * FROM tbl_pms_purchase_order WHERE coa_received=1 AND status !='cancelled' AND year='$year'";
  $str3 = "SELECT * FROM tbl_pms_purchase_order WHERE emp_id !='' and coa_received=0 AND status !='cancelled' AND remarks='Submitted' AND year='$year'";
  $str4 = "SELECT * FROM tbl_pms_purchase_order WHERE emp_id !='' AND status='cancelled' AND year='$year'";
} else {
  $office_param  = $office;
  $str1 = "SELECT * FROM tbl_pms_purchase_order WHERE with_resubmission=1 AND office='$office' AND year='$year'";
  $str2 = "SELECT * FROM tbl_pms_purchase_order WHERE coa_received=1 AND office='$office' AND status !='cancelled' AND year='$year'";
  $str3 = "SELECT * FROM tbl_pms_purchase_order WHERE emp_id !='' AND coa_received=0 AND office='$office' AND status !='cancelled' AND remarks='Submitted' AND year='$year'";
  $str4 = "SELECT * FROM tbl_pms_purchase_order WHERE emp_id !='' AND status='cancelled' AND office='$office' AND year='$year'";
}

$sql_res = mysqli_query($conn, $str1) or die(mysqli_error($conn));
$count_rows_res = mysqli_num_rows($sql_res);
$sql_received = mysqli_query($conn, $str2) or die(mysqli_error($conn));
$count_received = mysqli_num_rows($sql_received);
$sql_sub = mysqli_query($conn, $str3) or die(mysqli_error($conn));
$count_sub = mysqli_num_rows($sql_sub);
$sql_canc = mysqli_query($conn, $str4) or die("error c");
$count_canc = mysqli_num_rows($sql_canc);

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
  <link href="../assets/css/pms.css" rel="stylesheet">
  <!-- <link href="../assets/css/portal.css" rel="stylesheet"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    body {
      font-size: 0.85rem;
    }
  </style>
</head>

<body>
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
        <a class="nav-link active" href="purchase_order.php">
          <i class="bi bi-box-arrow-right"></i>
          <span>Purchase Order</span>
        </a>
      </li>

      <?php
      if ($role == 'admin') {
      ?>
        <li class="nav-item">
          <a class="nav-link collapsed" href="app_settings.php">
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

    <div class="pagetitle" style="margin-top:-20px">
      <h1><span style="padding-top:10px">Purchase Order</span>
        <span style="float:right;">
          <select class="form-select" aria-label="Default select example" onchange="changeYearOffice()" id="yearselect" style=" font-weight:600; ">
            <option value="2024" <?php echo $year == '2024' ? 'selected' : ''; ?>> YEAR 2024</option>
            <option value="2023" <?php echo $year == '2023' ? 'selected' : ''; ?>> YEAR 2023</option>
          </select>
        </span>


        
      </h1>
      <nav>
        <ol class="breadcrumb">
          <!-- <li class="breadcrumb-item active">Purchase Order Submission</li> -->
        </ol>
      </nav>
    </div>


    <section class="section dashboard">

      <div class="col-lg-12">
        <div class="row" style="padding-top:5px">
          <!-- Sales Card -->
          <div class="col-xxl-3 col-xl-12">
            <div class="card info-card sales-card" style="background-color:<?php echo $role == 'coa' && $count_sub > 0 ? '#FBDBD4' : ''; ?>
              ">

              <div class="filter">
                <a style="cursor:pointer" class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li onclick="location.href='submissions.php?request=pending&year=<?php echo $year; ?>'"><a class="dropdown-item" href="#">View submissions</a></li>
                </ul>
              </div>

              <div class="card-body">
                <h5 class="card-title"> Submissions <span> | <?php echo $year; ?></span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <img src="../assets/img/file.jpg" style="width:40px; ">
                  </div>
                  <div class="ps-3">
                    <h6><?php echo $count_sub; ?></h6>
                    <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Sales Card -->
          <!-- Customers Card -->
          <div class="col-xxl-3 col-xl-12">

            <div class="card info-card customers-card" style="background-color:<?php echo ($role == 'sub-admin' || $role == 'coa') && $count_rows_res > 0 ? '#FBDBD4' : ''; ?>">

              <div class="filter">
                <a style="cursor:pointer" class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li onclick="location.href='submissions.php?request=resubmission&year=<?php echo $year; ?>'"><a class="dropdown-item" href="#">View resubmissions</a></li>
              </div>

              <div class="card-body">
                <h5 class="card-title">Resubmissions<span> | <?php echo $cur_year; ?></span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <img src="../assets/img/resub.png" style="width:55px; ">
                    <!-- <i class="bi bi-people"></i> -->
                  </div>
                  <div class="ps-3">
                    <h6><?php echo $count_rows_res; ?></h6>
                    <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->

                  </div>
                </div>

              </div>
            </div>

          </div><!-- End Customers Card -->
          <!-- Revenue Card -->
          <div class="col-xxl-3 col-xl-12">
            <div class="card info-card revenue-card">

              <div class="filter">
                <a style="cursor:pointer" class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li onclick="location.href='submissions.php?request=received&year=<?php echo $year; ?>'"><a class="dropdown-item" href="#">View received submissions</a></li>
                </ul>
              </div>

              <div class="card-body">
                <h5 class="card-title">Received <span> | <?php echo $cur_year; ?></span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <img src="../assets/img/received.png" style="width:50px; ">
                  </div>
                  <div class="ps-3">
                    <h6><?php echo $count_received; ?></h6>
                    <!-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Revenue Card -->

          <!-- Revenue Card -->
          <div class="col-xxl-3 col-xl-12">
            <div class="card info-card revenue-card">

              <div class="filter">
                <a style="cursor:pointer" class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li onclick="location.href='submissions.php?request=cancelled&year=<?php echo $year; ?>'"><a class="dropdown-item" href="#">View Cancelled Purchase Orders</a></li>
                </ul>
              </div>

              <div class="card-body">
                <h5 class="card-title">Cancelled <span> | <?php echo $cur_year; ?></span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <img src="../assets/img/cancel.png" style="width:45px; ">
                  </div>
                  <div class="ps-3">
                    <h6><?php echo $count_canc; ?></h6>
                    <!-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Revenue Card -->

        </div>

        <div class="card">
          <div class="card-header">
            <h5 class="card-title" style="padding:3px; margin:0; font-size:1.1rem; padding-top:5px"><?php echo $office_param; ?> - Purchase Orders <?php echo $year; ?>
          
          
            <span style="float:right; margin-right: 20px">
          <select class="form-select" aria-label="Default select example" onchange="changeYearOffice()" id="officeselect">
            <?php
            if (($office == 'REGIONAL OFFICE' && $role == 'encoder') || $role == 'coa' || $role == 'admin') {
            ?>
              <option value="REGIONAL OFFICE" <?php echo $office_param == "REGIONAL OFFICE" ? 'selected' : ''; ?>>Regional Office</option>
            <?php
            }
            if (($office == 'ILOILO PROVINCE' && $role == 'encoder') || $role == 'coa' || $role == 'admin') {
            ?>
              <option value="ILOILO PROVINCE" <?php echo $office_param == "ILOILO PROVINCE" ? 'selected' : ''; ?>>Iloilo Province</option>
            <?php
            }
            if (($office == 'GUIMARAS' && $role == 'encoder') || $role == 'coa' || $role == 'admin') {
            ?>
              <option value="GUIMARAS" <?php echo $office_param == "GUIMARAS" ? 'selected' : ''; ?>>Guimaras</option>
            <?php
            }
            if (($office == 'ANTIQUE' && $role == 'encoder') || $role == 'coa' || $role == 'admin') {
            ?>
              <option value="ANTIQUE" <?php echo $office_param == "ANTIQUE" ? 'selected' : ''; ?>>Antique</option>
            <?php
            }
            if (($office == 'AKLAN' && $role == 'encoder') || $role == 'coa' || $role == 'admin') {
            ?>
              <option value="AKLAN" <?php echo $office_param == "AKLAN" ? 'selected' : ''; ?>>Aklan</option>
            <?php
            }
            if (($office == 'CAPIZ' && $role == 'encoder') || $role == 'coa' || $role == 'admin') {
            ?>
              <option value="CAPIZ" <?php echo $office_param == "CAPIZ" ? 'selected' : ''; ?>>Capiz</option>
            <?php
            }
            if (($office == 'NEGROS OCCIDENTAL' && $role == 'encoder') || $role == 'coa' || $role == 'admin') {
            ?>
              <option value="NEGROS OCCIDENTAL" <?php echo $office_param == "NEGROS OCCIDENTAL" ? 'selected' : ''; ?>>Negros Occidental</option>
            <?php
            }
            ?>
          </select>
        </span>
          </h5>
          </div>
          <div class="card-body">
            <table class="table datatable" style="background-color:white; text-align:center; margin-top:10px " id="myTable">
              <thead>
                <tr>
                  <th class="cell" style="font-size:0.85rem;color:#042B83; text-align:center">PO NuFamber</th>
                  <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Purpose</th>
                  <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Date Submitted</th>
                  <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Submitted By</th>
                  <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Status</th>
                  <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Remarks</th>
                </tr>
              </thead>

              <tbody id="myTable">
                <?php
    
                for ($x = 1; $x <= 500; $x++) {
                  $po_str = $x;
                  $length = strlen((string)$po_str);
                  for ($i = $length; $i < 4; $i++) {
                    $po_str = '0' . $po_str;
                  }
                  $po_number = $year . '-' . $po_str;
                  $purpose = '';
                  $date_uploaded = '';
                  $submitted_by =  '';
                  $status = 'no submission';
                  $remarks = '';
                  $fullname = '';
                  $me = '';
                  $color_rem = 'black';
                  $emp_id;
                  $po_id = '';
                  $purchase_order_sql = mysqli_query($conn, "SELECT * FROM tbl_pms_purchase_order WHERE office='$office_param' 
                  AND year='$year' AND po_number='$po_number' LIMIT 1") or die("error 4");

                  if (mysqli_num_rows($purchase_order_sql) == 1) {
                    while ($row = mysqli_fetch_array($purchase_order_sql)) {
                      $emp_id = $row['emp_id'];

                      if ($emp_id != null) {
                        $empsql = mysqli_query($conn, "SELECT * from tbl_employee where emp_id ='$emp_id' LIMIT 1");
                        while ($row2 = mysqli_fetch_array($empsql)) {
                          $fullname = ucwords(strtolower($row2['first_name'] . ' ' . $row2['last_name']));
                          $image = $row2['pic_emp_data'];
                          if ($_SESSION['emp_id'] === $row2['emp_id']) {
                            $me = '(me)';
                          } else {
                            $me = '';
                          }
                        }
                      }
                      $date_uploaded = date_format(date_create($row['date_uploaded']), "F j, Y");
                      $purpose = $row['purpose'];
                      $remarks = $row['remarks'];
                      $status = $row['status'];
                      $po_id = $row['po_id'];
                      if ($remarks == 'Cancelled') {
                        $color_rem = 'red';
                      } else if ($remarks == 'Submitted') {
                        $color_rem = 'blue';
                      } else {
                        $color_rem = 'green';
                      }
                    }
                  }
                ?>
                  <tr onclick="location.href='view_po.php?po_number=<?php echo $po_number; ?>&office=<?php echo $office; ?>'" style="cursor:pointer">
                    <td style="font-weight:bold"><?php echo $po_number; ?></td>
                    <td style="font-size:0.7rem; width:25%; text-align:left"><?php echo $purpose; ?></td>
                    <td><?php echo  $date_uploaded; ?></td>
                    <td style="font-size:0.9rem"><?php echo $fullname; ?> <span style="color:#808080; font-size:0.7rem"> <?php echo $me; ?></span></td>
                    <td>
                      <?php
                      if ($status == 'on time') {
                      ?>
                        <span class="badge rounded-pill bg-success text-light"> <?php echo $status; ?></span>
                      <?php
                      } else if ($status == 'late') {
                      ?>
                        <span class="badge rounded-pill bg-danger text-light"> <?php echo $status; ?></span>
                      <?php
                      } else if ($status == 'cancelled') {
                      ?>
                        <span class="badge rounded-pill text-light" style="background-color:#FF6101;"> <?php echo $status; ?></span>
                      <?php
                      } else {
                      ?>
                        <span class="badge rounded-pill  bg-dark text-light"> <?php echo $status; ?></span>
                      <?php
                      }
                      ?>
                    </td>
                    <td style="font-weight:bold; color:<?php echo $color_rem; ?>; font-size:0.8rem"><?php echo $remarks; ?></td>
                  </tr>
                <?php
                }

                ?>
            </table>
          </div>
          <div class="card-footer">
            Footer
          </div>
        </div>


    </section>
  </main>




  <!-- Modal -->
  <div class="modal fade" id="dtrModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Enter Custom Year </h5>
          <button class="btn-close" data-bs-dismiss="modal">
            <span aria-hidden="true"></span>
          </button>
        </div>
        <form style="padding: 20px 20px" action="manage_timesheet.php" method="GET">
          <div class="row mb-3">
            <label for="inputText" class="col-sm-2 col-form-label">Month</label>
            <div class="col-sm-10">
              <input type="hidden" value='<?php echo $emp_id; ?>' name="emp_id">
              <input type="number" class="form-control" id="floatingInput" name="dtr_month" required value="2024">
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



  <?php
  include("footer.php");
  ?>


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/js/main.js"></script>

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

</body>

<script>
  function changeYearOffice() {
    var x = document.getElementById("yearselect").value;
    var y = document.getElementById("officeselect").value;
    window.location.href = 'purchase_order.php?office_param=' + y + '&year=' + x;
  }
</script>

</html>