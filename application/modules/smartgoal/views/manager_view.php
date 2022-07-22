<?php

$user_details = $this->session->userdata();

$employee_details = $this->db->get_where('users',array('id'=>$user_id))->row_array();
$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
$account_details = $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();
$team_lead = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array();
$teamlead = $this->db->get_where('account_details',array('user_id'=>$team_lead['user_id']))->row_array();
?>

<!-- Content -->
                <div class="content container-fluid smart-goal-view-admin">
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
								<!-- <div class="col-sm-8">
									<div class="join-year">
										<span>Year</span>
										<select class="select form-control">
											<option>2019</option>
										</select>
									</div>
								</div> -->
							</div>
							<div class="performance-wrap">
							<?php if(!empty($smartgoals)){ 
								$total_count = count($smartgoals)+1;
								$count =1 ;
								foreach ($smartgoals as $smartgoal) {
								$actions = unserialize($smartgoal['action']);	
								$result = unserialize($smartgoal['result']);	
								?>
								
								
								<?php if($count == 1){?>
								<div class="row">
								<div class="form-group col-sm-6">
									<label>Goal Duration</label>
									<div class="radio_input">
										<label class="radio-inline custom_radio">
											<input type="radio" readonly name="goal_duration" <?php echo ($smartgoal['goal_duration'] == 1)?"checked":"";?> value="1">90 Days <span class="checkmark"></span>
										</label>
										<label class="radio-inline custom_radio">
											<input type="radio" readonly name="goal_duration" <?php echo ($smartgoal['goal_duration'] == 2)?"checked":"";?> value="2">6 Month <span class="checkmark"></span>
										</label>
										<label class="radio-inline custom_radio">
											<input type="radio"readonly name="goal_duration" <?php echo ($smartgoal['goal_duration'] == 3)?"checked":"";?> value="3">1 Year <span class="checkmark"></span>
										</label> 
									</div>
								</div>
								<div class="form-group col-sm-6">
									<div class="join-year">
										<label class="m-r-10">Year</label>
										<select class="select form-control" name="goal_year">
											<?php for($k =$e_year;$k>=$s_year;$k--){ ?>
									<option value="<?php echo $k; ?>" <?php echo ($smartgoal['goal_year']==$k)?'selected':''; ?> ><?php echo $k; ?></option>
									<?php } ?>
										</select>
									</div>
								</div>
								</div>
							<?php }?>
								<div class="row">
									<div class="col-md-12">
								<div class="performance-box">
									<div class="table-responsive">
										<table class="table performance-table perform-align">
											<thead>
												<tr>
													<th class="smt-goal-width">Goal <?php echo $count;?></th>
													<th class="text-center status-smart-goal">Status</th>
													<th class="text-center smart-goal-start">Start</th>
													<th class="text-center smart-goal-completed" >Completed</th>
													<th class="text-center smart-goal-rating">Rating</th>
													<th class="text-center smart-goal-feedback" style="">Feedback</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<div class="" style="margin-bottom: 15px;">
															 
															<input type="hidden" name="id" class="form-control" value="<?php echo $smartgoal['id']?>">
															<input type="hidden" name="teamlead_id" class="form-control" value="<?php echo $employee_details['teamlead_id']?>">
															<input type="text" readonly name="goals" class="form-control" value="<?php echo $smartgoal['goals']?>">

														</div>
														<div class="progress m-b-0">
                                                        
                                                        
                                                        	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $smartgoal['progress'];?>%">
                                                             <span class=""><?php echo $smartgoal['progress'];?>%</span>

                                                        </div>
                                                    </div>
                                                         <input type="hidden" class="goal_progress" name="progress" value="<?php echo $smartgoal['progress'];?>">
													</td>
													<?php if($smartgoal['status'] == 0 ){

														$status="Pending";
														$btn="btn-warning";

													}elseif ($smartgoal['status'] == 1 ) {
															$status="Approved";
															$btn="btn-success";

													}elseif ($smartgoal['status'] == 2 ) {
															$status="Rejected";
															$btn="btn-danger";
													} ?>
													<td class="text-center">														
														<div class="dropdown">
															<a class="btn <?php echo $btn;?> dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" style="padding:10px;min-width:100px;">
																<?php echo $status;?> <i class="caret"></i>
															</a>
															<div class="dropdown-menu float-right">
																<a class="dropdown-item" href="<?=base_url()?>smartgoal/smartgoal_status/<?php echo $smartgoal['id'];?>/1/<?php echo $smartgoal['user_id']; ?>">Approved</a>
																<a class="dropdown-item" href="<?=base_url()?>smartgoal/smartgoal_status/<?php echo $smartgoal['id'];?>/2/<?php echo $smartgoal['user_id']; ?>">Rejected</a>
																<a class="dropdown-item" href="<?=base_url()?>smartgoal/smartgoal_status/<?php echo $smartgoal['id'];?>/0/<?php echo $smartgoal['user_id']; ?>">Pending</a>
															</div>
														</div>
														
													</td>
													<td class="text-center">
	                                                   
	                                                        <input type="text" class="form-control" disabled="disabled" name="create_date" id="created_date" value="<?php echo date('d/m/Y',strtotime($smartgoal['create_date']));?>" required>
	                                                    
	                                                </td>
	                                                <td class="text-center">
	                                                    
	                                                        <input type="text" class="form-control " name="completed_date" id="completed_date" value="<?php echo date('d/m/Y',strtotime($smartgoal['completed_date']));?>"  disabled="disabled" required>
	                                                    
	                                                </td>
													<?php $ratings = $this->db->get_where('smart_goal_configuration')->row_array() ; ?>
												
													<td>
														<select class="form-control select smartgoal_rating" name="rating" data-id="<?php echo $smartgoal['id'] ?>">
															<option value="">Select</option>
															<?php if(isset($ratings) && !empty($ratings)){ 
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
															  ?>
															<option value="<?php echo $rating_no[$i];?>" <?php echo ($smartgoal['rating'] == $rating_no[$i])?"selected":"";?>><?php echo $rating_no[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>
														</select>
													</td>
													<?php  $p_feedback=$this->db->where('goal_id',$smartgoal['id'])->get('smartgoal_feedback')->row_array();  ?>
													<td class="text-center">
														<button style="min-width:50px;padding:10px;font-size:16px;" class="<?php echo ($p_feedback !='')?'btn btn-success':'btn btn-white';?>" type="button" data-toggle="modal" data-target="#add_opj_feedback<?php echo $smartgoal['id'];?>"><i class="fa fa-commenting"></i></button>
													</td>
												</tr>
											</tbody>
											<tbody>
												<!-- <tr>
													<td colspan="6">
														<div class="add-another">
															<a href="javascript:void(0);" class="add_goal_action"><i class="fa fa-plus"></i> Actions</a>
														</div>
													</td>
												</tr> -->
												<tr>
													<td class="smt-table-task-td-goal">
														<!-- Goal Actions -->
														<!-- <div class="task-wrapper goal-wrapper">
															<div class="task-list-container">
																<div class="task-list-body">
																	<div class="label-input">
																		<label>Action</label>
																		<textarea name="action" readonly class="form-control" rows="4" cols="50"><?php echo $performance_360['action']?></textarea>
																	</div>
																	
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
                                                                                <span class="action-circle large complete-btn" title=" <?php echo ($result[$i] == 1)?'Completed':'In-Completed';?>">
                                                                                    <i class="material-icons">check</i>
                                                                                </span>
                                                                            </span>
                                                                            <input type="text" class="form-control" name="action[]" data-action="action_1" value="<?php echo $actions[$i]?>" readonly>

                                                                            
                                                                        </div>
                                                                    </li>
                                                                  <?php } ?>   
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

														<!-- /Goal Actions -->
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
								</div>
							

							<?php $count++; } ?>

								
							</div>
							
							<?php } ?>
						
							
							
							
						</div>
					</div>
                </div>
				<!-- / Content -->
				<div id="opj_feedback" class="modal fade" role="dialog">
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
									<div class="review-author">
										<img class="avatar" alt="User Image" src="assets/img/user.jpg">
									</div>
									<div class="review-block">
										<div class="review-by">
											<span class="review-author-name">Mark Boydston</span>
										</div>
										<p>With great power comes great capability</p>
										<span class="review-date">Feb 6, 2019</span>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- /View Feedback Modal -->
		 <?php $smartgoals = $this->db->select()
							->from('smartgoal')
							->get()->result_array();
					
			foreach ($smartgoals as $smartgoal) { ?>
				
		<!-- Add Feedback Modal -->
		<div id="add_opj_feedback<?php echo $smartgoal['id']?>" class="modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<form class="form-horizontal"  action="<?=base_url()?>smartgoal/smartgoal_feedback" method="POST">
					<div class="modal-body">
						<ul class="review-list">
						<?php  $feed_backs = $this->db->select()
							->from('smartgoal_feedback')
							->join('account_details','account_details.user_id = smartgoal_feedback.user_id')
							->where('smartgoal_feedback.goal_id',$smartgoal['id'])
							->get()->result_array();  
							if(!empty($feed_backs)){
							?>
						
							<?php foreach ($feed_backs as $feed_back) { ?>
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
							<?php }?>							
						<?php }?>
						</ul>
						<div class="form-group">
							<label>Write Feedback</label>
							<textarea rows="4" class="form-control" name="feed_back"></textarea>
							<input type="hidden" name="goal_id" value="<?php echo $smartgoal['id']?>">
							<input type="hidden" name="user_id" value="<?php echo $smartgoal['user_id']?>">
						</div>
						<div class="submit-section">
							<input type="submit" value="Submit" class="btn btn-primary submit-btn">
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
	<?php } ?>
		<!-- /Add Feedback Modal -->
		
		


		<div class="sidebar-overlay" data-reff="#sidebar"></div>


		