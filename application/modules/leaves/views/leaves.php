<?php
	
$leave_weekend = $this->db->get_where('leave_weekend')->row_array();

$weekend = explode(',', $leave_weekend['days']);
 //echo "<pre>"; print_r($this->session->userdata()); exit; 
// $this->db->select_sum('leave_days');
// $total_count = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id !='=>'8','leave_id !='=>'9'))->row()->leave_days;
$annual_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id '=>'1'))->row_array();
$carry_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id '=>'2'))->row_array();
$earned_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id '=>'3'))->row_array();
$sck_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id '=>'4'))->row_array();
$hospiatality_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id '=>'5'))->row_array();

// $total_hosp_leave = $this->db->get_where('user_leaves',array('user_id'=>$this->session->userdata('user_id'),'leave_type'=>'5'))->result_array();

// $total_count = ($annual_leaves['leave_days'] + $carry_leaves['leave_days'] + $earned_leaves['leave_days']);
$last_yr = date("Y",strtotime("-1 year"));
// echo $last_yr; exit;
$carry_days = $this->db->select_sum('leave_days')
					   ->from('dgt_user_leaves')
					   ->where('user_id',$this->session->userdata('user_id'))
					   ->like('leave_from',$last_yr)
					   ->like('leave_to',$last_yr)
					   ->get()->row()->leave_days;
$total_hosp_leave = $this->db->select_sum('leave_days')
							   ->from('dgt_user_leaves')
							   ->where('user_id',$this->session->userdata('user_id'))
							   ->where('leave_type','5')
							   ->where('status','1')
							   ->get()->row()->leave_days;
					   // echo $this->db->last_query(); exit;

$last_yr_leaves = $this->db->get_where('yearly_leaves',array('years'=>$last_yr))->row_array();
if(count($last_yr_leaves) != 0 )
{
	$l = json_decode($last_yr_leaves['total_leaves'],TRUE);

	$lst_anu_leaves = $l['annual_leaves'];
	$lst_cr_leaves = $l['cr_leaves'];
	$last_total = $lst_anu_leaves +  $lst_cr_leaves;
	if($carry_days != '')
	{
		$bl_leaves = $carry_days - $last_total; 
	}else{
		$bl_leaves = 0; 
	}
	// echo $bl_leaves; exit;
	if($bl_leaves < 0){			
		$ext_leaves = abs($bl_leaves);
	}else{
		$ext_leaves = 0;
	}
	if($ext_leaves == $carry_leaves['leave_days'])
	{
		$total_count = ($annual_leaves['leave_days'] + $carry_leaves['leave_days']);
	}
	if($ext_leaves > $carry_leaves['leave_days'])
	{
		$total_count = ($annual_leaves['leave_days'] + $carry_leaves['leave_days']);
	}
	if($ext_leaves < $carry_leaves['leave_days']){
		$total_count = ($annual_leaves['leave_days'] + $ext_leaves);
	}
	if($ext_leaves == 0)
	{
		$total_count = $annual_leaves['leave_days'];
	}


}else{
	$total_count = $annual_leaves['leave_days'];
}

	// echo $total_count; exit;

?>
<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-sm-8 col-3">
			<h4 class="page-title">Leaves</h4>
			<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
					<li class="breadcrumb-item active">Leaves</li>
				</ul>
		</div>
		<div class="col-sm-4 col-9 text-right m-b-30">
		<?php if(App::is_permit('menu_leaves','create'))
			{
			?>			
			<a href="javascript:;" class="btn btn-primary add-btn  float-right New-Leave" onclick="$('.new_leave_reqst').show();$('#date_alert_msg').hide();" data-loginid="<?php echo $this->session->userdata('user_id'); ?>" ><i class="fa fa-plus"></i> <?='New Leave Request';?></a>
			<?php 
			}
			?>

			
		</div>
	</div>
</div>
	<?php  if($this->session->flashdata('alert_message')){?>
	<div class="card card-default" id="date_alert_msg">
		<div class="card-header font-bold" style="color:white; background:#FF0000">
			<i class="fa fa-info-circle"></i> Alert Details 
			<i class="fa fa-times float-right" style="cursor:pointer" onclick="$('#date_alert_msg').hide();"></i>
		</div>
		<div class="card-body">
			<p style="color:red"> Already you have make request for now requested Dates! Please Check...</p>
		</div>
	</div>
	<?php  }  ?>  
	
	<?php $leav_types =  $this->db->query("SELECT * FROM `dgt_common_leave_types` where status = 0")->result_array();  ?> 

	
	<?php if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'admin') || ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'superadmin')) { 


		$total_employees = $this->db->get_where('users',array('role_id'=>3,'activated'=>1,'banned'=>0))->result_array();

		// $today_leaves = $this->db->get_where('user_leaves',array('leave_to'=>date('Y-m-d'),'leave_from'=>date('Y-m-d'),'status'=>1))->result_array();

		$today_leaves = $this->db->query("SELECT * FROM `dgt_user_leaves` WHERE (('".date('Y-m-d')."' between `leave_from` and `leave_to`) or `leave_from` = '".date('Y-m-d')."' or `leave_to` = '".date('Y-m-d')."') AND `status` = 1")->result_array();
		
		$pending_leaves = $this->db->get_where('user_leaves',array('status'=>0))->result_array();

		$present_employees = (count($total_employees) - count($today_leaves));

		?>

		<!-- Leave Statistics -->
					<div class="row">
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Today Present</h6>
								<h4> <?php echo $present_employees; ?> / <?php echo count($total_employees); ?></h4>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stats-info">
								<h6 class="text-success">Planned Leaves</h6>
								<h4><?php echo count($today_leaves); ?> <span>Today</span></h4>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stats-info">
								<h6 class="text-danger">Unplanned Leaves</h6>
								<h4>0 <span>Today</span></h4>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Pending Requests</h6>
								<h4><?php echo count($pending_leaves); ?></h4>
							</div>
						</div>
					</div>
					<!-- /Leave Statistics -->



<?php
$this->db->where('leave_id !=',2);
$this->db->where('leave_id !=',3);
$this->db->where('leave_id !=',8);
$this->db->where('leave_id !=',9);
$all_leave_types = $this->db->get('common_leave_types')->result_array();
// echo $this->db->last_query(); exit;
?>


	<!-- user leaves -->
		<div class="row filter-row">
			<div class="col-sm-4 col-md-4 col-lg-2">
				<div class="form-group form-focus select-focus">
					<label class="control-label">Leave Type</label>
					
					<select class="select2-option floating form-control" id="ser_leave_type" name="ser_leave_type" required>
						<option value=""> All Type of Leaves </option>

							<?php foreach($all_leave_types as $all_leave){ ?>
								<option value="<?=$all_leave['leave_id']?>"><?=$all_leave['leave_type']?></option>
							    	<?php  } ?> 

						 
					</select>
					<label id="ser_leave_type_error" class="error display-none" for="ser_leave_type">Select a Leave Type</label>
				</div>
			</div>
			<div class=" col-sm-4 col-md-4 col-lg-2">
				<div class="form-group form-focus select-focus">
					<label class="control-label">Leave Status</label>
					<select class="select2-option floating form-control" id="ser_leave_sts" name="ser_leave_sts" required>
						<option value=""> All Status </option>
						<option value="0"> Pending </option>
						<option value="1"> Approved </option>
						<option value="2"> Rejected </option>
					</select>
					<label id="ser_leave_sts_error" class="error display-none" for="ser_leave_sts">Select a status</label>
				</div>
			</div>
			<div class="col-12 col-sm-4 col-md-4 col-lg-2">
				<div class="form-group form-focus">
					<label class="control-label">Employee Name</label>
					<input type="text" class="form-control floating" id="ser_leave_user_name" name="ser_leave_user_name" value="">
					<label id="ser_leave_user_name_error" class="error display-none" for="ser_leave_user_name">Username Shouldn't be empty</label>
				</div>
			</div>
			<div class="col-12 col-sm-4 col-md-4 col-lg-2 search_date">
				<!-- <div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-12 p-0"> -->
						<div class="form-group form-focus">
						<div class="ref-icon"> 
							<label class="control-label">From</label>
							<input class="form-control floating leaves-datepicker" readonly type="text" data-date-format="dd-mm-yyyy" id="ser_leave_date_from" value="" size="16">
							<i class="fa fa-refresh fa-clearicon" title="Clear To Date" onclick="$('#ser_leave_date_from').val('');$(this).parent().parent().removeClass('focused');"></i>
						</div>	
						</div>
						<label id="ser_leave_date_from_error" class="error display-none" for="ser_leave_date_from">Choose From date</label>
					</div>
					<div class="col-12 col-sm-4 col-md-4 col-lg-2">
						<div class="form-group form-focus">
						<div class="ref-icon">
							<label class="control-label">To</label>
							<input class="form-control floating leaves-datepicker leaves-to-datepicker" readonly type="text" data-date-format="dd-mm-yyyy" id="ser_leave_date_to" value="" size="16">
							<i class="fa fa-refresh fa-clearicon" title="Clear To Date" onclick="$('#ser_leave_date_to').val('');$(this).parent().parent().removeClass('focused');"></i>
							<label id="ser_leave_date_to_error" class="error display-none" for="ser_leave_date_to">Choose To date</label>
							</div>
						</div>
					</div>
				<!-- </div>
			</div> -->
			<div class="col-sm-4 col-md-4  col-lg-2">
				<a href="javascript:void(0)" class="btn btn-success btn-block" id="admin_search_leave"> Search </a>
			</div>
		</div>


		<div class="row">
		<div class="col-md-12">
		<div class="table-responsive">
			<?php 
			$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.avatar
										FROM `dgt_user_leaves` ul
										left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
										left join dgt_account_details ad on ad.user_id = ul.user_id 
										where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." order by ul.id  DESC ")->result_array();
			
			// print_r($leave_list); exit;
	   ?>
			 <table id="table-holidays" class="table lable-table table-striped custom-table m-b-0 AppendDataTables admin_leave_tbl">
				<thead>
					<tr >
						<th><b> No </b></th>
						<th><b> Employee </b> </th>
						<th><b> Leave Type </b></th>
						<th><b> From </b></th>
						<th><b> To </b></th>
						<th><b> Reason </b></th> 
						<th><b> No.of Days </b> </th>  
						<th><b> Status </b></th>  
						<th><b> Approval Reason </b></th>  
						<th class="text-right no-sort"><b>Action </b></th>  
					</tr>
				</thead>
				<tbody id="admin_leave_tbl">
					<?php 
					if(!empty($leave_list)){
					 foreach($leave_list as $key => $levs){  ?>
					
					<tr>
						<td><?=$key+1?></td>
						<td><h2 class="table-avatar"><a  href="<?php echo base_url(); ?>employees/profile_view/<?php echo $levs['user_id']; ?>" class="avatar"><img class="avatar" src="<?php echo base_url(); ?>assets/avatar/<?php echo $levs['avatar']; ?>" alt=""></a><a class="" href="<?php echo base_url()?>leaves/show_leave/<?=$levs['user_id']?>"><?=$levs['fullname']?></a></h2></td>
						<td><?=$levs['l_type']?></td>
						<td><?=date('d-m-Y',strtotime($levs['leave_from']))?></td>
						<td><?=date('d-m-Y',strtotime($levs['leave_to']))?></td>
						<td width="30%"><?=$levs['leave_reason']?></td>
						<td>
							<?php 
							echo $levs['leave_days'];
							if($levs['leave_day_type'] == 1){
								echo ' ( Full Day )';
							}else if($levs['leave_day_type'] == 2){
								echo ' ( First Half )';
							}else if($levs['leave_day_type'] == 3){
								echo ' ( Second Half )';
							}?>
						  </td>
						<td>
						<?php
						if($levs['status'] == 4){
								echo '<span class="badge badge-info"> TL - Approved</span><br>';
								echo '<span class="badge badge-danger"> Management - Pending</span>';
							}else if($levs['status'] == 7){
										echo '<span class="badge badge-danger"> Deleted </span>';
									}
							if($levs['status'] == 0){
								echo ' <span class="badge text-white" style="background:#D2691E"> Pending </span>';
							}else if($levs['status'] == 1){
								echo '<span class="badge badge-success"> Approved </span> ';
							}else if($levs['status'] == 2){
								echo '<span class="badge badge-danger"> Rejected</span>';
							}else if($levs['status'] == 3){
								echo '<span class="badge badge-danger"> Cancelled</span>';
							}
							?>
						</td>
						<td><?php echo $levs['reason']?$levs['reason']:'-' ?></td>
						<td class="text-right"> 
						<?php // if($levs['status'] == 4){ ?>
							 <a  class="btn btn-success btn-sm"  
							 data-toggle="ajaxModal" href="<?=base_url()?>leaves/approve/management/<?=$levs['id']?>" title="Approve" data-original-title="Approve" >
								<i class="fa fa-thumbs-o-up"></i> 
							 </a>
						 <?php // } 
						 // if($levs['status'] == 0 || $levs['status'] == 1){
						 // if($levs['status'] == 4 ){ ?>     
							 <a class="btn btn-danger btn-sm"  
							 data-toggle="ajaxModal" href="<?=base_url()?>leaves/reject/management/<?=$levs['id']?>" title="Reject" data-original-title="Reject">
								<i class="fa fa-thumbs-o-down"></i> 
							 </a>
						 <?php // } ?>
						 <!--<a class="btn btn-danger btn-sm"  
							 data-toggle="ajaxModal" href="<?=base_url()?>leaves/delete/<?=$levs['id']?>" title="Delete" data-original-title="Delete">
								<i class="fa fa-trash-o"></i> 
						 </a>-->
						</td>
					</tr>
				 <?php  } ?>  
				 <?php  }else{ ?>
						 <tr><td class="text-center" colspan="10">No details were found</td></tr>
						 <?php } ?>  
				</tbody>
		   </table>    
	    </div>
		</div>

	</div>
		<!-- user leave end -->
		<?php } ?>
		<?php if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'superadmin') && ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'admin')) { 

				$user_id = $this->session->userdata('user_id');
				$user_detail = $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();
		  		$total_leaves = array();
		  		$normal_leaves = array();
		  		$medical_leaves = array();
		  		$sick_leaves = array();
		  		$leaves = $this->Leaves_model->check_leavesById($user_id);
		  		$nor_leaves = $this->Leaves_model->check_leavesBycat($user_id,'1');
		  		$med_leaves = $this->Leaves_model->check_leavesBycat($user_id,'2');
		  		$sick_leav = $this->Leaves_model->check_leavesBycat($user_id,'4');
		  		$sk_leaves = $this->Leaves_model->check_leavesBycat($user_id,'3');
		  		$this->db->select_sum('leave_days');
				$this->db->where('leave_type !=','1');
				$this->db->where('leave_type !=','4');
				$other_leave_count = $this->db->get_where('user_leaves',array('user_id'=>$user_id,'status'=>1,"DATE_FORMAT(leave_from,'%Y')" => date('Y')))->row_array();

				$this->db->select_sum('leave_days');
				$this->db->where('leave_id !=','1');
				$this->db->where('leave_id !=','4');
				if($user_detail['gender'] =='male'){
					$this->db->where('leave_id !=','6');
				}
				if($user_detail['gender'] =='female'){
					$this->db->where('leave_id !=','7');
				}
				$this->db->where('status','0');
				$other_leave = $this->db->get('dgt_common_leave_types')->row_array();

		  		for($i=0;$i<count($leaves);$i++)
		  		{
		  			$total_leaves[] = $leaves[$i]['leave_days'];
		  		}
		  		foreach($nor_leaves as $n_leave)
		  		{
		  			$normal_leaves[] = $n_leave['leave_days'];
		  		}
		  		foreach($med_leaves as $md_leave)
		  		{
		  			$medical_leaves[] = $md_leave['leave_days'];
		  		}
		  		foreach($sk_leaves as $sk_leave)
		  		{
		  			$sick_leaves[] = $sk_leave['leave_days'];
		  		}
		  		foreach($sick_leav as $sick_lea)
		  		{
		  			$all_sick_leaves[] = $sick_lea['leave_days'];
		  		}

		  		$t_leaves = array_sum($total_leaves);
		  		$total_normal_leaves = $this->db->get_where('leave_types',array('id'=>1))->row_array();
		  		$lop = ($t_leaves - $total_normal_leaves['leave_days']);
		  		if($lop > 0 )
		  		{
		  			$lop_days = $lop;
		  		}else{
		  			$lop_days = 0;
		  		}

		  		$re_leaves = (12 - $t_leaves);

		  		$an_leaves        = array();
		  		$crfd_leaves      = array();
		  		$ernd_leaves      = array();
		  		$anu_leaves       = $this->Leaves_model->check_leavesBycat($user_id,'1');
		  		$cr_leaves 		  = $this->Leaves_model->check_leavesBycat($user_id,'2');
		  		$er_leaves 		  = $this->Leaves_model->check_leavesBycat($user_id,'3');
		  		foreach($anu_leaves as $anu_leave)
		  		{
		  			$an_leaves[] = $anu_leave['leave_days'];
		  		}
		  		foreach($cr_leaves as $cr_leave)
		  		{
		  			$crfd_leaves[] = $cr_leave['leave_days'];
		  		}
		  		foreach($er_leaves as $er_leave)
		  		{
		  			$ernd_leaves[] = $er_leave['leave_days'];
		  		}

		  		// $tot_leave_count = (array_sum($an_leaves) + array_sum($crfd_leaves) + array_sum($ernd_leaves));
		  		$tot_leave_count = (array_sum($an_leaves) + array_sum($crfd_leaves));

		  		$tot_sk_leaves = array_sum($all_sick_leaves)?array_sum($all_sick_leaves):'0';


		  		$extra_leaves = $this->db->get_where('assigned_policy_user',array('user_id'=>$user_id));

		  		$extra_policy_leaves = array();
		  		$all_extra_policy_leaves = array();

		  		foreach ($extra_leaves->result_array() as $extra) {
		  			$extra_days = $this->db->get_where('custom_policy',array('policy_id'=>$extra['policy_id']))->row_array();
		  			$extra_policy_leaves[] = $extra_days['policy_leave_days'];
		  		}

		  		

		  		$maternity_leaves = $this->db->get_where('common_leave_types',array('leave_id'=>'6'))->row_array();
		  		$paternity_leaves = $this->db->get_where('common_leave_types',array('leave_id'=>'7'))->row_array();



		  		$total_maternity_leave = $this->db->select_sum('leave_days')
												  ->from('dgt_user_leaves')
												  ->where('user_id',$this->session->userdata('user_id'))
												  ->where('leave_type','6')
												  ->where('status','1')
												  ->get()->row()->leave_days;


		  		$total_paternity_leave = $this->db->select_sum('leave_days')
												  ->from('dgt_user_leaves')
												  ->where('user_id',$this->session->userdata('user_id'))
												  ->where('leave_type','7')
												  ->where('status','1')
												  ->get()->row()->leave_days;



		  		$cr_yr = date('Y');
		  		$total_user_leaves = $this->db->select_sum('leave_days')
									   ->from('dgt_user_leaves')
									   ->where('user_id',$this->session->userdata('user_id'))
									   ->where('status','1')
									   ->where('leave_type','1')
									   ->like('leave_from',$cr_yr)
									   ->like('leave_to',$cr_yr)
									   ->get()->row()->leave_days;

				if($extra_leaves->num_rows() != 0)
				{
					$total_count = ($total_count + array_sum($extra_policy_leaves));
				}


				$sk_lops = ($sck_leaves['leave_days'] - $tot_sk_leaves);
				if($sk_lops < 0 )
				{
					$sick_lop = abs($sk_lops);
				}else{
					$sick_lop = 0;
				}
				$tot_anu_count = ($total_count - $total_user_leaves);
				if($tot_anu_count < 0 )
				{
					$anu_lop = abs($tot_anu_count);
				}
				else
				{
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


				// Maternity Leave Conditions..

			$doj = $user_detail['doj'];
			$cr_date = date('Y-m-d');

			$ts1 = strtotime($doj);
			$ts2 = strtotime($cr_date);
			$year1 = date('Y', $ts1);
			$year2 = date('Y', $ts2);
			$month1 = date('m', $ts1);
			$month2 = date('m', $ts2);
			$job_experience = (($year2 - $year1) * 12) + ($month2 - $month1);

			//total leaves


		$username = $this->db->get_where('dgt_account_details',array('user_id'=>$this->session->userdata('user_id')))->row_array();


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

		 ?>

			<!-- Leave Statistics -->
			<div class="row">
				<?php $all_common_leave_types = $this->db->get_where('common_leave_types',array('status '=>'0'))->result_array(); 
				if(count($all_common_leave_types) != 0){
					foreach($all_common_leave_types as $common_leave){
						$cr_yr = date('Y');
						?>
						
								
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
											  	->where('user_id',$this->session->userdata('user_id'))
											  	->where('leave_type',$common_leave['leave_id'])
										  		->where('status','1')
								   				->like('leave_from',$cr_yr)
							  					->like('leave_to',$cr_yr)
										  		->get()->row()->leave_days;

										  		// echo $other_all_leaves; exit;
								?>
								<div class="col-md-3">
										<div class="stats-info">	
									 <h6><?php echo $common_leave['leave_type'];?></h6>
									<h4><?php echo($other_all_leaves !='')?$other_all_leaves:0;?> / <?php echo $common_leave['leave_days']; ?></h4>
								</div>	
							</div>
							<?php } }elseif(($common_leave['leave_id'] != 2) && ($common_leave['leave_id'] != 8) && ($common_leave['leave_id'] != 9)){?>

								
								<?php if($common_leave['leave_id'] == 6){ 
									if($user_detail['gender'] == 'female'){ 

										$total_maternity_leave = $this->db->select_sum('leave_days')
										  	->from('dgt_user_leaves')
										  	->where('user_id',$this->session->userdata('user_id'))
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
											  	->where('user_id',$this->session->userdata('user_id'))
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
											  	->where('user_id',$this->session->userdata('user_id'))
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





			<div class="col-md-3">
				<div class="stats-info">
					<h6>Total Leaves</h6>
					<h4><?php echo $t_leaves; ?> / <?php echo($total_leave['leave_days'] != 0)?$total_leave['leave_days']:0;?></h4>
				</div>
			</div>
			<div class="col-md-3">
				<div class="stats-info">
					<h6>Loss of Pay</h6>
					<h4><?php echo $total_lop; ?></h4>
				</div>
			</div>
		</div>
		<!-- /Leave Statistics -->

		<!-- user leaves -->

		<div class="card card-white new_leave_reqst" style="display:none">
		<div class="card-header p-15">
			<h3 class="card-title">New Leave Request</h3>
		</div>
		
		<div class="card-body"> 
			<?php $attributes = array('class' => 'bs-example form-horizontal','id'=> 'employeesAddLeave');
			echo form_open(base_url().'leaves/add',$attributes); ?> 	
				<div class="form-group">
					<label class="col-lg-2 control-label"> Leave Type <span class="text-danger">*</span></label>
					<div class="col-lg-4">
						<select class="select2-option form-control" style="width:100%;" id="req_leave_type" name="req_leave_type"> 
							<option value=""> -- Select Leave Type -- </option>
							<?php 
							$all_leave_types = $this->db->order_by('leave_type','ASC')->get_where('common_leave_types',array('status'=>'0','leave_id !='=>'8','leave_id !='=>'9'))->result_array();
							foreach($all_leave_types as $all_leave){
							    	if($job_experience < 3){ // More than 3 months
							    if(($all_leave['leave_id'] != 2) && ($all_leave['leave_id'] != 6) && ($all_leave['leave_id'] != 7) && ($all_leave['leave_id'] != 8) && ($all_leave['leave_id'] != 9)){
							?>
								<option value="<?=$all_leave['leave_id']?>"><?=$all_leave['leave_type']?></option>
							<?php } }elseif(($all_leave['leave_id'] != 2) && ($all_leave['leave_id'] != 8) && ($all_leave['leave_id'] != 9)){

								if($user_detail['gender'] == 'female'){ 
									if($all_leave['leave_id'] != 7){ ?>
										<option value="<?=$all_leave['leave_id']?>"><?=$all_leave['leave_type']?></option>

								<?php }
							} else if($user_detail['gender'] == 'male') {
								if($all_leave['leave_id'] != 6){ ?>
										<option value="<?=$all_leave['leave_id']?>"><?=$all_leave['leave_type']?></option>

								<?php }
							}
							 ?>
							
								
							<?php } } ?>       
							
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">From <span class="text-danger">*</span></label>
					<div class="col-lg-4">
						<input class="form-control" readonly size="16" type="text"
						onchange="leave_days_calc();"
						  value="" name="req_leave_date_from" id="req_leave_date_from" data-date-format="dd-mm-yyyy" > 
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">To <span class="text-danger">*</span></label>
					<div class="col-lg-4">
					<input class=" form-control" readonly size="16" type="text"  
					onchange="leave_days_calc();"
					value="" name="req_leave_date_to" id="req_leave_date_to" data-date-format="dd-mm-yyyy" > 
					</div>
				</div>
				<div class="form-group" style="display:none" id="leave_day_type">
					<label class="col-lg-2 control-label">  &nbsp; </label>
					<div class="col-lg-4"> 
					 Full Day <input type="radio" class="relativ-radio" name="req_leave_day_type" value="1" checked="checked" onclick="leave_day_type();"> 
					 &nbsp; First Half <input type="radio" class="relativ-radio" name="req_leave_day_type" value="2" onclick="leave_day_type();"> 
					 &nbsp; Second Half <input type="radio" class="relativ-radio" name="req_leave_day_type" value="3" onclick="leave_day_type();">
					 </div>
				</div> 
				<div class="form-group">
					<label class="col-lg-2 control-label"> Number of days </label>
					<div class="col-lg-4">
						<input type="text" name="req_leave_count" class="form-control" id="req_leave_count" value="" readonly="readonly">
						<?php $teamlead_details = $this->db->get_where('dgt_users',array('id'=>$this->session->userdata('user_id')))->row_array(); ?>
						<input type="hidden" name="teamlead_id" id="teamlead_id" value="<?php echo $teamlead_details['teamlead_id']; ?>">
						<!-- <span style="color:red;display: none;" id="lop_call">LOP</span> -->
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label"> Leave reason <span class="text-danger">*</span></label>
					<div class="col-lg-4">
						<textarea class="form-control" name="req_leave_reason" id="staff_leave_reason"></textarea>
					</div>
				</div> 
				<div class="form-group">
					<label class="col-lg-2 control-label"> &nbsp; </label>
					<div class="col-lg-4">
						<button class="btn btn-success" type="submit" id="employee_add_leave"> Send Leave Request </button>
						<button class="btn btn-danger" type="button" onclick="$('.new_leave_reqst').hide();"> Cancel </button>
					 </div>
				</div> 
				
			
		</div>
	</div> 

	<?php 
	$check_teamlead = $this->db->get_where('dgt_users',array('id'=>$this->session->userdata('user_id')))->row_array(); 

	$this->db->where("FIND_IN_SET(".$this->session->userdata('user_id').", teamlead_id)");

	$check_approver = $this->db->get('dgt_user_leaves')->num_rows(); 	
	// echo $this->db->last_query(); exit;
	if($check_approver > 0){
		$check_teamlead['is_teamlead'] = 'yes';
	}else{
		$check_teamlead['is_teamlead'] = 'no';
	}

	if($check_teamlead['is_teamlead'] == 'yes'){
	?>
	
	<ul class="nav nav-tabs nav-tabs-solid m-b-30 m-t-15">
	    <li class="choosetype personal nav-item" data-type="personal"><a href="javascript:void(0)" class="nav-link">Personal Leaves</a></li>                       
	  	<li class="choosetype team active nav-item" data-type="team"><a href="javascript:void(0)" class="nav-link">Team Leaves</a></li>
 	</ul>
	<?php } ?>
	<div  id="team_leaves">
			
				<h3 class="card-title">Leaves Details</h3>
			
				<div class="table-responsive">
				   <?php 
				   
				   // print_r($check_teamlead); exit;
				   if($check_teamlead['is_teamlead'] == 'no')
				   {
					$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type
													FROM `dgt_user_leaves` ul
													left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
													where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and 
													ul.status != 6 and ul.user_id =".$this->tank_auth->get_user_id()." order by ul.id  ASC ")->result_array();
				}
				if($check_teamlead['is_teamlead'] == 'yes')
				   {
					$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type
													FROM `dgt_user_leaves` ul
													left join dgt_leave_approvers la on la.leave_id = ul.id
													left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
													where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and 
													ul.status != 7 and la.approvers =".$check_teamlead['id']." and la.view_status = 1 order by ul.id  ASC ")->result_array();
				}
					
				   ?>
					<table id="table-holidays" class="table  lable-table table-striped custom-table m-b-0 AppendDataTables">
						<thead>
							<tr>
								<th> No </th>
								<?php if($check_teamlead['is_teamlead'] == 'yes') { ?>
								<th> Employee Name</th>
								<?php }?>
								<th> Leave Type </th>
								<th> From </th>
								<th> To </th>
								<th> Reason </th> 
								<th> No.of Days </th>  
								<th> Status </th>  
								<th> Approval Reason </th>  
								<th class="no-sort"> Action </th>  
							</tr>
						</thead>
						<tbody>
						 <?php 
						 	if(!empty($leave_list)){
						  foreach($leave_list as $key => $levs){  ?>
							<tr>
								<td><?=$key+1?></td>
								<?php if($check_teamlead['is_teamlead'] == 'yes') { $user_details = $this->db->get_where('account_details',array('user_id'=>$levs['user_id']))->row_array(); ?>
								<td><a class="text-info" href="<?php echo base_url()?>leaves/show_leave/<?=$levs['user_id']?>"><?=$user_details['fullname']?></a></td> <?php } ?>
								<td><?=$levs['l_type']?></td>
								<td><?=date('d-m-Y',strtotime($levs['leave_from']))?></td>
								<td><?=date('d-m-Y',strtotime($levs['leave_to']))?></td>
								<td><?=$levs['leave_reason']?></td>
								<td>
									<?php 
									echo $levs['leave_days'];
									if($levs['leave_day_type'] == 1){
										echo ' ( Full Day )';
									}else if($levs['leave_day_type'] == 2){
										echo ' ( First Half )';
									}else if($levs['leave_day_type'] == 3){
										echo ' ( Second Half )';
									}
									?>
								</td>
								<td>
								<?php
								if($check_teamlead['is_teamlead'] == 'no'){
										if($levs['status'] == 0){
										if($check_teamlead['is_teamlead'] == 'no')
										echo '<span class="badge" style="background:#D2691E"> Pending </span>';
										if($check_teamlead['is_teamlead'] == 'yes')
										echo '<span class="badge" style="background:#D2691E"> Requested </span>';
									}else if($levs['status'] == 1){
										echo '<span class="badge badge-success"> Approved </span> ';
									}else if($levs['status'] == 2){
										echo '<span class="badge badge-danger"> Rejected</span>';
									}else if($levs['status'] == 3){
										echo '<span class="badge badge-danger"> Cancelled</span>';
									}else if($levs['status'] == 4){
										echo '<span class="badge badge-info"> TL - Approved</span><br>';
										echo '<span class="badge badge-danger"> Management - Pending</span>';
									}else if($levs['status'] == 5){
										echo '<span class="badge badge-danger"> TL - Rejected</span>';
									}else if($levs['status'] == 7){
										echo '<span class="badge badge-danger"> Deleted </span>';
									}
								} 			  
								if($check_teamlead['is_teamlead'] == 'yes')
				   				{
									$leave_approvers_status   = $this->db->get_where('dgt_leave_approvers',array('approvers'=>$this->session->userdata('user_id'),'leave_id'=>$levs['id']))->row_array();
									 // print_r($this->db->last_query()); exit()	;
									if(isset($leave_approvers_status)&&!empty($leave_approvers_status))
									{
										if($leave_approvers_status['status'] == 0){
												if($check_teamlead['is_teamlead'] == 'no')
												echo '<span class="badge" style="background:#D2691E"> Pending </span>';
												if($check_teamlead['is_teamlead'] == 'yes')
												echo '<span class="badge" style="background:#D2691E"> Requested </span>';
												}else if($leave_approvers_status['status'] == 1){
													echo '<span class="badge badge-success"> Approved </span> ';
												}else if($leave_approvers_status['status'] == 2){
													echo '<span class="badge badge-danger"> Rejected</span>';
												}
									}
								}	
						
								?>
								</td>
								<td><?php echo $levs['reason']?$levs['reason']:'-' ?></td>
								<td> 
									<?php if($check_teamlead['is_teamlead'] == 'no'){ ?>
								<?php if($levs['status'] == 0){ ?> 
									 <a class="btn btn-danger btn-sm"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/cancel/<?=$levs['id']?>" title="Cancel" data-original-title="Cancel">
										<i class="fa fa-times"></i> 
									 </a>
								<?php } }
								if($check_teamlead['is_teamlead'] == 'yes'){ 
									if(($levs['status'] != 3) &&  ($levs['status'] != 1)){
								 ?>
								 
									 <a  class="btn btn-success btn-sm"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/approve/teamlead/<?=$levs['id']?>" title="Approve" data-original-title="Approve" >
										<i class="fa fa-thumbs-o-up"></i> 
									 </a> 
									 <a class="btn btn-danger btn-sm"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/reject/teamlead/<?=$levs['id']?>" title="Reject" data-original-title="Reject">
										<i class="fa fa-thumbs-o-down"></i> 
									 </a>
								 <?php
									 
									 } } ?>
									 <!--<a class="btn btn-danger btn-sm"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/delete/<?=$levs['id']?>" title="Delete" data-original-title="Delete">
										<i class="fa fa-trash-o"></i> 
									 </a>-->
								</td>
							</tr>
						 <?php  } ?>  
						 <?php  }else{ ?>
						 <tr><td colspan="10">No details were found</td></tr>
						 <?php } ?>  
						</tbody>
				   </table>    
				</div>
			
		
		
	 <!-- user leave end -->
	 <?php } ?>
	 <div class="card panel-table" id="Personal_leaves" style="display: none;">
			<div class="card-header">
				<h3 class="card-title">Leaves Details</h3>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <?php 
				   // $check_teamlead = $this->db->get_where('dgt_users',array('id'=>$this->session->userdata('user_id')))->row_array();
				   // print_r($check_teamlead); exit;
				   // if($check_teamlead['is_teamlead'] == 'no')
				   // {
					$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type
													FROM `dgt_user_leaves` ul
													left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
													where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and 
													ul.status != 6 and ul.user_id =".$this->tank_auth->get_user_id()." order by ul.id  ASC ")->result_array();
				// }
				// if($check_teamlead['is_teamlead'] == 'yes')
				//    {
				// 	$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type
				// 									FROM `dgt_user_leaves` ul
				// 									left join dgt_common_leave_types lt on lt.id = ul.leave_type
				// 									where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and 
				// 									ul.status != 7 and ul.teamlead_id =".$check_teamlead['id']." order by ul.id  ASC ")->result_array();
				// }
					
				   ?>
					<table id="table-holidays" class="table  lable-table table-striped custom-table m-b-0 AppendDataTables">
						<thead>
							<tr>
								<th> No </th>
								<th> Leave Type </th>
								<th> From </th>
								<th> To </th>
								<th> Reason </th> 
								<th> No.of Days </th>  
								<th> Status </th>  
								<th> Approval Reason </th>  
								<th class="no-sort"> Action </th>  
							</tr>
						</thead>
						<tbody>
						 <?php 
						 	if(!empty($leave_list)){
						  foreach($leave_list as $key => $levs){  ?>
							<tr>
								<td><?=$key+1?></td>
								<td><?=$levs['l_type']?></td>
								<td><?=date('d-m-Y',strtotime($levs['leave_from']))?></td>
								<td><?=date('d-m-Y',strtotime($levs['leave_to']))?></td>
								<td><?=$levs['leave_reason']?></td>
								<td>
									<?php 
									echo $levs['leave_days'];
									if($levs['leave_day_type'] == 1){
										echo ' ( Full Day )';
									}else if($levs['leave_day_type'] == 2){
										echo ' ( First Half )';
									}else if($levs['leave_day_type'] == 3){
										echo ' ( Second Half )';
									}
									?>
								</td>
								<td>
								<?php
									if($levs['status'] == 0){
										if($check_teamlead['is_teamlead'] == 'no')
										echo '<span class="badge" style="background:#D2691E"> Pending </span>';
										if($check_teamlead['is_teamlead'] == 'yes')
										echo '<span class="badge" style="background:#D2691E"> Requested </span>';
									}else if($levs['status'] == 1){
										echo '<span class="badge badge-success"> Approved </span> ';
									}else if($levs['status'] == 2){
										echo '<span class="badge badge-danger"> Rejected</span>';
									}else if($levs['status'] == 3){
										echo '<span class="badge badge-danger"> Cancelled</span>';
									}else if($levs['status'] == 4){
										echo '<span class="badge badge-info"> TL - Approved</span><br>';
										echo '<span class="badge badge-danger"> Management - Pending</span>';
									}else if($levs['status'] == 5){
										echo '<span class="badge badge-danger"> TL - Rejected</span>';
									}else if($levs['status'] == 7){
										echo '<span class="badge badge-danger"> Deleted </span>';
									}
								?>
								</td>
								<td><?php echo $levs['reason']?$levs['reason']:'-';?></td>
								<td> 
									<?php if($check_teamlead['is_teamlead'] == 'no'){ ?>
								<?php if($levs['status'] == 0){ ?> 
									 <a class="btn btn-danger btn-sm"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/cancel/<?=$levs['id']?>" title="Cancel" data-original-title="Cancel">
										<i class="fa fa-times"></i> 
									 </a>
								<?php } }
								if($check_teamlead['is_teamlead'] == 'yes'){ 
									if($levs['status'] != 7){
									// if(($levs['status'] != 3) &&  ($levs['status'] != 1)){
								 ?>
									 <!-- <a  class="btn btn-success btn-sm"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/approve/teamlead/<?=$levs['id']?>" title="Approve" data-original-title="Approve" >
										<i class="fa fa-thumbs-o-up"></i> 
									 </a> 
									 <a class="btn btn-danger btn-sm"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/reject/teamlead/<?=$levs['id']?>" title="Reject" data-original-title="Reject">
										<i class="fa fa-thumbs-o-down"></i> 
									 </a> -->
									 <?php if(App::is_permit('menu_leaves','delete'))
									 {
									?>										
									 <a class="btn btn-danger btn-sm"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/delete/<?=$levs['id']?>" title="Delete" data-original-title="Delete">
										<i class="fa fa-trash-o"></i> 
									 </a>
									<?php 
									 }
								 } } ?>
								</td>
							</tr>
						 <?php  } ?>  
						 <?php  }else{ ?>
						 <tr><td colspan="10">No details were found</td></tr>
						 <?php } ?>  
						</tbody>
				   </table>    
				</div>
			</div>
		</div>
</div> 




