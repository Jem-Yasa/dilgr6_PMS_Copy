<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>DILG Region 6 PMS</title>
  <meta charset="utf-8">
  <link rel="icon" href="../assets/img/dilg_logo.png" type="image/x-icon">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>|
</head>

<body>

  <?php
  session_start();
  include '../db/connect.php';
  date_default_timezone_set('Asia/Manila');
  $action = $_GET['action'];
  $po_id = $_GET['po_id'];
  $str_query = '';
  $date_rec = date("Y-m-d h:i:s");
  $filename;
  $curr_yr = date("Y");
  $office = '';
  $file_upload = '';
  $sql1 = "SELECT * from tbl_pms_purchase_order WHERE po_id='$po_id'LIMIT 1";
  $result = mysqli_query($conn, $sql1);	
  $str_action = $action == 'remove' ? 'removed' : $action;
  $remarks = '';
  $po_number = '';

  if($result){
    while ($myrowsel = mysqli_fetch_array($result)) 
    {
      $office = $myrowsel['office'];
      $po_number = $myrowsel['po_number'];
      $remarks = $myrowsel['remarks'];
         if($myrowsel['new_uploaded'] != ''){
           $file_upload= $myrowsel['new_uploaded'];

         } else {
           $file_upload= $myrowsel['uploaded_file'];
         }
    }
  }


  if($action ==='cancelled'){
    $str_query = "UPDATE tbl_pms_purchase_order SET status='cancelled', remarks='Cancelled' WHERE po_id='$po_id'";
  } else if($action === 'received') {
   
    $remarks = 'Received';
    $str_query = "UPDATE tbl_pms_purchase_order SET  remarks='Received', coa_received=1, approved_res=0, with_resubmission=0,
    date_received='$date_rec' WHERE po_id='$po_id'";
  } else {
    $str_query = "DELETE FROM tbl_pms_purchase_order WHERE po_id='$po_id'";
  }

  $sql = mysqli_query($conn, $str_query) or die(mysqli_error($conn));
  $text = $action === 'remove' ? ' remove purchase order submission.' : ' marked this purchase order submission as '.$action;
  $comment_text =  $text.'.';
  $emp_id = $_SESSION['emp_id'];
  $sql_comment = mysqli_query($conn, "INSERT INTO tbl_pms_po_comment (comment_text, emp_id, po_id, is_notif) 
  VALUES ('$comment_text', '$emp_id', '$po_id', 1)") or die(mysqli_error($conn));
    
    if ($sql && $sql_comment) {
  ?>
      <script>
        swal({
          title: "Submission has been <?php echo $str_action.' '; ?> successfully.",
          icon: "success",
        }).then((value) => {
          window.location.href='../views/view_po.php?po_number=<?php  echo $po_number; ?>&office=<?php  echo $office; ?>';
        });
      </script>
    <?php
    } else {
    ?>
      <script>
        swal({
          title: "Action Failed.",
          icon: "error",
        }).then((value) => {
            history.back();
        });
      </script>
  <?php
    
  }

  ?>
</body>

</html>