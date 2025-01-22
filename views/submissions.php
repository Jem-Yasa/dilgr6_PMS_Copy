
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

$request = $_GET['request'];
$year = $_GET['year'];
$str_sql= '';
if($request == 'received'){
  if($role === 'encoder'){
    $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE  coa_received=1 AND office='$office' AND status !='cancelled' AND year='$year'
    ORDER BY po_number ASC";
  } else {
    $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE  coa_received=1  AND status !='cancelled' AND year='$year'
    ORDER BY date_received DESC";
  }
} else if ($request == 'resubmission'){
  if($role === 'encoder'){
    $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE with_resubmission=1 AND office='$office' AND status !='cancelled' AND year='$year'
  ORDER BY po_number ASC";
  } else  if($role === 'coa'){
    $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE with_resubmission=1 AND approved_res=1 AND status !='cancelled' AND year='$year'
    ORDER BY po_number ASC";
  } else {
    $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE with_resubmission=1 AND status !='cancelled' AND approved_res=0 AND year='$year'
    ORDER BY po_number ASC";
  }
} else if ($request == 'pending'){
  if($role === 'encoder'){
    $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE emp_id !='' and coa_received=0 AND office='$office' AND status !='cancelled' AND year='$year'
    ORDER BY po_number ASC";
  } else {
    $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE emp_id !='' and coa_received=0 AND status !='cancelled' AND year='$year'
    ORDER BY date_uploaded ASC";
  }
} else if ($request == 'cancelled'){
  if($role === 'encoder'){
    $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE emp_id !='' AND status='cancelled' AND office='$office' AND year='$year'
    ORDER BY po_number ASC";
  } else {
    $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE emp_id !='' AND status='cancelled' AND year='$year'
    ORDER BY po_number ASC";
  }
} 
 else {
 // $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE office='$office_param'
  //ORDER BY po_number ASC";
}
$title ='';
if($request == 'received'){
  $title = 'Received Submissions';
} else if ($request == 'resubmission'){
  $title = 'Resubmission Requests';
} else if ($request == 'pending'){
  $title = 'Submission Requests';
} else if ($request == 'cancelled'){
  $title = 'Cancelled Purchase Orders';
} else {

  //
}
$stat = '';
if($request == 'received'){
  $stat = 'received';
} else if ($request == 'resubmission'){
  $stat = 'for approval';
} else if ($request == 'pending'){
  $stat = 'pending';
} else {
  //
}

$sql = mysqli_query($conn, $str_sql) or die("error");
$count_rows = mysqli_num_rows($sql);
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
  <!-- <link href="../assets/css/portal.css" rel="stylesheet"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style> 
.nav-tabs-bordered .nav-link.active {
    background-color: #fff;
    color: #4154f1;
    border: none;
    border-bottom: 2px solid #4154f1;
    font-weight:600;
}
.pdf {
  width:45px;
  height:52px;
  cursor: pointer;
}
.container {
  position: relative;
}

.container:hover .pdf {
  transform: scale(1.4);
}
tr:hover {
  background-color:#ECEDFF;
}
td {
  text-overflow: ellipsis;
  max-height: 50px;
}
th {
  text-align: center;
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
            <span>PO Submission</span>
          </a>
        </li>
       
        <?php
        if($role == 'admin'){
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

    <div class="pagetitle">
      <h1> <a href="purchase_order.php" href="#" onclick="history.back()"><i class="bx bx-arrow-back"> </i> </a> &ThickSpace; <?php echo $title; ?> 
      <span class="badge bg-danger text-white"><?php echo $count_rows; ?></span>

      <span style="float:right">
      <select class="form-select" aria-label="Default select example" onchange="changeYear()" id="officeselect" style="background-color:#707070; font-weight:600; color:white">
                 <option value="2024" <?php echo $year == '2024' ? 'selected' : ''; ?>> YEAR 2024</option>
                  <option value="2023" <?php echo $year == '2023' ? 'selected' : ''; ?>> YEAR 2023</option>
                    </select>
      </span>
      </h1>
      
      <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="purchase_order.php">PO Submission</a></li>
                    <li class="breadcrumb-item active"> <?php echo $title; ?></li>
                    
                </ol>
            </nav>
    </div>


    <section class="section dashboard">
      <div class="align-items-center justify-content-between; ">
        <div class="tab-content pt-2" id="borderedTabContent">
        <table class="table datatable" style="background-color:white; text-align:center; margin-top:10px " id="myTable">
        <thead>
        <tr>
        <th class="cell" style="font-size:0.85rem;color:#042B83; text-align:center">Queue #</th>
            <th class="cell" style="font-size:0.85rem;color:#042B83; text-align:center">PO Number</th>
            <th class="cell" style="font-size:0.85rem;color:#042B83; text-align:center">Office</th>
            <!-- <th class="cell" style="font-size:0.85rem;color:#042B83; text-align:center">Issuance Date</th> -->
            <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Purpose</th>
            <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Date <?php echo $request === 'resubmission'? 'Resubmitted': 'Submitted'; ?></th>
            <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Submitted By</th>
            <!-- <th class="cell" style="font-size:0.75rem;color:#042B83">File</th> -->
            <?php
               if($request === 'received'){
                ?>
            <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Date Received</th>
                <?php
               }
            ?>
           
            <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Status</th>
            <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Remarks</th>
  
        </tr>
        </thead>
<tbody id="myTable">
  <?php
  if ($count_rows > 0) {
    $count = 0;
    while ($row = mysqli_fetch_array($sql)) {
      $count ++;
      $emp_id_po = $row['emp_id'];
      $fullname ='';
      $image ='';
      if($emp_id != null){
        $empsql = mysqli_query($conn, "SELECT * from tbl_employee where emp_id ='$emp_id_po' LIMIT 1");
        while($row2= mysqli_fetch_array($empsql)){
          $fullname = ucwords(strtolower($row2['first_name'] . ' ' . $row2['last_name']));
          $image = $row2['pic_emp_data'];
        }
      }
      $color_rem ='green';
      $approved_res = $row['approved_res'];
      $po_number = $row['po_number'];
      $po_office = $row['office'];
      $issuance_date = $request=='resubmission' ? $row['new_issuance_date'] :  $row['issuance_date'] ;
      $date_uploaded = $request=='resubmission' ? $row['new_submission_date'] :  $row['date_uploaded'] ;;
      $off = $row['office'];
      $purpose = $row['purpose'];
      $status = $row['status'];
      if($request == 'resubmission'){
        if($approved_res){
          $purpose = $row['purpose'];
          $status = $row['status'];
        } else {
          $purpose = $row['new_purpose'];
          $status = $row['new_status'];
        }
  
      }

      // $uploaded_file = $row['uploaded_file'];
      $remarks = $request ==='received' ? 'Received' : $row['remarks'];
      $po_id = $row['po_id'];
      if($remarks == 'Cancelled'){
        $color_rem ='red';
      } else if($remarks == 'Submitted' || $remarks == 'Resubmitted'){
        $color_rem ='blue';
      } else {
        //$color_rem ='green';
      }
      $date_received = $row['date_received'];
  ?>

  <?php



if($request == 'resubmission' && ($role !== 'coa')){
  ?>
        <tr onclick="location.href='view_resubmission.php?po_id=<?php echo $po_id; ?>'" style="cursor:pointer">
      
  <?php
} else {
  ?>
<tr onclick="location.href='view_po.php?po_number=<?php echo $po_number; ?>&office=<?php echo $po_office; ?>'" style="cursor:pointer">
  <?php
}
?>

      <td style="font-weight:bold"><?php echo $count; ?></td>
      <td style="font-weight:bold"><?php echo $po_number; ?></td>
      <td><?php echo $off; ?></td>

      <td style="font-size:0.7rem; width:25%; text-align:left"><?php echo $purpose; ?></td>
      <td><?php echo  $emp_id_po == null ? '' : date_format(date_create($date_uploaded),"M j, Y"); ?></td>
      <td><?php echo $fullname; ?></td>

    <?php
                if($request === 'received'){
                ?>
             <td><?php echo date_format(date_create($date_received),"M j, Y"); ?></td>
                <?php
               }
            ?>

      <td>  <?php
       if($emp_id_po != null ) {
?>
  <?php    
    if($status == 'on time'){
      ?>
      <span class="badge rounded-pill bg-success text-light"> <?php echo $status; ?></span>
      <?php
    } else if($status == 'late') {
      ?>
      <span class="badge rounded-pill bg-danger text-light"> <?php echo $status; ?></span>
      <?php
    } else if($status == 'cancelled') {
      ?>
  <span class="badge rounded-pill text-light" style="background-color:#FF6101;"> <?php echo $status; ?></span>
      <?php
} else {
  ?>
      <span class="badge rounded-pill  bg-dark text-light"> <?php echo $status; ?></span>
  <?php
}?>
<?php
       } 
      ?>
    </td>
        </td>
        <td style="font-weight:bold; color:<?php echo $color_rem; ?>; font-size:0.8rem"><?php echo $remarks; ?></td>

      
  <?php


    }
  }
  ?>
  </tr>
</table>
              </div><!-- End Bordered Tabs -->

       
      </div>

    </section>
  </main>




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

  

</body>

<script>
   function changeYear(){
            var x = document.getElementById("officeselect").value; 
            window.location.href='submissions.php?request=<?php echo $request; ?>&year='+x;
        }
  </script>

</html> 