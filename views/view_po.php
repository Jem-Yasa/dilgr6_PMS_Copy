<?php
session_start();
include '../db/connect.php';
include '../actions/functions.php';

$emp_id = $_SESSION['emp_id'];

$check = is_pms_user($emp_id, $conn);
$role = $check ? $check['pms_role'] : '';
$role_office = $check ? $check['pms_office'] : '';
$po_number = $_GET['po_number'];
$office = $_GET['office'];
$po_id = '';
$cur_user = display_user($emp_id, $conn);
$fullname = $emp_id ? ucwords($cur_user['first_name'] . ' ' . $cur_user['last_name']) : '';
$image = $emp_id ? $cur_user['pic_emp_data'] : '';


$year = explode("-",$po_number)[0];


$po_owner_name = '';
$po_owner_image = '';
$issuance_date ='';
$date_uploaded = '';
$purpose  = '';
$uploaded_file = '';
$remarks = '';
$status = '';
$deadline = '';
$with_resubmission = '';
$new_submission_date= '';
$new_uploaded='';
$po_emp_id= '';
$office_po= '';
$coa_received= '';
$res_remarks= '';
$approved_res= '';
$date_received= '';

$check_po_uploaded = mysqli_query($conn, "SELECT * FROM tbl_pms_purchase_order
                           WHERE po_number='$po_number' AND office='$office' LIMIT 1");

if(mysqli_num_rows($check_po_uploaded) == 1){
    while($each = mysqli_fetch_array($check_po_uploaded)){
        $po_id= $each['po_id'];
    }
}

if($po_id != ''){
    $po_details = getPODetails($po_id, $conn);
    $po_number = $po_details['po_number'];
    $po_owner_name = $po_details['po_owner_name'];
    $po_owner_image = $po_details['po_owner_image'];
    $issuance_date = $po_details['issuance_date'];
    $date_uploaded = $po_details['date_uploaded'];
    $purpose  = $po_details['purpose'];
    $uploaded_file = $po_details['uploaded_file'];
    $remarks = $po_details['remarks'];
    $status = $po_details['status'];
    $deadline = $po_details['deadline'];
    $with_resubmission = $po_details['with_resubmission'];
    $new_submission_date= $po_details['new_submission_date'];
    $new_uploaded= $po_details['new_uploaded'];
    $po_emp_id= $po_details['po_owner_emp_id'];
    $office_po= $po_details['office'];
    $coa_received= $po_details['coa_received'];
    $res_remarks= $po_details['res_remarks'];
    $approved_res= $po_details['approved_res'];
    $date_received= $po_details['date_received'];
}



if($role === 'encoder' && $role_office !== $office){
    header('Location: error404.html');  
} 

if (!isset($_SESSION['is_login']) || !isset($_SESSION['emp_id'])) {
    header('Location: ../index.php');
  } else {
    if(!is_pms_user($emp_id, $conn)){
      header('Location: error404.html');  
    } 
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
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
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
    .user-img {
        margin-top: 4px;
    }

    .nav-tabs-bordered .nav-link.active {
        background-color: #fff;
        color: #4154f1;
        border: none;
        border-bottom: 2px solid #4154f1;
        font-weight: 600;
    }

    .pdf {
        margin-top: 10px;
        width: 45px;
        height: 52px;
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

    .container:hover .pdf {
        transform: scale(1.4);
    }

    th {
        width: 20%;
    }

    #card:hover {
        transform: translate3D(0, -1px, 0) scale(1.05);
        cursor: pointer;
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
            <h1><a href="#" onclick="checkBack()" ><i class="bx bx-arrow-back"> </i> </a> &ThickSpace;
                <?php echo  $office . ' - P.O. No. ' . $po_number; ?>

                <span style="float:right; margin-right:20px;">

                    <?php
                    if ($po_id == '' && $role !== "coa") {
                       

                    ?>
                    <button class="btn btn-dark rounded-pill" data-bs-toggle="modal" data-bs-target="#submitPO"
                        style="font-weight:bold; font-size:1rem">
                        <i class="bi bi-cloud-upload"></i> Submit</button>
                    <?php
                        
                    } else {
                        if($remarks === 'Cancelled') {
                            ?>
                    <span
                        style="margin:10px; color:#CB1000; font-size:1.5rem; font-weight:800; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif"><?php echo strtoupper($remarks); ?>
                    </span>
                    <?php
                        } else {
                            ?>
                    <span style="margin:10px; color:green; font-size:1.1rem; font-weight:600"><?php echo $remarks; ?>
                    </span>
                    <?php
                        }  
                        
                    }
                    ?>



                </span>


            </h1>

            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="purchase_order.php">Purchase Order</a></li>
                    <li class="breadcrumb-item active">P.O. No. <?php echo $po_number; ?></li>

                </ol>
            </nav>
        </div>


        <section class="section dashboard">
            <div class="align-items-center justify-content-between; ">

                <div class="card p-4" style="text-align:center;">
                    <div class="filter">
                        <?php 
                    if(true){
                        ?>
                        <a style="cursor:pointer" class="icon" href="#" data-bs-toggle="dropdown"><i
                                class="bi bi-three-dots" style="font-size:1.7rem"></i></a>
                        <?php

                    }
                    ?>


                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">


                            <?php
                    if (!$po_emp_id && $role != "coa") {
                        ?>
                            <li onclick=""><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#submitPO">Submit</a></li>
                            <?php
                    }
                    if ($po_emp_id != '' && $role != "coa" && !$with_resubmission && $coa_received) {
                        ?>
                            <li onclick=""><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Resubmit</a></li>
                            <?php
                    }
                    if ($po_emp_id != '' && $role != "coa" && !$coa_received && $status != 'cancelled') {
                        ?>
                            <li onclick="manageAction('<?php echo $po_id; ?>', 'remove')"><a class="dropdown-item"
                                    href="#">Remove submission</a></li>
                            <?php
                    }
                    if ($po_emp_id != '' && $role === "coa" && $status != 'cancelled'&& (!$coa_received || ($with_resubmission && $coa_received))) {
                        ?>

                            <li onclick="manageAction('<?php echo $po_id; ?>', 'received')"><a class="dropdown-item"
                                    href="#">Mark as Received</a></li>
                            <?php
                    }
                     if ($po_emp_id != '' && $role === "encoder" && $status != 'cancelled') {
                        ?>
                            <li onclick="manageAction('<?php echo $po_id; ?>', 'cancelled')"><a class="dropdown-item"
                                    href="#">Mark as Cancelled</a></li>
                            <?php
                    }
                    ?>

                            <!-- <li onclick="location.href='submissions.php?request=pending'"><a class="dropdown-item" href="#">Cancel Resubmission</a></li> -->
                            <!-- <li onclick="location.href='submissions.php?request=pending'"><a class="dropdown-item" href="#">Remove Submission</a></li> -->

                        </ul>
                    </div>
                    <h3 style="font-size:1.3rem; font-weight:bold">PURCHASE ORDER DETAILS</h3>

                    <?php
                    if ($po_emp_id) {
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

                        <?php
                            if ($new_submission_date) {
                            ?>
                        <tr>
                            <th>Date Resubmitted:</th>
                            <td> <?php echo date('F j, Y, h:i a', strtotime($date_uploaded)); ?></td>
                        </tr>
                        <?php
                            }
                            ?>




                        <tr>
                            <th>Submitted by:</th>
                            <td style="padding-bottom:5px">

                                <?php
                                    if ($po_owner_image == '' || $po_owner_image == null) {
                                    ?>
                                <img src="../assets/img/no-photo.jpg" class="rounded-circle"
                                    style="width:50px; height:50px">
                                <?php
                                    } else {
                                        echo '<img src="data:image;base64,' . base64_encode($po_owner_image) . '" class="rounded-circle"
                                style="width:50px; height:50px">';
                                    }

                                    echo ' ' . $po_owner_name;
                                    ?>
                            </td>
                        </tr>
                        <?php
                            if ($with_resubmission && !$approved_res) {
                            ?>
                        <tr>
                            <th>Resubmission Request:</th>
                            <td> <a style="font-weight:bold; text-decoration:underline; font-size:0.9rem; color:red"
                                    href="view_resubmission.php?po_id=<?php echo $po_id; ?>">
                                    P.O No. <?php echo $po_number . ' Resubmission (pending approval)'; ?></a></td>
                        </tr>
                        <?php
                            }
                            ?>

                        <?php
                            if ($res_remarks) {
                            ?>
                        <tr>
                            <th>Resubmission Remarks:</th>
                            <td> <?php echo $res_remarks; ?></td>
                        </tr>
                        <?php
                            }
                            if ($coa_received) {
                                ?>
                        <tr>
                            <th>Date Received:</th>
                            <td> <?php echo date('F j, Y, h:i a', strtotime($date_received)); ?></td>
                        </tr>
                        <?php
                                }
                            ?>
                        <tr>
                            <th>Status:</th>
                            <td> <span class="<?php
                                                    if ($status == 'on time') {
                                                        echo "badge rounded-pill bg-success text-light";
                                                    } else if ($status == 'for approval') {
                                                        echo "badge rounded-pill bg-warning text-light";
                                                    } else {
                                                        echo "badge rounded-pill bg-danger text-light";
                                                    } ?>">
                                    <?php echo $status; ?></span></td>
                        </tr>

                    </table>
                    <?php
                    } else {
                    ?>
                    <h5 style="text-align:center; padding-top:100px; padding-bottom:100px; font-size:0.9rem">No
                        submission as of the moment.</h5>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <?php
            if ($po_emp_id && !$new_uploaded) {
            ?>
                <h5 style="margin-bottom:15px; font-weight:bold; font-size:1.1rem">Uploaded File/s</h5>


                <div onclick="openFile('<?php echo $po_id; ?>', 'submission', '<?php echo $coa_received; ?>')"
                    class="card" style="width: 18rem; margin-right:2rem; margin-left:1rem;" id="card">
                    <img src="../assets/img/pdf.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"> <?php echo  $office_po . ' - P.O. No. ' . $po_number; ?></h5>
                        <p class="card-text" style="font-size:0.8rem; margin-top:-20px">Date Submitted: <span
                                style="font-weight:bold"> &ThickSpace;<?php echo $date_uploaded; ?></span></p>
                    </div>
                </div>
                <?php
            }
            if($new_uploaded) {
            ?>
                <div onclick="openFile('<?php echo $po_id; ?>', 'resubmission', '<?php echo $coa_received; ?>')"
                    class="card" style="width: 18rem;" id="card">
                    <img src="../assets/img/pdf.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"> <?php echo  $office_po . ' - P.O. No. ' . $po_number; ?></h5>
                        <p class="card-text" style="font-size:0.8rem; margin-top:-20px">Date Submitted: <span
                                style="font-weight:bold"> &ThickSpace;<?php echo $date_uploaded; ?></span></p>
                        <p class="card-text" style="font-size:0.8rem;"><span style="font-weight:bold; color:red">
                                &ThickSpace;RESUBMITTED</span></p>
                    </div>
                </div>
                <?php
            }
            ?>


            </div>

            <?php
            if($po_id != ''){
                ?>

              
            <div class="container mt-5 pb-0">
                <div class="row  d-flex justify-content-center">
                    <div class="row">
                        <div class="headings d-flex justify-content-between align-items-center mb-3">
                            <h5>Activity</h5>

                        </div>
                        <?php
                        $comment_sql = mysqli_query($conn, "SELECT * FROM tbl_pms_po_comment
                          INNER JOIN tbl_employee ON tbl_pms_po_comment.emp_id=tbl_employee.emp_id 
                           WHERE po_id='$po_id' ORDER BY date_added ASC");
                        if ($comment_sql) {
                            while ($row = mysqli_fetch_array($comment_sql)) {
                                $fullname_commenter = ucwords(strtolower($row['first_name'] . ' ' . $row['last_name']));
                                $image_commenter = $row['pic_emp_data'];
                                $commenter_uname = $row['user_name'];
                                $comment = $row['comment_text'];
                                $is_notif = $row['is_notif'];
                                $added = $row['date_added'];
                                $me = ($row['emp_id'] === $emp_id) ? '(me)' : '';
                        ?>
                        <div class="card pt-2 pr-3 border-0 mb-2" style="width:90%">
                            <div class="d-flex flex-start w-100">
                                <?php
                                        if ($is_notif) {
                                        ?>
                                <img src="../assets/img/dilg_logo.png" class="rounded-circle shadow-1-strong me-3"
                                    width="35" height="35">
                                <?php
                                        } else {


                                            if ($image_commenter == '' || $image_commenter == null) {
                                            ?>
                                <img src="../assets/img/no-photo.jpg" class="rounded-circle shadow-1-strong me-3"
                                    width="40" height="40">
                                <?php
                                            } else {
                                                echo '<img src="data:image;base64,' . base64_encode($image_commenter) . '" alt="avatar" width="40" height="40" 
                                         class="rounded-circle shadow-1-strong me-3">';
                                            }
                                        }
                                        ?>
                                <div class="form-outline w-100">
                                    <?php
                                            if (!$is_notif) {
                                            ?>
                                    <p style="margin-bottom:0"><span
                                            style="font-weight:600; font-size:0.9rem"><?php echo $fullname_commenter; ?></span>
                                        <small class="font-weight-bold text-primary"
                                            style="font-size:0.7rem; font-weight:bold">
                                            @<?php echo $commenter_uname; ?></small>
                                        <small style="color: #909090; font-size:0.7rem"><?php echo $me; ?></small>
                                        <span style="float:right; color: gray; font-size:0.9rem">
                                            <small><?php echo timeAgo($row['date_added']); ?></small></span>
                                    <p> <small class="font-weight-bold"><?php echo $comment; ?> </small></p>

                                    <p style="margin-top:-15px; color: gray; font-size:0.8rem"><span
                                            class=""><?php echo date('F j, Y, h:i a', strtotime($added)); ?> </span></p>
                                    <?php
                                            } else {
                                            ?>
                                    <span style="float:right; color: gray; font-size:0.9rem">
                                        <small><?php echo timeAgo($row['date_added']); ?></small></span>
                                    <p>

                                        <span
                                            style="font-weight:600; font-size:0.9rem"><?php echo $fullname_commenter; ?></span>
                                        <small style="font-size:0.7rem; font-weight:bold; "
                                            class="font-weight-bold text-primary">
                                            @<?php echo $commenter_uname; ?></small>
                                        <small class="font-weight-bold"><?php echo $comment; ?> </small>
                                    </p>
                                    <p style="margin-top:-15px; color: gray; font-size:0.8rem"><span
                                            class=""><?php echo date('F j, Y, h:i a', strtotime($added)); ?> </span></p>

                                    <?php
                                            }
                                            ?>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        }
                        ?>

                        <div class="card-footer py-3 border-0" style="background-color: #f8f9fa; width:91%">
                            <form action="../actions/post_comment.php" method="POST">
                                <input type="hidden" name="emp_id" value="<?php echo $emp_id; ?>" />
                                <input type="hidden" name="po_id" value="<?php echo $po_id; ?>" />


                                <div class="d-flex flex-start w-100">
                                    <?php
                                    if ($image == '' || $image == null) {
                                    ?>
                                    <img src="../assets/img/no-photo.jpg" class="rounded-circle shadow-1-strong me-3"
                                        alt="avatar" width="40" height="40">
                                    <?php
                                    } else {
                                        echo '<img src="data:image;base64,' . base64_encode($image) . '" alt="avatar" width="40" height="40" 
                                         class="rounded-circle shadow-1-strong me-3">';
                                    }
                                    ?>
                                    <div class="form-outline w-100">
                                        <textarea class="form-control" id="textAreaExample" rows="4" name="comment_text"
                                            style="font-size:0.8rem"></textarea>
                                    </div>
                                </div>
                                <div class="float-end mt-2 pt-1">
                                    <button type="submit" class="btn btn-primary btn-sm">Post comment</button>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            }

            ?>

        </section>
    </main>




    <?php
    include("footer.php");
    ?>


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>
    <script src="../assets/js/main.js"></script>


    <!-- Submit PO Modal -->
    <div class="modal fade" id="submitPO" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <img src="../assets/img/dilg_logo.png" style="width:45px; margin-right:10px">
                    <h5 class="modal-title" style="font-weight:bold;">Submit Purchase Order </h5>
                    <button class="btn-close" data-bs-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <form method="POST" action="../actions/submit_po.php" enctype="multipart/form-data">
                    <input type='hidden' name="office" value='<?php echo $office; ?>'>
                    <input type='hidden' name="year" value='<?php echo $year; ?>'>
                    <input type='hidden' name="po_number" value='<?php echo $po_number; ?>'>
                    <div class="modal-body">

                        <div class="centered">
                            <h6>P.O. No.:</h6>
                            <input type="text" class="form-control" id="floatingInput" value="<?php echo $po_number; ?>"
                                disabled style="font-weight:bold; font-size:1.3rem">
                            <br>
                            <h6>Purpose:</h6>
                            <textarea name="purpose" class="form-control" style="height: 60px" required></textarea>
                            <br>
                            <h6>Date of issuance/ Date of Receipt:</h6>
                            <input type="date" class="form-control" id="issuance" name="issuance_date" required>

                            <br>
                            <br>
                            <h6>Select File to Upload (Only PDF File accepted):</h6>
                            <input style="margin-left:50px;" type="file" name="pdf_file" accept=".pdf" required />
                            <br> <br>


                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark rounded-pill"
                            data-bs-dismiss="modal">Close</button>

                        <button type="submit" style="border-radius: 30px 0px 30px 30px; width:120px; font-weight:500;"
                            class="btn btn-dark rounded-pill">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight:bold">Resubmit Purchase Order </h5>
                    <button class="btn-close" data-bs-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <form method="POST" action="../actions/resubmit_po.php" enctype="multipart/form-data">
                    <div class="modal-body">

                        <div class="centered">
                            <p style="color:red"><i>Note: Resubmission of PO is subject for approval.</i></p>
                            <h6>P.O. No.:</h6>
                            <input type="hidden" name="po_id" value="<?php echo $po_id; ?>">
                            <input type="text" class="form-control" id="floatingInput" value="<?php echo $po_number; ?>"
                                disabled style="font-weight:bold; font-size:1.3rem">
                            <br>
                            <h6>Purpose:</h6>
                            <textarea name="purpose" class="form-control"
                                style="height: 60px"><?php echo $purpose; ?></textarea>
                            <br>
                            <h6>Date of issuance:</h6>
                            <input type="date" class="form-control" id="issuance" name="issuance_date"
                                value="<?php echo $issuance_date; ?>" required>

                            <br>

                            <h6>Select File to Upload (Only PDF File accepted):</h6>
                            <input style="margin-left:50px;" type="file" name="pdf_file" accept=".pdf" required />
                            <br> <br>
                            <h6>Other remarks:</h6>
                            <textarea name="res_remarks" class="form-control" style="height: 40px"></textarea>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" style="border-radius: 30px 0px 30px 30px; width:120px; font-weight:500;"
                            class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        <button type="submit" style="border-radius: 30px 0px 30px 30px; width:120px; font-weight:500;"
                            class="btn btn-black">Resubmit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>







</body>
<script>
function checkBack() {
    var prev = document.referrer;
    if (prev.includes("manage_resubmission") || prev.includes("post_comment") || prev.includes("manage_sub")) {
        window.location.href = "purchase_order.php";

    } else {
        history.back();
    }
}

// for revision
function openFile(po_id, method, received) {
    var poRemarks = '<?php echo $remarks; ?>';
    if (received == 1 && poRemarks != 'Resubmitted') {
        window.open("po_received.php?po_id=" + po_id);
    } else {
        window.open("po_file.php?po_id=" + po_id + '&method=' + method, '_blank');
    }

}

function manageAction(po_id, action) {
    var str = '';
    var text_str = '';
    if (action == 'received') {
        str = 'Mark as Recieved?';
        text_str = 'Are you sure you want to mark this PO as received?.';
    } else if (action == 'cancelled') {
        str = 'Mark as Cancelled';
        text_str = 'Are you sure you want to mark this PO as cancelled? This cannot be undone.';
    } else {
        str = 'Remove Submission?';
        text_str =
            'Are you sure you want to remove submission? This will remove all the details and file attachment of the purchase order.';
    }

    swal({
        title: str,
        text: text_str,
        icon: "info",
        buttons: [
            'Cancel',
            'Confirm'
        ],
    }).then(function(isConfirm) {
        if (isConfirm) {
            window.location.href = "../actions/manage_sub.php?po_id=" + po_id + "&action=" + action;
        }
    });




}
</script>

</html>