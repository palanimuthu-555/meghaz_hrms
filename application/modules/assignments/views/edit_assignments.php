<div class="content">
	 <div class="page-header">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Edit Assignment</h4>
			<ul class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
				<li class="breadcrumb-item"><a href="<?=base_url()?>assignments"><?=lang('assignments')?></a></li>
				<li class="breadcrumb-item active">Edit Assignments</li>
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
			echo form_open(base_url().'assignments/edit_assignment_det',$attributes); ?>
			<?=$this->session->flashdata('form_error') ?>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Billed Account <span class="text-danger">*</span> </label>
							<div class="row">
								<div class="col-md-12">
								<input type="hidden" name="assignment_id" value="<?=$projects['id']?>" >
									<select class="select2-option form-control" name="billed_ac" > 
										<?php foreach (Client::get_all_clients() as $key => $c) { ?>
										<option 
										
										<?php 
										if ($c->co_id == $projects['billed_ac']) { ?> selected = "selected" <?php }   ?>
										value="<?=$c->co_id?>"><?=ucfirst($c->company_name)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Bill Rate <span class="text-danger">*</span></label>
							<input type="text" class="form-control" value="<?=$projects['bill_rate']?>" placeholder="Bill Rate" name="bill_rate" id="bill_rate">
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
										<option <?php 
										if ($c->co_id == $projects['payee_ac']) { ?> selected = "selected" <?php }   ?>
										value="<?=$c->co_id?>"><?=ucfirst($c->company_name)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Pay Rate <span class="text-danger">*</span></label>
							<input type="text" class="form-control" value="<?=$projects['pay_rate']?>" placeholder="Pay Rate" name="pay_rate" id="pay_rate">
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
										<option <?php 
										if ($ce->id == $projects['pr_resource']) { ?> selected = "selected" <?php }   ?>
										value="<?=$ce->id?>"><?=ucfirst($ce->fullname)?></option>
										<?php } ?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Position Resource Reference Rate</label>
							<input type="text" value="<?=$projects['prr_rate']?>" class="form-control" placeholder="PRR Rate" name="prr_rate" id="prr_rate">
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
										<option 
										<?php if ($ce->id == $projects['pa_resource']) { ?> selected = "selected" <?php }   ?>
										value="<?=$c->co_id?>"><?=ucfirst($c->company_name)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Position Account Reference Rate</label>
							<input type="text" value="<?=$projects['par_rate']?>" class="form-control" placeholder="PAR Rate" name="par_rate" id="par_rate">
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
										<option <?php if ($c->id == $projects['cr_resource']) { ?> selected = "selected" <?php }   ?>
										value="<?=$c->id?>"><?=ucfirst($c->fullname)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Candidate Resource Reference Rate </label>
							<input type="text" value="<?=$projects['crr_rate']?>" class="form-control" placeholder="CRR Rate" name="crr_rate" id="crr_rate">
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
										<option <?php if ($c->co_id == $projects['ca_reference']) { ?> selected = "selected" <?php }   ?>
										value="<?=$c->co_id?>"><?=ucfirst($c->company_name)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Candidate Account Reference Rate</label>
							<input type="text" value="<?=$projects['car_rate']?>" class="form-control" placeholder="CAR Rate" name="car_rate" id="car_rate">
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
										<option <?php if ($c->id == $projects['recruiter_ref']) { ?> selected = "selected" <?php }   ?> value="<?=$c->id?>"><?=ucfirst($c->fullname)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Recruiter Reference Rate</label>
							<input type="text" value="<?=$projects['rr_rate']?>" class="form-control" placeholder="RR Rate" name="rr_rate" id="rr_rate">
						</div>	
					</div>	
				</div>	
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Bill Terms <span class="text-danger">*</span></label>
							<input type="text" value="<?=$projects['bill_terms']?>" class="form-control" placeholder="Bill Terms" name="bill_terms" id="bill_terms">
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Pay Terms <span class="text-danger">*</span></label>
							<input type="text" value="<?=$projects['pay_terms']?>" class="form-control" placeholder="Pay Terms" name="pay_terms" id="pay_terms">
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
										<option value="10" <?php if($projects['bill_cycle'] == '10'){echo 'selected';}?>>Weekly</option>
										<option value="11" <?php if($projects['bill_cycle'] == '11'){echo 'selected';}?>>BiWeekly</option>
										<option value="12" <?php if($projects['bill_cycle'] == '12'){echo 'selected';}?>>SemiMonthly</option>
										<option value="13" <?php if($projects['bill_cycle'] == '13'){echo 'selected';}?>>Monthly</option>
										<option value="14" <?php if($projects['bill_cycle'] == '14'){echo 'selected';}?>>ByDate</option>
										<option value="15" <?php if($projects['bill_cycle'] == '15'){echo 'selected';}?>>4Weeks</option>
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
										<option value="10" <?=($projects['pay_cycle'] == '10' ? ' selected="selected"':'')?>>Weekly</option>
										<option value="11" <?=($projects['pay_cycle'] == '11' ? ' selected="selected"':'')?>>BiWeekly</option>
										<option value="12" <?=($projects['pay_cycle'] == '12' ? ' selected="selected"':'')?>>SemiMonthly</option>
										<option value="13" <?=($projects['pay_cycle'] == '13' ? ' selected="selected"':'')?>>Monthly</option>
										<option value="14" <?=($projects['pay_cycle'] == '14' ? ' selected="selected"':'')?>>ByDate</option>
										<option value="15" <?=($projects['pay_cycle'] == '15' ? ' selected="selected"':'')?>>4Weeks</option>
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
										<option <?php if ($c->id == $projects['resources']) { ?> selected = "selected" <?php }   ?> value="<?=$c->id?>"><?=ucfirst($c->fullname)?></option>
										<?php }?>
									</select> 
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Assignment Name <span class="text-danger">*</span></label>
							<input type="text" value="<?=$projects['assignment_name']?>" class="form-control" placeholder="Assignment Name" name="assignment_name" id="assignment_name">
						</div>	
					</div>	
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('start_date')?> <span class="text-danger">*</span></label> 
							<input class="datepicker-input form-control" type="text" value="<?=$projects['start_date']?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
						</div> 
					</div> 
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('due_date')?> <span class="text-danger">*</span></label>
							<input class="datepicker-input form-control" type="text" value="<?=$projects['due_date']?>"  name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
						</div> 
					</div> 
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Margin Rate <span class="text-danger">*</span></label>
							<input type="text" class="form-control" value="<?=$projects['margin_rate']?>" placeholder="Margin Rate" name="margin_rate" id="margin_rate">
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Default Hours <span class="text-danger">*</span></label>
							<input type="text" class="form-control" value="<?=$projects['default_hours']?>" placeholder="Default Hours" name="default_hours" id="default_hours">
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
										<option value="1" <?=($projects['emp_type'] == '1' ? ' selected="selected"':'')?>>W2</option>
										<option value="2" <?=($projects['emp_type'] == '2' ? ' selected="selected"':'')?>>C2C</option>
										<option value="3" <?=($projects['emp_type'] == '3' ? ' selected="selected"':'')?>>1099</option>
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
										<option value="24" <?=($projects['week_start_day'] == '24' ? ' selected="selected"':'')?>>Monday</option>
										<option value="25" <?=($projects['week_start_day'] == '25' ? ' selected="selected"':'')?>>Tuesday</option>
										<option value="26" <?=($projects['week_start_day'] == '26' ? ' selected="selected"':'')?>>Wednesday</option>
										<option value="27" <?=($projects['week_start_day'] == '27' ? ' selected="selected"':'')?>>Thursday</option>
										<option value="28" <?=($projects['week_start_day'] == '28' ? ' selected="selected"':'')?>>Friday</option>
										<option value="29" <?=($projects['week_start_day'] == '29' ? ' selected="selected"':'')?>>Saturday</option>
										<option value="30" <?=($projects['week_start_day'] == '30' ? ' selected="selected"':'')?>>Sunday</option>
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
										<option value="true"  <?=($projects['individual_invoice'] == 'true' ? ' selected="selected"':'')?>>True</option>
										<option value="false" <?=($projects['individual_invoice'] == 'false' ? ' selected="selected"':'')?>>False</option>
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
										<option value="true" <?=($projects['is_active']== 'true' ? ' selected="selected"':'')?>>True</option>
										<option value="false" <?=($projects['is_active'] == 'false' ? ' selected="selected"':'')?>>False</option>
									</select> 
								</div>
							</div>	
						</div>	
					</div>	
				</div>
				<div class="m-t-20 text-center submit-section">
					<button id="project_add_submit" class="btn btn-primary submit-btn"><i class="fa fa-plus"></i> Update</button>
				</div>
			</form>
		</div>
	</div>
	<!-- End Project Form -->
</div>
</div>
</div>