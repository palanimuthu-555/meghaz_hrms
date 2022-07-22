<?php $departments = $this->db->order_by("deptname", "asc")->get('departments')->result(); ?>
<div class="content">
	<!-- <div class="row">
		<div class="col-sm-12">
			<h4 class="page-title m-b-0"><?php echo lang('employee_management');?></h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li><a href="#">Home</a></li>
				<li><a href="#">Employees</a></li>
				<li><a href="#">Shift Schedule</a></li>
				<li class="active">Daily Schedule</li>
			</ul>
		</div>
	</div> -->
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="page-title"><?php echo lang('daily_schedule');?></h3>
				<ul class="breadcrumb">
					<li class="breadcrumb-item active"><a href="<?php echo base_url();?>">Dashboard</a></li>
					<li class="breadcrumb-item active"> <?php echo lang('daily_schedule')?></li>
				</ul>
			</div>
		</div>
	</div>
	
			<form id="timesheet_search" method="post" action="<?php echo base_url().'shift_scheduling/'; ?>">
		<div class="row filter-row">
			
			<div class="col-sm-6 col-12 col-md-3 col-lg-2">
				<div class="form-group form-focus">
					<label class="control-label"><?php echo lang('employee');?></label>
					<input type="text" class="form-control floating" name="username" id="username" value="<?php echo(isset($username))?$username:""?>">
					<label id="username_error" class="error display-none" for="username"><?php echo lang('please_enter_the_employee_name');?></label>
				</div>
			</div>
			<div class="col-sm-6 col-12 col-md-3">
				<div class="form-group form-focus select-focus" style="width:100%;">
					<label class="control-label">Select Department</label>
						<select class="select form-control floating" id="department_id" name="department_id" style="padding: 14px 9px 0px;">
						<option value="" selected="selected"><?php echo lang('all_departments');?></option>
						<?php if(!empty($departments)){ ?>
						<?php foreach ($departments as $department) { ?>
						<option value="<?php echo $department->deptid; ?>" <?php echo (isset($department_id) && $department_id == $department->deptid)?"selected":""?>><?php echo $department->deptname; ?></option>
						<?php  } ?>
						<?php } ?>
					</select>
					<label id="department_id_error" class="error display-none" for="department_id"><?php echo lang('please_select_the_department');?></label>
				
				</div>
			</div>
			<div class="col-sm-6 col-12 col-md-3 col-lg-2">
				<div class="form-group form-focus">
					<label class="control-label"><?php echo lang('date');?></label>
					<input type="text" class="form-control floating date_range" id="schedule_date" name="schedule_date" value="<?php echo (isset($schedule_date))?$schedule_date:"";?>" autocomplete="off">
					<label id="schedule_date_error" class="error display-none" for="schedule_date"><?php echo lang('please_select_the_date');?></label>
				</div>
			</div>
			<div class="col-sm-6 col-12 col-md-3 col-lg-2">
				<div class="form-group form-focus select-focus" style="width:100%;">
					<label class="control-label"><?php echo lang('date_view');?></label>
					<select class="select form-control floating" style="padding: 14px 9px 0px;" name="week" id="week">
						<option value="" selected="selected"><?php echo lang('select');?></option>
						<option value="week" <?php echo (isset($week) && $week == 'week')?"selected":"";?>><?php echo lang('week');?></option>
						<option value="month" <?php echo (isset($week) && $week == 'month')?"selected":"";?>><?php echo lang('month');?></option>
					</select>
					<label id="week_error" class="error display-none" for="week"><?php echo lang('month');?></label>
				</div>
			</div>
			<div class="col-sm-6 col-6 col-md-3">  
				<!-- <a href="javascript:void(0)" id="employee_search_btn" onclick="filter_next_page(1)" class="btn btn-success btn-block btn-searchEmployee btn-circle"> Search </a>  -->

				<button id="shif_schedule_search_btn" class="btn btn-primary btn-block btn-searchEmployee btn-circle" ><?php echo lang('search');?></button>   
			</div>
		
		</div>
		</form>
	
	
		<div class="row d-flex align-items-center">	
			<div class="col-sm-5 mb-2">
				<h4 class="page-title text-dark"><?php echo lang('daily_schedule');?></h4>
			</div>
			<div class="col-md-7 mb-2">
				<?php if(App::is_permit('menu_shift_scheduling','create')){?><a href="<?php echo base_url(); ?>shift_scheduling/add_schedule" class="btn add-btn mb-2"><i class="fa fa-plus"></i><?php echo lang('assign_shift');?></a><?php }?>
				<?php if(App::is_permit('menu_shift_scheduling','read')){?><a href="<?php echo base_url(); ?>shift_scheduling/shift_list" class="btn add-btn mb-2 m-r-5"><?php echo lang('shifts');?></a>		<?php }?>
				<!-- <a href="<?php echo base_url(); ?>shift_scheduling/schedule_group" class="btn add-btn m-r-5"><?php echo lang('rotary_schedule_groups');?></a>	 -->	
			</div>
		</div>
		<!-- /Page Title -->
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table m-b-0" id="policies_table">
						<thead>
							<tr>
								<th><?php echo lang('scheduled_shift');?></th>

								<?php
									if(isset($schedule_date) && !empty($schedule_date)){
                            		$schedules_date = explode('-', $schedule_date);
                        			$from_date = date("Y-m-d", strtotime($schedules_date));       
       								$to_date = date("Y-m-d", strtotime($schedules_date));
        							$earlier = new DateTime($from_date);
									$later = new DateTime($to_date);

        							$col_count = $later->diff($earlier)->format("%a");
                            					
            //                 					$to_date = date("Y-m-d", $your_to_date);
            //                 					$this->db->where('schedule_date >=', $from_date);
												// $this->db->where('schedule_date <=', $to_date);
                            			}else if(isset($week) && !empty($week)){
                            				if($week == 'week'){
                            					$dt_min = new DateTime("last saturday"); // Edit
											$dt_min->modify('+1 day'); // Edit
											$dt_max = clone($dt_min);
											$dt_max->modify('+6 days');
											$week_start = $dt_min->format('Y/m/d');
											$week_end = $dt_max->format('Y/m/d');
											// echo 'This Week ('.$dt_min->format('m/d/Y').'-'.$dt_max->format('m/d/Y').')'; 
												$col_count = 6;
                            				} else{

                            					$first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
												$last_day_this_month  = date('Y-m-t');
												$month_start = new DateTime($first_day_this_month);
												$month_end = new DateTime($last_day_this_month);

			        							$col_count = $month_end->diff($month_start)->format("%a");

                            				}
                            				
                            			}
                            			 else{
                            			 	$datetime = new DateTime(date());
					                        $start_date =  $datetime->format('d');
                            				// $col_count = date('t') - $start_date ;
                            				$col_count = 30 ;

					                        // $currentDayOfMonth=date('j');
					                        // $datetime = new DateTime($_POST['schedule_date']);
					                        // $start_date =  $datetime->format('d');
                            			}
                            			
								 for ($i=0; $i <=$col_count ; $i++) { 
								 	if(isset($schedule_date) && !empty($schedule_date)){
										echo'<th class="text-center">'.date('D d', strtotime('+'.$i.' days', strtotime($schedules_date[0]))).'</th>';
								 	}else if(isset($week) && !empty($week)){
								 		if($week == 'week'){
								 			echo'<th class="text-center">'.date('M D d', strtotime('+'.$i.' days', strtotime($week_start))).'</th>';
								 		} else{
								 			echo'<th class="text-center">'.date('M D d', strtotime('+'.$i.' days', strtotime($first_day_this_month))).'</th>';
								 		}
								 	}else{
								 		echo'<th class="text-center">'.date('M D d', strtotime('+'.$i.' days', time())).'</th>';
								 	}
									
								} ?>
								
							</tr>
						</thead>
						<tbody>
						<?php 
						if (count($shift_scheduling) > 0) {
							foreach ($shift_scheduling as $shift) {
								if(!empty($shift['user_id'])){

							$employee_shift = $this->db->get_where('shift_scheduling',array('employee_id'=>$shift['employee_id']))->result_array();

							$this->db->select('d.designation');
							$this->db->from('users as u');
							$this->db->join('designation as d','d.id=u.designation_id');
							$this->db->where('u.id',$shift['employee_id']);
							$employee_position = $this->db->get()->row_array();

							
							 ?>
							<tr>
								<td>
									<div class="user_det_list">
										<a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $shift['employee_id'];?>">
										<img class="avatar" src="<?php echo user::avatar_url($shift['employee_id'])?>">
										</a>
										<h2>
											<a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $shift['employee_id'];?>">
											<span class="username-info text-dark"><?php echo ucfirst(user::displayName($shift['employee_id']));?></span>
											</a>
											 <span class="userrole-info"><?php echo isset($employee_position['designation']) ? ucfirst($employee_position['designation']) : ''; ?></span>
										</h2>
									</div>
								</td>
								<?php 
								// if (count($employee_shift) > 0) {
								if(isset($schedule_date) && !empty($schedule_date)){
                            		$schedules_date = explode('-', $schedule_date);
                        			$from_date = date("Y-m-d", strtotime($schedule_date));       
       								$to_date = date("Y-m-d", strtotime($schedule_date));
        							$earlier = new DateTime($from_date);
									$later = new DateTime($to_date);

        							$col_count = $later->diff($earlier)->format("%a");
                            					
            //                 					$to_date = date("Y-m-d", $your_to_date);
            //                 					$this->db->where('schedule_date >=', $from_date);
												// $this->db->where('schedule_date <=', $to_date);
                            			}else if(isset($week) && !empty($week)){
                            				if($week == 'week'){
                            					$dt_min = new DateTime("last saturday"); // Edit
											$dt_min->modify('+1 day'); // Edit
											$dt_max = clone($dt_min);
											$dt_max->modify('+6 days');
											$week_start = $dt_min->format('Y/m/d');
											$week_end = $dt_max->format('Y/m/d');
											// echo 'This Week ('.$dt_min->format('m/d/Y').'-'.$dt_max->format('m/d/Y').')'; 
												$col_count = 6;
                            				} else{

                            					$first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
												$last_day_this_month  = date('Y-m-t');
												$month_start = new DateTime($first_day_this_month);
												$month_end = new DateTime($last_day_this_month);

			        							$col_count = $month_end->diff($month_start)->format("%a");

                            				}
                            				
                            			}
                            			 else{
                            			 	$datetime = new DateTime(date());
					                        $start_date =  $datetime->format('d');
                            				// $col_count = date('t') - $start_date ;
                            				$col_count = 30 ;
                            			}
									 for ($i=0; $i <=$col_count ; $i++) { 
									 	if(isset($schedule_date) && !empty($schedule_date)){
									 		$your_from_date = strtotime('+'.$i.' days', strtotime($schedules_date[0]));
									 		$your_to_date = strtotime('+'.$i.' days', strtotime($schedules_date[1]));

									 	}else if(isset($week) && !empty($week)){
									 		if($week == 'week'){
                            					$week_from_date = strtotime('+'.$i.' days', strtotime($week_start));
									 		$week_to_date = strtotime('+'.$i.' days', strtotime($week_end));
											// // echo 'This Week ('.$dt_min->format('m/d/Y').'-'.$dt_max->format('m/d/Y').')'; 
												
                            				} else{

                            					$first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
												$last_day_this_month  = date('Y-m-t');
												$month_from_date = strtotime('+'.$i.' days', strtotime($first_day_this_month));
									 		$month_to_date = strtotime('+'.$i.' days', strtotime($last_day_this_month));

                            				}
                            				
									 	}else{
									 	 $your_date = strtotime('+'.$i.' days', time());
									 	 $new_date = date("Y-m-d", $your_date);

									 	}
                            
                            			$this->db->where('employee_id',$shift['employee_id']);

                            			if(isset($schedule_date) && !empty($schedule_date)){
                            					
                            					$from_date = date("Y-m-d", $your_from_date);
                            					$to_date = date("Y-m-d", $your_to_date);
                            					$this->db->where('schedule_date', $from_date);
												// $this->db->where('schedule_date <=', $to_date);
                            			} elseif(isset($week) && !empty($week)){
                            				if($week == 'week'){
                            					$from_date = date("Y-m-d", $week_from_date);
                            					$to_date = date("Y-m-d", $week_to_date);
                            					$this->db->where('schedule_date', $from_date);
												// $this->db->where('schedule_date <=', $to_date);
                            				}else{
                            					$from_date = date("Y-m-d", $month_from_date);
                            					$to_date = date("Y-m-d", $month_to_date);
                            					$this->db->where('schedule_date', $from_date);
												// $this->db->where('schedule_date <=', $to_date);
                            				}

                            			} else {                            				
                            					$this->db->where('schedule_date',$new_date);
                            			}
                            			$employee_shifts = $this->db->get('shift_scheduling')->row_array();
                            			$shift_color = $this->db->get_where('shifts',array('id'=>$employee_shifts['shift_id']))->row_array();

                            				   // echo'<pre>';print_r($employee_shift);
                            				   // echo $this->db->last_query(); exit;
									 	// $employee_shifts = $this->db->get_where('shift_scheduling',array('employee_id'=>$shift['employee_id'],'schedule_date'=>$new_date))->row_array();
							// foreach ($employee_shift as $employee_shifts) { 
									     

                            if(!empty($employee_shifts)){
								?>
									<td>
										
									<div class="user-add-shedule-list">
										<h2 style="border-radius:5px;padding:5px;">
											<a href="<?php echo base_url(); ?>shift_scheduling/edit_schedule/<?php echo $employee_shifts['id'];?>/<?php echo $employee_shifts['schedule_date'];?>" style="border:2px dashed <?php echo(!empty($shift_color['color']))?$shift_color['color']:"#512da8"?>" >
												<span class="userrole-info "><?php echo ucfirst($shift_color['shift_name']);?></span>
											<span class="username-info m-b-10"><?php echo date("g:i a", strtotime($employee_shifts['start_time']));
?> - <?php echo date("g:i a", strtotime($employee_shifts['end_time']));

?> ( <?php echo differnceTime($employee_shifts['schedule_date'].' '.$employee_shifts['start_time'],$employee_shifts['schedule_date'].' '.$employee_shifts['end_time'],$employee_shifts['break_time']);?>)</span>											
											</a>
										</h2>
									</div>
								</td>
								<?php } else { ?> 
									<td>
									<div class="user-add-shedule-list">
										<a href="<?php echo base_url(); ?>shift_scheduling/add_schedule/<?php echo $shift['employee_id'];?>/<?php echo $new_date;?>">
										<span><i class="fa fa-plus"></i></span>
										</a>
									</div>
								</td>
								<?php }
							// }
							}
								 // } ?>
									
								<!-- <td>
									<div class="user-add-shedule-list">
										<a href="<?php echo base_url(); ?>shift_scheduling/add_schedule">
										<span><i class="fa fa-plus"></i></span>
										</a>
									</div>
								</td> -->
								
								
							</tr>
							<?php 
						}
						} 
						} else{ ?>
							<tr>
								<td colspan="57">No Records Found</td>
							</tr>
						<?php } ?>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

