<!DOCTYPE html>
<html lang="en">
<head>
  <title>DILG Region 6 PMS</title>
  <link href="../assets/img/dilg_logo.png" rel="icon">
  <link rel="shortcut icon" type="image/x-icon" href="../assets/img/dilg_logo.png" />

</head>
<body>

<?php
session_start();
include '../db/connect.php';
if (!$_SESSION['is_login'] || !isset($_SESSION['user_name'])) {                        
  header('Location: index.php');
} 
$po_id = $_GET['po_id'];
$method = $_GET['method'];
$sql = "SELECT * from tbl_pms_purchase_order WHERE po_id='$po_id'LIMIT 1";
$result = mysqli_query($conn, $sql);	
$file_upload = '';
while ($myrowsel = mysqli_fetch_array($result)) 
   {
        header("Content-Type: application/pdf");

        if($method === 'resubmission'){
          echo $myrowsel['new_uploaded'];
        } else {
          echo $myrowsel['uploaded_file'];
        }
        exit();
   }
?>

</body>
</html>

