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
    $po_number = $_POST['po_number'];
    $year = $_POST['year'];
    $office = $_POST['office'];
    $purpose = addslashes($_POST['purpose']);
    $emp_id = $_SESSION['emp_id'];
    $file_tmp =  addslashes(file_get_contents($_FILES['pdf_file']['tmp_name']));
    $issuance_date = $_POST['issuance_date'];
    $status = '';
    $remarks = 'Submitted';
    $submission_date =  date('Y-m-d');
    $deadline =  date('Y-m-d', strtotime($issuance_date. ' + 5 days'));
$cur = date("Y-m-d H:i:s");

    if (strtotime($submission_date) <= strtotime(date('Y-m-d', strtotime($deadline)))) {
      $status = 'on time';
    } else {
      $status = 'late';
    }

    $comment_text = ' submitted a purchase order.';
    $is_notif = true;


    $insert = mysqli_query($conn, "INSERT INTO tbl_pms_purchase_order (po_number, year, office, purpose, uploaded_file, issuance_date, emp_id,
    status, remarks, deadline, date_uploaded) VALUES ('$po_number', '$year', '$office', '$purpose', '$file_tmp', '$issuance_date', '$emp_id', '$status', '$remarks',
    '$deadline', '$cur')") or die(mysqli_error($conn));

    if ($insert) {
      $po_id = mysqli_insert_id($conn);
      $sql_comment = mysqli_query($conn, "INSERT INTO tbl_pms_po_comment (comment_text, emp_id, po_id, is_notif) 
      VALUES ('$comment_text', '$emp_id', '$po_id', '$is_notif')") or die(mysqli_error($conn));
        
  ?>
      <script>
        swal({
          title: "Successfully submitted.",
          icon: "success",
        }).then((value) => {
          history.back();
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
          window.location.href = "../views/purchase_order.php";
        });
      </script>
  <?php
    }
  }

  ?>
</body>

</html>