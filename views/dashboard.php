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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>DILG Region 6 PMS</title>
  <link href="../assets/img/dilg_logo.png" rel="icon">
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
  <link href="../assets/css/pms.css" rel="stylesheet">
  <!-- <link href="../assets/css/portal.css" rel="stylesheet"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


  <style>
    body {
      font-size: 0.85rem;
    }
  </style>
</head>

<body>
  <?php
  include("header.php");
  ?>
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link active" href="dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="purchase_order.php">
          <i class="bi bi-box-arrow-right"></i>
          <span>Purchase Order</span>
        </a>
      </li>

      <?php
      if ($role == 'admin') {
      ?>
        <li class="nav-item">
          <a class="nav-link collapsed" href="app_settings.php">
            <i class="ri-settings-2-line"></i>
            <span>Application Settings</span>
          </a>
        <?php
      }
        ?>
    
        <!-- End F.A.Q Page Nav -->
    </ul>
  </aside><!-- End Sidebar-->


  <main id="main" class="main">
    <div class="pagetitle" style="margin-top:-20px">
      <h1>Dashboard

        <span style="float:right; ">
          <button type="button" data-bs-toggle="modal" data-bs-target="#monthlyreport" class="btn btn-secondary" fdprocessedid="mhwoxj">Generate Report</button>
        </span>
        <span style="float:right;margin-right:20px; cursor:pointer; margin-top:5px " onclick="location.href='export_report.php?month=<?php echo $cur_month; ?>&office=<?php echo $office; ?>'">
          <i class="bi bi-download"></i>
        </span>
      </h1>
      <nav>
        <ol class="breadcrumb">

          <li class="breadcrumb-item active" style="font-size:1rem; font-weight:500; margin-top:5px">Submission of
            <span style="font-weight:600; color:blue; font-size:1.05rem">DILG <?php echo $office; ?></span> for the Month of <span style="font-weight:600; font-size:1.05rem; color:blue"><?php echo $cur_month_str; ?></span>
          </li>
        </ol>
      </nav>
    </div>


    <section class="section dashboard">

      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Export Data</h6>
                </li>

                <li><a class="dropdown-item" href="#">Submitted On time</a></li>
                <li><a class="dropdown-item" href="#">Submitted Late</a></li>
                <li><a class="dropdown-item" href="#">All</a></li>
              </ul>
            </div>
            <div class="card-body">
              <h1 class="card-title">Submission Timeframe <span>| <?php echo $cur_month_str; ?></span> </h1>

              <!-- Pie Chart -->
              <div id="pieChart" style="min-height: 380px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  echarts.init(document.querySelector("#pieChart")).setOption({
                    title: {
                      text: '',
                      subtext: 'Timeframe Status',
                      left: 'center'
                    },
                    tooltip: {
                      trigger: 'item'
                    },
                    legend: {
                      orient: 'vertical',
                      left: 'left'
                    },
                    series: [{
                      name: 'Status',
                      type: 'pie',
                      radius: '50%',
                      data: [{
                          value: <?php echo $report_data['ontime']; ?>,
                          name: 'On time',
                          color: 'success',
                          itemStyle: {
                            color: '#85DE85'
                          },
                        },
                        {
                          value: <?php echo $report_data['late']; ?>,
                          name: 'Late',
                          itemStyle: {
                            color: '#FB5E60'
                          },
                        },
                      ],
                      emphasis: {
                        itemStyle: {
                          shadowBlur: 10,
                          shadowOffsetX: 0,
                          shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                      }
                    }]
                  });
                });
              </script>
              <!-- End Pie Chart -->
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">Purchase Order Submission <span>| <?php echo $cur_month_str; ?></span></h5>

              <div id="trafficChart" style="min-height: 400px; -webkit-tap-highlight-color: transparent; user-select: none; position: relative;" class="echart" _echarts_instance_="ec_1709604093623">
                <div style="position: relative; width: 261px; height: 400px; padding: 0px; margin: 0px; border-width: 0px; cursor: pointer;"><canvas data-zr-dom-id="zr_0" width="326" height="500" style="position: absolute; left: 0px; top: 0px; width: 261px; height: 400px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div>
                <div class="" style="position: absolute; display: block; border-style: solid; white-space: nowrap; z-index: 9999999; box-shadow: rgba(0, 0, 0, 0.2) 1px 2px 10px; transition: opacity 0.2s cubic-bezier(0.23, 1, 0.32, 1) 0s, visibility 0.2s cubic-bezier(0.23, 1, 0.32, 1) 0s; background-color: rgb(255, 255, 255); border-width: 1px; border-radius: 4px; color: rgb(102, 102, 102); font: 14px / 21px &quot;Microsoft YaHei&quot;; padding: 10px; top: 0px; left: 0px; transform: translate3d(-81px, 161px, 0px); border-color: rgb(115, 192, 222); pointer-events: none; visibility: hidden; opacity: 0;">
                  <div style="margin: 0px 0 0;line-height:1;">
                    <div style="font-size:14px;color:#666;font-weight:400;line-height:1;">Access From</div>
                    <div style="margin: 10px 0 0;line-height:1;">
                      <div style="margin: 0px 0 0;line-height:1;"><span style="display:inline-block;margin-right:4px;border-radius:10px;width:10px;height:10px;background-color:#73c0de;"></span><span style="font-size:14px;color:#666;font-weight:400;margin-left:2px">Video Ads</span><span style="float:right;margin-left:20px;font-size:14px;color:#666;font-weight:900">300</span>
                        <div style="clear:both"></div>
                      </div>
                      <div style="clear:both"></div>
                    </div>
                    <div style="clear:both"></div>
                  </div>
                </div>
              </div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  echarts.init(document.querySelector("#trafficChart")).setOption({
                    tooltip: {
                      trigger: 'item'
                    },
                    legend: {
                      top: '5%',
                      left: 'center'
                    },
                    series: [{
                      name: 'Status',
                      type: 'pie',
                      radius: ['40%', '70%'],
                      avoidLabelOverlap: false,
                      label: {
                        show: false,
                        position: 'center'
                      },
                      emphasis: {
                        label: {
                          show: true,
                          fontSize: '18',
                          fontWeight: 'bold'
                        }
                      },
                      labelLine: {
                        show: false
                      },
                      data: [{
                          value: <?php echo $report_data['submitted']; ?>,
                          name: 'Submitted'
                        },
                        {
                          value: <?php echo $report_data['received']; ?>,
                          name: 'Received'
                        },
                        {
                          value: <?php echo $report_data['cancelled']; ?>,
                          name: 'Cancelled'
                        },
                        {
                          value: <?php echo $report_data['resubmitted']; ?>,
                          name: 'Resubmitted'
                        },

                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div>
        </div>


      </div>




      <div class="row">
        <div class="col-8">
          <div class="card recent-sales overflow-auto">

            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li><a class="dropdown-item" href="#">Export</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title"> Submissions <span>| <?php echo $cur_month_str; ?></span></h5>

              <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                <div class="dataTable-top">
                  <div class="dataTable-dropdown"><label><select class="dataTable-selector" fdprocessedid="52y8yj">
                        <option value="5">5</option>
                        <option value="10" selected="">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                      </select> entries per page</label></div>
                  <div class="dataTable-search"><input class="dataTable-input" placeholder="Search..." type="text" fdprocessedid="6a1a1b"></div>
                </div>
                <div class="dataTable-container">
                  <table class="table table-borderless datatable dataTable-table">
                    <thead>
                      <tr>
                        <th scope="col" data-sortable=""><a href="#" class="dataTable-sorter">PO </a></th>
                        <th scope="col" data-sortable=""><a href="#" class="dataTable-sorter">Purpose</a></th>
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
                          <tr onclick="location.href='view_po.php?po_number=<?php echo $po_number; ?>&office=<?php echo $po_office; ?>'" style="cursor:pointer">
                            <td><?php echo $po_number; ?></td>
                            <td><?php echo mb_strimwidth($purpose, 0, 20, "..."); ?></td>
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
                            <td style="font-weight:bold; color:<?php echo $color_rem; ?>; font-size:0.8rem"><?php echo $remarks; ?></td>
                          </tr>
                      <?php

                        }
                      }
                      ?>
                    </thead>
                    <tbody>


                    </tbody>
                  </table>
                </div>
                <div class="dataTable-bottom">
                  <div class="dataTable-info">Showing 1 to 5 of 5 entries</div>
                  <nav class="dataTable-pagination">
                    <ul class="dataTable-pagination-list"></ul>
                  </nav>
                </div>
              </div>

            </div>

          </div>
        </div>


        <div class="col-4">
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">My Recent Activity <span>| Today</span></h5>

              <div class="activity">
                <?php
                $emp_id = $_SESSION['emp_id'];
                $comment_sql = mysqli_query($conn, "SELECT * FROM tbl_pms_po_comment
                INNER JOIN tbl_pms_purchase_order ON tbl_pms_purchase_order.po_id = tbl_pms_po_comment.po_id
                INNER JOIN tbl_employee ON tbl_employee.emp_id = tbl_pms_po_comment.emp_id
                   WHERE tbl_pms_po_comment.emp_id='$emp_id' ORDER BY date_added DESC LIMIT 6");
                if ($comment_sql) {
                  while ($row = mysqli_fetch_array($comment_sql)) {
                    $po_id = $row['po_id'];
                    
                    $po_name = '';
                    $time_ago = $row['date_added'];
                    $person = '';
                    $isnotif = $row['is_notif'] ? '' : 'commented';
                    $po_number = '';
                    $po_office = '';

                    $po_name = $row['office'] . '-' . $row['po_number'];
                    $po_number = $row['po_number'];
                    $po_office = $row['office'];
              
                    $message = $row['comment_text'];
                    if ($row['emp_id'] === $emp_id) {
                      $person = 'You';
                    } else {
                      $row_emp = $row['emp_id'];
                      $person = $row['user_name'];
                    }

                ?>

                    <div class="activity-item d-flex">
                      <div class="activite-label" style="font-size:0.7rem; width:80px"><?php echo timeAgo($time_ago); ?></div>
                      <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>
                      <div class="activity-content">
                        <?php echo $person . ' ' . $isnotif . ' ' . $message; ?>
                        <a href="view_po.php?po_number=<?php echo $po_number; ?>&office=<?php echo $po_office; ?>"> <?php echo $po_name; ?> </a>
                      </div>
                    </div><!-- End activity item-->
                <?php
                  }
                }

                ?>
              </div>

            </div>
          </div>
        </div>


      </div>




      <!-- Modal -->
      <div class="modal fade" id="monthlyreport" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Generate Report </h5>
              <button class="btn-close" data-bs-dismiss="modal">
                <span aria-hidden="true"></span>
              </button>
            </div>
            <form style="padding: 20px 20px" action="dashboard.php" method="GET">
              <div class="row mb-3">
                <label for="inputText" class="col-sm-2 col-form-label">Month</label>
                <div class="col-sm-10">
                  <input type="month" class="form-control" id="floatingInput" name="month" value="<?php echo $cur_month; ?>" required>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-2 col-form-label">Office</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="Default select example" name="office" required>
                    <?php if (($role == 'encoder' && $office == 'REGIONAL OFFICE') || $role != 'encoder') {
                    ?>
                      <option value="Regional Office" selected>Regional Office</option>
                    <?php
                    }
                    if (($role == 'encoder' && $office == 'ILOILO CITY') || $role != 'encoder') {
                    ?>
                      <option value="Iloilo City">Iloilo City</option>
                    <?php
                    }
                    if (($role == 'encoder' && $office == 'ILOILO PROVINCE') || $role != 'encoder') {
                    ?>
                      <option value="Iloilo Province">Iloilo Province</option>
                    <?php
                    }
                    if (($role == 'encoder' && $office == 'GUIMARAS') || $role != 'encoder') {
                    ?>
                      <option value="Guimaras">Guimaras</option>
                    <?php
                    }
                    if (($role == 'encoder' && $office == 'ANTIQUE') || $role != 'encoder') {
                    ?>
                      <option value="Antique">Antique</option>
                    <?php
                    }
                    if (($role == 'encoder' && $office == 'AKLAN') || $role != 'encoder') {
                    ?>
                      <option value="Aklan">Aklan</option>
                    <?php
                    }
                    if (($role == 'encoder' && $office == 'BACOLOD') || $role != 'encoder') {
                    ?>
                      <option value="Bacolod">Bacolod</option>
                    <?php
                    }
                    if (($role == 'encoder' && $office == 'CAPIZ') || $role != 'encoder') {
                    ?>
                      <option value="Capiz">Capiz</option>
                    <?php
                    }
                    if (($role == 'encoder' && $office == 'NEGROS OCCIDENTAL') || $role != 'encoder') {
                    ?>
                      <option value="Negros Occidental">Negros Occidental</option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" style="border-radius: 30px 0px 30px 30px; width:120px; font-weight:500;" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" style="border-radius: 30px 0px 30px 30px; width:120px; font-weight:500;" class="btn btn-primary">Submit</button>
              </div>
            </form><!-- End General Form Elements -->
          </div>
        </div>
      </div>




    </section>


  </main>



  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

</body>


</html>
