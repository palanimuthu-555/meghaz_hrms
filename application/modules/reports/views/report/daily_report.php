<script src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="<?=base_url()?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/daterangepicker/daterangepicker.css"/> 


<?php 

date_default_timezone_set('Asia/Kolkata');
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
   
   $record = $this->db->select('ad.*')
  				->from('attendance_details ad')
  				->join('users u', 'u.id=ad.user_id')
  				->where(array('a_month'=>$a_month,'a_year'=>$a_year))
  				->get()
  				->result_array();

   /*$where     = array('subdomain_id'=>$subdomain_id,'a_month'=>$a_month,'a_year'=>$a_year);
   // $this->db->select('month_days,month_days_in_out');
   $record  = $this->db->get_where('dgt_attendance_details',$where)->result_array();*/
?>

<div class="content">
	

			<div class="row mb-4">
				<div class="col-sm-8">
					<h4 class="page-title m-b-0"><?=lang('daily_report')?></h4>
				</div>
				<div class="col-sm-4 text-right">
					<!-- <a class="btn btn-white m-r-5" href="javascript: void(0);" id="filter_search">
						<i class="fa fa-filter m-r-0"></i>
					</a> -->
					 <div class="btn-group">
			            <button class="btn btn-light m-b-10 dropdown-toggle" data-toggle="dropdown"><?=lang('export')?></button>
			           
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
                <?php  $report_name = lang('daily_report');?>
                 <button class="btn btn-block" onclick="daily_report_excel('<?php echo $report_name;?>','excel_export_id');" style="text-align: left;"> <span style="font-size: 18px;text-align: left;"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span><span><?=lang('excel')?></span> </button>
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

		
		<?php 
			if(!empty($record)){
	            foreach ($record as $key => $value) {
	              $all_user_id[] = $value['user_id'];
	            }
          	}
	      	$all_user_id =  array_unique($all_user_id);

	             // echo "<pre>";   print_r($user_id); exit;
	      	// Today present and absent recrds
	      	$today_present = 0;
	      	$today_absent = 0;
	      	$today_late = 0;

           	foreach ($all_user_id as $key => $value) {

	           	if($value !=1){
                    
                  	$user_id = $value;
                  	$where     = array('ad.user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
                  	
                  	$results = $this->db->select('ad.*')
                  				->from('attendance_details ad')
                  				->join('users u', 'u.id=ad.user_id', 'left')
                  				->where($where)
                  				->get()
                  				->result_array();
                  	/*
                 	$this->db->select('month_days,month_days_in_out');
                 	$results  = $this->db->get_where('dgt_attendance_details',$where)->result_array();*/
                 	foreach ($results as $rows) {
		              	$current_date=date('Y-m-d');
		              	$current_day =date('d');
		              	$current_month =date('m');
			            $current_year =date('Y');			           
			            $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date'=>$current_date);
			            $user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->row_array(); 
			            $shift =  $this->db->get_where('shifts',array('id' => $user_schedule['shift_id']))->row_array(); 

                        if(!empty($rows['month_days'])){
	                        $month_days =  unserialize($rows['month_days']);
	                        $month_days_in_out =  unserialize($rows['month_days_in_out']);
	                        $day = $month_days[$current_day-1];
	                        $day_in_out = $month_days_in_out[$current_day-1];
	                        $latest_inout = end($day_in_out);
                         	if(!empty($user_schedule)){
		                       
                        
		                        if(!empty($day['punch_in']))
		                        {		                        	
		                           $today_present++;	

			                        	$later_entry_minutes = later_entry_minutes($user_schedule['schedule_date'].' '.$user_schedule['max_start_time'],$schedule_date.' '.$day['punch_in']);


                           				if($later_entry_minutes > 0){
                           					$today_late++;
                           				}                           
		                       	} else {
		                       		
		                       		$today_absent++;
		                       	}
	                        } 
	                	}
			        }
	         	}
	         	
	        }

	   
	        ?>
	        <div class="row justify-content-center">
							<div class="col-md-3 col-sm-6">
								<div class="card">
									<div class="card-body text-center">
										<h3><b><?php echo count($record);?></b></h3>
										<p>Total Employees</p>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="card">
									<div class="card-body text-center">
										<h3 class="text-success"><b><?php echo (isset($present) && !empty($present))?$today_present:0;?></b></h3>
										<p>Today Present</p>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="card">
									<div class="card-body text-center">
										<h3 class="text-danger"><b><?php echo (isset($today_absent) && !empty($today_absent))?$today_absent:0;;?></b></h3>
										<p>Today Absent</p>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="card">
									<div class="card-body text-center">
										<h3><b><?php echo (isset($today_late) && !empty($today_late))?$today_late:0;;?></b></h3>
										<p>Today Late</p>
									</div>
								</div>
							</div>
						</div>
		
			<form method="post" action="" class="filter-form" id="filter_inputs">
				<div class="row  filter-row">
				
		          <div class="col-md-3">
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
		              <select class="select2-option form-control floating" name="teamlead_id" id="teamlead_id" >
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
		          <div class="col-md-3 col-lg-3">
		            <div class="form-group form-focus select-focus">
		              <label class="control-label"><?=lang('rangeof_time')?></label>
		              <input type="text" name="range" id="reportrange" class="pull-right form-control floating" value="<?php echo (isset($_POST['range']) && !empty($_POST['range']))?$_POST['range']:'';?>">
		             
		            </div>
		          </div>
					<!-- <div class="col-md-3">
						<div class="form-group">
							<label><?=lang('rangeof_time')?></label>
							<div id="reportrange">
								<i class="fa fa-calendar"></i>&nbsp;
								<span></span> <i class="fa fa-caret-down"></i>
							</div>
						</div>
					</div> -->
					
					<div class="col-md-2">  
						
						<button class="btn btn-success btn-md" type="submit"><?=lang('run_report')?></button>
						<label class="d-block">&nbsp;</label>
					</div>
				</div>
			</form>
		
			
				<div class="table-responsive">

			<table id="excel_export_id" class="table table-striped custom-table m-b-0 AppendDataTables">
				<thead>
					<tr>
						<th><?=lang('date')?> </th>
						<th><?=lang('employee')?> </th>
						<th><?=lang('status')?></th>
					</tr>
				</thead>
				<tbody>
				<?php 
				 $user_id = array();
				if(!empty($_POST['user_id']) || !empty($_POST['department_id']) || !empty($_POST['teamlead_id']) || !empty($_POST['range']))
                { 
                 
                

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
                    	
                    	 // echo "<pre>";   print_r($_POST); exit;
                      // $all_users = $this->db->get_where('users',array('role_id !='=>2,'role_id !='=>1,'activated'=>1,'banned'=>0))->result_array();
                     /* $this->db->where('a_month >=', $start_month);
                      $this->db->where('a_month <=', $end_month);
                      $this->db->where('a_year >=', $start_year);
                      $this->db->where('a_year <=', $end_year);
                      $this->db->where('subdomain_id', $subdomain_id);
                      $all_users =  $this->db->get('attendance_details')->result_array();
*/
                      	
                      	$all_users = $this->db->select('ad.*')
                      				->from('attendance_details ad')
                      				->join('users u', 'u.id=ad.user_id')
                      				->where(array('a_month >='=>$start_month, 'a_month <='=>$end_month, 'a_year >='=>$start_year,'a_year <='=>$end_year))
                      				->get()
                      				->result_array();
                       //echo "<pre>";   print_r($all_users); exit;
                       if(!empty($all_users)){
                        foreach ($all_users as $key => $value) {
                          $user_ids[] = $value['user_id'];
                          
                        }
                        $user_id = $user_ids;

                      }
                    }
                     
                  } 
              	} else{            	 
	               if(!empty($record)){
	                foreach ($record as $key => $value) {
	                  $user_id[] = $value['user_id'];
	                }
              	}
          	}
              $user_id =  array_unique($user_id);

                     // echo "<pre>";   print_r($user_id); exit;

                   foreach ($user_id as $key => $value) {

                   	if($value !=1){
                    
                  $user_id = $value;

$user_details= $this->db->get_where('users',array('id'=>$user_id))->row_array();
$account_details= $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();
                      ?>
             
              
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
                      	
                      	$results = $this->db->select('month_days,month_days_in_out')
                      				->from('attendance_details ad')
                      				->join('users u', 'u.id=ad.user_id')
                      				->where(array('user_id'=>$user_id, 'a_month '=>$start_month, 'a_year '=>$start_year))
                      				->get()
                      				->result_array();
                      /*$this->db->select('month_days,month_days_in_out');
                      $this->db->where('user_id', $user_id);
                      $this->db->where('a_month ', $start_month);
                      // $this->db->where('a_month <=', $end_month);
                      // $this->db->where('a_year >=', $start_year);
                      $this->db->where('a_year ', $start_year);
                      $this->db->where('subdomain_id', $subdomain_id);
                      $results =  $this->db->get('attendance_details')->result_array();*/

                    } else{
                      	$a_year    = date('Y');
                      	$a_month   = date('m');                      	
                      	$results = $this->db->select('month_days,month_days_in_out')
                      				->from('attendance_details ad')
                      				->join('users u', 'u.id=ad.user_id')
                      				->where(array('user_id'=>$user_id, 'a_month '=>$a_month, 'a_year '=>$a_year))
                      				->get()
                      				->result_array();

                     /*$where     = array('subdomain_id'=>$subdomain_id,'user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
                     $this->db->select('month_days,month_days_in_out');
                     $results  = $this->db->get_where('dgt_attendance_details',$where)->result_array();*/
                     
                    }
                   
                     
                     $sno=1;
                     // echo "<pre>";print_r($results); 
                     foreach ($results as $rows) {

                          $list=array();
                          if(isset($_POST['range']) && !empty($_POST['range'])){
                            $number = $col_count;
                            $start_val = 0;
                          }else{
                            $month = $a_month;
                            $year = $a_year;

                            $number = $a_day;
                            // $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            $start_val = $a_day;

                          }
                          $week_off = 0;
                          $actually_worked = 0;
                          $absent = 0;
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
                                  $shift =  $this->db->get_where('shifts',array('id' => $user_schedule['shift_id']))->row_array(); 
	                               if(!empty($user_schedule)){
	                                  $total_scheduled_hour = work_hours($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$user_schedule['schedule_date'].' '.$user_schedule['end_time'],$user_schedule['break_time']);

	                                  $total_scheduled_minutes = $total_scheduled_hour;                                     
	                                  
	                                } else{
	                                  $total_scheduled_minutes = 0;
	                                }


                                if(!empty($rows['month_days'])){
     
    
                                $month_days =  unserialize($rows['month_days']);
                                $month_days_in_out =  unserialize($rows['month_days_in_out']);
                                $day = $month_days[$a_day-1];
                                $day_in_out = $month_days_in_out[$a_day-1];
                                $latest_inout = end($day_in_out);
                               
                                 
                                 $k = 1;
                               
                        
                             $user_details= $this->db->get_where('users',array('id'=>$user_id))->row_array();
							$account_details= $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();                    
							if(!empty($user_details['designation_id'])){
		                      $designation = $this->db->get_where('designation',array('id'=>$user_details['designation_id']))->row_array();
		                      $designation_name = $designation['designation'];
		                      
		                    }else{
		                      $designation_name = '-';
		                    }
                    ?>


                     <tr>
                     		<td><?php echo $new_date ;?> <br>
                        <?php echo date('l', $time)?>
                      </td>
               				<td>
               					<div class="user_det_list" style="margin-bottom: 10px;">
				                    <a href="<?php echo base_url().'employees/profile_view/'.$user_id;?>"> <img class="avatar"  src="<?php echo base_url();?>assets/avatar/<?php echo $account_details['avatar']?>"></a>
				                    <h2><span class="username-info text-dark"><?php echo ucfirst(user::displayName($user_details['id']));?></span>
				                    <span class="userrole-info"> <?php echo $designation_name;?></span>
				                   </h2>
				                  </div>
				                   <?php echo !empty($shift['shift_name'])?ucfirst($shift['shift_name']):''?>&nbsp;<?php echo !empty($total_scheduled_minutes)?'('.intdiv($total_scheduled_minutes, 60).'.'. ($total_scheduled_minutes % 60).' hrs)':'';?><br>	
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
					                                    

					                 echo !empty($punch_detail['punch_in'])?'<i class="fa fa-arrow-right text-success"></i> &nbsp; '.date("g:i a", strtotime($punch_detail['punch_in'])).' &nbsp;|&nbsp ':''; ?><?php echo !empty($punch_detail['punch_out'])?'<i class="fa fa-arrow-left text-danger"></i> &nbsp;  '.date("g:i a", strtotime($punch_detail['punch_out'])):''; ?>  <br>
					               <?php }?>						
						</td>
                      
                     
                      <?php

                      // if(date('D', $time)=='Sat' || date('D', $time)=='Sun')
                      if(empty($user_schedule))
                      {
                        if(empty($day['punch_in']))
                        {
                           ?>
                         <td><span class="badge bg-primary text-white" style="background-color:  #ff1a75;padding: 5px 20px;
    font-size: 14px;min-width: 110px;display: inline-block;"><?php echo lang('week_off');?></span></td>    
                      
                          
                       <?php }?>
                        
                     <?php  }
                      else
                      {
                        
                        if(!empty($day['punch_in']))
                        {
                        	
                           $later_entry_minutes = later_entry_minutes($user_schedule['schedule_date'].' '.$user_schedule['max_start_time'],$schedule_date.' '.$day['punch_in']);


                           if($later_entry_minutes > 0){?>
                           	<td><span class="label label-warning" style="background-color: rgb(241, 180, 76);padding: 5px 20px;
    font-size: 14px;min-width: 110px;display: inline-block;"><?php echo lang('delay');?></span></td>
                          <?php  } else{?>
                          	<td><span class="label label-success" style="background-color: #1eb53a; padding: 5px 20px;
    font-size: 14px;min-width: 110px;display: inline-block;"><?php echo lang('present');?></span></td>
                          <?php }
                           ?>
                           
                       <?php } else {?>
                       	<td><span class="label label-info" style="background-color: rgb(61, 142, 248); padding: 5px 20px;
    font-size: 14px;min-width: 110px;display: inline-block;"><?php echo lang('absent');?></span></td>
                       <?php }?>
                      
                      
                    </tr>
                    <?php } }  } } } } ?>
					
				</tbody>
			</table>
		</div>
	</div>
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
	
// var present= '<?php echo $present;?>';	
// var absent= '<?php echo $absents;?>';	
// var late=  '<?php echo $late;?>';	
// alert(present);
// alert(absent);
// alert(late);


</script>