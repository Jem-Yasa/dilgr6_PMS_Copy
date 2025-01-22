<!DOCTYPE html>
<html lang="en">
<head>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>DILG Region 6 PMS</title>
    <link href="../assets/img/dilg_logo.png" rel="icon">
   
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>|
   
</head>
<body>

<?php
session_start();
include '../db/connect.php';
date_default_timezone_set('Asia/Manila'); 

if (!$_SESSION['is_login'] || !isset($_SESSION['user_name'])) {
    header('Location: index.php');
    exit();
}

$po_id = $_GET['po_id'];

// Fetch PO details
$sql = "SELECT * FROM tbl_pms_purchase_order WHERE po_id='$po_id' LIMIT 1";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$date_received = '';
$office = '';
$curr_yr = date("Y");

while ($row = mysqli_fetch_array($result)) {
    $date_received = $row['date_received'];
    $office = $row['office'];
}

// Fetch the PDF blob
$sql = "SELECT * FROM tbl_pms_purchase_order WHERE po_id = '$po_id' LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) === 0) {
    die("No PDF found for the given ID.");
}

$pdfBlob = null;
while ($row = mysqli_fetch_array($result)) {
    $pdfBlob = $row['uploaded_file'];
}

// Write the BLOB data to a temporary file
$tempFilePath = tempnam(sys_get_temp_dir(), 'pdf');
file_put_contents($tempFilePath, $pdfBlob);

require_once('../vendor/autoload.php');
ob_start();

$pdf = new \setasign\Fpdi\Fpdi();
$pages_count = $pdf->setSourceFile($tempFilePath);

for ($i = 1; $i <= $pages_count; $i++) {
    $pdf->AddPage();
    $tplIdx = $pdf->importPage($i);
    $pdf->useTemplate($tplIdx, ['adjustPageSize' => true]);

    if ($i == 1) {
      $pdf->SetFont('Helvetica', 'B', 16);
      $pdf->SetTextColor(141, 125, 193);
      $watermarkText = 'COA RECEIVED';
      $pdf->SetXY(180, 5);
      $pdf->Cell(1, 10,'COA - DILG', 0, 0, 'C');
      $pdf->SetFont('Helvetica', 'B', 12);
      $pdf->Cell(1, 18,'REGION VI', 0, 0, 'C');
      $pdf->SetFont('Helvetica', 'B', 18);
      $pdf->Cell(1, 27,'RECEIVED', 0, 0, 'C');
      $pdf->SetFont('Helvetica', 'B', 8);
      $pdf->Cell(1, 35,'DATE: '.$date_received, 0, 0, 'C');
    }
}

$pdf->Output();
ob_end_flush();

unlink($tempFilePath);

?>


</body>
</html>

