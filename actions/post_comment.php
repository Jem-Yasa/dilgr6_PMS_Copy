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
  $comment_text = $_POST['comment_text'];
  $emp_id = $_POST['emp_id'];
  $po_id = $_POST['po_id'];


  $sql = mysqli_query($conn, "INSERT INTO tbl_pms_po_comment (comment_text, emp_id, po_id) 
  VALUES ('$comment_text', '$emp_id', '$po_id')") or die(mysqli_error($conn));
    
    if ($sql) {
  ?>
      <script>
        swal({
          text: "Successfully posted.",
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
          text: "Failed to post a comment.",
          icon: "error",
        }).then((value) => {
          window.location.reload(history.back());
      
        });
      </script>
  <?php
    
  }

  ?>
</body>

</html>