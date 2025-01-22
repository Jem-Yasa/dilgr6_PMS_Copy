<?php
date_default_timezone_set('Asia/Manila');

function display_user($emp_id, $conn){
    $emp_arr = array();
    $query = mysqli_query($conn, "SELECT * FROM tbl_employee 
        INNER JOIN tbl_util_position ON tbl_employee.pos_id= tbl_util_position.pos_id 
        INNER JOIN tbl_util_empstatus ON tbl_employee.emp_status_id= tbl_util_empstatus.emp_status_id 
        INNER JOIN tbl_util_division ON tbl_employee.div_id= tbl_util_division.div_id 
        INNER JOIN tbl_util_office ON tbl_employee.office_id= tbl_util_office.office_id 
        WHERE emp_id= '$emp_id' LIMIT 1") or die("Can't query the database");

    $count = mysqli_num_rows($query);
    if($count === 1){
        while($row= mysqli_fetch_array($query)){
            $emp_arr['emp_id'] = $row['emp_id'];
            $emp_arr['bio_id'] = $row['bio_id'];
            $emp_arr['email'] = $row['email'];
            $emp_arr['first_name'] = $row['first_name'];
            $emp_arr['middle_name'] = $row['middle_name'];
            $emp_arr['last_name'] = $row['last_name'];
            $emp_arr['birth_date'] = $row['birth_date'];
            $emp_arr['gender'] = $row['gender'];
            $emp_arr['user_name'] = strtolower($row['user_name']);
            $emp_arr['user_pass'] = $row['user_pass'];
            $emp_arr['pic_emp_data'] = $row['pic_emp_data'];
            $emp_arr['nationality'] = $row['nationality'];
            $emp_arr['mobile_num'] = $row['mobile_num'];
            $emp_arr['home_num'] = $row['home_num'];
            $emp_arr['home_address'] = $row['home_address'];
            $emp_arr['hire_date'] = $row['hire_date'];
            $emp_arr['reports_to_emp_id'] = $row['reports_to_emp_id'];
            $emp_arr['reports_to_name'] = $row['reports_to_name'];
            $emp_arr['contact_phone'] = $row['contact_phone'];
            $emp_arr['contact_rel'] = $row['contact_rel'];
            $emp_arr['about'] = $row['about'];
            $emp_arr['contact_person'] = $row['contact_person'];
            $emp_arr['sss_num'] = $row['sss_num'];
            $emp_arr['gsis_num'] = $row['gsis_num'];
            $emp_arr['tin_num'] = $row['tin_num'];
            $emp_arr['ph_num'] = $row['ph_num'];
            $emp_arr['pagibig_num'] = $row['pagibig_num'];
            $emp_arr['pos_desc'] = $row['pos_desc'];
            $emp_arr['office_desc'] = $row['office_desc'];
            $emp_arr['stat_desc'] = $row['stat_desc'];
            $emp_arr['div_desc'] = $row['div_desc'];
        }
    }
    return $emp_arr;
}

function is_hrmis_admin($emp_id, $conn){
    $arr = array();
    $hrmis_q = mysqli_query($conn, "SELECT * FROM tbl_hrmis_admin
    WHERE emp_id = '$emp_id' LIMIT 1") or die ("Can't query the database");
    $count = mysqli_num_rows($hrmis_q);
    if($count === 1){
        $arr['hrmis_admin'] = true;
        while($row= mysqli_fetch_array($hrmis_q)){
            $arr['hrmis_admin_role']  = trim($row['hrmis_role']);
        }
    } 
    return $arr;
}

function is_pms_user($emp_id, $conn){
    $pms_arr = array();
    $query_procurement_user = mysqli_query($conn, "SELECT * FROM tbl_pms_user 
    WHERE emp_id = '$emp_id' LIMIT 1") or die ("Can't query the database");
    $count = mysqli_num_rows($query_procurement_user);
    if($count === 1){
        $pms_arr['pms_user'] = true;
        while($row= mysqli_fetch_array($query_procurement_user)){
            $pms_arr['pms_role']  = trim($row['role']);
            $pms_arr['pms_office'] = $row['office_prov'] ;
        }
    } 
    return $pms_arr;
}

function is_active($cur_tab, $role, $office){
    $str= '';
    if($role === 'encoder' && $office == $cur_tab){
      $str= 'active';
    }
    if($role !== 'encoder' && $cur_tab === 'REGIONAL OFFICE'){
      $str= 'active';
    }
    return $str;
  }
  
  function is_tab_selected($cur_tab, $role, $office){
    $is_selected = false;
    if($role !== 'encoder'){
      $is_selected = true;
    }
    if($role === 'encoder' && $office == $cur_tab){
      $is_selected = true;
    }
   return $is_selected;
  }
  
  function show_active($cur_tab, $role, $office){
    $show_active = '';
    if($role !== 'encoder' && $cur_tab === 'REGIONAL OFFICE'){
      $show_active = 'show active';
    }
    if($role === 'encoder' && $office == $cur_tab){
      $show_active = 'show active';
    }
   return $show_active;
  }
  
  
function timeAgo($time_ago)
{
    date_default_timezone_set('Asia/Manila');
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed;
    $minutes    = round($time_elapsed / 60);
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400);
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640);
    $years      = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "just now";
    }
    //Minutes
    else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "one minute ago";
        } else {
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if ($hours <= 24) {
        if ($hours == 1) {
            return "an hour ago";
        } else {
            return "$hours hrs ago";
        }
    }
    //Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "yesterday";
        } else {
            return "$days days ago";
        }
    }
    //Weeks
    else if ($weeks <= 4.3) {
        if ($weeks == 1) {
            return "a week ago";
        } else {
            return "$weeks weeks ago";
        }
    }
    //Months
    else if ($months <= 12) {
        if ($months == 1) {
            return "a month ago";
        } else {
            return "$months months ago";
        }
    }
    //Years
    else {
        if ($years == 1) {
            return "one year ago";
        } else {
            return "$years years ago";
        }
    }
}

function getPODetails($po_id, $conn){
    $po_arr = array();
    $q = mysqli_query($conn, "SELECT * FROM tbl_pms_purchase_order
    WHERE po_id = '$po_id' LIMIT 1") or die ("Can't query the database");
    if($q){
        while($row2= mysqli_fetch_array($q)){
           $po_arr['po_owner_emp_id']  =  $row2['emp_id'] ;
           $name  =  '';
           $po_image =  '';
           $emp_id_po =  $row2['emp_id'] ;

           $q2 = mysqli_query($conn, "SELECT * FROM tbl_employee
           WHERE emp_id = '$emp_id_po' LIMIT 1") or die ("Can't query the database");

           if($q2 && $emp_id_po){
            while($row3= mysqli_fetch_array($q2)){
                $name  =  ucwords(strtolower($row3['first_name'].' '.$row3['last_name'].' '. $row3['ext_name']));
                $po_image  =  $row3['pic_emp_data'];
            }
           }
           $po_arr['po_owner_name']  =  $name;
           $po_arr['po_owner_image'] = $po_image;
            $po_arr['po_number'] = $row2['po_number'];
            $po_arr['uploaded_file'] = $row2['uploaded_file'];
            $po_arr['issuance_date'] =  $row2['issuance_date'];
            $po_arr['deadline'] =  $row2['deadline'];
            $po_arr['status'] =  $row2['status'];
            $po_arr['remarks'] =  $row2['remarks'];
            $po_arr['date_uploaded'] =  $row2['date_uploaded'];
            $po_arr['office'] =  $row2['office'];
            $po_arr['purpose'] =  $row2['purpose'];
            $po_arr['with_resubmission'] =  $row2['with_resubmission'];
            $po_arr['year'] =  $row2['year'];
            $po_arr['new_uploaded'] =  $row2['new_uploaded'];
            $po_arr['new_issuance_date'] =  $row2['new_issuance_date'];
            $po_arr['new_deadline'] =  $row2['new_deadline'];
            $po_arr['new_status'] =  $row2['new_status'];
            $po_arr['new_purpose'] =  $row2['new_purpose'];
            $po_arr['new_submission_date'] =  $row2['new_submission_date'];
            $po_arr['approved_res'] =  $row2['approved_res'];
            $po_arr['coa_received'] =  $row2['coa_received'];
            $po_arr['date_received'] =  $row2['date_received'];
            $po_arr['res_remarks'] =  $row2['res_remarks'];
            $po_arr['approved_res'] =  $row2['approved_res'];
        }
    } 
    return $po_arr;
}

function get_monthly_report($conn, $month, $office){
    $monthly_data_arr = array();
    $start = $month.'-01';
    $end = $month.'-31';
    $total_on_time = 0;
    $total_late = 0;
    $total_cancelled = 0;
    $total_submitted = 0;
    $total_resubmitted = 0;
    $total_received = 0;
    $sql_all_sub_month = mysqli_query($conn, "SELECT * FROM tbl_pms_purchase_order WHERE office='$office' 
        AND issuance_date BETWEEN '$start' AND '$end' ORDER by date_uploaded DESC") or die(mysqli_error($conn));
    if($sql_all_sub_month){
        while($row = mysqli_fetch_array($sql_all_sub_month)){
            if($row['status'] == 'on time'){
                $total_on_time ++;
            } else if($row['status'] == 'late'){
                $total_late ++;
            } else if($row['status'] == 'cancelled'){
                $total_cancelled ++;
            } else {
                // none
            }

            if($row['remarks'] == 'Submitted'){
                $total_submitted ++;
            } else  if($row['remarks'] == 'Resubmitted'){
                $total_resubmitted ++;
            } else  if($row['remarks'] == 'Received'){
                $total_received ++;
            } else {
                // none
            }
        }
    }
    $monthly_data_arr['query_str'] = "SELECT * FROM tbl_pms_purchase_order WHERE office='$office' 
    AND issuance_date BETWEEN '$start' AND '$end' ORDER by date_uploaded DESC";
    $monthly_data_arr['ontime'] =  $total_on_time;
    $monthly_data_arr['late'] =  $total_late;
    $monthly_data_arr['cancelled'] =  $total_cancelled;
    $monthly_data_arr['submitted'] =  $total_submitted;
    $monthly_data_arr['resubmitted'] =  $total_resubmitted;
    $monthly_data_arr['received'] =  $total_received;

    return $monthly_data_arr;
}