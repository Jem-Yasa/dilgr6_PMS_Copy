<?php
session_start();
include '../db/connect.php';
include '../actions/functions.php';
date_default_timezone_set('Asia/Manila');
$emp_id = $_SESSION['emp_id'];
$cur_year = date("Y");
$cur_month = date("Y-m");
$check = is_pms_user($emp_id, $conn);
$role = $check ? $check['pms_role'] : '';
$office = $check ? $check['pms_office'] : '';


if (!isset($_SESSION['is_login']) || !isset($_SESSION['emp_id'])) {
  header('Location: ../index.php');
} else {
  if (!is_pms_user($emp_id, $conn)) {
    header('Location: error404.html');
  }
}

if (isset($_GET['office'])) {
  $office = $_GET['office'];
}
if (isset($_GET['month'])) {
  $cur_month = $_GET['month'];
}

$report_data = get_monthly_report($conn, $cur_month, $office);
$cur_month_str = date('F Y', strtotime($cur_month));
?>
<html>

<head>
  <title>DILG Region 6 PMS</title>
  <link href="../assets/img/dilg_logo.png" rel="icon">
  <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
  <script src="html2pdf.bundle.min.js"></script>
  <script defer src="../assets/plugins/fontawesome/js/all.min.js"></script>
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>|
  <link href="../assets/css/style.css" rel="stylesheet">
  <link href="../assets/css/portal.css" rel="stylesheet">
  <link href="../assets/css/datepicker.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <style>
    table,
    tr,
    td,
    th {
      border: 1px solid black;
      border-collapse: collapse;
    }

    .table td,
    .table th {
      padding: 0.2rem;
      vertical-align: top;
      border-top: 1px solid #dee2e6;
      font-size: 0.65rem;
    }

    td {
      padding: 0;
      margin: 0;

    }

    table {
      border-collapse: collapse;
      border-spacing: 0px;
      color: black;
      font-weight: 500;
      text-align: center;
    }

    body {
      color: black;
      font-weight: 500;
    }
  </style>
</head>

<body>
  <script>
    function createPDF() {
      document.getElementById("export").style.display = "block";
      const docElement = document.getElementById('export');
      html2pdf()
        .set({
          filename: 'submissions_report.pdf',
          margin: [0.3, 0.3, 0.3, 0.3],
          pagebreak: {
            avoid: ['tr', 'td']
          },

          image: {
            type: 'jpeg',
            quality: 4
          },
          jsPDF: {
            orientation: 'portrait',
            unit: 'in',
            format: 'A4'
          },
          html2canvas: {
            useCORS: true,
            scale: 10,
            size: 'A4'

          }
        })
        .from(docElement)
        .save();

      setTimeout(function() {
        document.getElementById("export").style.display = "none";
        history.back();
      }, 1000);
    }
  </script>
  <div id="export">
  <p style="text-align: center; margin-bottom:3px;">
                    <img src="../assets/img/dilg_logo.png" width=50 class="center">  <img src="../assets/img/bagong-pilipinas-logo.png" width=50 class="center">
    </p>
    <h5 class="card-title" style="text-align:center; font-size:0.9rem; margin-top:-20px"> PURCHASE ORDER SUBMISSION REPORT
              </h5>
              <p style="text-align:center; margin-top:-23px; font-weight:500; padding-bottom:10px;font-size:0.8rem"> For the Month of <?php echo $cur_month_str; ?>
    </p>
    <p style="text-align:center; margin-top:-25px; font-weight:500; font-size:0.75rem"> <?php echo strtoupper($office); ?>
    </p>

    <table class="table table-borderless datatable dataTable-table">
      <thead>
        <tr>
          <th scope="col" data-sortable=""><a href="#" class="dataTable-sorter">PO Number</a></th>
          <th scope="col" data-sortable=""><a href="#" class="dataTable-sorter">Purpose</a></th>
          <th scope="col" data-sortable=""><a href="#" class="dataTable-sorter">Date of Issuance</a></th>
          <th scope="col" data-sortable=""><a href="#" class="dataTable-sorter">Date Submitted</a></th>
          <th scope="col" data-sortable=""><a href="#" class="dataTable-sorter">Submitted By</a></th>
          <th scope="col" data-sortable=""><a href="#" class="dataTable-sorter">Status</a></th>
          <th scope="col" data-sortable=""><a href="#" class="dataTable-sorter">Remarks</a></th>
        </tr>
        <?php
        $sql_final = mysqli_query($conn, $report_data['query_str']) or die(mysqli_error($conn));
        $count_rows = mysqli_num_rows($sql_final);

        if ($count_rows > 0) {
          while ($row = mysqli_fetch_array($sql_final)) {
            $emp_id = $row['emp_id'];
            $fullname = '';
            $image = '';
            $me = '';
            $color_rem = 'black';
            if ($emp_id != null) {
              $empsql = mysqli_query($conn, "SELECT * from tbl_employee where emp_id ='$emp_id' LIMIT 1");
              while ($row2 = mysqli_fetch_array($empsql)) {
                $fullname = ucwords(strtolower($row2['first_name'] . ' ' . $row2['last_name']));
                $image = $row2['pic_emp_data'];
                if ($_SESSION['emp_id'] === $row2['emp_id']) {
                  $me = '(me)';
                } else {
                  $me = '';
                }
              }
            }

            $po_number = $row['po_number'];
            
            $po_office = $row['office'];
            $issuance_date = $row['issuance_date'];
            $date_uploaded = $row['date_uploaded'];
            $purpose = $row['purpose'];
            // $uploaded_file = $row['uploaded_file'];
            $remarks = $row['remarks'];
            $status = $row['status'];
            $po_id = $row['po_id'];
            if ($remarks == 'Cancelled') {
              $color_rem = 'red';
            } else if ($remarks == 'Submitted') {
              $color_rem = 'blue';
            } else {
              $color_rem = 'green';
            }
        ?>

<tr onclick="location.href='view_po.php?po_number=<?php echo $po_number; ?>&office=<?php echo $po_office; ?>&'" style="cursor:pointer">
              <td><?php echo $po_number; ?></td>
              <td style="width:27%; text-align:left; font-size:0.5rem"><?php echo $purpose; ?></td>
              <td><?php echo date_format(date_create($issuance_date), "F j, Y"); ?></td>
              <td><?php echo  $emp_id === null  || $emp_id === '' ? '' : date_format(date_create($date_uploaded), "F j, Y"); ?></td>
              <td><?php echo $fullname; ?> <span style="color:#808080; font-size:0.7rem"> <?php echo $me; ?></td>
              <td><?php
                  if ($status == 'on time') {
                  ?>
                  <span class="badge rounded-pill bg-success text-light"> <?php echo $status; ?></span>
                <?php
                  } else if ($status == 'late') {
                ?>
                  <span class="badge rounded-pill bg-danger text-light"> <?php echo $status; ?></span>
                <?php
                  } else if ($status == 'cancelled') {
                ?>
                  <span class="badge rounded-pill text-light" style="background-color:#FF6101;"> <?php echo $status; ?></span>
                <?php
                  } else {
                ?>
                  <span class="badge rounded-pill  bg-dark text-light"> <?php echo $status; ?></span>
                <?php
                  } ?>
              </td>
              <td style="font-weight:bold; color:<?php echo $color_rem; ?>; font-size:0.6rem"><?php echo $remarks; ?></td>
            </tr>
        <?php

          }
        }
        ?>
      </thead>
      <tbody>


      </tbody>
    </table>




    <p style="margin-top:20px; color:grey; font-size:0.6rem;">
      <a style="color:gray" href="https://pms.region6.dilg.gov.ph">https://pms.region6.dilg.gov.ph </a> &ThickSpace;
      Date Generated: <?php echo date_format(date_create(date('Y-m-d H:i:s')), "j-M-y h:i A");; ?>
    </p>

  </div>
  <!-- Vendor JS Files -->

  <script>
  createPDF();
  </script>
</body>

</html>