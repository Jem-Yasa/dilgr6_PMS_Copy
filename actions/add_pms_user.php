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
    $emp_id = $_POST['emp_id'];
    $pms_role = $_POST['pms_role'];
    $remarks = $_POST['remarks'];
    $office_prov = $_POST['office'];
    $sql = mysqli_query($conn, "INSERT INTO tbl_pms_user (emp_id, role, role_level_desc, office_prov) 
    VALUES ('$emp_id', '$pms_role ', '$remarks', '$office_prov')") or die(mysqli_error($conn));


    if ($sql) {
    ?>
        <script type="text/javascript">
            swal({
                title: "Successfully added.",
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
                text: "Failed to add.  <?php echo mysqli_error($conn); ?>",
                icon: "error",
                button: "Okay",
            }).then((value) => {
                window.location.href="../views/app_settings.php";
            });
        </script>
    <?php
    }


    ?>

</body>

</html>