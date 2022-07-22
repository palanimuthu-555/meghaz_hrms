<?php 	$departments = $this->db->order_by("deptname", "asc")->get('departments')->result(); 
		$shifts = $this->db->where('published',1)->order_by("id", "asc")->get('shifts')->result();
?>
<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-sm-8">
			<h4 class="page-title m-b-0"><?php echo lang('shift_scheduling');?></h4>
			<ul class="breadcrumb m-b-20 p-l-0" style="background:none; border:none;">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('employees');?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>shift_scheduling"><?php echo lang('shift_scheduling');?></a></li>
				<li class="breadcrumb-item active"><?php echo lang('edit_schedule');?></li>
			</ul>
		</div>
		<div class="col-sm-4  text-right m-b-20">     
	          <a class="btn add-btn" href="<?=base_url()?>shift_scheduling"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>
	      </div>
	</div>
</div>
	<!-- <div class="card-box">
		<ul class="nav nav-tabs nav-tabs-solid page-tabs">
			<li ><a href="<?php echo base_url(); ?>shift_scheduling">Daily Schedule</a></li>
			<li class="active"><a href="<?php echo base_url(); ?>shift_scheduling/add_schedule"><?php echo lang('add_schedule');?></a></li></ul>
		</div> -->
	<div class="row">
		<div class="col-lg-8 offset-lg-2">
			<!-- Add Schedule -->
			<div class="card">
				
				<div class="card-body">
					<h6 class="card-title"><?php echo lang('edit_schedule');?></h6>
					<form method="POST" id="scheduleAddForm" action="<?php echo base_url().'shift_scheduling/edit_schedule'?>">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">

									<?php if(isset($employee_details) && !empty($employee_details)){?>
										<input class="form-control" type="hidden" value="<?=$employee_details['deptid']?>" name="department" />								
										<input class="form-control" type="hidden" value="<?=$employee_details['user_id']?>" name="employee[]" />								
										<input class="form-control" type="hidden" value="<?=$schedule_date?>" name="schedule_date" />								
										<input class="form-control" type="hidden" value="0" name="single_insert" />								
										<input class="form-control" type="hidden" value="<?=$employee_details['id']?>" name="id" />			
										<?php 
											if($employee_details['schedule_date']<= date('Y-m-d')){ ?>
											<input class="form-control" type="hidden" value="<?=$employee_details['shift_id']?>" name="shift_id" />		
										<?php }
										?>					

									<?php } ?>
									<label><?php echo lang('department');?> <span class="text-danger">*</span></label>
									<select class="select event-from-time form-control" name="department" id="department" <?php echo (isset($employee_details['deptname']))?"disabled":""?>>
										<option value="" selected disabled><?php echo lang('department');?></option>
										<?php
										if(!empty($departments))	{
										foreach ($departments as $department){ ?>
										<option value="<?=$department->deptid?>" <?php echo (isset($employee_details['deptname']) && ($employee_details['deptid'] == $department->deptid))?"selected":""?>><?=$department->deptname?></option>
										<?php } ?>
										<?php } ?>s
									</select>
								</div>
								<div class="form-group">
									<label><?php echo lang('employee_name');?> <span class="text-danger">*</span></label>
									<select class="select2-option event-from-time form-control" multiple="multiple"  style="width:100%;"  name="employee[]" id="employee"  <?php echo (isset($employee_details['fullname']))?"disabled":""?>> 
										<?php if(isset($employee_details['user_id']) && (!empty($employee_details['user_id'] ))){ ?>
											<option value="<?=$employee_details['user_id']?>" selected><?=ucfirst(User::displayName($employee_details['user_id']))?></option>
									<?php } ?>
										
										
									</select>											
								</div>
								<!--<div class="form-group">
									<label><?php echo lang('color');?> </label>
									<-- <input type="color" class="form-control" name="color" value="<?php echo (!empty($employee_details['color']))?$employee_details['color']:"#1eb53a"?>"> --
									<select name="color" class="form-control">
										<option value=""><?php echo lang('select_color')?></option>
										<option value="#f0092c" <?php if(isset($employee_details['color']) && $employee_details['color']== '#f0092c'){ echo "selected";}?>>Red</option>
										<option value="#083ef0" <?php if(isset($employee_details['color']) && $employee_details['color']== '#083ef0'){ echo "selected";}?>>Blue</option>
										<option value="#f0cd05" <?php if(isset($employee_details['color']) && $employee_details['color']== '#f0cd05'){ echo "selected";}?>>Yellow</option>
										<option value="#05f024" <?php if(isset($employee_details['color']) && $employee_details['color']== '#05f024'){ echo "selected";}?>>Green</option>
										<option value="#f0f5f1" <?php if(isset($employee_details['color']) && $employee_details['color']== '#f0f5f1'){ echo "selected";}?>>White</option>
									</select>
								</div>-->
								<div class="form-group">
									<label><?php echo lang('start_date');?> <span class="text-danger">*</span></label>
									<div class="cal-icon">
										<input class="datepicker-schedule form-control" name="schedule_date" id="schedule_date" data-date-format="dd-mm-yyyy" value="<?php echo (isset($schedule_date) && !empty($schedule_date))?$schedule_date:date('d-m-Y');?>" <?php echo (isset($schedule_date) && !empty($schedule_date))?"disabled":"";?>>
									</div>
								</div>
								<?php

										if($employee_details['schedule_date']<= date('Y-m-d')){
											$disabled='disabled';
										}else{
											$disabled='';
										}
										?>
								<div class="form-group">
                                    <label><?php echo lang('shifts');?> <span class="text-danger">*</span></label>
									<select class="select event-from-time form-control" name="shift_id" id="shift_id" <?php echo $disabled;?>>
										<option value="" selected disabled><?php echo lang('shifts');?></option>
										<?php
										if(!empty($shifts))	{
											$j =1;
										foreach ($shifts as $shift){ ?>
										<option value="<?=$shift->id?>" <?php echo(!empty($employee_details['shift_id'] && $employee_details['shift_id'] == $shift->id))?"selected":"";?>><?php echo $shift->shift_name;?></option>
										<?php $j++; } ?>
										<?php } ?>
									</select>
                                </div>
								<div class="row">
									<!-- <div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('min_start_time');?> <span class="text-danger">*</span></label>
											<div class='input-group'>
												<input type="text" class="form-control" name="min_start_time" id="min_start_time" value="<?php if(isset($employee_details) && ($employee_details['min_start_time'] == '00:00:00')){
													echo '';
												} else { echo (isset($employee_details) && !empty($employee_details['min_start_time']))?date('h:i a', strtotime($employee_details['min_start_time'])):"";}?>" readonly>
												
											</div>											
										</div>
									</div> -->
									<div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('start_time');?> <span class="text-danger">*</span></label>
											<div class='input-group'>
												<input type="text" class="form-control" name="start_time" id="start_time" value="<?php  if(isset($employee_details) && ($employee_details['start_time'] == '00:00:00')){
													echo '';
												} else {  echo (isset($employee_details) && !empty($employee_details['start_time']))?date('h:i a', strtotime($employee_details['start_time'])):"";}?>" readonly>
												
											</div>
										</div>
									</div>
									<!-- <div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('max_start_time');?> <span class="text-danger">*</span></label>
											<div class='input-group '>
												<input type="text" class="form-control" name="max_start_time" id="max_start_time" value="<?php  if(isset($employee_details) && ($employee_details['max_start_time'] == '00:00:00')){
													echo '';
												} else {  echo (isset($employee_details) && !empty($employee_details['max_start_time']))?date('h:i a', strtotime($employee_details['max_start_time'])):"";}?>" readonly>
												
											</div>
										</div>
									</div> -->
																		
								<!-- </div>
								<div class="row"> -->
								<!-- 	<div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('min_end_time');?> <span class="text-danger">*</span></label>
											<div class='input-group '>
												<input type="text" class="form-control" name="min_end_time" id="min_end_time" value="<?php if(isset($employee_details) && ($employee_details['min_end_time'] == '00:00:00')){
													echo '';
												} else { echo (isset($employee_details) && !empty($employee_details['min_end_time']))?date('h:i a', strtotime($employee_details['min_end_time'])):"";}?>" readonly>
												
											</div>											
										</div>
									</div> -->
									<div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('end_time');?> <span class="text-danger">*</span></label>
											<div class='input-group'>
												<input type="text" class="form-control" name="end_time" id="end_time" value="<?php if(isset($employee_details) && ($employee_details['end_time'] == '00:00:00')){
													echo '';
												} else {  echo (isset($employee_details) && !empty($employee_details['end_time']))?date('h:i a', strtotime($employee_details['end_time'])):"";}?>" readonly>
												
											</div>												
										</div>
									</div>
									<!-- <div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('max_end_time');?> <span class="text-danger">*</span></label>
											<div class='input-group'>
												<input type="text" class="form-control" name="max_end_time" id="max_end_time" value="<?php if(isset($employee_details) && ($employee_details['max_end_time'] == '00:00:00')){
													echo '';
												} else {  echo (isset($employee_details) && !empty($employee_details['max_end_time']))?date('h:i a', strtotime($employee_details['max_end_time'])):"";}?>" readonly>
											</div>											
										</div>
									</div>-->									
								</div> 
								<div class="form-group shift_details">
									<label><?php echo lang('break_time');?> (<?php echo lang('in_minutes');?>) </label>
									<div class='input-group'>
										<input type="text" class="form-control" name="break_time" id="break_time" value="<?php echo (isset($employee_details) && !empty($employee_details['break_time']))?$employee_details['break_time']:"";?>" readonly>
										
									</div>											
								</div>	
								<div class="form-group">
									<div class="checkbox  <?php echo (isset($employee_details['cyclic_shift']) && ($employee_details['cyclic_shift'] ==1))?"":"hide";?>">
									  <label><input type="checkbox"  name="cyclic_shift" id="" value="1" class="recurring mr-2" <?php echo (isset($employee_details['cyclic_shift']) && ($employee_details['cyclic_shift'] ==1))?"checked":"";?> onclick="return false;">Cyclic Shift</label>
									</div>
								</div>
								<div class="exist_data <?php echo ($employee_details['recurring_shift'] == 1)?"":"hide";?>">
									
								<div class="form-group">
									<div class="checkbox <?php echo (isset($employee_details['recurring_shift']) && ($employee_details['recurring_shift'] ==1))?"":"hide";?>">
									  <label><input type="checkbox"  name="recurring_shift" id="" value="1" class="recurring mr-2" <?php echo (isset($employee_details['recurring_shift']) && ($employee_details['recurring_shift'] ==1))?"checked":"";?> onclick="return false;">Recurring Shift</label>
									</div>
								</div>
								
								<!--  -->	
								
								<div class="form-group repeat_week <?php echo (isset($employee_details['cyclic_shift']))?"":"hide";?>">
									<label><?php echo lang('repeat_every');?>y</label>
									<select class="select form-control recurring" name="repeat_week" id="repeat_week" onchange="return false;">
										
										<option value="1" <?php echo ($employee_details['repeat_week'] ==1)?"selected":"";?>>1</option>
										<option value="2" <?php echo ($employee_details['repeat_week'] ==2)?"selected":"";?>>2</option>
										<option value="3" <?php echo ($employee_details['repeat_week'] ==3)?"selected":"";?>>3</option>
										<option value="4" <?php echo ($employee_details['repeat_week'] ==4)?"selected":"";?>>4</option>
									</select>
									<label><?php echo lang('week');?>(s)</label>
								</div>		
								<?php $weekdays = explode(',',$employee_details['week_days']);?>
								<div class="form-group wday-box">
									<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="monday" class="days recurring " <?php echo in_array('monday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">M</span></label>
    
   
							      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="tuesday" class="days recurring" <?php echo in_array('tuesday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">T</span></label>
								   
							      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="wednesday" class="days recurring" <?php echo in_array('wednesday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">W</span></label>
								   
							      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="thursday" class="days recurring" <?php echo in_array('thursday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">T</span></label>
								    
							      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="friday" class="days recurring" <?php echo in_array('friday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">F</span></label>
								   
							      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="saturday" class="days recurring" <?php echo in_array('saturday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">S</span></label>
								  
							      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="sunday" class="days recurring" <?php echo in_array('sunday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">S</span></label>
								</div>	
								<div class="form-group end_date1">
									<label><?php echo lang('end_on');?> <span class="text-danger">*</span></label>
									<div class="cal-icon">
									<input class="datepicker-schedule form-control end_date recurring " class="form-control" data-date-format="dd-mm-yyyy"  name="end_date"   value="<?php echo (isset($employee_details['end_date']) && ($employee_details['end_date'] =='0000-00-00'))?date('d-m-Y',strtotime($employee_details['schedule_date'])):date('d-m-Y',strtotime($employee_details['end_date']));?>"  <?php echo (isset($employee_details['indefinite']) && ($employee_details['indefinite'] ==1))?"disabled":"";?>>	
									</div>								
								</div>	
								<div class="form-group shift_details hide">
									<div class="checkbox indefinite_checkbox_edit ">
								 	 	<label><input type="checkbox"  name="indefinite" id="indefinite_edit" value="1" class="recurring mr-2" <?php echo (isset($employee_details['indefinite']) && ($employee_details['indefinite'] ==1))?"checked":"";?> onclick="return false;">Idefinite</label>
									</div>
								</div>
								</div>

								<!-- <div class="form-group shift_details ">
										<div class="checkbox">
										  
										</div>
								</div> -->
								<div class="form-group shift_details repeat_week hide">
									<label><?php echo lang('repeat_every');?></label>
									<div class='input-group'>
										<input type="text" class="form-control" name="repeat_week" id="repeat_week" value="" readonly>
									</div>	
									
								</div>	
								<div class="form-group wday-box">
									
								</div>	
								<div class="form-group shift_details edit_end_date <?php echo ($employee_details['recurring_shift'] == 1 || $employee_details['cyclic_shift'] ==1)?"hide":"";?>">
									<label>End on <span class="text-danger">*</span></label>
									<input class="datepicker-schedule form-control end_date" class="form-control" data-date-format="dd-mm-yyyy"  name="end_date"   value="<?php echo (isset($employee_details['end_date']) && ($employee_details['end_date'] =='0000-00-00'))?date('d-m-Y',strtotime($employee_details['schedule_date'])):date('d-m-Y',strtotime($employee_details['end_date']));?>"  >								
								</div>	
								<div class="form-group shift_details hide">
									<div class="checkbox indefinite_checkbox">
									 
									</div>
								</div>
								<!-- <div class="form-group">
									<label>Repeat</label>
									<select class="select event-from-time form-control" name="repeat_time" id="repeat_time" <?php echo (isset($employee_details))?"disabled":"";?>>
										<option value="0">Never</option>
										<option value="1">This week</option>
										<option value="2">Every 2 week</option>
										<option value="3">Every 3 week</option>
										<option value="4">Every 4 week</option>
										<option value="5">Every 5 week</option>
										<option value="6">Every 6 week</option>
										<option value="7">Every 7 week</option>
										<option value="8">Every 8 week</option>
									</select>
								</div>								
								<div class="form-group">
									<label>Add a tag </label>
									<input class="form-control" type="text" data-role="tagsinput" name="tag" id="tag" value="<?php echo (isset($employee_details) && !empty($employee_details['tag']))?$employee_details['tag']:"";?>" />
								</div>
								<div class="form-group">
									<label>Add a note</label>
									<textarea class="form-control" rows="4" name="note" id="note"><?php echo (isset($employee_details) && !empty($employee_details['note']))?$employee_details['note']:"";?></textarea>
								</div> -->
								<!-- <div class="form-group">
									<label>Publish</label>
									<div class="material-switch">
										<input id="someSwitch" class="form-control" name="publish" type="checkbox"/ checked value="1">
										<label for="someSwitch" class="label-warning"></label>
									</div>
								</div> -->
								<div class="form-group total_cyclic_days <?php echo ($employee_details['cyclic_shift'] ==1)?"":"hide"?>">
									<label><?php echo lang('no_of_days_in_cycle');?> </label>
									<input class="form-control" type="text" name="no_of_days_in_cycle" id="total_cyclic_days" value="<?php echo $employee_details['no_of_days_in_cycle']?>" readonly / keyup="return false;">
								</div>
								<div class="form-group wday-box cyclic_days">
									<?php
									if($employee_details['cyclic_shift'] ==1){
									
										for ($i=1; $i < $employee_details['no_of_days_in_cycle']+1; $i++) { ?>
										
											<label class="checkbox-inline "><input type="checkbox" name="workdays[]" value="<?php echo $i;?>" class="days recurring" <?php echo ($employee_details['workday'] >= $i)?"checked":""?> onclick="return false;"><span class="checkmark"><?php echo $i;?></span></label>	
										
										
									<?php }
									} ?>	
								</div>
								<!-- <div class="form-group">
                                    <label class="d-block"><?php echo lang('accept_extra_hours');?></label>
                                    <div class="status-toggle">
                                        <input type="checkbox" id="accept_extras" name="accept_extras" class="check" value="1" <?php echo ($employee_details['accept_extras'] == 1)?"checked":"";?>>
                                        <label for="accept_extras" class="checktoggle">checkbox</label>
                                    </div>
                                </div>
								<div class="form-group">
                                        <label class="d-block"><?php echo lang('publish');?></label>
                                        <div class="status-toggle">
                                            <input type="checkbox" id="contact_status" name="publish" class="check" <?php echo ($employee_details['published'] == 1)?"checked":"";?> value="1">
                                            <label for="contact_status" class="checktoggle">checkbox</label>
                                        </div>
                                    </div> -->
								<div class="submit-section">
									<a href="<?php echo base_url(); ?>shift_scheduling" class="btn btn-danger bg-orange text-white submit-btn m-b-5" type="submit"><?php echo lang('cancel');?></a>
									<button class="btn btn-primary submit-btn m-b-5" id="submit_shift_scheduling_add" type="submit"><?php echo lang('save');?></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>