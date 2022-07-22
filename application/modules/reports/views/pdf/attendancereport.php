<link rel="stylesheet" href="<?=base_url()?>assets/css/app.css" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

<style type="text/css">
  .pure-table td, .pure-table th {
    border-bottom: 1px solid #cbcbcb;
    border-width: 0 0 0 1px;
    margin: 0;
    overflow: visible;
    padding: .5em 1em;
}
.table td {
    vertical-align: middle !important;
}
.table tr th {
    font-weight: 600px;
}
.inside-report + tbody + tr:nth-child(odd) {
 background-color: #fff;
}

.inside-report + tbody + tr:nth-child(even) {
 background-color:#cbcbcb;
}
</style>
<?php 
$branch_id = $this->session->userdata('branch_id');

ini_set('memory_limit', '-1');
$cur = App::currencies(config_item('default_currency')); 
$customer = ($client > 0) ? Client::view_by_id($client) : array();
$report_by = (isset($report_by)) ? $report_by : 'all';
   if(!empty($_POST['total_record'])){
                    $attendance_record= "none";
                  }else{
                    $attendance_record= "block";
                  }
                  if(!empty($_POST['attendance_record'])){
                    $total_record= "none";
                  }else{
                    $total_record= "block";
                  }
                   ?>


<div class="rep-container">
  <div class="page-header text-center">
  <h3 class="reports-headerspacing"><b><?=lang('attendance_report')?></b></h3>
  <?php if($client != NULL){ ?>
  <h5><span><?=lang('client_name')?>:</span>&nbsp;<?=$customer->company_name?>&nbsp;</h5>
  <?php } ?>
</div>


         <table border="0"cellpadding="0" cellspacing="0" height="100%" width="1200px" class="inside-report">" 
            <thead>
              <tr class="attendance_record" style="background-color:#cbcbcb;" >                
                  <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('date')?></th>
                  <th align="left" valign="middle" style="font-weight:700;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('workday')?></th>
                  <th align="left" valign="middle" style="font-weight:900;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('work')?></th>
                  <th align="left" valign="middle" style="font-weight:900;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('late_arrival')?></th>
                  <th align="left" valign="middle" style="font-weight:900;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('missing_work')?></th>
                  <th align="left" valign="middle" style="font-weight:900;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('extra_time')?></th>
                  <!-- <th align="left" valign="middle" style="font-weight:900;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('night_hours')?></th>
                  <th align="left" valign="middle" style="font-weight:900;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('extra_night_hours')?></th>
                  <th align="left" valign="middle" style="font-weight:900;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('work_code')?></th> -->
              </tr>
            </thead>
            <tbody>
              <?php 
              // $subdomain_id     = $this->session->userdata('subdomain_id');
              // $night_hours = $this->db->get_where('night_hours',array('subdomain_id' =>$subdomain_id ))->row_array();
              if(!empty($_POST['user_id']) || !empty($_POST['department_id']) || !empty($_POST['teamlead_id']) || !empty($_POST['range']))
                { 
                  // print_r($_POST); exit();
                  // if(isset($_POST['id_code']) && !empty($_POST['id_code'])){
                  //   $users= $this->db->get_where('users',array('subdomain_id'=>$subdomain_id,'id_code'=>$_POST['id_code']))->row_array();
                  //   if(!empty($users)){
                  //     $user_id[] = $users['id'];
                  //   }else{
                  //     $user_id[] =array();
                  //   }
                  // } 
                  if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                    $user_id[] = $_POST['user_id'];
                  }
                  if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                    //$dept_users= $this->db->get_where('users',array('subdomain_id'=>$subdomain_id,'department_id'=>$_POST['department_id']))->result_array();
                    $dept_id = $_POST['department_id'];
                    $dept_users= $this->db->select('*')
                                ->from('users')
                                // ->where('subdomain_id',$subdomain_id)
                                // ->where("FIND_IN_SET('$dept_id',department_id) !=", 0)
                                ->where_in('department_id',$_POST['department_id'])
                                ->get()
                                ->result_array();
                    if(!empty($dept_users)){
                      foreach ($dept_users as $key => $value) {
                        $user_id[] = $value['id'];
                      }
                    }
                  }
                  if(isset($_POST['teamlead_id']) && !empty($_POST['teamlead_id'])){
                    $team_users= $this->db->get_where('users',array('teamlead_id'=>$_POST['teamlead_id']))->result_array();
                    if(!empty($team_users)){
                      foreach ($team_users as $key => $value) {
                        $user_id[] = $value['id'];
                      }
                    }
                  }
                  if(isset($_POST['range']) && !empty($_POST['range'])){
                   
                    $date_range = explode('-', $_POST['range']);
                    $start_date = $date_range[0];
                    $end_date = $date_range[1];
                    $start_time=strtotime($start_date);
                    $start_day=date("d",$start_time);
                    $start_month=date("m",$start_time);
                    $start_year=date("Y",$start_time);
                    $end_date=strtotime($end_date);
                    $end_day=date("d",$end_date);
                    $end_month=date("m",$end_date);
                    $end_year=date("Y",$end_date);
                   
                    $from_date = date("Y-m-d", $start_time);       
                      $to_date = date("Y-m-d", $end_date);
                      $earlier = new DateTime($from_date);
                      $later = new DateTime($to_date);

                      $col_count = $later->diff($earlier)->format("%a");
                      
                    if(empty($user_id)){
                      // $all_users = $this->db->get_where('users',array('role_id !='=>2,'role_id !='=>1,'activated'=>1,'banned'=>0))->result_array();
                      /*$this->db->where('a_month >=', $start_month);
                      $this->db->where('a_month <=', $end_month);
                      $this->db->where('a_year >=', $start_year);
                      $this->db->where('a_year <=', $end_year);
                      $this->db->where('subdomain_id', $subdomain_id);
                      $all_users =  $this->db->get('attendance_details')->result_array();*/

                        // if($branch_id != '') {
                        //     $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                        // }
                        $all_users = $this->db->select('ad.*')
                                    ->from('attendance_details ad')
                                    ->join('users u', 'u.id=ad.user_id')
                                    ->where(array('a_month >='=>$start_month, 'a_month <='=>$end_month, 'a_year >='=>$start_year,'a_year <='=>$end_year))
                                    ->get()
                                    ->result_array();

                       if(!empty($all_users)){
                        foreach ($all_users as $key => $value) {
                          $user_id[] = $value['user_id'];
                        }
                      }
                    }
                  } 
                $user_id =  array_unique($user_id);
                  // print_r($user_id); exit;
                  foreach ($user_id as $key => $value) {
                    
                    $user_id = $value;

                    $user_details= $this->db->get_where('users',array('id'=>$user_id))->row_array();
                    $account_details= $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();
                    if(!empty($user_details['designation_id'])){
                      $designation = $this->db->get_where('designation',array('id'=>$user_details['designation_id']))->row_array();
                      $designation_name = $designation['designation'];
                      
                    }else{
                      $designation_name = '-';
                    }
                    $imgs = '';
                    if($account_details['avatar'] != 'default_avatar.jpg'){
                        $imgs = $account_details['avatar'];
                        
                    }else{
                        $imgs = "default_avatar.jpg";
                    }
                      ?>             

               <tr style="background-color:#fff;font-family:'Open Sans',arial,sans-serif!important;">
                <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                  <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                    <tr>
                      <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:20px;color:#333333;">
                      <?php echo ucfirst(user::displayName($value));?>
                      </td>
                    </tr>
                    <tr>
                      <td align="left" style="text-transform:capitalize;font-size:16px;color:#333;">
                       <?php echo $designation_name;?> <?php echo !empty($user_details['id_code'])?$user_details['id_code']:"-";?>
                      </td>
                    </tr>
                  </table>
                    
                </td>
                <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    
                </td>
                <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                  
                </td>
                <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                
                </td>
                <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                
                </td>
                <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                  
                </td>
                
              </tr>
              
             <?php

                    if(isset($_POST['attendance_month']) && !empty($_POST['attendance_month']))
                    {
                      $a_month=$_POST['attendance_month'];
                    }

                     if(isset($_POST['attendance_year']) && !empty($_POST['attendance_year']))
                    {
                      $a_year=$_POST['attendance_year'];
                    }


                    
                     
                     // print_r($_POST['range']); exit;
                    if(isset($_POST['range']) && !empty($_POST['range'])){
                      /*$this->db->select('month_days,month_days_in_out');
                      $this->db->where('user_id', $user_id);
                      $this->db->where('a_month ', $start_month);
                      // $this->db->where('a_month <=', $end_month);
                      // $this->db->where('a_year >=', $start_year);
                      $this->db->where('a_year ', $start_year);
                      $this->db->where('subdomain_id', $subdomain_id);
                      $results =  $this->db->get('attendance_details')->result_array();*/

                     
                        $results = $this->db->select('month_days,month_days_in_out')
                                    ->from('attendance_details ad')
                                    ->join('users u', 'u.id=ad.user_id')
                                    ->where(array('user_id'=>$user_id, 'a_month '=>$start_month, 'a_year '=>$start_year))
                                    ->get()
                                    ->result_array();

                    } else{
                      $a_year    = date('Y');
                      $a_month   = date('m');

                     $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
                    
                        $results = $this->db->select('month_days,month_days_in_out')
                                    ->from('attendance_details ad')
                                    ->join('users u', 'u.id=ad.user_id')
                                    ->where($where)
                                    ->get()
                                    ->result_array();
                     /*$this->db->select('month_days,month_days_in_out');
                     $results  = $this->db->get_where('dgt_attendance_details',$where)->result_array();*/
                     
                    }
                   
                     
                   $sno=1;
                    $total_scheduled_work =0;
                    $actually_worked = 0;
                    $workday = 0;
                    $absent = 0;
                    $total_production = 0;
                    $all_user_schedule = array();
                    $total_scheduled_minutes = 0;
                    $total_night_production_hour = 0;
                    $total_extra_night_production_hour = 0;
                    $total_late_arrival = 0;
                    $total_missing_hour = 0;
                    $work_hours = 0;
                    $scheduled_minutes = 0;
                    $today_work_hour = array();
                    $overtimes  = 0;
                     $overtime  = 0;
                     $total_overtime  = 0;
                     // echo "<pre>";print_r($results); 
                     foreach ($results as $rows) {

                          $list=array();
                          if(isset($_POST['range']) && !empty($_POST['range'])){
                            $number = $col_count;
                            $start_val = 0;
                          }else{
                            $month = $a_month;
                            $year = $a_year;

                            $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            $start_val = 1;

                          }
                          $week_off = 0;
                          
                          // $sno=1;
                          for($d=$start_val; $d<=$number; $d++)
                           {
                            if(isset($_POST['range']) && !empty($_POST['range'])){
                                  $time =   date(strtotime('+'.$d.' days', strtotime($date_range[0])));
                                } else{
                                   $time=mktime(12, 0, 0, $month, $d, $year);     
                              

                                }

                              // if (date('m', $time)==$month)       
                                  $date=date('d M Y', $time);
                                  $new_date=date('d/m/Y', $time);
                                  $schedule_date=date('Y-m-d', $time);
                                  $a_day =date('d', $time);
                                  $a_month =date('m', $time);
                                  $a_year =date('Y', $time);
                                   // echo print_r($schedule_date) ; exit;   
                                  /*$this->db->select('month_days,month_days_in_out');
                                  $this->db->where('user_id', $user_id);
                                  $this->db->where('a_month ', $a_month);
                                  // $this->db->where('a_month <=', $end_month);
                                  // $this->db->where('a_year >=', $start_year);
                                  $this->db->where('a_year ', $a_year);
                                  $this->db->where('subdomain_id', $subdomain_id);
                                  $rows =  $this->db->get('attendance_details')->row_array(); */
                                  
                                $rows = $this->db->select('month_days,month_days_in_out')
                                            ->from('attendance_details ad')
                                            ->join('users u', 'u.id=ad.user_id')
                                            ->where(array('user_id'=>$user_id, 'a_month '=>$a_month, 'a_year '=>$a_year))
                                            ->get()
                                            ->row_array();

                                  $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date'=>$schedule_date);
                                  $user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->row_array(); 
                                  $shift =  $this->db->get_where('shifts',array('id' => $user_schedule['shift_id']))->result_array(); 
                                   $all_user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->result_array(); 
                                  if(count($all_user_schedule) == 1){
                                  
                                  
                                    if(!empty($user_schedule)){
                                      $work_hours = work_time($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$user_schedule['schedule_date'].' '.$user_schedule['end_time'],$user_schedule['break_time']);
                                      $total_scheduled_hour = hours_to_mins($work_hours);

                                      $total_scheduled_minutes = $total_scheduled_hour;                                     
                                        
                                    } else{
                                      $total_scheduled_minutes = 0;
                                    }
                                  }
                                  if(count($all_user_schedule) > 1){
                                    $shift_id = array();
                                    $scheduled_minutes =0;
                                    foreach ($all_user_schedule as $value) {
                                       $work_hours = work_time($value['schedule_date'].' '.$value['start_time'],$value['schedule_date'].' '.$value['end_time'],$value['break_time']);
                                      $work_hours = hours_to_mins($work_hours);
                                      $scheduled_minutes += $work_hours;                                      
                                      $shift_id[] = $value['shift_id']; 
                                      # code...
                                    }
                                    $total_scheduled_minutes = $scheduled_minutes;
                                    $shift_ids = implode(',', $shift_id);
                                    $this->db->where_in('id',$shift_id);
                                    $shift = $this->db->get('shifts')->result_array(); 
                                    // echo $this->db->last_query();
                                    // echo print_r($shift); exit;
                                  }


                                if(!empty($rows['month_days'])){
     
    
                                $month_days =  unserialize($rows['month_days']);
                                $month_days_in_out =  unserialize($rows['month_days_in_out']);
                                $day = $month_days[$a_day-1];
                                $day_in_out = $month_days_in_out[$a_day-1];
                                $latest_inout = end($day_in_out);
                                // if(isset($day['punchin_workcode']) && !empty($day['punchin_workcode'])){
                                //    $punchin_workcodes =  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $day['punchin_workcode']))->row_array();
                                //    $punchin_workcode= '('.$punchin_workcodes['incident_name'].')';
                                // }else{
                                //   $punchin_workcode ='';
                                // }
                                // if(isset($day['punchout_workcode']) && !empty($day['punchout_workcode'])){
                                //   $punchout_workcodes=  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $day['punchout_workcode']))->row_array(); 
                                //    $punchout_workcode= '('.$punchout_workcodes['incident_name'].')';
                                //  }else{
                                //   $punchout_workcode ='';
                                //  }
                                 $production_hour=0;
                                 $night_production_hour=0;
                                 $extra_night_production_hour=0;
                                 $break_hour=0;
                                 $k = 1;
                                foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                                {

                                    if(!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']))
                                    {
                                       $days = $a_day;
                                        // $today_work_where     = array('subdomain_id'=>$subdomain_id,'employee_id'=>$user_id,'schedule_date'=>date('Y-m-'.$days));
                                        // $today_work_hour = $this->db->get_where('shift_scheduling',$today_work_where)->row_array();
                                        $today_work_hour = $this->db->query('select * from dgt_shift_scheduling where employee_id ="'.$user_id.'" and schedule_date ="'.$schedule_date.'" AND ((start_time <= "'.$punch_detail['punch_in'].'" and end_time >="'.$punch_detail['punch_in'].'") or (start_time >= "'.$punch_detail['punch_in'].'")) limit 1')->row_array();
                                        if(!empty($today_work_hour)){
                                            if($today_work_hour['free_shift'] == 1 ){
                                            $later_entry_hours = 0;
                                          
                                          
                                           
                                        }else{
                                      if($k == 1){                                       
                                         // print_r($today_work_hour); exit();
                                        $later_entry_hours = later_entry_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['max_start_time'],$schedule_date.' '.$punch_detail['punch_in']);   
                                       $extra_hours = extra_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],$schedule_date.' '.$punch_detail['punch_in']);     
                                       // echo $days; exit;
                                       $first_punch_in = $punch_detail['punch_in'];
                                      $start_between = start_between($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],$schedule_date.' '.$punch_detail['punch_in'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']); 
                       
                                    }
                                    $end_between = end_between($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],$schedule_date.' '.$punch_detail['punch_out'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']); 

                                      $between_minstartto_start = between_minstartto_start($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']);
                                      if($punch_detail['punch_out'] > $today_work_hour['max_end_time']){
                                        $between_endto_max_end = between_endto_max_end($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']);
                                      }else{
                                        $between_endto_max_end = 0;
                                      }
                                      
                                    }
                                  }
                                        $production_hour += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));
                                       // if(!empty($night_hours)){
                                       //      $night_hours_punch_in = night_hours_punch_in($today_work_hour['schedule_date'].' '.$night_hours['start_time'],$today_work_hour['schedule_date'].' '.$night_hours['end_time'],$today_work_hour['schedule_date'].' '.$punch_detail['punch_in']);
                                       //      $night_hours_punch_out = night_hours_punch_out($today_work_hour['schedule_date'].' '.$night_hours['start_time'],$today_work_hour['schedule_date'].' '.$night_hours['end_time'],$today_work_hour['schedule_date'].' '.$punch_detail['punch_out']);
                                           
                                       //     if ($night_hours_punch_in =='yes' && $night_hours_punch_out =='yes'){
                                       //        $night_production_hour += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));
                                       //     }
                                       //     if ($night_hours_punch_in =='yes' && $night_hours_punch_out =='no'){
                                       //        $night_production_hour += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($night_hours['end_time'])));
                                       //     }
                                       //     if ($night_hours_punch_in =='no' && $night_hours_punch_out =='yes'){
                                       //        $night_production_hour += time_difference(date('H:i',strtotime($night_hours['start_time'])),date('H:i',strtotime($punch_detail['punch_out'])));
                                       //        $extra_night_production_hour += time_difference(date('H:i',strtotime($night_hours['start_time'])),date('H:i',strtotime($punch_detail['punch_out'])));

                                       //     }
 

                                       //  }
                                    }
                                              
                                  $k++;                              
                                     
                                }
                                 // echo 'between_minstartto_start'.$between_minstartto_start; exit;
                                if($production_hour > 0 && $later_entry_hours>0){
                                  $production_hour = $production_hour-$end_between;
                                } else{
                                  $production_hour = $production_hour-$start_between-$end_between;
                                }
                                if($production_hour<0){
                                  $production_hour = 0;
                                }

                             for ($i=0; $i <count($month_days_in_out[$a_day-1]) ; $i++) { 

                                        if(!empty($month_days_in_out[$a_day-1][$i]['punch_out']) && $month_days_in_out[$a_day-1][ $i+1 ]['punch_in'])
                                        {
                                            
                                            $break_hour += time_difference(date('H:i',strtotime($month_days_in_out[$a_day-1][$i]['punch_out'])),date('H:i',strtotime($month_days_in_out[$a_day-1][ $i+1 ]['punch_in'])));
                                        }

                                        
                              }

                              // $overtimes=($production_hour+$break_hour)-($total_scheduled_minutes);
                              if($user_schedule['accept_extras'] == 1){
                                $overtimes=($production_hour)-($total_scheduled_minutes);
                                if($overtimes > 0)
                                {
                                  $overtime=$overtimes;

                                  
                                }
                                else
                                {
                                  $overtime=0;                                  
                                  $extra_night_production_hour=0;
                                }
                              } else{
                                $overtime=0;
                                $extra_night_production_hour=0;
                              }
                              
                               $missing_work=($total_scheduled_minutes)-($production_hour);
                                // echo $missing_work; exit;
                                if($missing_work > 0)
                                {
                                  $missing_work=$missing_work;
                                 
                                }
                                else
                                {
                                  $missing_work=0;
                                  
                                }

                                if($production_hour >= $total_scheduled_minutes){
                                  $work_color = '#25b932';
                                }else{
                                  $work_color = '#ef0f48';
                                }
                                
                              // if ($sno % 2 == 0) {
                              //   $color = '#cbcbcb';
                              // }else{
                              //   $color = '#fff';
                              // }                

                    ?>


                    <tr style="background-color:<?php echo ($sno % 2 == 0)?'#fff':'#eee';?>;font-family:'Open Sans',arial,sans-serif!important;display:<?php echo $attendance_record;?>">
                      
                     
                      <?php

                      // if(date('D', $time)=='Sat' || date('D', $time)=='Sun')
                      if(empty($user_schedule))
                      {
                        if(!empty($day['punch_in']))
                        {
                           $total_scheduled_work += $total_scheduled_minutes;
                          $total_production += $production_hour;
                          $total_night_production_hour += $night_production_hour;
                          $total_extra_night_production_hour += $extra_night_production_hour;
                          $total_overtime +=$production_hour;
                        if(!empty($day['punch_in']))
                        {
                           // $later_entry_hours = later_entry_hours($user_schedule['schedule_date'].' '.$user_schedule['max_start_time'],$schedule_date.' '.$day['punch_in']);
                           $actually_worked++;
                        } else {
                          // $later_entry_hours = '-';
                          $absent++;

                            // if($branch_id != '') {
                            //     $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                            // }
                            // $absent_workcodes = $this->db->select('ci.*')
                            //     ->from('calendar_incident ci')
                            //     ->join('users u', 'u.id=ci.emp_id', 'left')
                            //     ->where(array('ci.subdomain_id'=>$subdomain_id,'emp_id' => $user_id,'start_date'=>$schedule_date))
                            //     ->get()
                            //     ->row_array();

                          // $absent_workcodes =  $this->db->get_where('calendar_incident',array('subdomain_id'=>$subdomain_id,'emp_id' => $user_id,'start_date'=>$schedule_date))->row_array();
                          // if(!empty($absent_workcodes)){

                          // $absent_incident =  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $absent_workcodes['incident']))->row_array();
                          // }else{
                          //    $absent_incident = array();
                          // }
                        }?>
                         <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                              <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                              <?php echo $new_date ;?>
                              </td>
                            </tr>
                            <tr>
                              <td align="left" style="text-transform:capitalize;font-size:14px;color:#333;">
                               <?php echo lang(strtolower(date('l', $time)))?>
                              </td>
                            </tr>
                          </table>
                            
                        </td>
                      <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                                            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                                              <tr>
                                                <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                                                  <?php echo lang('absent') ?>
                                                <!-- <?php $abs_incident = !empty($absent_incident)?'<span style="color:red;">'.$absent_incident['incident_name'].'</span>':'<span style="color:red;">'.lang('absent').'</span>'?> -->
                        <?php
                          if(!empty($shift)){
                            foreach ($shift as $key => $shifts) {
                              echo $shifts['shift_name'].' ('.$work_hours.'),';
                            } 
                          }else{
                            echo '';
                          }
                          // echo !empty($total_scheduled_minutes)?'('.intdiv($total_scheduled_minutes, 60).'.'. ($total_scheduled_minutes % 60).' hrs)':'(-)';
                         ?>
                        </span>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td align="left" style="text-transform:capitalize;font-size:14px;color:#333;"><?php echo lang('absent') ?>
                                                   <!-- <?php $abs_incident = !empty($absent_incident)?'<span style="color:red;">'.$absent_incident['incident_name'].'</span>':'<span style="color:red;">'.lang('absent').'</span>'?> -->
                       <?php
                          if(!empty($shift)){
                            foreach ($shift as $key => $shifts) {
                              echo $shifts['shift_name'].' ('.$work_hours.')';
                            } 
                          }else{
                            echo '';
                          }
                          // echo !empty($total_scheduled_minutes)?'('.intdiv($total_scheduled_minutes, 60).'.'. ($total_scheduled_minutes % 60).' hrs)':'(-)';
                         ?>
                 <?php
                $punchin_workcode = '';
                $punchout_workcode = '';
                foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                {
                  //  if(isset($punch_detail['punchin_workcode']) && !empty($punch_detail['punchin_workcode'])){
                  //   $punchin_workcodes = $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $punch_detail['punchin_workcode']))->row_array();
                  //  $punchin_workcode= '('.$punchin_workcodes['incident_name'].')';
                  // }else{
                  //   $punchin_workcode = '';
                  // }
                  
                  //  if(isset($punch_detail['punchout_workcode']) && !empty($punch_detail['punchout_workcode'])){
                  //     $punchout_workcodes=  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $punch_detail['punchout_workcode']))->row_array(); 
                  //      $punchout_workcode= '('.$punchout_workcodes['incident_name'].')';
                  //    }else{
                  //     $punchout_workcode ='';
                  //    }
                                    

                 
                  echo !empty($punch_detail['punch_in'])?'<i class="fa fa-arrow-right text-success"></i> &nbsp; '.date("g:i a", strtotime($punch_detail['punch_in'])).'  &nbsp;|&nbsp ':'<span style="color:red;">'.lang('absent').'</span>'; ?><?php echo !empty($punch_detail['punch_out'])?'<i class="fa fa-arrow-left text-danger"></i> &nbsp;  '.date("g:i a", strtotime($punch_detail['punch_out'])):''; ?> 
               <?php }?>
                          </td>
                        </tr>
                      </table>  
                    </td>
                     
                     <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#25b932;">
                           <?php echo !empty($production_hour)?intdiv($production_hour, 60).'.'. ($production_hour % 60).' hrs':'-';?>
                            </td>
                          </tr>
                          
                        </table>
                      </td>    
                       <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#ef0f48;">
                          <?php echo '-';?>
                            </td>
                          </tr>
                          
                        </table>
                      </td> 
                        <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#ef0f48;">
                          <?php echo !empty($missing_work)?intdiv($missing_work, 60).'.'. ($missing_work % 60).' hrs':'-';?>
                            </td>
                          </tr>
                          
                        </table>
                      </td>
                      <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#25b932;">
                          <?php  echo !empty($production_hour)?intdiv($production_hour, 60).'.'. ($production_hour % 60).' hrs':'-';?>
                            </td>
                          </tr>
                          
                        </table>
                      </td>
                      
                      <!--  <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#25b932;">
                              <?php echo !empty($night_production_hour)?intdiv($night_production_hour, 60).'.'. ($night_production_hour % 60).' hrs':'-';?>
                            </td>
                          </tr>
                          
                        </table>
                      </td>  
                       <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#25b932;">
                              <?php echo !empty($extra_night_production_hour)?intdiv($extra_night_production_hour, 60).'.'. ($extra_night_production_hour % 60).' hrs':'-';?>
                            </td>
                          </tr>
                          
                        </table>
                      </td>
                      <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#ef0f48;">
                          <?php echo '-';?>
                            </td>
                          </tr>
                          
                        </table>
                      </td> -->
                       
                       
                       <?php   
                        }
                        else
                        { $week_off++;?>

                           <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                              <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                              <?php echo $new_date ;?>
                              </td>
                            </tr>
                            <tr>
                              <td align="left" style="text-transform:capitalize;font-size:14px;color:#333;">
                               <?php echo lang(strtolower(date('l', $time)));?>
                              </td>
                            </tr>
                          </table>
                            
                        </td>
                         
                      <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                              <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                             
                              </td>
                            </tr>
                            
                          </table>
                            
                        </td>
                       <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                              <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                             
                              </td>
                            </tr>
                            
                          </table>
                            
                        </td>
                         <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;color:red; ">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                              <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                             <?php echo lang('week_off');?>
                              </td>
                            </tr>
                            
                          </table>
                            
                        </td>

                         <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;color:red; ">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                              <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                             
                              </td>
                            </tr>
                            
                          </table>
                            
                        </td>
                       <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;color:red; ">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                              <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                             
                              </td>
                            </tr>
                            
                          </table>
                            
                        </td>
                      <!-- <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;color:red; ">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                              <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                             
                              </td>
                            </tr>
                            
                          </table>
                            
                        </td>
                         <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;color:red; ">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                              <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                             
                              </td>
                            </tr>
                            
                          </table>
                            
                        </td>

                         <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;color:red; ">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                              <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                             
                              </td>
                            </tr>
                            
                          </table>
                            
                        </td>
                       -->
                       <?php 
                        }   ?>
                       
                     <?php  }
                      else
                      {
                        $total_scheduled_work += $total_scheduled_minutes;
                        $total_production += $production_hour;
                        $total_night_production_hour += $night_production_hour;
                        $total_extra_night_production_hour += $extra_night_production_hour;                        
                        $total_missing_hour += $missing_work;
                        $total_overtime +=$overtime;
                        $workday++;
                        if(!empty($day['punch_in']))
                        {
                           $later_entry_hours = !empty($later_entry_hours)?intdiv($later_entry_hours, 60).'.'. ($later_entry_hours % 60).' hrs':'-';
                            $total_late_arrival += $later_entry_hours;
                           $actually_worked++;
                           
                        } else {
                          $later_entry_hours = '-';
                          $absent++;
                          // if($branch_id != '') {
                          //       $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                          //   }
                          //   $absent_workcodes = $this->db->select('ci.*')
                          //       ->from('calendar_incident ci')
                          //       ->join('users u', 'u.id=ci.emp_id', 'left')
                          //       ->where(array('ci.subdomain_id'=>$subdomain_id,'emp_id' => $user_id,'start_date'=>$schedule_date))
                          //       ->get()
                          //       ->row_array();
                                
                          //$absent_workcodes =  $this->db->get_where('calendar_incident',array('subdomain_id'=>$subdomain_id,'emp_id' => $user_id,'start_date'=>$schedule_date))->row_array();
                          // if(!empty($absent_workcodes)){

                          // $absent_incident =  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $absent_workcodes['incident']))->row_array();
                          // }else{
                          //   $absent_incident = array();
                          // }
                        }?>
                        <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                              <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                              <?php echo $new_date ;?>
                              </td>
                            </tr>
                            <tr>
                              <td align="left" style="text-transform:capitalize;font-size:14px;color:#333;">
                               <?php echo lang(strtolower(date('l', $time)));?>
                              </td>
                            </tr>
                          </table>
                            
                        </td>
                        <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                              <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                                <!--  <?php $abs_incident = !empty($absent_incident)?'<span style="color:red;">'.$absent_incident['incident_name'].'</span>':'<span style="color:red;">'.lang('absent').'</span>'?> -->
                       <?php
                          if(!empty($shift)){
                            foreach ($shift as $key => $shifts) {
                              echo $shifts['shift_name'].' ('.$work_hours.')';
                            } 
                          }else{
                            echo '';
                          }
                          // echo !empty($total_scheduled_minutes)?'('.intdiv($total_scheduled_minutes, 60).'.'. ($total_scheduled_minutes % 60).' hrs)':'(-)';
                         ?>
                        <br>
                              </td>
                            </tr>
                            <tr>
                              <td align="left" style="text-transform:capitalize;font-size:14px;color:#333;">
                                <?php
                                  $punchin_workcode = '';
                                  $punchout_workcode = '';
                                  foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                                  {
                                    //  if(isset($punch_detail['punchin_workcode']) && !empty($punch_detail['punchin_workcode'])){
                                    //   $punchin_workcodes = $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $punch_detail['punchin_workcode']))->row_array();
                                    //  $punchin_workcode= '('.$punchin_workcodes['incident_name'].')';
                                    // }else{
                                    //   $punchin_workcode = '';
                                    // }
                                    
                                    //  if(isset($punch_detail['punchout_workcode']) && !empty($punch_detail['punchout_workcode'])){
                                    //     $punchout_workcodes=  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $punch_detail['punchout_workcode']))->row_array(); 
                                    //      $punchout_workcode= '('.$punchout_workcodes['incident_name'].')';
                                    //    }else{
                                    //     $punchout_workcode ='';
                                    //    }
                                                      

                                   echo !empty($punch_detail['punch_in'])?date("g:i a", strtotime($punch_detail['punch_in'])):'<span style="color:red;">'.lang('absent').'</span>'; ?><?php echo !empty($punch_detail['punch_out'])?date("g:i a", strtotime($punch_detail['punch_out'])):''; ?> <br>
                                 <?php }?>
                              </td>
                            </tr>
                          </table>
                            
                        </td>
                       
                       <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:<?php echo $work_color;?>;">
                           <?php echo !empty($production_hour)?intdiv($production_hour, 60).'.'. ($production_hour % 60).' hrs':'-';?>
                            </td>
                          </tr>
                          
                        </table>
                      </td>  
                      <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#ef0f48;">
                          <?php echo $later_entry_hours;?>
                            </td>
                          </tr>
                          
                        </table>
                      </td>  
                       <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#ef0f48;">
                          <?php echo !empty($missing_work)?intdiv($missing_work, 60).'.'. ($missing_work % 60).' hrs':'-';?>
                            </td>
                          </tr>
                          
                        </table>
                      </td>  
                      <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#25b932;">
                              <?php
                              //  if($today_work_hour['accept_extras'] == 0){
                              //   echo "-";
                              // }else {
                                 echo !empty($overtime)?intdiv($overtime, 60).'.'. ($overtime % 60).' hrs':'-';
                              // }
                              ?>
                            </td>
                          </tr>
                          
                        </table>
                      </td>  
                      <!-- <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#25b932;">
                              <?php echo !empty($night_production_hour)?intdiv($night_production_hour, 60).'.'. ($night_production_hour % 60).' hrs':'-';?>
                            </td>
                          </tr>
                          
                        </table>
                      </td>  
                       <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#25b932;">
                              <?php echo !empty($extra_night_production_hour)?intdiv($extra_night_production_hour, 60).'.'. ($extra_night_production_hour % 60).' hrs':'-';?>
                            </td>
                          </tr>
                          
                        </table>
                      </td>  
                      <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#ef0f48;">
                             <?php echo '-';?>
                            </td>
                          </tr>
                          
                        </table>
                      </td>   -->
                      <?php  
                      }
                     ?>
                    </tr>
                    <?php }  $sno++;} $reported_days = $number+1; ?>


<?php if(empty($_POST['total_record'])){ ?>

                     <tr class="total_record" style="background-color:#cbcbcb;font-family:'Open Sans',arial,sans-serif!important;display:<?php echo $total_record;?>">
               <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <b><?php echo lang('total_reported_days'); ?></b>
                        </td>
                      </tr>
                      
                    </table>
                  </td> 
                 
                  <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <b><?php echo lang('total_of_time') ?> <br><?php echo lang('that_the_employee') ?> <br><?php echo lang('should_have_worked') ?></b>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                  <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <b><?php echo lang('total_of_time') ?> <br><?php echo lang('that_the_employee') ?> <br><?php echo lang('actually_worked') ?></b>
                        </td>
                      </tr>
                      
                    </table>
                  </td>  
                  <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <b><?php echo lang('total_of_time') ?> <br><?php echo lang('that_the_employee') ?> <br><?php echo lang('late_arrival') ?></b>
                        </td>
                      </tr>
                      
                    </table>
                  </td>  
                  <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <b><?php echo lang('total_of_time') ?> <br><?php echo lang('that_the_employee') ?> <br><?php echo lang('missed_work') ?></b>
                        </td>
                      </tr>
                      
                    </table>
                  </td>  
                  <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <b><?php echo lang('total_of_time') ?> <br><?php echo lang('that_the_employee') ?> <br><?php echo lang('extra_hours_worked') ?></b>
                        </td>
                      </tr>
                      
                    </table>
                  </td>  
                  <!-- <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <b><?php echo lang('total_of_time') ?> <br><?php echo lang('that_the_employee') ?> <br><?php echo lang('night_hours_worked') ?></b>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                   <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <b><?php echo lang('total_of_time') ?> <br>t<?php echo lang('that_the_employee') ?> <br><?php echo lang('extra_night_hours_worked') ?></b>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                 
                  <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <b><?php echo lang('total_amounts_of') ?><br> <?php echo lang('time_for_all_the') ?><br> <?php echo lang('work_codes_used') ?></b>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                   <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <b><?php echo lang('days_the_employee') ?><br> <?php echo lang('should_have_worked') ?></b>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                   <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <b><?php echo lang('days_the_employee') ?> <br> <?php echo lang('actually_worked') ?></b>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                  <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <b><?php echo lang('days_the_employee') ?><br> <?php echo lang('was_absent') ?></b>
                        </td>
                      </tr>
                      
                    </table>
                  </td> -->
              
               
             </tr>

              <tr class="total_record" style="background-color:#cbcbcb;font-family:'Open Sans',arial,sans-serif!important;">
                <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <?php echo $reported_days;?>
                        </td>
                      </tr>
                      
                    </table>
                  </td> 
                   
                   <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                   <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <?php echo !empty($total_scheduled_work)?intdiv($total_scheduled_work, 60).'.'. ($total_scheduled_work % 60).' hrs':'0 hrs';?>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                 <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                  <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <?php echo !empty($total_production)?intdiv($total_production, 60).'.'. ($total_production % 60).' hrs':'0 hrs';?>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                   <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                  <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <?php echo $total_late_arrival;?>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                   <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                  <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <?php echo !empty($total_missing_hour)?intdiv($total_missing_hour, 60).'.'. ($total_missing_hour % 60).' hrs':'0 hrs';?>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                   <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                  <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <?php echo !empty($total_overtime)?intdiv($total_overtime, 60).'.'. ($total_overtime % 60).' hrs':'0 hrs';                     
                      ?>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                   <!-- <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                  <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <?php echo !empty($total_night_production_hour)?intdiv($total_night_production_hour, 60).'.'. ($total_night_production_hour % 60).' hrs':'0 hrs';?>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                   <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                  <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <?php echo !empty($total_extra_night_production_hour)?intdiv($total_extra_night_production_hour, 60).'.'. ($total_extra_night_production_hour % 60).' hrs':'0 hrs';?>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                  <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                  <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <?php echo '-';?>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                  <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <?php echo $workday;?>
                        </td>
                      </tr>
                      
                    </table>
                  </td> 
                   <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <?php echo $actually_worked;?>
                        </td>
                      </tr>
                      
                    </table>
                  </td>
                   <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                      <tr>
                        <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <?php echo $absent;?>
                        </td>
                      </tr>
                      
                    </table>
                  </td> -->
               <!-- <td><?php echo $reported_days-$week_off;?></td> -->
               
             </tr>

            
                   
                 <?php }  } } 

                  
                    ?>
            
           <?php } else {?>
            <tr><td ><?php echo lang('no_records_found')?></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <!-- <td></td>
              <td></td>
              <td></td> -->
            
            </tr>
          <?php  } ?>
            </tbody>
          </table>
      </div>






     <!--Empty table--->
      
      <!--/Empty table-->