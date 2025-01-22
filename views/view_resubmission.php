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
$cur_user = display_user($emp_id, $conn);
$fullname = $emp_id ? $cur_user['first_name'] . ' ' . $cur_user['last_name'] : '';
$image = $emp_id ? $cur_user['pic_emp_data'] : '';
$username = $emp_id ? $cur_user['user_name'] : '';
$po_id = $_GET['po_id'];
$po_number;
$po_fullname;
$po_image;
$issuance_date;
$date_uploaded;
$purpose;
$uploaded_file;
$remarks;
$status;
$deadline;
$emp_id;
$office_po;
$with_resubmission;
$resubmission_comment;
$resub_remarks;
$sql = mysqli_query($conn, "SELECT * FROM tbl_pms_purchase_order WHERE po_id='$po_id' LIMIT 1");

if ($sql) {
    while ($row = mysqli_fetch_array($sql)) {
        $po_number = $row['po_number'];
        $po_emp_id = $row['emp_id'];
        $with_resubmission =  $row['with_resubmission'];
        $issuance_date = $row['new_issuance_date'];
        $date_uploaded = $row['new_submission_date'];
        $purpose = $row['new_purpose'];
        $uploaded_file = $row['new_uploaded'];
        $remarks = $row['remarks'];
        $status = $row['new_status'];
        $deadline = $row['new_deadline'];
        $office_po = $row['office'];
        $resub_remarks = $row['res_remarks'];
    }
}
if($role === 'encoder' && $office_po !== $office){
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
    <!-- <link href="../assets/css/portal.css" rel="stylesheet"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        
        #card:hover {
            transform: translate3D(0, -1px, 0) scale(1.05);
            cursor: pointer;
        }
        .nav-tabs-bordered .nav-link.active {
            background-color: #fff;
            color: #4154f1;
            border: none;
            border-bottom: 2px solid #4154f1;
            font-weight: 600;
        }

        .pdf {
margin-top:10px;
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

        .pdf_view {
            width: 100%;
            height: 600px;
        }

        th {
            width: 20%;
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
        <div class="pagetitle">
            <h1><?php echo  $office. ' - P.O. No. ' .$po_number; ?>
            <?php
      
            if($role === 'admin' || $role === 'sub-admin'){
              
                ?>
  <span style="float:right; margin-right:20px;">
    <button class="btn btn-secondary rounded-pill"  onclick="declineSubmission()" 
    style="font-weight:bold; font-size:1rem">
    Decline</button>
    <button class="btn btn-success rounded-pill" onclick="approveSubmission()"
    style="font-weight:bold; font-size:1rem">
    Approve</button>
     </span>
        <?php
            }
            ?>  
            </h1>
            <nav>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="purchase_order.php">Purchase Order</a></li>

                
                    <li class="breadcrumb-item active"><a href="view_po.php?po_number=<?php echo $po_number; ?>&office=<?php echo $office_po; ?>">P.O. No. <?php echo $po_number; ?></a> </li>
                    <li class="breadcrumb-item active">Resubmission Request</li>
                </ol>
            </nav>
        </div>
        <section class="section dashboard">
            <div class="align-items-center justify-content-between; ">
      <h6 style="padding:10px 10px">Original Submission: <a style="font-weight:bold; text-decoration:underline" href="view_po.php?po_number=<?php echo $po_number; ?>&office=<?php echo $office; ?>">P.O. No. <?php echo $po_number; ?></a></h6>
            <h5 style="font-size:1.2rem; font-weight:bold; color:white; padding: 20px; background-color:#E84D4D; text-align:center">
        RESUBMISSION REQUEST</h5>
                <div class="card p-4" style="text-align:left; background-color:#F4E1E1">
                    <h3 style="font-size:1.5rem; font-weight:bold">PURCHASE ORDER <span style="color:red;"> (Pending Approval)</span></h3>
                    <?php
                    if($po_emp_id !== null){
                        ?>
                    <table style="text-align:left">
                        <tr>
                            <th>P.O. No.:</th>
                            <td> <?php echo $po_number; ?></td>
                        </tr>
                        <tr>
                            <th>Issuance Date:</th>
                            <td> <?php echo date_format(date_create($issuance_date), "F j, Y"); ?></td>
                        </tr>
                        <tr>
                            <th>Purpose:</th>
                            <td> <?php echo $purpose; ?></td>
                        </tr>
                        <tr>
                            <th>Deadline:</th>
                            <td> <?php echo date_format(date_create($deadline), "F j, Y"); ?></td>
                        </tr>
                        <tr>
                            <th>Date Submitted:</th>
                            <td> <?php echo date('F j, Y, h:i a', strtotime($date_uploaded)); ?></td>
                        </tr>

                        <tr>
                            <th>Date Resubmitted:</th>
                            <td> <?php echo date('F j, Y, h:i a', strtotime($date_uploaded)); ?></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td> <span class="<?php
                                                if ($status == 'on time') {
                                                    echo "badge rounded-pill bg-success text-light";
                                                }else if($status == 'for approval') {
                                                    echo "badge rounded-pill bg-warning text-light";
                                                } else {
                                                    echo "badge rounded-pill bg-danger text-light";
                                                } ?>">
                                    <?php echo $status; ?></span></td>
                        </tr>
                        <tr>
                            <th>Remarks:</th>
                            <td> <?php echo $remarks; ?></td>
                        </tr>

                   
                        <tr>
                            <th>Submitted by:</th>
                            <td style="padding-bottom:5px"> 

                            <?php
                            if ($image == '' || $image == null) {
                            ?>
                                <img src="../assets/img/no-photo.jpg" class="rounded-circle" style="width:50px; height:50px">
                            <?php
                            } else {
                                echo '<img src="data:image;base64,' . base64_encode($image) . '" class="rounded-circle"
                                style="width:50px; height:50px">';
                            }

                           echo ' ' .$fullname;
                            ?>
                            </td>
                        </tr>
                                            
                        <tr>
                                <th>Resubmission Remarks:</th>
                                <td> <?php echo $resub_remarks; ?></td>
                            </tr>
                        <br>
                           
                    </table>
                   
           
                    <?php
                    } else {
                        ?>
                    <h5 style="text-align:center; padding-top:100px; padding-bottom:100px; font-size:0.9rem">No submission as of the moment.</h5>

                    <?php
                    }
                    ?>

                </div>
            </div>



            <h5 style="margin-bottom:15px; font-weight:bold; font-size:1rem">Uploaded File/s</h5>
            <div onclick="openFile('<?php echo $po_id; ?>')" class="card" style="width: 18rem;" id="card">
                <img src="../assets/img/pdf.jpg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"> <?php echo  $office . ' - P.O. No. ' . $po_number; ?></h5>
                    <p class="card-text" style="font-size:0.8rem; margin-top:-20px">Date Submitted: <span style="font-weight:bold"> &ThickSpace;<?php echo $date_uploaded; ?></span></p>
                    <p class="card-text" style="font-size:0.8rem;"><span style="font-weight:bold; color:red"> &ThickSpace;RESUBMITTED</span></p>
                </div>
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


<script>
function declineSubmission(){
    swal({
        title: "Decline Resubmission",
      text: "Are you sure you want to decline resubmission?",
      icon: "warning",
      confirmButtonColor: "#DD6B55",
      buttons: [
        'CANCEL',
        'DECLINE'
      ],
    }).then(function(isConfirm) {
      if (isConfirm) {
        window.location.href = "../actions/manage_resubmission.php?method=decline&po_id=<?php echo $po_id; ?>";
      }
    });
  }

function approveSubmission(){
    swal({
      title: "Confirm Submission Approval",
      icon: "info",
      buttons: [
        'CANCEL',
        'APPROVE'
      ],
    }).then(function(isConfirm) {
      if (isConfirm) {
        window.location.href = "../actions/manage_resubmission.php?method=approve&po_id=<?php echo $po_id; ?>";
      }
    });
  }

    function openFile(po_id){
        window.open("po_file.php?po_id="+ po_id + '&method=resubmission', '_blank');
    }

</script>



</body>

</html>