<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-sm-8 col-3">
			<?php 
			$user_id = $this->uri->segment(3);
			$username = $this->db->get_where('dgt_account_details',array('user_id'=>$user_id))->row_array();
			$user_detail = $this->db->get_where('dgt_account_details',array('user_id'=>$user_id))->row_array();


			$doj = $user_detail['doj'];
			$cr_date = date('Y-m-d');

			$ts1 = strtotime($doj);
			$ts2 = strtotime($cr_date);
			$year1 = date('Y', $ts1);
			$year2 = date('Y', $ts2);
			$month1 = date('m', $ts1);
			$month2 = date('m', $ts2);
			$job_experience = (($year2 - $year1) * 12) + ($month2 - $month1);

			// echo $job_experience; exit;
			
			?>
			<h4 class="page-title"><?php echo $username['fullname']?> Leaves</h4>
			<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
					<li class="breadcrumb-item active"><a href="<?=base_url()?>leaves">Leaves</a></li>
					<li class="breadcrumb-item active">Leave Details</li>
				</ul>
		</div>
		<div class="col-sm-4 col-9 text-right m-b-30">
			<?php if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'admin') && ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'superadmin')) { ?>
			<a href="javascript:;" class="btn btn-primary rounded float-right New-Leave" onclick="$('.new_leave_reqst').show();$('#date_alert_msg').hide();" data-loginid="<?php echo $this->session->userdata('user_id'); ?>" ><i class="fa fa-plus"></i> <?='New Leave Request';?></a>
			<?php } ?>
		</div>
	</div>
</div>
	<?php  if($this->session->flashdata('alert_message')){?>
	<div class="card panel-default" id="date_alert_msg">
		<div class="card-header font-bold" style="color:white; background:#FF0000">
			<i class="fa fa-info-circle"></i> Alert Details 
			<i class="fa fa-times float-right" style="cursor:pointer" onclick="$('#date_alert_msg').hide();"></i>
		</div>
		<div class="card-body">
			<p style="color:red"> Already you have make request for now requested Dates! Please Check...</p>
		</div>
	</div>
	<?php  }  ?>  
	
	<?php $leav_types =  $this->db->query("SELECT * FROM `dgt_common_leave_types` where status = 0")->result_array(); 
			$check_teamlead = $this->db->get_where('dgt_users',array('id'=>$this->session->userdata('user_id')))->row_array(); 

			$this->db->where("FIND_IN_SET(".$this->session->userdata('user_id').", teamlead_id)");

	$check_approver = $this->db->get('dgt_user_leaves')->num_rows(); 	
	// echo $this->db->last_query(); exit;
	if($check_approver > 0){
		$check_teamlead['is_teamlead'] = 'yes';
	}else{
		$check_teamlead['is_teamlead'] = 'no';
	}

	 ?> 

	
	<?php if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'admin') || ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'superadmin') || ($check_teamlead['is_teamlead'] =='yes')) { 


		
		$annual_leave = $this->db->get_where('dgt_common_leave_types',array('leave_id'=> 1))->row_array();
		$sick_leave = $this->db->get_where('dgt_common_leave_types',array('leave_id'=> 4))->row_array();
		$this->db->select_sum('leave_days');
		$this->db->where('leave_id !=','1');
		$this->db->where('leave_id !=','4');
		if($username['gender'] =='male'){
			$this->db->where('leave_id !=','6');
		}
		if($username['gender'] =='female'){
			$this->db->where('leave_id !=','7');
		}
		$this->db->where('status','0');
		$other_leave = $this->db->get('dgt_common_leave_types')->row_array();	

		 


		$this->db->select_sum('leave_days');
		if($job_experience < 3){
			$this->db->where('leave_id !=','6');
			$this->db->where('leave_id !=','7');
			$this->db->where('leave_id !=','8');
			$this->db->where('leave_id !=','9');
		} else {
			if($username['gender'] =='male'){
				$this->db->where('leave_id !=','6');
			}
			if($username['gender'] =='female'){
				$this->db->where('leave_id !=','7');
			}
		}
		$this->db->where('status','0');
		$total_leave = $this->db->get('dgt_common_leave_types')->row_array();


		$this->db->select_sum('leave_days');
		$annual_leave_count = $this->db->get_where('user_leaves',array('leave_type'=> 1,'user_id'=>$user_id,'status'=>1,"DATE_FORMAT(leave_from,'%Y')" => date('Y')))->row_array();
		// echo $this->db->last_query(); exit;
		$this->db->select_sum('leave_days');
		$sick_leave_count = $this->db->get_where('user_leaves',array('leave_type'=> 4,'user_id'=>$user_id,'status'=>1,"DATE_FORMAT(leave_from,'%Y')" => date('Y')))->row_array();

		$this->db->select_sum('leave_days');
		$this->db->where('leave_type !=','1');
		$this->db->where('leave_type !=','4');
		$other_leave_count = $this->db->get_where('user_leaves',array('user_id'=>$user_id,'status'=>1,"DATE_FORMAT(leave_from,'%Y')" => date('Y')))->row_array();
		

		$this->db->select_sum('leave_days');
		$leave_count = $this->db->get_where('user_leaves',array('user_id'=> $user_id,'status'=>1,"DATE_FORMAT(leave_from,'%Y')" => date('Y')))->row_array();  

		$this->db->select_sum('leave_days');
		$leave_dayss = $this->db->get('common_leave_types')->row_array();

		$sk_lops = ($sick_leave['leave_days'] - $sick_leave_count['leave_days']);
				if($sk_lops < 0 )
				{
					$sick_lop = abs($sk_lops);
				}else{
					$sick_lop = 0;
				}
				$tot_anu_count = ($annual_leave['leave_days'] - $annual_leave_count['leave_days']);
				if($tot_anu_count < 0 )
				{
					$anu_lop = abs($tot_anu_count);
				}else{
					$anu_lop = 0;
				}
				// $tot_hosp_count = ($hospiatality_leaves['leave_days'] - $total_hosp_leave);
				// if($tot_hosp_count < 0 )
				// {
				// 	$hosp_lop = abs($tot_hosp_count);
				// }else{
				// 	$hosp_lop = 0;
				// }

				$tot_other_count = ($other_leave['leave_days'] - $other_leave_count['leave_days']);
				if($tot_other_count < 0 )
				{
					$other_lop = abs($tot_other_count);
				}else{
					$other_lop = 0;
				}


				$total_lop = ($anu_lop + $sick_lop + $other_lop);
		  
		?>

		<!-- Leave Statistics -->
					<div class="row">

						<?php $all_common_leave_types = $this->db->get_where('common_leave_types',array('status '=>'0'))->result_array(); 
						// echo print_r($all_common_leave_types); 
				if(count($all_common_leave_types) != 0){
					foreach($all_common_leave_types as $common_leave){
						$cr_yr = date('Y');
						?>
						<!-- <div class="col-md-3">
							<div class="stats-info">
								<h6><?php echo $common_leave['leave_type'];?></h6> -->
								<!-- Annuall leaves -->
								<!-- <?php if($common_leave['leave_id'] == 1){
									
		  							$anual_leaves = $this->db->select_sum('leave_days')
									   ->from('dgt_user_leaves')
									   ->where('user_id',$this->session->userdata('user_id'))
									   ->where('status','1')
									   ->where('leave_type',$common_leave['leave_id'])
									   ->like('leave_from',$cr_yr)
									   ->like('leave_to',$cr_yr)
									   ->get()->row()->leave_days;
								 ?>
									<h4><?php echo($anual_leaves !='')?$anual_leaves:0;?> / <?php echo $total_count; ?></h4>
								<?php } ?> -->

								<!-- Maternity leave -->

							<?php if($job_experience < 3){ // More than 3 months
							    if(($common_leave['leave_id'] != 2) && ($common_leave['leave_id'] != 6) && ($common_leave['leave_id'] != 7) && ($common_leave['leave_id'] != 8) && ($common_leave['leave_id'] != 9)){

							?>
								 <!-- Other all leaves -->
								
								<?php $other_all_leaves = $this->db->select_sum('leave_days')
											  	->from('dgt_user_leaves')
											  	->where('user_id',$user_id)
											  	->where('leave_type',$common_leave['leave_id'])
										  		->where('status','1')
								   				->like('leave_from',$cr_yr)
							  					->like('leave_to',$cr_yr)
										  		->get()->row()->leave_days;

										  		
								?>
								<div class="col-md-3">
										<div class="stats-info">	
									 <h6><?php echo $common_leave['leave_type'];?></h6>
									<h4><?php echo($other_all_leaves !='')?$other_all_leaves:0;?> / <?php echo $common_leave['leave_days']; ?></h4>
								</div>	
							</div>
							<?php } }elseif(($common_leave['leave_id'] != 2) && ($common_leave['leave_id'] != 8) && ($common_leave['leave_id'] != 9)){

									
								?>

								
								<?php if($common_leave['leave_id'] == 6){ 
									if($user_detail['gender'] == 'female'){ 

										$total_maternity_leave = $this->db->select_sum('leave_days')
										  	->from('dgt_user_leaves')
										  	->where('user_id',$user_id)
										  	->where('leave_type',$common_leave['leave_id'])
										  	->where('status','1')
								   			->like('leave_from',$cr_yr)
							  				->like('leave_to',$cr_yr)
										  ->get()->row()->leave_days;
								?>	
									<div class="col-md-3">
										<div class="stats-info">							
											<h6><?php echo $common_leave['leave_type'];?></h6>
											<h4><?php echo($total_maternity_leave !='')?$total_maternity_leave:0;?> / <?php echo $common_leave['leave_days']; ?></h4>
										</div>	
									</div>	
								
								<?php   } } elseif($common_leave['leave_id'] == 7){ 

									if($user_detail['gender'] == 'male'){ 
									$total_paternity_leave = $this->db->select_sum('leave_days')
											  	->from('dgt_user_leaves')
											  	->where('user_id',$user_id)
											  	->where('leave_type',$common_leave['leave_id'])
										  		->where('status','1')
								   				->like('leave_from',$cr_yr)
							  					->like('leave_to',$cr_yr)
										  		->get()->row()->leave_days;
								?>
								<div class="col-md-3">
										<div class="stats-info">	
									 <h6><?php echo $common_leave['leave_type'];?></h6>
									<h4><?php echo($total_paternity_leave !='')?$total_paternity_leave:0;?> / <?php echo $common_leave['leave_days']; ?></h4>
								</div>
							</div>
								
								<?php }  } else{ 
								 ?>
								<?php $other_all_leaves = $this->db->select_sum('leave_days')
											  	->from('dgt_user_leaves')
											  	->where('user_id',$user_id)
											  	->where('leave_type',$common_leave['leave_id'])
										  		->where('status','1')
								   				->like('leave_from',$cr_yr)
							  					->like('leave_to',$cr_yr)
										  		->get()->row()->leave_days;

										  		// echo print_r($other_all_leaves); exit;
								?>
								<div class="col-md-3">
										<div class="stats-info">	
									 <h6><?php echo $common_leave['leave_type'];?></h6>
									<h4><?php echo($other_all_leaves !='')?$other_all_leaves:0;?> / <?php echo $common_leave['leave_days']; ?></h4>
								</div>
							</div>	
								
							<?php }
						}  ?>

					<?php }
				}
			?>


						<!-- <div class="col-md-3">
							<div class="stats-info">
								<h6>Annual Leave</h6>
								<h4> 
									<?php if($annual_leave_count['leave_days'] != '') { echo $annual_leave_count['leave_days']; } elseif($annual_leave_count['leave_days'] == '') { echo '0'; } ?> / <?php echo $annual_leave['leave_days'];?>
								</h4>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Sick Leave</h6>
								<h4><?php if($sick_leave_count['leave_days'] != '') { echo $sick_leave_count['leave_days']; } elseif($sick_leave_count['leave_days'] == '') { echo '0'; } ?> / <?php echo $sick_leave['leave_days'];?></h4>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Other Leaves</h6>
								<h4><?php if($other_leave_count['leave_days'] != '') { echo $other_leave_count['leave_days']; } elseif($other_leave_count['leave_days'] == '') { echo '0'; } ?> / <?php echo $other_leave['leave_days'];?></h4>
							</div>
						</div> -->
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Total Leaves</h6>
								<h4><?php echo ($leave_count['leave_days'] !='')?$leave_count['leave_days']:0;?>/<?php echo($total_leave['leave_days'] != 0)?$total_leave['leave_days']:0;?></h4> 
								
							</div>
						</div>
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Loss of Pay</h6>
								<h4>
								<!-- <?php
								if($leave_count['leave_days'] > $leave_dayss['leave_days'])
								{
									$lop = $leave_count['leave_days'] - $leave_dayss['leave_days'];
								} 
								else
								{
									$lop = 0;
									
								}
								?><?php echo $lop?> -->
								<?php  echo $total_lop;?>
								</h4>
							</div>
						</div>
					</div>
					<!-- /Leave Statistics -->






	
		<div class="card-box">
		<div class="row">
		<div class="col-md-12">
		<div class="table-responsive">
			<?php 
			$user_id = $this->uri->segment(3);
			

	  		
			$leave_details = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname
										FROM `dgt_user_leaves` ul
										left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
										left join dgt_account_details ad on ad.user_id = ul.user_id 
										where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and  ul.user_id='".$user_id."' ")->result_array();
			 // print_r($leave_details); exit;
	   		?>
			 <table id="table-holidays" class="table table-striped custom-table m-b-0 AppendDataTables">
				<thead>
					<tr class="table_heading">
						<th> No </th>
						<th> Leave Type </th>
						<th> From </th>
						<th> To </th>
						<th> Reason </th> 
						<th> No.of Days </th>  
						<th> Status </th>  
						<th> Approval Reason </th>  
						
					</tr>
				</thead>
				<tbody id="admin_leave_tbl">
					<?php 
					if(!empty($leave_details)){
					 foreach($leave_details as $key => $details){  ?>
					
					<tr>
						<td><?=$key+1?></td>
						
						<td><?=$details['l_type']?></td>
						<td><?=date('d-m-Y',strtotime($details['leave_from']))?></td>
						<td><?=date('d-m-Y',strtotime($details['leave_to']))?></td>
						<td width="30%"><?=$details['leave_reason']?></td>
						<td>
							<?php 
							echo $details['leave_days'];
							if($details['leave_day_type'] == 1){
								echo ' ( Full Day )';
							}else if($details['leave_day_type'] == 2){
								echo ' ( First Half )';
							}else if($details['leave_day_type'] == 3){
								echo ' ( Second Half )';
							}?>
						  </td>
						<td>
						<?php
						if($details['status'] == 4){
								echo '<span class="badge badge-info"> TL - Approved</span><br>';
								echo '<span class="badge badge-danger"> Management - Pending</span>';
							}else if($details['status'] == 7){
										echo '<span class="badge badge-danger"> Deleted </span>';
									}
							if($details['status'] == 0){
								echo ' <span class="badge text-white" style="background:#D2691E"> Pending </span>';
							}else if($details['status'] == 1){
								echo '<span class="badge badge-success"> Approved </span> ';
							}else if($details['status'] == 2){
								echo '<span class="badge badge-danger"> Rejected</span>';
							}else if($details['status'] == 3){
								echo '<span class="badge badge-danger"> Cancelled</span>';
							}
							?>
						</td>
						<td><?php echo $details['reason']?$details['reason']:'-'; ?></td>
					</tr>
				 <?php  } ?>  
				 <?php  }else{ ?>
						 <tr><td class="text-center" colspan="9">No details were found</td></tr>
						 <?php } ?>  
				</tbody>
		   </table>    
	    </div>
		</div>
		</div>
	</div>
		<!-- user leave end -->
		<?php } ?>
		

			

		


