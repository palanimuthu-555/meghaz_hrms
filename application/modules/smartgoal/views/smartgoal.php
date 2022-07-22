<?php

// $user_details = $this->session->userdata();

$employee_details = $this->db->get_where('users',array('id'=>$user_id))->row_array();
$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
$account_details = $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();
$team_lead = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array();
$teamlead = $this->db->get_where('account_details',array('user_id'=>$team_lead['user_id']))->row_array();
?>

<!-- Content -->
                <div class="content container-fluid">
					<div class="row">
						<div class="col-sm-8">
							<h4 class="page-title">SMART Goals</h4>
							<!-- <ol class="breadcrumb page-breadcrumb">
								<li><a href="#">Offer Accepted</a></li>
								<li><a href="#">Completed Forms</a></li>
								<li class="active">360 Performance</li>
							</ol> -->
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-sm-4">
									<table class="table table-border user-info-table">
										<tbody>
											<tr>
												<td>Employee</td>
												<td class="text-right"><?php echo $account_details['fullname']?></td>
												
											</tr>
											<tr>
												<td>Position</td>
												<td class="text-right"><?php echo $designation['designation']?></td>	
												
											</tr>
											<tr>
												<td>Direct Manager</td>
												<td class="text-right"><?php echo $teamlead['fullname']?></td>
													
											</tr>											
										</tbody>
									</table>
								</div>
								<?php 
							$s_year = '2019';
							$select_y = date('Y');

							$s_month = date('m');
							$e_year = date('Y');
						 ?>
								
							</div>
							<div class="performance-wrap">
							<?php 
							
							if(!empty($smartgoal)){ 


								$total_count = count($smartgoal)+1;
								$count =1 ;
								foreach ($smartgoal as $smartgoals) {

									 
									 $p_feedback=$this->db->where('goal_id',$smartgoals['id'])->get('smartgoal_feedback')->row_array(); 

								$actions = unserialize($smartgoals['action']);
								$result = unserialize($smartgoals['result']);
								
								?>
								
								<form class="update_360"  action="<?php echo base_url()?>smartgoal/create_360" method="POST">
								<?php if($count == 1){?>
									<div class="row">
										<div class="form-group col-sm-6">
											<label>Goal Duration</label>
											<div class="radio_input">
												<label class="radio-inline custom_radio p-t-0">
													<input type="radio" name="goal_duration" <?php echo ($smartgoals['goal_duration'] == 1)?"checked":"";?> value="1">90 Days <span class="checkmark"></span>
												</label>
												<label class="radio-inline custom_radio p-t-0">
													<input type="radio" name="goal_duration" <?php echo ($smartgoals['goal_duration'] == 2)?"checked":"";?> value="2">6 Month <span class="checkmark"></span>
												</label>
												<label class="radio-inline custom_radio p-t-0">
													<input type="radio" name="goal_duration" <?php echo ($smartgoals['goal_duration'] == 3)?"checked":"";?> value="3">1 Year <span class="checkmark"></span>
												</label> 
											</div>
										</div>

										<div class="form-group col-sm-6 float-right">
											<div class="join-year">
												<label class="m-r-10">Year</label>
												<select class="select form-control" name="goal_year">
													<?php for($k =$e_year;$k>=$s_year;$k--){ ?>
											<option value="<?php echo $k; ?>" <?php echo ($smartgoals['goal_year']==$k)?'selected':''; ?> ><?php echo $k; ?></option>
											<?php } ?>
												</select>
											</div>
										</div>
									</div>

							<?php }?>
								<div class="row">
									<div class="col-md-12">
								<div class="performance-box">
									<a href="<?php echo base_url()?>smartgoal/delete_goal/<?php echo $smartgoals['id']; ?>" class="goal_remove goals_remove" title="Remove"><i class="fa fa-times"></i></a>
									<div class="table-responsive">
										<table class="table performance-table perform-align" style="table-layout:fixed;width:initial;">
											<thead>
												<tr>
													<th class="goalCount smt-goal-width" >Goal <?php echo $count;?></th>
													<th class="text-center" style="min-width:140px;width:140px;max-width:140px;">Status</th>
													<th class="text-center smart-goal-start">Start</th>
													<th class="text-center smart-goal-completed">Completed</th>
													<th class="text-center smart-goal-rating">Rating</th>
													<th class="text-center" style="width: 110px;max-width:110px;">Feedback</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<div class="" style="margin-bottom: 15px;">
															<input type="hidden" name="id" class="form-control" value="<?php echo $smartgoals['id']?>">
															<input type="hidden" name="teamlead_id" class="form-control" value="<?php echo $employee_details['teamlead_id']?>">
															<input type="text" name="goals" class="form-control" value="<?php echo $smartgoals['goals']?>" required>
														</div>
														<div class="progress m-b-0">
                                                        
                                                        
                                                        	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $smartgoals['progress'];?>%">
                                                            <span class="progress_per"><?php echo $smartgoals['progress'];?>%</span>

                                                        </div>
                                                         <input type="hidden" class="goal_progress" name="progress" value="<?php echo $smartgoals['progress'];?>">
                                                    </div>

													</td>
													<?php if($smartgoals['status'] == 0 ){

														$status="Pending";
														$btn="btn-warning";

													}elseif ($smartgoals['status'] == 1 ) {
															$status="Approved";
															$btn="btn-success";

													}elseif ($smartgoals['status'] == 2 ) {
															$status="Rejected";
															$btn="btn-danger";
													} ?>
													<td class="text-center">
														<input type="hidden" name="status" class="form-control" value="<?php echo $smartgoals['status'];?>">
														<button class="btn <?php echo $btn;?>" style="padding:10px;min-width:100px;"><?php echo $status;?></button>
													</td>
													<?php $ratings = $this->db->get_where('smart_goal_configuration')->row_array() ; ?>
													<td class="text-center">
	                                                    <div class="cal-icon">
	                                                        <input type="text" class="form-control datetimepicker" name="create_date" id="created_date" value="<?php echo date('d/m/Y',strtotime($smartgoals['create_date']));?>" required>
	                                                    </div>
	                                                </td>
	                                                <td class="text-center">
	                                                    <div class="cal-icon">
	                                                        <input type="text" class="form-control datetimepicker" name="completed_date" id="completed_date" value="<?php echo date('d/m/Y',strtotime($smartgoals['completed_date']));?>" required>
	                                                    </div>
	                                                </td>
													<td>
														<select class="form-control " name="rating" disabled="disabled">
															<option>Select</option>
															<?php if(isset($ratings) && !empty($ratings)){ 
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
															  ?>
															<option value="<?php echo $rating_no[$i];?>" <?php echo ($smartgoals['rating'] == $rating_no[$i])?"selected":"";?>><?php echo $rating_no[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>
														</select>
													</td>
													<td class="text-center">
														<button style="min-width:50px;padding:10px;font-size:16px;" class="btn <?php echo ($p_feedback !='')?'btn-success':'btn-white';?>" type="button" data-toggle="modal" data-target="#opj_feedback<?php echo $smartgoals['id'];?>"><i class="fa fa-commenting"></i></button>
													</td>
													<td>
														
													</td>
												</tr>
											</tbody>
											<tbody>
												<tr>
													<td colspan="6">
														<div class="add-another">
															<a href="javascript:void(0);" class="add_goal_action"><i class="fa fa-plus"></i> Actions</a>
														</div>
													</td>
												</tr>
												<tr>
													<td class="smt-table-task-td-goal">
														<!-- Goal Actions -->
														<!-- <div class="task-wrapper goal-wrapper">
															<div class="task-list-container">
																<div class="task-list-body">
																	<div class="label-input">
																		<label>Action</label>
																		<textarea name="action" class="form-control" rows="4" cols="50" required><?php echo $performance_360['action']?></textarea>
																	</div>
																	
																</div>
																
																<div class="m-t-30 text-center">
																	<button class="btn btn-primary" type="submit" id="create_offers_submit">Create 360 Performance</button>
																</div>
															</div>
														</div> -->

														<div class="task-wrapper goal-wrapper">
                                                        <div class="task-list-container">
                                                            <div class="task-list-body">
                                                                <ul class="task-list" id="tasklist">
                                                                	<?php for ($i = 0; $i < count($actions); $i++)  {
                                                					?>
                                                                    <li class="task <?php echo ($result[$i] == 1)?'completed':'';?>">
                                                                        <div class="task-container">
                                                                            <span class="task-action-btn task-check">
                                                                                <span class="action-circle large complete-btn" onclick="progress_smartgoal(this)" title="Mark Complete">
                                                                                    <i class="material-icons">check</i>
                                                                                </span>
                                                                            </span>
                                                                            <input type="text" class="form-control" name="action[]" data-action="action_1" value="<?php echo $actions[$i]?>">

                                                                             <input type="hidden" class="form-control result" name="result[]"  value="<?php echo $result[$i]?>">

                                                                            <span class="task-action-btn task-btn-right">
                                                                                <span class="action-circle large delete-btn" title="Delete Goal Action">
                                                                                    <i class="material-icons">delete</i>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                    </li>
                                                                  <?php } ?>   
                                                                </ul>
                                                            </div>
                                                            <div class="task-list-footer">
                                                                <div class="new-task-wrapper">
                                                                    <textarea class="add-new-goal" placeholder="Enter new goal action here. . ."></textarea>
                                                                    <span class="error-message hidden">You need to enter a goal action first</span>
                                                                    <span class="add-new-task-btn btn btn-success add_goal">Add Goal Action</span>
                                                                    <span class="cancel-btn btn close-goal-panel">Close</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="notification-popup hide">
                                                        <p>
                                                            <span class="task"></span>
                                                            <span class="notification-text"></span>
                                                        </p>
                                                    </div>

                                                    <div class="m-t-30 text-right">
																	<button class="btn btn-primary" type="submit" id="create_okr_submit">Update Goal</button>
																</div>
														<!-- /Goal Actions -->
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
								</form>
								
								
							
							

							<?php $count++; } ?>

								<div class="add-another-goal">
									<a href="javascript:void(0);" id="add_smart_another_goal" ><i class="fa fa-plus"></i> Add Another Goal</a>
									<input type="hidden" id="count" value="<?php echo $total_count; ?>">
									<input type="hidden" id="teamlead" value="<?php echo $employee_details['teamlead_id']?>">
								</div>
							</div>
							
							<?php }else {?>
							<div class="performance-wrap">
								<form class="perform_360"  action="<?=base_url()?>smartgoal/create_360" method="POST">
								<div class="row">
									<div class="form-group col-sm-6" >
										<label>Goal Duration</label>
										<div class="radio_input">
											<label class="radio-inline custom_radio p-t-0">
												<input type="radio" name="goal_duration" checked="" value="1">90 Days <span class="checkmark"></span>
											</label>
											<label class="radio-inline custom_radio p-t-0">
												<input type="radio" name="goal_duration" value="2">6 Month <span class="checkmark"></span>
											</label>
											<label class="radio-inline custom_radio p-t-0">
												<input type="radio" name="goal_duration" value="3">1 Year <span class="checkmark"></span>
											</label> 
										</div>
									</div>
									<div class="form-group col-sm-6 float-right">
										<div class="join-year">
											<label class="m-r-10">Year</label>
											<select class="select form-control" name="goal_year">
												<?php for($k =$e_year;$k>=$s_year;$k--){ ?>
										<option value="<?php echo $k; ?>" <?php echo ($e_year==$k)?'selected':''; ?> ><?php echo $k; ?></option>
										<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
								<div class="performance-box">
									<div class="table-responsive">
										<table class="table performance-table perform-align" style="table-layout: fixed;width:initial;">
											<thead>
												<tr>
													<th class="goalCount smt-goal-width">Goal 1</th>
													<th class="text-center" style="min-width: 140px;width:140px;max-width:140px;">Status</th>
													<th class="text-center smart-goal-start">Start</th>
													<th class="text-center smart-goal-completed">Completed</th>
													<th class="text-center smart-goal-rating">Rating</th>
													<th class="text-center" style="min-width: 110px;width:110px;max-width:110px;">Feedback</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<div class="" style="margin-bottom: 15px;">
															<input type="text" name="goals" class="form-control" value="" required>
															<input type="hidden" name="teamlead_id" class="form-control" value="<?php echo $employee_details['teamlead_id']?>">
														</div>

														<div class="progress m-b-0">
                                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="progress_percentage" data-progress="progress_1" value=""></span>

                                                        </div>
                                                         <input type="hidden" class="goal_progress" name="progress" value="">
                                                    </div>
													</td>
													<td class="text-center">
														<input type="hidden" name="status" class="form-control" value="">
														<button class="btn btn-warning" style="padding:10px;min-width:100px;">Pending</button>
													</td>
													<td class="text-center">
	                                                    <div class="cal-icon">
	                                                        <input type="text" class="form-control datetimepicker" name="create_date" id="created_date" required>
	                                                    </div>
	                                                </td>
	                                                <td class="text-center">
	                                                    <div class="cal-icon">
	                                                        <input type="text" class="form-control datetimepicker" name="completed_date" id="completed_date" required>
	                                                    </div>
	                                                </td>
													<td>
														<select class="form-control " name="rating" disabled="disabled">
															<option value="" selected="selected">Select</option>
															<?php if(isset($ratings) && !empty($ratings)){ 
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
															  ?>
															<option value="<?php echo $rating_no[$i];?>"><?php echo $rating_no[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>
														</select>
													</td>
													<td class="text-center">
														<button style="min-width:50px;padding:10px;font-size:16px;" class="btn btn-white" type="button" data-toggle="modal" data-target="#opj_feedback"><i class="fa fa-commenting"></i></button>
													</td>
												</tr>
											</tbody>
											<tbody>
												<tr>
													<td colspan="6">
														<div class="add-another">
															<a href="javascript:void(0);" class="add_goal_action"><i class="fa fa-plus"></i> Actions</a>
														</div>
													</td>
												</tr>
												<tr>
													<td class="smt-table-task-td-goal">
														<!-- Goal Actions -->
														<!-- <div class="task-wrapper goal-wrapper">
															<div class="task-list-container">
																<div class="task-list-body">
																	<div class="label-input">
																		<label>Action</label>
																		<textarea name="action" class="form-control" rows="4" cols="50" required></textarea>
																	</div>
																	
																</div>
																
																<div class="m-t-30 text-center">
																	<button class="btn btn-primary" type="submit" id="create_offers_submit">Create 360 Performance</button>
																</div>
															</div>
														</div> -->

														 <div class="task-wrapper goal-wrapper">
                                                        <div class="task-list-container">
                                                            <div class="task-list-body">
                                                                <ul class="task-list" id="tasklist">
                                                                    <li class="task">
                                                                        <div class="task-container">
                                                                            <span class="task-action-btn task-check">
                                                                                <span class="action-circle large complete-btn" onclick="progress_smartgoal(this)" title="Mark Complete">
                                                                                    <i class="material-icons">check</i>
                                                                                </span>
                                                                            </span>
                                                                            <input type="text" class="form-control" name="action[]" data-action="action_1"placeholder="Goal Action 1">
                                                                             <input type="hidden" class="form-control result" name="result[]"  value="">

                                                                            <span class="task-action-btn task-btn-right">
                                                                                <span class="action-circle large delete-btn" title="Delete Goal Action">
                                                                                    <i class="material-icons">delete</i>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                    </li>
                                                                    <li class="task">
                                                                        <div class="task-container">
                                                                            <span class="task-action-btn task-check">
                                                                                <span class="action-circle large complete-btn" onclick="progress_smartgoal(this)" title="Mark Complete">
                                                                                    <i class="material-icons">check</i>
                                                                                </span>
                                                                            </span>
                                                                              <input type="text" class="form-control" name="action[]" data-action="action_1" placeholder="Goal Action 2">
                                                                              <input type="hidden" class="form-control result" name="result[]"  value="">
                                                                              
                                                                            <span class="task-action-btn task-btn-right">
                                                                                <span class="action-circle large delete-btn" title="Delete Goal Action">
                                                                                    <i class="material-icons">delete</i>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="task-list-footer">
                                                                <div class="new-task-wrapper">
                                                                    <textarea class="add-new-goal" placeholder="Enter new goal action here. . ."></textarea>
                                                                    <span class="error-message hidden">You need to enter a goal action first</span>
                                                                    <span class="add-new-task-btn btn add_goal">Add Goal Action</span>
                                                                    <span class="cancel-btn btn close-goal-panel">Close</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="notification-popup hide">
                                                        <p>
                                                            <span class="task"></span>
                                                            <span class="notification-text"></span>
                                                        </p>
                                                    </div>

                                                    <div class="m-t-30 text-right">
																	<button class="btn btn-primary" type="submit" id="create_offers_submit">Create Goal</button>
																</div>
														<!-- /Goal Actions -->
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
								</form>
								<div class="add-another-goal">
									<a href="javascript:void(0);" id="add_smart_another_goal" ><i class="fa fa-plus"></i> Add Another Goal</a>
									<input type="hidden" id="count" value="2">
								</div>
								
							
							</div>
						<?php } ?>
						
							
							
							
						</div>
					</div>
                </div>
				<!-- / Content -->
				<?php $smartgoal = $this->db->select()
							->from('smartgoal')
							->get()->result_array();
					
			foreach ($smartgoal as $smartgoals) { ?>
				<div id="opj_feedback<?php echo $smartgoals['id']?>" class="modal center-modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<div class="modal-body">
						
						<ul class="review-list">
							<?php $feed_backs = $this->db->select()
							->from('smartgoal_feedback')
							->join('account_details','account_details.user_id = smartgoal_feedback.user_id')
							->where('goal_id',$smartgoals['id'])
							->get()->result_array();  


						
							if(!empty($feed_backs)){
							$count = count($feed_backs);
							 foreach ($feed_backs as $feed_back) { ?>
								
											
							<li>
								<div class="review">
									<div class="review-author">
										<?php if(!empty($feed_back['avatar'])) {?>
											<img class="avatar" alt="User Image" src="<?=base_url()?>assets/avatar/<?php echo $feed_back['avatar'];?>">
										<?php } else {?>
										<img class="avatar" alt="User Image" src="assets/img/user.jpg">
									<?php }?>
									</div>
									<div class="review-block">
										<div class="review-by">
											<span class="review-author-name"><?php echo $feed_back['fullname'];?></span>
										</div>
										<p><?php echo $feed_back['feed_back'];?></p>
										<span class="review-date"><?php echo date('M j, Y',strtotime($feed_back['created_date']));?></span>
									</div>
								</div>
							</li>
							<hr>	
						<?php } } else {?>
							<li>
								<div class="review">
									
									<div class="review-block">										
										<p>No review Found</p>
									</div>
								</div>
							</li>
						<?php }?>

						</ul>

					</div>
				</div>
			</div>
		</div>
	<?php } ?>
		<!-- /View Feedback Modal -->

		
		
		<!-- Add Feedback Modal -->
		<div id="opj_feedback" class="modal center-modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<div class="modal-body">
						<ul class="review-list">
							<li>
								<div class="review">
									
									<div class="review-block">										
										<p>No review Found</p>
									</div>
								</div>
							</li>
						

						</ul>
						<!-- <div class="submit-section">
							<input type="submit" value="Submit" class="btn btn-primary submit-btn">
						</div> -->
					</div>
				</div>
			</div>
		</div>
		<!-- /Add Feedback Modal -->
		


		<div class="sidebar-overlay" data-reff="#sidebar"></div>


		<script type="text/javascript">
			// var ratings = <?php echo $ratings; ?>;
			 var ratings_value = new Array();
			 var definition_value = new Array();
			// var rating_value = <?php echo $rating_value; ?>;
   //          var definition = '<?php echo $definition; ?>';

    	
    <?php foreach($rating_no as $val){ ?>
        ratings_value.push('<?php echo $val; ?>');
    <?php } ?>
    <?php foreach($rating_value as $val){ ?>
        definition_value.push('<?php echo $val; ?>');
    <?php } ?>
    	console.log(ratings_value);
    	console.log(definition_value);
		</script>


		 <script id="goal-template">
            <li class="task">
                <div class="task-container">
                    <span class="task-action-btn task-check">
                        <span class="action-circle large complete-btn" onclick="progress_smartgoal(this)" title="Mark Complete">
                            <i class="material-icons">check</i>
                        </span>
                    </span>
                    <input type="text" class="task-label form-control"> 
                    <input type="hidden" name="result[]" class="form-control result" value=""> 
                 
                    <span class="task-action-btn task-btn-right">
                        <span class="action-circle large delete-btn" title="Delete Goal Action">
                            <i class="material-icons">delete</i>
                        </span>
                    </span>
                </div>
            </li>
        </script>
       <!--  <script type="text/javascript">
			
			$(document).ready(function() {
			  $("#datetimepicker").datepicker();
			  $('.cal-icon::after').click(function() {
			    $("#datetimepicker").focus();
			  });
			});
			                
		</script> -->