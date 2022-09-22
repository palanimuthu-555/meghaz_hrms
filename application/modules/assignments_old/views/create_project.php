<div class="content">
	 <div class="page-header">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Create Assignment</h4>
			<ul class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
				<li class="breadcrumb-item"><a href="<?=base_url()?>assignments"><?=lang('assignments')?></a></li>
				<li class="breadcrumb-item active">Create Assignments</li>
			</ul>
		</div>
	</div>
</div>
		
	<!-- Start Project Form -->
	<div class="row">
		<div class="col-md-12">
				<div class="card">
	        	<div class="card-body">
			<?php
			$attributes = array('class' => 'bs-example','autocomplete'=>'off','id'=>'projectAddForm');
			echo form_open(base_url().'assignments/add',$attributes); ?>
			<?=$this->session->flashdata('form_error') ?>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Billed Account <span class="text-danger">*</span> </label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="billed_ac" > 
										<?php foreach (Client::get_all_clients() as $key => $c) { ?>
										<option value="<?=$c->co_id?>"><?=ucfirst($c->company_name)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Bill Rate <span class="text-danger">*</span></label>
							<input type="text" class="form-control" placeholder="Bill Rate" name="bill_rate" id="bill_rate">
						</div>	
					</div>	
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Payee Account <span class="text-danger">*</span> </label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="payee_ac" > 
										<?php foreach (Client::get_all_clients() as $key => $c) { ?>
										<option value="<?=$c->co_id?>"><?=ucfirst($c->company_name)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Pay Rate <span class="text-danger">*</span></label>
							<input type="text" class="form-control" placeholder="Pay Rate" name="pay_rate" id="pay_rate">
						</div>	
					</div>	
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Position Resource Reference </label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="pr_resource" > 
										<?php foreach (User::get_all_users_detail() as $key => $ce) { ?>
										<option value="<?=$ce->id?>"><?=ucfirst($ce->fullname)?></option>
										<?php } ?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Position Resource Reference Rate</label>
							<input type="text" class="form-control" placeholder="PRR Rate" name="prr_rate" id="prr_rate">
						</div>	
					</div>	
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Position Account Reference</label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="pa_resource" > 
										<?php foreach (Client::get_all_clients() as $key => $c) { ?>
										<option value="<?=$c->co_id?>"><?=ucfirst($c->company_name)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Position Account Reference Rate</label>
							<input type="text" class="form-control" placeholder="PAR Rate" name="par_rate" id="par_rate">
						</div>	
					</div>	
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Candidate Resource Reference</label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="cr_resource" > 
										<?php foreach (User::get_all_users_detail() as $key => $c) { ?>
										<option value="<?=$c->id?>"><?=ucfirst($c->fullname)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Candidate Resource Reference Rate </label>
							<input type="text" class="form-control" placeholder="CRR Rate" name="crr_rate" id="crr_rate">
						</div>	
					</div>	
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Candidate Account Reference</label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="ca_reference" > 
										<?php foreach (Client::get_all_clients() as $key => $c) { ?>
										<option value="<?=$c->co_id?>"><?=ucfirst($c->company_name)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Candidate Account Reference Rate</label>
							<input type="text" class="form-control" placeholder="CAR Rate" name="car_rate" id="car_rate">
						</div>	
					</div>	
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Recruiter Reference</label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="recruiter_ref" > 
										<?php foreach (User::get_all_users_detail() as $key => $c) { ?>
										<option value="<?=$c->id?>"><?=ucfirst($c->fullname)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Recruiter Reference Rate</label>
							<input type="text" class="form-control" placeholder="RR Rate" name="rr_rate" id="rr_rate">
						</div>	
					</div>	
				</div>	
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Bill Terms <span class="text-danger">*</span></label>
							<input type="text" class="form-control" placeholder="Bill Terms" name="bill_terms" id="bill_terms">
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Pay Terms <span class="text-danger">*</span></label>
							<input type="text" class="form-control" placeholder="Pay Terms" name="pay_terms" id="pay_terms">
						</div>	
					</div>	
				</div>	

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Bill Cycle <span class="text-danger">*</span> </label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="bill_cycle" > 
										<option value="">Select Bill Cycle</option>
										<option value="10">Weekly</option>
										<option value="11">BiWeekly</option>
										<option value="12">SemiMonthly</option>
										<option value="13">Monthly</option>
										<option value="14">ByDate</option>
										<option value="15">4Weeks</option>
									</select> 
								</div>
							</div>	
						</div>	
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label>Pay Cycle <span class="text-danger">*</span> </label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="pay_cycle" > 
										<option value="">Select Pay Cycle</option>
										<option value="10">Weekly</option>
										<option value="11">BiWeekly</option>
										<option value="12">SemiMonthly</option>
										<option value="13">Monthly</option>
										<option value="14">ByDate</option>
										<option value="15">4Weeks</option>
									</select> 
								</div>
							</div>	
						</div>	
					</div>	
				</div>	

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Resource <span class="text-danger">*</span> </label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="resources" > 
										<?php foreach (User::get_all_users_detail() as $key => $c) { ?>
										<option value="<?=$c->id?>"><?=ucfirst($c->fullname)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Assignment Name <span class="text-danger">*</span></label>
							<input type="text" class="form-control" placeholder="Assignment Name" name="assignment_name" id="assignment_name">
						</div>	
					</div>	
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('start_date')?> <span class="text-danger">*</span></label> 
							<input class="datepicker-input form-control" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
						</div> 
					</div> 
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('due_date')?> <span class="text-danger">*</span></label>
							<input class="datepicker-input form-control" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
						</div> 
					</div> 
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Margin Rate <span class="text-danger">*</span></label>
							<input type="text" class="form-control" placeholder="Margin Rate" name="margin_rate" id="margin_rate">
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Default Hours <span class="text-danger">*</span></label>
							<input type="text" class="form-control" placeholder="Default Hours" name="default_hours" id="default_hours">
						</div>	
					</div>	
				</div>	
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Employment Type <span class="text-danger">*</span> </label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="emp_type" > 
										<option value="">Select Employment Type</option>
										<option value="1">W2</option>
										<option value="2">C2C</option>
										<option value="3">1099</option>
									</select> 
								</div>
							</div>	
						</div>	
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label>Week Start Day <span class="text-danger">*</span> </label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="week_start_day" > 
										<option value="">Select Week Start Day</option>
										<option value="24">Monday</option>
										<option value="25">Tuesday</option>
										<option value="26">Wednesday</option>
										<option value="27">Thursday</option>
										<option value="28">Friday</option>
										<option value="29">Saturday</option>
										<option value="30">Sunday</option>
									</select> 
								</div>
							</div>	
						</div>	
					</div>	
				</div>	
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Individual Invoice <span class="text-danger">*</span> </label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="individual_invoice" > 
										<option value="">Select Status</option>
										<option value="true">True</option>
										<option value="false">False</option>
									</select> 
								</div>
							</div>	
						</div>	
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label>Is Active <span class="text-danger">*</span> </label>
							<div class="row">
								<div class="col-md-12">
									<select class="select2-option form-control" name="is_active" > 
										<option value="">Select Status</option>
										<option value="true">True</option>
										<option value="false">False</option>
									</select> 
								</div>
							</div>	
						</div>	
					</div>	
				</div>
				<div class="m-t-20 text-center submit-section">
					<button id="project_add_submit" class="btn btn-primary submit-btn"> Add Assignment</button>
				</div>
			</form>
		</div>
	</div>
	<!-- End Project Form -->
</div>
</div>
</div>