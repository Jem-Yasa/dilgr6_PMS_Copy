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
  $method = $_GET['method'];
  $po_id = $_GET['po_id'];
  $resetval = '';
  $str_query = '';

  if($method === 'approve'){
    $select = mysqli_query($conn, "SELECT * FROM tbl_pms_purchase_order where po_id='$po_id' LIMIT 1");
    if($select){
        while ($row = mysqli_fetch_array($select)){
            $new_purpose = $row['new_purpose'];
            $new_issuance_date = $row['new_issuance_date'];
            $new_status='';
            $new_submission_date =  $row['new_submission_date']; 
            $new_deadline = $row['new_deadline'];

            if (strtotime($new_submission_date) <= strtotime(date('Y-m-d', strtotime($new_deadline)))) {
                $new_status = 'on time';
              } else {
                $new_status = 'late';
              }

          
            $str_query = "UPDATE tbl_pms_purchase_order SET new_purpose='$resetval', date_uploaded='$new_submission_date',
            new_issuance_date='$resetval', new_status='$resetval', new_deadline='$resetval', 
            purpose = '$new_purpose', issuance_date='$new_issuance_date', status='$new_status',
            deadline='$new_deadline', approved_res=1 WHERE po_id='$po_id'";

        }
    }
   
  } else {
    $str_query = "UPDATE tbl_pms_purchase_order SET new_purpose='$resetval', new_uploaded='$resetval',
    new_issuance_date='$resetval', new_status='$resetval', new_deadline='$resetval',
    new_submission_date='$resetval', resubmission_comment='$resetval', with_resubmission=0
    WHERE po_id='$po_id'";
  }


    $update_sql = mysqli_query($conn, $str_query) or die(mysqli_error($conn));

    $comment_text = '';
    if($method === 'approve'){
      $comment_text = ' approved this purchase order resubmission.';
    } else {
      $comment_text = ' declined this purchase order resubmission.';
    }

    $is_notif = true;
    $emp_id = $_SESSION['emp_id'];
    $sql_comment = mysqli_query($conn, "INSERT INTO tbl_pms_po_comment (comment_text, emp_id, po_id, is_notif) 
    VALUES ('$comment_text', '$emp_id', '$po_id', '$is_notif')") or die(mysqli_error($conn));
    


    if ($update_sql && $sql_comment) {
  ?>
      <script>
        swal({
          title: "Resubmission has been <?php  echo $method ==='approve' ? 'approved': 'declined'; ?> successfully.",
          icon: "success",
        }).then((value) => {
         window.location.href='../views/purchase_order.php';
        });
      </script>
    <?php
    } else {
    ?>
      <script>
        swal({
          text: "Failed to submit. Please try again later.",
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