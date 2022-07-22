<?php
	$jtype=array(0=>'unassigned');
	    foreach ($offer_jobtype as $jkey => $jvalue) {
	            $jtype[$jvalue->id]=$jvalue->job_type;                        
	     }
	
	?>
<div class="content p-t-10">
	<div class="page-header">
	<div class="row">
		<div class="col-12">
			<h4 class="page-title"><?php echo lang('offered_jobs');?></h4>
			 <ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/dashboard">Recruiting Process</a></li>
				 <li class="breadcrumb-item"><?php echo lang('offered_jobs');?></li>
				
				</ul> 
		</div>
	</div>
</div>
	<!--<div class="card-box">
	<div class="row filter-row">
		<div class="col-sm-6 col-12 col-md-2">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label">Experience Level</label>
				<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 
					<option value="" selected="selected">Early Professional (<3 years)</option>
					<option value="1">Executive</option>
					<option value="2">Intern</option>
					<option value="3">Professional (>3 years)</option>
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-12 col-md-2">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label">Job Category</label>
				<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 
					<option value="" selected="selected">Software Development & Support</option>
					<option value="1">Communications</option>
					<option value="2">Design & Offering Management</option>
					<option value="3">Marketing & Communications</option>
					<option value="4">Product Services</option>
					<option value="5">Project Management</option>
					<option value="6">Technical Specialist</option>
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-12 col-md-2">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label">Job Country</label>
				<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 
					<option value="" selected="selected">Albania</option>
					<option value="1">Canada</option>
					<option value="2">Colombia</option>
					<option value="3">Germany</option>
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-12 col-md-3"> 
			<div class="form-group form-focus m-b-5">
				<label class="control-label">Enter Keywords</label>
				<input type="text" class="form-control floating">
			</div>
		</div>

		<div class="col-sm-6 col-6 col-md-3">  
			<a href="javascript:void(0)" id="employee_search_btn" onclick="filter_next_page(1)" class="btn btn-success btn-block btn-searchEmployee btn-circle"> Search </a>  
		</div>  
	</div>
</div>-->
	
	
	<div class="card-box">
		<div class="table-responsive">
			<table class="table table-striped custom-table mb-0 datatable">
				<thead>
					<tr>
						<th>#</th>
						<th class="text-capitalize"><?php echo lang('job_title');?></th>
						<th><?php echo lang('department')?></th>
						<th class="text-center"><?php echo lang('job_types');?></th>
						<th class="text-center"><?php echo lang('status');?></th> 
						<!-- <th class="text-center"><?php echo lang('action');?></th> -->
					</tr>
				</thead>
				<tbody>

					<?php 
					$i=1;
					foreach($offered_jobs as $key => $jobs){?>
					<tr>
						<td><?php echo $i++;?></td>
						<td class="text-capitalize"><a href="<?php echo base_url(); ?>candidates/job_view/<?php echo $jobs->id;?>"><?php echo $jobs->job_title;?></a></td>
						<td><?php echo $jobs->deptname;?></td>
						<td class="text-center">
							<div class="action-label">
								<a class="btn btn-white btn-sm btn-rounded" href="#">
								<i class="fa fa-dot-circle-o text-danger"></i> <?php echo $jobs->job_type;?>
								</a>
							</div>
						</td>
						 <td class="text-center">
							<div class="dropdown action-label">
								<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
								<i class="fa fa-dot-circle-o text-danger"></i> <?php 
								if($jobs->user_job_status==7){echo lang('offered');}
								if($jobs->user_job_status==8){echo lang('offer_accepted');}
								if($jobs->user_job_status==9){echo lang('offer_rejected');}
								if($jobs->user_job_status==10){echo lang('offer_declined');}
								?>
								</a>
								<?php if($jobs->user_job_status==7){?>	
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="<?php echo site_url('candidates/user_offer_status_change/8/'.$jobs->candidate_id.'/'.$jobs->id);?>"><i class="fa fa-dot-circle-o text-info"></i> <?php echo lang("accept_offer"); ?></a>
									<a class="dropdown-item" href="<?php echo site_url('candidates/user_offer_status_change/9/'.$jobs->candidate_id.'/'.$jobs->job_id.'/');?>"><i class="fa fa-dot-circle-o text-danger"></i>  <?php echo lang("reject_offer"); ?></a>
									
								</div> 
							<?php } ?>
							</div>
							</td> 
						<!-- <td class="text-center">
							<a href="<?php echo site_url('candidates/download_offer/'.$jobs->id);?>" class="btn btn-sm btn-info download-offer"><span style="font-size: 14px;"><i class="fa fa-download m-r-5"></i><?php echo lang('download_offer'); ?></span></a>
						</td> -->
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
