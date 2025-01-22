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
    include '../db/connect.php';
    $procurement_user_id = $_GET['procurement_user_id'];

    $sql = mysqli_query($conn, "DELETE FROM tbl_pms_user WHERE procurement_user_id='$procurement_user_id'") or die(mysqli_error($conn));


    if ($sql) {
    ?>
        <script type="text/javascript">
            swal({
                title: "Removed successfully.",
                icon: "success"
            }).then((value) => {
               window.location.href="../views/app_settings.php";
            });
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            swal({
                text: "Failed to remove.  <?php echo mysqli_error($conn); ?>",
                icon: "error",
                button: "Okay",
            }).then((value) => {
                window.location.href="app_settings.php";
            });
        </script>
    <?php
    }


    ?>

</body>

</html>