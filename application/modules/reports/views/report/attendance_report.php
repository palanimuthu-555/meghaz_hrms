<?php //echo 'session<pre>'; print_r($this->session->userdata()); exit; ?>
<script src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="<?=base_url()?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/daterangepicker/daterangepicker.css"/> 

<?php 
// $branch_id = $this->session->userdata('branch_id');

$cur = App::currencies(config_item('default_currency')); 
$task = ($task_id > 0) ? $this->db->get_where('tasks',array('t_id'=>$data['task_id'])) : array();
$project_id = (isset($task_id)) ? $task_id : '';
$task_progress = (isset($task_progress)) ? $task_progress : '';
$task_id = (isset($company_id)) ? $company_id : '';



// date_default_timezone_set('Asia/Kolkata');
  $punch_in_date = date('Y-m-d');
  $punch_in_time = date('H:i');
  $punch_in_date_time = date('Y-m-d H:i');


   $strtotime = strtotime($punch_in_date_time);
   $a_year    = date('Y',$strtotime);
   $a_month   = date('m',$strtotime);
   $a_day     = date('d',$strtotime);
   $a_days     = date('d',$strtotime);
   $a_dayss     = date('d',$strtotime);
   $a_cin     = date('H:i',$strtotime);
   // $subdomain_id     = $this->session->userdata('subdomain_id');
   // $night_hours = $this->db->get_where('night_hours',array('subdomain_id' =>$subdomain_id ))->row_array();

   $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
    // if($branch_id != '') {
    //     $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
    // }
   $record = $this->db->select('ad.*')
                ->from('attendance_details ad')
                ->join('users u', 'u.id=ad.user_id')
                ->where($where)
                ->get()
                ->row_array();
   /*
   $this->db->select('month_days,month_days_in_out');
   $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();*/

   $punchin_id = 1;
   if(!empty($record['month_days'])){
     
    
      $month_days =  unserialize($record['month_days']);
      $month_days_in_out =  unserialize($record['month_days_in_out']);
     
     $a_day -=1;

     if(!empty($month_days[$a_day])  && !empty($month_days_in_out[$a_day])){  

      $day = $month_days[$a_day];
      $day_in_out = $month_days_in_out[$a_day];


      $latest_inout = end($day_in_out);

    
        if($day['day'] == '' || !empty($latest_inout['punch_out'])){ 
          $punch_in = $day['punch_in'];
          $punch_in_out = $latest_inout['punch_in'];
          $punch_out_in = $latest_inout['punch_out'];
          $punchin_id = 1;
        }else{
           $punch_in = $day['punch_in'];
          $punch_in_out = $latest_inout['punch_in'];
          $punch_out_in = $latest_inout['punch_out'];
          $punchin_id = 0;
        }
     }
         
            
     

     $punchin_time = date("g:i a", strtotime($day['punch_in']));
     $punchout_time = date("g:i a", strtotime($day['punch_out']));
   }

  ?>


  <?php
        $a_dayss -=1;
        $production_hour=0;
        $break_hour=0;

         if(!empty($record['month_days_in_out'])){

         $month_days_in_outss =  unserialize($record['month_days_in_out']);

                              
          foreach ($month_days_in_outss[$a_dayss] as $punch_detailss) 
          {

              if(!empty($punch_detailss['punch_in']) && !empty($punch_detailss['punch_out']))
              {
                
                  $production_hour += time_difference(date('H:i',strtotime($punch_detailss['punch_in'])),date('H:i',strtotime($punch_detailss['punch_out'])));
              }
                        
                                          
               
          }

           for ($i=0; $i <count($month_days_in_outss[$a_dayss]) ; $i++) { 

                      if(!empty($month_days_in_outss[$a_dayss][$i]['punch_out']) && $month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])
                      {
                          
                          $break_hour += time_difference(date('H:i',strtotime($month_days_in_outss[$a_dayss][$i]['punch_out'])),date('H:i',strtotime($month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])));
                      }

                      
            }
        }
    
       $s_year = '2015';
              $select_y = date('Y');

              $s_month = date('m');
              $e_year = date('Y');





?>

<div class="content">
 <!--  <div class="row"> 
    <div class="col-sm-12  text-right m-b-20">     
      <a class="btn back-btn m-b-10" href="<?=base_url()?>attendance"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>
    </div>
  </div> -->
    <div class="page-header">
      <div class="row">
        <div class="col-sm-8">
          <h4 class="page-title m-b-10 m-r-10" style="display: inline-block;"><?=lang('attendance_report')?></h4>
            <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                  <li class="breadcrumb-item active">Report</li>
              </ul>
        </div>
        <div class="col-sm-4 text-right">
         <!--  <a class="btn btn-white m-r-5 m-b-10" href="javascript: void(0);" id="filter_search">
            <i class="fa fa-filter m-r-0"></i>
          </a> -->
          <div class="btn-group">
            <button class="btn btn-light dropdown-toggle m-b-10" data-toggle="dropdown"><?=lang('export')?></button>
           
            <ul class="dropdown-menu export" style="left:auto; right:0px !important; min-width: 93px !important">  
              <li>
                <form method="post" action="">
                    <input type="hidden" class="form-control" name = "pdf" value="1">
                    
                    <input type="hidden" class="form-control department_id_excel" name = "department_id" value="<?php echo (isset($_POST['department_id']) && !empty($_POST['department_id']))?$_POST['department_id']:'';?>">
                    <input type="hidden" class="form-control teamlead_id_excel" name = "teamlead_id" value="<?php echo (isset($_POST['teamlead_id']) && !empty($_POST['teamlead_id']))?$_POST['teamlead_id']:'';?>">
                    <input type="hidden" class="form-control range_excel" name = "range" value="<?php echo (isset($_POST['range']) && !empty($_POST['range']))?$_POST['range']:'';?>">
                    <input type="hidden" class="form-control user_id_excel" name = "user_id" value="<?php echo (isset($_POST['user_id']) && !empty($_POST['user_id']))?$_POST['user_id']:'';?>">
                   
                    <button class=" btn  btn-block" type="submit" style="text-align: left;"> <span style="font-size: 18px;text-align: left;"><i class="fa fa-file-pdf-o"></i></span> <span><?=lang('pdf')?></span></button>
                     <!-- <a href="#" class="pull-right" id="attendance_report_pdf1" type="submit"> -->
                     
                      <!-- </a> -->
                </form>
               
              </li>

           
              <li>
                <?php  $report_name = lang('attendance_report');?>
                 <button class="btn  btn-block" onclick="attendance_report_excel('<?php echo $report_name;?>','attendance_report_excel');" style="text-align: left;"> <span style="font-size: 18px;text-align: left;"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span><span><?=lang('excel')?></span> </button>
              </li>
            </ul>
          </div>
          <?=$this->load->view('report_header');?>
          <?php if($this->uri->segment(3) && count($employees)> 0 ){ ?>
          <a href="<?=base_url()?>reports/employeepdf/<?=$company_id;?>" class="btn btn-primary pull-right">
            <i class="fa fa-file-pdf-o"></i> <?=lang('pdf')?>
          </a>
          <?php } ?>
        </div>
      </div>
</div>
        
  

  
          

<form method="post" action="" class="filter-form" id="filter_inputs" >
        <div class="row filter-row">
          <!-- <div class="col-md-3">
            <div class="form-group">
              <label><?=lang('employees_code')?></label>
              <input type="text" class="form-control" name = "id_code" value="<?php echo (isset($_POST['id_code']) && !empty($_POST['id_code']))?$_POST['id_code']:'';?>">          
             
            </div>
          </div> -->
          <div class="col-md-3 col-lg-2">
            <div class="form-group form-focus select-focus">
              <label class="control-label"><?=lang('employees')?></label>
              <select class="select2-option form-control floating" name="user_id" id="user_name">
                    <optgroup label="">
                    <option value=""><?php echo lang('select_employee');?></option> 
                        <?php 
                       
                        $employee = $this->db->get_where('users',array('role_id'=>3,'activated'=>1,'banned'=>0))->result();


                        foreach ($employee as $c): 
                        ?>

                            <option value="<?php echo $c->id;?>" <?php echo(isset($_POST['user_id']) && $_POST['user_id'] == $c->id)?"selected":"";?>><?php echo User::displayName($c->id);?></option>
                        <?php endforeach;  ?>
                    </optgroup>
                </select>
            </div>
          </div>
          <?php $departments = $this->db->order_by("deptname", "asc")->get_where('departments')->result(); ?>
          <div class="col-md-3 col-lg-2">
            <div class="form-group form-focus select-focus">
              <label class="control-label"><?=lang('department')?></label>
              <select class="select2-option form-control floating" name="department_id" id="department" >
                    <option value="" selected ><?php echo lang('select_department');?></option>
                    <?php
                    if(!empty($departments))  {
                    foreach ($departments as $department){ ?>
                    <option value="<?=$department->deptid?>" <?php echo (isset($_POST['department_id']) && ($_POST['department_id'] == $department->deptid))?"selected":""?>><?=$department->deptname?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
            </div>
          </div>
          
          <?php 
            

          $teamlead_id = $this->db->where(array('role_id'=>3,'activated'=>1,'banned'=>0,'is_teamlead'=>'yes')) -> get('users')->result(); ?>
          <div class="col-md-3 col-lg-2">
            <div class="form-group form-focus select-focus">
              <label class="control-label"><?=lang('employees_boss')?></label>
              <select class="select2-option form-control floating" name="teamlead_id" >
                    <option value="" selected ><?php echo lang('select_boss');?></option>
                    <?php
                    if(!empty($teamlead_id))  {
                    foreach ($teamlead_id as $teamlead){ ?>
                    <option value="<?=$teamlead->id?>" <?php echo (isset($_POST['teamlead_id']) && ($_POST['teamlead_id'] == $teamlead->id))?"selected":""?>><?php echo User::displayName($teamlead->id);?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
            </div>
          </div>
          <div class="col-md-3 col-lg-2">
            <div class="form-group form-focus select-focus">
              <label class="control-label"><?=lang('rangeof_time')?></label>
              <input type="text" name="range" id="reportrange" class="pull-right form-control floating" value="<?php echo (isset($_POST['range']) && !empty($_POST['range']))?$_POST['range']:'';?>">
        
            </div>
          </div>
          
          <div class="col-md-2">  
           <!--  <label class="d-block">&nbsp;</label> -->
            <button class="btn btn-success btn-md" type="submit"><?=lang('run_report')?></button>
          </div>
        </div>
      </form>



      <div class="table-responsive mt-3">
        <table id="table-attendance_reports" class="table table-striped custom-table m-b-0 AppendDataTables">
            <thead>
              <tr class="attendance_record">
                <th><?=lang('date')?></th>
                <th><?=lang('workday')?></th>
                <th><?=lang('work')?></th>
                <th><?=lang('late_arrival')?></th>
                <th><?=lang('missing_work')?></th>
                <th><?=lang('extra_time')?></th>
                <!-- <th><?=lang('night_hours')?></th> -->
                <!-- <th><?=lang('extra_night_hours')?></th> -->
                <!-- <th><?=lang('work_code')?></th> -->
              
              </tr>
            </thead>
            <tbody>
              <?php if( !empty($_POST['user_id']) || !empty($_POST['department_id']) || !empty($_POST['teamlead_id']) || !empty($_POST['range']))
                { 
                  // print_r($_POST); exit();
                 
                  if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                    $user_id[] = $_POST['user_id'];
                  }
                  if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                    //$dept_users= $this->db->get_where('users',array('subdomain_id'=>$subdomain_id,'department_id'=>$_POST['department_id']))->result_array();
                    $dept_id = $_POST['department_id'];
                    $dept_users= $this->db->select('*')
                                ->from('users')                                
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
              <tr>
                <td>

                  <div class="user_det_list">
                    <a href="<?php echo base_url().'employees/profile_view/'.$value;?>"> <img class="avatar" src="<?php echo base_url();?>assets/avatar/<?php echo  $imgs;?>"></a>
                    <h2><span class="username-info"><?php echo ucfirst(user::displayName($value));?></span>
                    <span class="userrole-info"> <?php echo $designation_name;?></span></h2>
                  </div>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <!--<td></td>
                <td></td>
                <td></td> -->
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
                     /* $this->db->select('month_days,month_days_in_out');
                      $this->db->where('user_id', $user_id);
                      $this->db->where('a_month ', $start_month);
                      // $this->db->where('a_month <=', $end_month);
                      // $this->db->where('a_year >=', $start_year);
                      $this->db->where('a_year ', $start_year);
                      $this->db->where('subdomain_id ', $subdomain_id);
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
                    /* $this->db->select('month_days,month_days_in_out');
                     $results  = $this->db->get_where('dgt_attendance_details',$where)->result_array();*/
                     
                    }
                   
                     
                     $sno=1;
                     $total_scheduled_work =0;
                    $actually_worked = 0;
                    $workday = 0;
                    $absent = 0;
                    $total_production = 0;
                    $total_night_production_hour = 0;
                    $total_extra_night_production_hour = 0;
                    $total_late_arrival = 0;
                    $total_missing_hour = 0;
                    $all_user_schedule = array();
                    $total_scheduled_minutes = 0;
                    $work_hours = 0;
                    $scheduled_minutes = 0;
                    $today_work_hour = array();
                     $overtimes  = 0;
                     $overtime  = 0;
                     $total_overtime  = 0;
                    // $number = $col_count;

                      // echo "<pre>";print_r($col_count); 
                     foreach ($results as $rows) {
                      // if($user_id == 3500){
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
                          $total_scheduled_minutes = 0;
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
                                 /* $this->db->select('month_days,month_days_in_out');
                                  $this->db->where('user_id', $user_id);
                                  $this->db->where('a_month ', $a_month);
                                  // $this->db->where('a_month <=', $end_month);
                                  // $this->db->where('a_year >=', $start_year);
                                  $this->db->where('a_year ', $a_year);
                                  $this->db->where('subdomain_id ', $subdomain_id);
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
                               
                                 $production_hour=0;
                                 // $night_production_hour=0;
                                 // $extra_night_production_hour=0;
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
                                        //     $night_hours_punch_in = night_hours_punch_in($today_work_hour['schedule_date'].' '.$night_hours['start_time'],$today_work_hour['schedule_date'].' '.$night_hours['end_time'],$today_work_hour['schedule_date'].' '.$punch_detail['punch_in']);
                                        //     $night_hours_punch_out = night_hours_punch_out($today_work_hour['schedule_date'].' '.$night_hours['start_time'],$today_work_hour['schedule_date'].' '.$night_hours['end_time'],$today_work_hour['schedule_date'].' '.$punch_detail['punch_out']);
                                           
                                        //    if ($night_hours_punch_in =='yes' && $night_hours_punch_out =='yes'){
                                        //       $night_production_hour += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));
                                        //    }
                                        //    if ($night_hours_punch_in =='yes' && $night_hours_punch_out =='no'){
                                        //       $night_production_hour += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($night_hours['end_time'])));
                                        //    }
                                        //    if ($night_hours_punch_in =='no' && $night_hours_punch_out =='yes'){
                                        //       $night_production_hour += time_difference(date('H:i',strtotime($night_hours['start_time'])),date('H:i',strtotime($punch_detail['punch_out'])));
                                        //       $extra_night_production_hour += time_difference(date('H:i',strtotime($night_hours['start_time'])),date('H:i',strtotime($punch_detail['punch_out'])));

                                        //    }
 

                                        // }
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
                                                 

                    ?>


                    <tr class="attendance_record">
                      
                     
                      <?php

                      // if(date('D', $time)=='Sat' || date('D', $time)=='Sun')
                      if(empty($user_schedule))
                      {
                        if(!empty($day['punch_in']))
                        {
                           $total_scheduled_work += $total_scheduled_minutes;
                          $total_production += $production_hour;
                          // $total_night_production_hour += $night_production_hour;
                          // $total_extra_night_production_hour += $extra_night_production_hour;
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
                            //     ->where(array('emp_id' => $user_id,'start_date'=>$schedule_date))
                            //     ->get()
                            //     ->row_array();

                          // $absent_workcodes =  $this->db->get_where('calendar_incident',array('subdomain_id'=>$subdomain_id,'emp_id' => $user_id,'start_date'=>$schedule_date))->row_array();
                          // if(!empty($absent_workcodes)){

                          // $absent_incident =  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $absent_workcodes['incident']))->row_array();
                          // }else{
                          //    $absent_incident = array();
                          // }
                        }?>
                       <td><?php echo $new_date ;?> <br>
                        <?php echo lang(strtolower(date('l', $time)))?>
                      </td>
                      <td>
                       
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
                        <br>

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
                         
                      <td><?php echo !empty($production_hour)?intdiv($production_hour, 60).'.'. ($production_hour % 60).' hrs':'-';?></td>
                      <td><?php echo '-';?></td>
                      <td><?php echo !empty($missing_work)?intdiv($missing_work, 60).'.'. ($missing_work % 60).' hrs':'-';?></td>
                      <td>
                        <?php 
                        // if($today_work_hour['accept_extras'] == 0){
                        // echo "-";
                      // }else {
                         echo !empty($production_hour)?intdiv($production_hour, 60).'.'. ($production_hour % 60).' hrs':'-';
                      // }
                      ?>
                        
                      </td>
                    <!--   <td><?php echo !empty($night_production_hour)?intdiv($night_production_hour, 60).'.'. ($night_production_hour % 60).' hrs':'-';?></td>
                       <td><?php echo !empty($extra_night_production_hour)?intdiv($extra_night_production_hour, 60).'.'. ($extra_night_production_hour % 60).' hrs':'-';?></td>
                       <td><?php echo '-';?></td> -->
                       
                       <?php   
                        }
                        else
                        { $week_off++;?>
                          <td style="color:red;"><?php echo $new_date;?> <br>
                        <?php echo lang(strtolower(date('l', $time)));?>
                      </td>
                      <td></td>
                      <!-- <td></td> -->
                      <!-- <td></td>
                      <td></td> -->
                      
                           <?php echo'<td  align="center" style="color:red;text-align: center;"> '.lang('week_off').'  </td>';
                        }?>
                        <td></td>
                      <td></td>
                      <td></td>
                     <?php  }
                      else
                      {
                        $total_scheduled_work += $total_scheduled_minutes;
                        $total_production += $production_hour;
                        // $total_night_production_hour += $night_production_hour;
                        // $total_extra_night_production_hour += $extra_night_production_hour;
                        $total_missing_hour += $missing_work;
                        $total_overtime +=$overtime;
                        $workday++;
                        if(!empty($day['punch_in']))
                        {
                           
                          // if($user_schedule['free_shift'] == 1 ){
                          //   $later_entry_hours = '-';
                          // }else{
                          
                           $later_entry_hours = !empty($later_entry_hours)?intdiv($later_entry_hours, 60).'.'. ($later_entry_hours % 60).' hrs':'-';
                          // }
                           $total_late_arrival += $later_entry_hours;
                   
                           $actually_worked++;
                           
                        } else {
                          $later_entry_hours = '-';
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

                          //$absent_workcodes =  $this->db->get_where('calendar_incident',array('subdomain_id'=>$subdomain_id,'emp_id' => $user_id,'start_date'=>$schedule_date))->row_array();
                          // if(!empty($absent_workcodes)){

                          // $absent_incident =  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $absent_workcodes['incident']))->row_array();
                          // }else{
                          //   $absent_incident = array();
                          // }
                        }?>
                       <td><?php echo $new_date;?> <br>
                        <?php echo lang(strtolower(date('l', $time)));?>
                      </td>
                      <td>
                        
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
                        <br>
                       
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
                                    

                 echo !empty($punch_detail['punch_in'])?'<i class="fa fa-arrow-right text-success"></i> &nbsp; '.date("g:i a", strtotime($punch_detail['punch_in'])).'  &nbsp;|&nbsp ':'<span style="color:red;">'.lang('absent').'</span>'; ?><?php echo !empty($punch_detail['punch_out'])?'<i class="fa fa-arrow-left text-danger"></i> &nbsp;  '.date("g:i a", strtotime($punch_detail['punch_out'])):''; ?>  <br>
               <?php }?>
              </td>
                         
                      <td><?php echo !empty($production_hour)?intdiv($production_hour, 60).'.'. ($production_hour % 60).' hrs':'-';?></td>
                      <td><?php echo $later_entry_hours;?></td>
                      <td><?php echo !empty($missing_work)?intdiv($missing_work, 60).'.'. ($missing_work % 60).' hrs':'-';?></td>
                      <td>
                        <?php 
                        // if($today_work_hour['accept_extras'] == 0){
                        // echo "-";
                      // }else {
                         echo !empty($overtime)?intdiv($overtime, 60).'.'. ($overtime % 60).' hrs':'-';
                      // }
                      ?></td>
                      <!--  <td><?php echo !empty($night_production_hour)?intdiv($night_production_hour, 60).'.'. ($night_production_hour % 60).' hrs':'-';?></td>
                       <td><?php echo !empty($extra_night_production_hour)?intdiv($extra_night_production_hour, 60).'.'. ($extra_night_production_hour % 60).' hrs':'-';?></td>
                       <td><?php echo '-';?></td> -->
                      <?php  
                      }
                     ?>
                    </tr>
                    <?php } } $reported_days = $number+1;?>
                     <tr class="total_record">
             
               <td><?php echo lang('total_reported_days'); ?></td>
              
               <td><?php echo lang('total_of_time') ?> <br><?php echo lang('that_the_employee') ?> <br><?php echo lang('should_have_worked') ?></td>
               <td><?php echo lang('total_of_time') ?><br> <?php echo lang('that_the_employee') ?><br> <?php echo lang('actually_worked') ?></td>
               <td><?php echo lang('total_of_time') ?><br> <?php echo lang('that_the_employee') ?><br> <?php echo lang('late_arrival') ?></td>
               <td><?php echo lang('total_of_time') ?><br> <?php echo lang('that_the_employee') ?><br> <?php echo lang('missed_work') ?></td>
               <td><?php echo lang('total_of_time') ?><br> <?php echo lang('that_the_employee') ?><br> <?php echo lang('extra_hours_worked') ?></td>
               <!-- <td><?php echo lang('total_of_time') ?> <br><?php echo lang('that_the_employee') ?> <br><?php echo lang('night_hours_worked') ?></td>
               <td><?php echo lang('total_of_time') ?> <br>t<?php echo lang('that_the_employee') ?> <br><?php echo lang('extra_night_hours_worked') ?></td>
               
               <td><?php echo lang('total_amounts_of') ?><br> <?php echo lang('time_for_all_the') ?><br> <?php echo lang('work_codes_used') ?></td> -->
                <!-- <td><?php echo lang('days_the_employee') ?><br> <?php echo lang('should_have_worked') ?></td>
               <td><?php echo lang('days_the_employee') ?> <br> <?php echo lang('actually_worked') ?></td>
               <td> <?php echo lang('days_the_employee') ?><br> <?php echo lang('was_absent') ?></td> -->
               <!-- <td><?php echo lang('totals_for_the') ?><br><?php echo lang('columns_configured') ?></td> -->
               
             <tr>
              <tr class="total_record">
               <td><?php echo $reported_days;?></td>
               <!-- <td><?php echo $reported_days-$week_off;?></td> -->
              
               <td><?php echo !empty($total_scheduled_work)?intdiv($total_scheduled_work, 60).'.'. ($total_scheduled_work % 60).' hrs':'0 hrs';?></td>
               <td><?php echo !empty($total_production)?intdiv($total_production, 60).'.'. ($total_production % 60).' hrs':'0 hrs';?></td>
               <td><?php echo $total_late_arrival;?></td>
               <td><?php echo !empty($total_missing_hour)?intdiv($total_missing_hour, 60).'.'. ($total_missing_hour % 60).' hrs':'0 hrs';?></td>
                <td><?php echo !empty($total_overtime)?intdiv($total_overtime, 60).'.'. ($total_overtime % 60).' hrs':'0 hrs';                     
                      ?></td>
               <!-- <td><?php echo !empty($total_night_production_hour)?intdiv($total_night_production_hour, 60).'.'. ($total_night_production_hour % 60).' hrs':'0 hrs';?></td>
               
               <td><?php echo !empty($total_extra_night_production_hour)?intdiv($total_extra_night_production_hour, 60).'.'. ($total_extra_night_production_hour % 60).' hrs':'0 hrs';?></td>
               <td></td> -->
               <!--  <td><?php echo $workday;?></td>
               <td><?php echo $actually_worked;?></td>
               <td><?php echo $absent;?></td> -->
             </tr>
                    <?php   } } 

                  
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

      </div>
    </div>
  </section>
</div>



<script>
  // var start = moment().subtract(29, 'days');
  var start = moment();
  var end = moment();

  $('#reportrange').daterangepicker({
    // startDate: start,
    // endDate: end,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
  });
</script>
     