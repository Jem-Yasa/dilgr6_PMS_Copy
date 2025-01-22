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
include '../actions/functions.php';
$user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
$user_pass =  mysqli_real_escape_string($conn, $_POST['user_pass']);

$query = mysqli_query($conn, "SELECT * FROM tbl_employee 
WHERE user_name = '$user_name' and user_pass = '$user_pass' LIMIT 1") or die ("Can't query the database");
$count = mysqli_num_rows($query);

if($count === 1){
    while($profilerow= mysqli_fetch_array($query)){
    if(!is_pms_user($profilerow['emp_id'], $conn)){
        header('Location: ../views/error404.html');  
    }  else {
        $_SESSION['emp_id'] = $profilerow['emp_id'];
        $_SESSION['user_name'] = $profilerow['user_name'];
        $_SESSION['is_login'] = true; 
        ?>
        <script>
            swal({
            text: "Successfully logged in.",
            icon: "success",
            }).then((value) => {
            window.location.href="../views/dashboard.php";
            });
        </script>
        <?php  
    }
    
    }   
} else {
    ?>
    <script type="text/javascript">
        swal({
            title: "Invalid credentials",
            text: "Please try again. Make sure your username and password is correct.",
            icon: "error",
            button: "Okay",
        }).then((value) => {
            window.location.href="../index.php";
        });
    </script>
    <?php   
}










 
   

?>

</body>
</html>