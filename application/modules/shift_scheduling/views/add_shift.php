	
<?php $departments = $this->db->order_by("deptname", "asc")->get('departments')->result(); ?>
<?php
	 // if(isset($employee_details) && !empty($employee_details)){
 	// 	$shifts = $this->db->where('published',1)->where('recurring_shift',0)->order_by("id", "asc")->get('shifts')->result();
 	// }else{
 	// 	$shifts = $this->db->where('published',1)->order_by("id", "asc")->get('shifts')->result();
 	// }
 	$shifts = $this->db->where('published',1)->order_by("id", "asc")->get('shifts')->result();
 ?>
<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-md-8">
			<h4 class="page-title m-b-0"><?php echo lang('add_shift');?></h4>
			<ul class="breadcrumb m-b-20 p-l-0" style="background:none; border:none;">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('employees');?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>shift_scheduling"><?php echo lang('shift_scheduling');?></a></li>
				<li class="breadcrumb-item active"><?php echo lang('add_shift');?></li>
			</ul>
		</div>
		<div class="col-sm-4  text-right m-b-20">     
	          <a class="btn add-btn" href="<?=base_url()?>shift_scheduling/shift_list"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>
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
					<!-- <h6 class="card-title"><?php echo lang('add_shift');?></h6> -->
					<form method="POST" id="scheduleAddForm" action="<?php echo base_url().'shift_scheduling/add_shift'?>" autocomplete="off">
						<div class="row">
							<div class="col-md-12">	
								<div class="form-group">
									<label><?php echo lang('shift_name');?> <span class="text-danger">*</span><span id="check_shiftname" style="display: none;color:red;">Shift Already Exist!</span></label>
									<input type="text" class="form-control shift_name" name="shift_name" id="shift_name">
								</div>
								<?php 
								// $schedule_group = $this->db->order_by("group_name", "asc")->get('rotary_schedule_group')->result(); ?>
								<!-- <div class="form-group">
									<label><?=lang('rotary_schedule_groups')?> </label>
									
									<select class="select2-option form-control"  name="group_id" id="">
										<option value="" selected disabled><?=lang('select_group')?></option>
										<?php
										if(!empty($schedule_group))	{
										foreach ($schedule_group as $rotary_group){ ?>
										<option value="<?=$rotary_group->id?>"><?php echo Ucfirst($rotary_group->group_name)?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div> -->
								<div class="form-group">
									<label><?php echo lang('start_date');?><span class="text-danger">*</span></label>
									<div class="cal-icon">
										<input class="start_date-schedule form-control" name="start_date" id="start_date" data-date-format="dd-mm-yyyy" value="" placeholder="DD/MM/YYYY"  data-date-start-date="0d">
									</div>
								</div>	
								<div class="row">
									<!--<div class="col-md-4" >
										<div class="form-group">
											<label><?php echo lang('min_start_time');?> <span class="text-danger">*</span></label>
										 	<div class='time-icon date time_picker'>
												<input type="text" class="form-control" name="min_start_time" id="min_start_time">
												<-- <span class="input-group-addon" ><i class="fa fa-clock-o"></i></span> --
												 
											</div>
										</div>
									</div>-->
									<!-- <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">@example.com</span>
  </div>
</div> -->
									<div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('start_time');?> <span class="text-danger">*</span></label>
											 <div class='time-icon date time_picker'> 
											
												<!-- <input type="text" class="form-control" name="start_time" id="start_time"> -->
												 <input type="text" class="form-control datetimepicker-input" id="start_time" data-toggle="datetimepicker" name="start_time" data-target="#start_time" />
											</div>											
										</div>
									</div>
									<!-- <div class="col-md-4" >
										<div class="form-group">
											<label><?php echo lang('max_start_time');?><span class="text-danger">*</span></label>
											<div class='time-icon date time_picker'>
												<input type="text" class="form-control" name="max_start_time" id="max_start_time">
												 
											</div>												
										</div>
									</div> -->									
								<!-- </div>
								<div class="row"> -->
									<!-- <div class="col-md-4" style="display: none">
										<div class="form-group">
											<label><?php echo lang('min_end_time');?><span class="text-danger">*</span></label>
											<div class='time-icon date time_picker'>
												<input type="text" class="form-control" name="min_end_time" id="min_end_time">
												
											</div>	
										</div>
									</div> -->
									<div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('end_time');?><span class="text-danger">*</span></label>
											<div class='time-icon date time_picker'>
												<input type="text" class="form-control" name="end_time" id="end_time" data-toggle="datetimepicker" data-target="#end_time">
												
											</div>
										</div>
									</div>
									<!-- <div class="col-md-4" style="display: none">
										<div class="form-group">
											<label><?php echo lang('max_end_time');?> <span class="text-danger">*</span></label>
											<div class='time-icon date time_picker'>
												<input type="text" class="form-control" name="max_end_time" id="max_end_time">
												
											</div>											
										</div>
									</div> -->
									
								</div>		
								<div class="form-group">
									<label><?php echo lang('break_time');?> (<?php echo lang('in_minutes');?>) </label>
									<div class='input-group'>
										<input type="text" class="form-control only-numeric" name="break_time" id="break_time">
										<span class="error" style="color: red; display: none"><?php echo lang('numbers_only_allowed');?></span>
									</div>
								</div>
								<div class="form-group">
									<label><?php echo lang('color');?> </label>

									 <input type="color" class="form-control" name="color" value="#235ca5">

									<!-- <select name="color" class="form-control">
										<option value=""><?php echo lang('select_color')?></option>
										<option value="#f0092c">Red</option>
										<option value="#083ef0" selected>Blue</option>
										<option value="#f0cd05">Yellow</option>
										<option value="#05f024">Green</option>
										<option value="#f0f5f1">White</option>
									</select> -->
								</div>
								<div class="form-group">
									<div class="checkbox">
									  <label><input type="checkbox"  name="recurring_shift" id="recurring_shift" value="1" class="mr-2"><?php echo lang('recurring_shift');?></label>
									</div>
									<div class="checkbox">
									  <label><input type="checkbox"  name="cyclic_shift" id="cyclic_shift" value="1" class="mr-2"><?php echo lang('cyclic_shift');?></label>
									</div>
								</div>
								<div class="form-group hide total_cyclic_days">
									<label><?php echo lang('no_of_days_in_cycle');?> </label>
									<input class="form-control" type="text" name="no_of_days_in_cycle"  id="total_cyclic_days" />
								</div>
								<div id="recurring" class="hide">
									<!-- <div class="form-group">
										<label><?php echo lang('repeat_every');?></label>
										<select class="select form-control recurring" name="repeat_week" id="repeat_week">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
										</select>
										<label><?php echo lang('week');?>(s)</label>
									</div>	 -->	

									<div class="form-group wday-box">
										<label class="checkbox-inline "><input type="checkbox" name="week_days[]" value="monday" class="days recurring"><span class="checkmark">M</span></label>
	    
	   
								      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="tuesday" class="days recurring"><span class="checkmark">T</span></label>
									   
								      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="wednesday" class="days recurring"><span class="checkmark">W</span></label>
									   
								      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="thursday" class="days recurring"><span class="checkmark">T</span></label>
									    
								      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="friday" class="days recurring"><span class="checkmark">F</span></label>
									   
								      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="saturday" class="days recurring"><span class="checkmark">S</span></label>
									  
								      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="sunday" class="days recurring"><span class="checkmark">S</span></label>
									</div>


									
									
									<div class="form-group week_days">
										
										
									</div>
								
								</div>
								<div class="form-group indefinite">
									<div class="checkbox">
									  <label><input type="checkbox"  name="indefinite" id="indefinite" value="1" class="mr-2"><?php echo lang('indefinite');?></label>
									</div>
								</div>
								<div class="form-group wday-box cyclic_days">
										
								</div>
								<div class="form-group recurring_end_date">
									<label><?php echo lang('end_on');?> <span class="text-danger">*</span></label>
									<div class="cal-icon">
										<input class="datepicker-schedule form-control" name="end_date" id="end_date" data-date-format="dd-mm-yyyy" value="" placeholder="DD/MM/YYYY" data-date-start-date="0d">
									</div>
								</div>	
								
								<div class="form-group">
									<label><?php echo lang('add_a_tag');?> </label>
									<input class="form-control" type="text" data-role="tagsinput" name="tag" id="tag" />
								</div>
								
								<div class="form-group">
									<label><?php echo lang('add_a_note');?></label>
									<textarea class="form-control" rows="4" name="note" id="note"></textarea>
								</div>
								<!-- <div class="form-group">
									<label>Publish</label>
									<div class="material-switch">
										<input id="someSwitch" class="form-control" name="publish" type="checkbox"/ checked value="1">
										<label for="someSwitch" class="label-warning"></label>
									</div>
								</div> -->
								<!-- <div class="form-group">
                                        <label class="d-block"><?php echo lang('publish');?></label>
                                        <div class="status-toggle">
                                            <input type="checkbox" id="contact_status" name="publish" class="check" value="1" checked>
                                            <label for="contact_status" class="checktoggle"><?php echo lang('checkbox');?></label>
                                        </div>
                                    </div> -->
								<div class="submit-section">
									<a href="<?php echo base_url(); ?>shift_scheduling/shift_list" class="btn bg-danger text-white submit-btn m-b-5" type="submit">Cancel</a>
									<button class="btn btn-primary submit-btn m-b-5" id="submit_scheduling_add" type="submit"><?php echo lang('save');?></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
