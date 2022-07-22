<div class="content"> 
 <div class="page-header"> 
	<div class="row">
		<div class="col-4">
			<h4 class="page-title"><?='Pay Slip';?></h4>
			<ul class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active"><?='Pay Slip';?></li>
            </ul>
		</div>
		<div class="col-8 text-right m-b-30">
		<?php
		if(App::is_permit('menu_payroll','create'))
		{
		?>
			<a class="btn add-btn float-right" href="#" data-toggle="modal" data-target="#run_payroll">Run Payroll</a>
		<?php
		}
		?>

		</div>
	</div>
	<?php  
//	if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'admin') || ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'superadmin')) { ?>
	
	<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
	   <?php 
		$users_list = $this->db->query("SELECT u.*,ad.*,d.designation,(select concat(amount,'[^]',date_created) from dgt_salary where user_id = u.id order by id desc limit 1) as salary_det
									FROM `dgt_users` u  
									left join dgt_account_details ad on ad.user_id = u.id 
									left join dgt_designation d on d.id=u.designation_id
									where u.activated = 1 and u.role_id = 3 order by u.created desc")->result_array();
		
	   ?>
	   <table id="table-users" class="table table-striped custom-table m-b-0 AppendDataTables ">
			<thead>
				<tr>
					<th> # </th> 
					<th> Fullname </th> 
					<th class="text-center"> Current Salary </th>
					<th> Designation </th>
					<th class="hidden-sm">Joining Date</th>
					<th class="text-right">Payroll Status</th>
					<?php
					if(App::is_permit('menu_payroll','read'))
					{
					?>
					<th class="col-options no-sort text-right">Options</th>
					<?php
					}
					?>
				</tr>
			 </thead>
			 <tbody>
				<?php  foreach($users_list as $key => $usrs){  ?>
				<tr>
					<td><?=$key+1?></td>
					<td>
						<a class="float-left avatar">
							<?php 
							if(config_item('use_gravatar') == 'TRUE' AND 
								Applib::get_table_field(Applib::$profile_table,
									array('user_id'=>$usrs['user_id']),'use_gravatar') == 'Y'){
												$user_email = Applib::login_info($usrs['user_id'])->email; 
							?>
							<img src="<?=$this->applib->get_gravatar($user_email)?>" class="rounded-circle">
							<?php }else{ ?>
							<img src="<?=base_url()?>assets/avatar/<?=Applib::profile_info($usrs['user_id'])->avatar?>" class="rounded-circle">
							<?php } ?>
						</a>
						<h2>
							<a href="javascript:;"> 
								<?=$usrs['fullname']?>
							</a>
						</h2>
					</td> 
					
					<td class="text-center">  
					<?php
						$salary = ''; 
						if(isset($usrs['salary_det'])&& $usrs['salary_det'] != ''){
							$exp = explode('[^]',$usrs['salary_det']);
							if($exp[0] != 0){ $salary = $exp[0]; }
						} 

						$user_details = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$usrs['user_id']))->row_array();
						?>
						<strong> <?php echo  $user_details['salary']?$user_details['salary']:'N/A'; ?> </strong> 
					</td>
					<td>
						<span class="badge badge-info"><?php echo $usrs['designation'];?></span>
					</td>
					<td>
						<?=strftime(config_item('date_format'), strtotime($usrs['doj']));?>
					</td> 
					<td class="text-center"> 
						<span class="status-toggle" style="display: flex;justify-content: center;">
							<input type="checkbox" value="1" class="check" onchange="user_status_change(<?php echo $usrs['user_id'];?>)" id="payroll_user_status<?php echo $usrs['user_id'];?>" <?php if($usrs['status']=='1') echo'checked' ;?>>
							<label class="checktoggle" for="payroll_user_status<?php echo $usrs['user_id'];?>">checkbox</label>
						</span>

					</td>
                    <?php
					if(App::is_permit('menu_payroll','read'))
					{
					?>					
					<td class="text-center">

						<!-- <a class="btn btn-success btn-sm" data-toggle="ajaxModal" href="<?=base_url()?>payroll/edit_salary/<?=$usrs['user_id']?>" title="Edit Salary" data-original-title="Edit Salary">
							<i title="Edit Salary" class="fa fa-suitcase"></i>
						</a>
						<a class="btn btn-danger btn-sm" data-toggle="ajaxModal" href="<?=base_url()?>payroll/create/<?=$usrs['user_id']?>" title="Create Pay Slip" data-original-title="Create Pay Slip">
							<i title="Create Pay Slip" class="fa fa-money"></i>
						</a>     -->

						<a class="btn btn-danger btn-sm"  href="<?=base_url()?>payroll/payslip/<?=$usrs['user_id']?>" title="View Pay Slip" >
							<i title="View Pay Slip" class="fa fa-money"></i></a>
					</td>
					<?php
					}
					?>
				</tr>
				<?php } ?>  
			</tbody>
	   </table>   
		</div>
		</div> 
   </div>

   <?php // } ?>
</div>

<!-- Run Payroll Modal -->
<div class="modal custom-modal fade" id="run_payroll" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-head">
					<h3>Run Payroll</h3>
					<p>Are you sure want to run payroll?</p>

				</div>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<a href="<?php echo base_url();?>payroll/run_payroll" class="btn btn-primary continue-btn">Yes</a>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary continue-btn">No</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Run Payroll Modal -->