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

  if ($_FILES['pdf_file']['error'] != 0) {
    echo 'Something wrong with the file.';
  } else {
    $po_id = $_POST['po_id'];
    $new_purpose = addslashes($_POST['purpose']);
    $emp_id = $_SESSION['emp_id'];
    $file_tmp =  addslashes(file_get_contents($_FILES['pdf_file']['tmp_name']));
    $new_issuance_date = $_POST['issuance_date'];
    $new_status = '';
    $new_submission_date =  date('Y-m-d');
    $new_deadline =  date('Y-m-d', strtotime($new_issuance_date. ' + 5 days'));
    $res_remarks = isset($_POST['res_remarks']) ? $_POST['res_remarks'] : '';
    $cur = date("Y-m-d H:i:s");
    $with_resubmission = 1;
    $remarks = 'Resubmitted';
    if (strtotime($new_submission_date) <= strtotime(date('Y-m-d', strtotime($new_deadline)))) {
      $new_status = 'on time';
    } else {
      $new_status = 'late';
    }


    $update_sql = mysqli_query($conn, "UPDATE tbl_pms_purchase_order SET new_purpose='$new_purpose', new_uploaded='$file_tmp',
    new_issuance_date='$new_issuance_date', new_status='$new_status', new_deadline='$new_deadline',
    new_submission_date='$cur', with_resubmission='$with_resubmission', remarks='$remarks', res_remarks='$res_remarks' WHERE po_id='$po_id'") or die("error");

    $comment_text = ' resubmitted a purchase order.';
    $is_notif = true;

    $sql_comment = mysqli_query($conn, "INSERT INTO tbl_pms_po_comment (comment_text, emp_id, po_id, is_notif) 
    VALUES ('$comment_text', '$emp_id', '$po_id', '$is_notif')") or die(mysqli_error($conn));

    if ($update_sql && $sql_comment) {
  ?>
      <script>
        swal({
          title: "Successfully resubmitted.",
          icon: "success",
        }).then((value) => {
          window.location.href='../views/view_resubmission.php?po_id=<?php echo $po_id; ?>';
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
  }

  ?>
</body>

</html>