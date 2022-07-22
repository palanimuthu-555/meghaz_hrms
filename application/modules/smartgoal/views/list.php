<div class="content">
	<div class="row">
		<div class="col-sm-8 col-3">			
			<h4 class="page-title">SMART Goals</h4>
		</div>
		<?php 
			 $check_report_to = $this->db->get_where('users',array('id '=>$this->session->userdata('user_id')))->row_array();
		?>

		<?php if ($check_report_to['teamlead_id'] != 0) { ?>
		<div class="col-sm-4 col-9 text-right m-b-30">
				<a href="<?php echo base_url()?>smartgoal/manager_performance" class="btn btn-primary rounded float-right New-Leave" ><i class="fa fa-plus"></i> <?='Manager Performance';?></a>			
		</div>
	<?php } ?>
	</div>
	
	

		<div class="row">
		<div class="col-md-12">
		<div class="table-responsive">
			<?php 
			// $user_id = $this->uri->segment(3);
				  		
			
					
			
	   		?>
			 <table id="table-holidays" class="table table-striped custom-table m-b-0 AppendDataTables">
				<thead>
					<tr class="table_heading">
						<th> No </th>
						<th> Name </th>
						<th> Designation</th>
						<th> Team lead </th>
						<th> Goal Duaration </th> 
						 
						
					</tr>
				</thead>
				<tbody id="admin_leave_tbl">
					<?php 
					if(!empty($smartgoal)){
					 foreach($smartgoal as $key => $details){

					 	$employee_details = $this->db->get_where('users',array('id'=>$details['user_id']))->row_array();
$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
$account_details = $this->db->get_where('account_details',array('user_id'=>$details['user_id']))->row_array();
$team_lead = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array();
$teamlead = $this->db->get_where('account_details',array('user_id'=>$team_lead['user_id']))->row_array();
					   ?>
					
					<tr>
						<td><?=$key+1?></td>
						
						<td><a class="text-info" href="<?php echo base_url()?>smartgoal/show_smartgoal/<?=$details['user_id']?>"><?=$account_details['fullname']?></a></td>
						<td><?php echo $designation['designation']?></td>
						<td><?=$teamlead['fullname']?></td>
						<td>
							<?php if($details['goal_duration'] == 1){
								echo '90 Days';
							} elseif($details['goal_duration'] == 2){
								echo "6 Months";
							} else {
								echo "1 Year";
							}?>
								
						</td>
											
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
		

			

		


