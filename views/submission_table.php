
<?php
$office_param = $_GET['office_param'];
$request = $_GET['request'];
$str_sql= '';
if($request == 'received'){
  $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE office='$office_param' and coa_received=1
  ORDER BY po_number ASC";
} else if ($request == 'resubmission'){
  $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE office='$office_param' and  with_resubmission=1
  ORDER BY po_number ASC";
} else if ($request == 'pending'){
  $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE office='$office_param' and emp_id !='' and coa_received=0
  ORDER BY po_number ASC";
} else {
  $str_sql = "SELECT * FROM tbl_pms_purchase_order WHERE office='$office_param'
  ORDER BY po_number ASC";
}



?>

<table class="table datatable" style="background-color:white; text-align:center; margin-top:10px " id="myTable">
<thead>
  <tr>
    <th class="cell" style="font-size:0.85rem;color:#042B83; text-align:center">PO Number</th>
    <!-- <th class="cell" style="font-size:0.85rem;color:#042B83; text-align:center">Issuance Date</th> -->
    <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Purpose</th>
    <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Date Submitted</th>
    <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Submitted By</th>
    <!-- <th class="cell" style="font-size:0.75rem;color:#042B83">File</th> -->
    <th class="cell" style="font-size:0.75rem;color:#042B83; text-align:center">Status</th>
  </tr>
</thead>

<tbody id="myTable">
  <?php
  $sql = mysqli_query($conn, $str_sql) or die("error 4");
$count_rows = mysqli_num_rows($sql);

  if ($count_rows > 0) {
    while ($row = mysqli_fetch_array($sql)) {
      $emp_id = $row['emp_id'];
      $fullname ='';
      $image ='';
      $me ='';
      if($emp_id != null){
        $empsql = mysqli_query($conn, "SELECT * from tbl_employee where emp_id ='$emp_id' LIMIT 1");
        while($row2= mysqli_fetch_array($empsql)){
          $fullname = $row2['first_name'] . ' ' . $row2['last_name'];
          $image = $row2['pic_emp_data'];
          if($_SESSION['emp_id'] === $row2['emp_id']){
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
  ?>
   <tr onclick="location.href='view_po.php?po_number=<?php echo $po_number; ?>&office=<?php echo $po_office; ?>&'" style="cursor:pointer">
      <td style="font-weight:bold"><?php echo $po_number; ?></td>
  
      <td style="font-size:0.7rem; width:25%; text-align:left"><?php echo $purpose; ?></td>
      <td><?php echo  $emp_id == null ? '' : date_format(date_create($date_uploaded),"F j, Y"); ?></td>
      <td style="font-size:0.9rem"><?php echo $fullname; ?> <span style="color:#808080; font-size:0.7rem"> <?php echo $me; ?></span></td>
      <!-- <td> -->
      <!-- <div class="container">  
      <a href="#"  onclick="openFile('')" class="pdf">
      
      <img src="../assets/img/pdf.png" class="pdf"></a>
      </div> -->
    <!-- </td> -->
      <td> 
      <?php    
    if($status == 'on time'){
      ?>
      <span class="badge rounded-pill bg-success text-light"> <?php echo $status; ?></span>
      <?php
    } else if($status == 'late') {
      ?>
      <span class="badge rounded-pill bg-danger text-light"> <?php echo $status; ?></span>
      <?php
    } else if($status == 'cancelled') {
      ?>
  <span class="badge rounded-pill text-light" style="background-color:#FF6101;"> <?php echo $status; ?></span>
      <?php
} else {
  ?>
      <span class="badge rounded-pill  bg-dark text-light"> <?php echo $status; ?></span>
  <?php
}?>

    </td>
        </td>
      </tr>
  <?php


    }
  }
  ?>
</table>

<script>
    function openFile(po_id){
        window.open("po_file.php?po_id="+ po_id + '&method=submission', '_blank');
    }
</script>
