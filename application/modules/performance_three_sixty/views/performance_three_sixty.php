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
							<h4 class="page-title">Competency Performance</h4>
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
							
							if(!empty($performances_360)){ 


								$total_count = count($performances_360)+1;
								$count =1 ;
								foreach ($performances_360 as $performance_360) {

									 
									 $p_feedback=$this->db->where('goal_id',$performance_360['id'])->get('three_sixty_feedback')->row_array(); 

								$actions = unserialize($performance_360['action']);
								$result = unserialize($performance_360['result']);
								
								?>
								
								<form class="update_360"  action="<?=base_url()?>performance_three_sixty/create_360" method="POST">
								<?php if($count == 1){?>
									<div class="row">
										<div class="form-group col-sm-6">
											<label>Goal Duration</label>
											<div class="radio_input">
												<label class="radio-inline custom_radio p-t-0">
													<input type="radio" name="goal_duration" <?php echo ($performance_360['goal_duration'] == 1)?"checked":"";?> value="1">90 Days <span class="checkmark"></span>
												</label>
												<label class="radio-inline custom_radio p-t-0">
													<input type="radio" name="goal_duration" <?php echo ($performance_360['goal_duration'] == 2)?"checked":"";?> value="2">6 Month <span class="checkmark"></span>
												</label>
												<label class="radio-inline custom_radio p-t-0">
													<input type="radio" name="goal_duration" <?php echo ($performance_360['goal_duration'] == 3)?"checked":"";?> value="3">1 Year <span class="checkmark"></span>
												</label> 
											</div>
										</div>

										<div class="form-group col-sm-6">
											<div class="join-year">
												<label class="m-r-10">Year</label>
												<select class="select form-control" name="goal_year">
													<?php for($k =$e_year;$k>=$s_year;$k--){ ?>
											<option value="<?php echo $k; ?>" <?php echo ($performance_360['goal_year']==$k)?'selected':''; ?> ><?php echo $k; ?></option>
											<?php } ?>
												</select>
											</div>
										</div>
									</div>

							<?php }?>

								<div class="performance-box">
									<a href="<?=base_url()?>performance_three_sixty/delete_goal/<?php echo $performance_360['id']; ?>" class="goal_remove goals_remove" title="Remove"><i class="fa fa-times"></i></a>
									<div class="table-responsive">
										<table class="table performance-table  perform-align">
											<thead>
												<tr>
													<th class="goalCount com-goal-width">Goal <?php echo $count;?></th>
													<th class="text-center" style="min-width:140px;width:140px;max-width:140px;">Status</th>
													<th class="text-center com-self-rating">Self Rating</th>
													<th class="text-center com-rating">Rating</th>
													<th class="text-center" style="min-width:110px;width: 110px;max-width:110px;">Feedback</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<div class="goal-form-width">
															<input type="hidden" name="id" class="form-control" value="<?php echo $performance_360['id']?>">
															<input type="hidden" name="teamlead_id" class="form-control" value="<?php echo $employee_details['teamlead_id']?>">
															<input type="text" name="goals" class="form-control" value="<?php echo $performance_360['goals']?>" required>
														</div>
														<div class="progress m-b-0 goal-progress-wid">
                                                        	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $performance_360['progress'];?>%">
                                                            <span class="progress_per"><?php echo $performance_360['progress'];?>%</span>

                                                        </div>
                                                         <input type="hidden" class="goal_progress" name="progress" value="<?php echo $performance_360['progress'];?>">
                                                    </div>

													</td>
													<?php if($performance_360['status'] == 0 ){

														$status="Pending";
														$btn="btn-warning";

													}elseif ($performance_360['status'] == 1 ) {
															$status="Approved";
															$btn="btn-success";

													}elseif ($performance_360['status'] == 2 ) {
															$status="Rejected";
															$btn="btn-danger";
													} ?>
													<td class="text-center">
														<input type="hidden" name="status" class="form-control" value="<?php echo $performance_360['status'];?>">
														<button class="btn <?php echo $btn;?>" style="min-width:100px;padding:10px;"><?php echo$status;?></button>
													</td>
													<?php $ratings = $this->db->get_where('competency_ratings')->row_array() ; ?>
													<td>
														<select class="form-control " name="self_rating" >
															<option value="">Select</option>
															<?php if(isset($ratings) && !empty($ratings)){ 
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_value[$i])){
															  ?>
															<option value="<?php echo $rating_no[$i];?>" <?php echo ($performance_360['self_rating'] == $rating_no[$i])?"selected":"";?>><?php echo $rating_no[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>
														</select>
													</td>
													<td>
														<select class="form-control " name="rating" disabled="disabled">
															<option value="">Select</option>
															<?php if(isset($ratings) && !empty($ratings)){ 
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
															  ?>
															<option value="<?php echo $rating_no[$i];?>" <?php echo ($performance_360['rating'] == $rating_no[$i])?"selected":"";?>><?php echo $rating_no[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>
														</select>
													</td>
													<td class="text-center">
														<button style="min-width:50px;padding:10px;font-size:16px;" class="btn  <?php echo ($p_feedback !='')?'btn-success':'btn-white';?>" type="button" data-toggle="modal" data-target="#opj_feedback<?php echo $performance_360['id'];?>"><i class="fa fa-commenting"></i></button>
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
													<td class="table-task-td-goal">
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

														<div class="task-wrapper goal-wrapper com-task-wrap">
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
															<button class="btn btn-primary" type="submit" id="create_okr_submit">Update 360 Performance</button>
														</div>
														<!-- /Goal Actions -->
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								</form>
								
								
							
							

							<?php $count++; } ?>

								<div class="add-another-goal">
									<a class=" m-b-0" href="javascript:void(0);" id="add_another_goal" ><i class="fa fa-plus"></i> Add Another Goal</a>
									<input type="hidden" id="count" value="<?php echo $total_count; ?>">
									<input type="hidden" id="teamlead" value="<?php echo $employee_details['teamlead_id']?>">
								</div>
							</div>
							
							<?php }else {?>
							<div class="performance-wrap">
								<form class="perform_360"  action="<?=base_url()?>performance_three_sixty/create_360" method="POST">
								<!-- <div class="row" style="margin-left: 0px; margin-right: -64px;"> -->
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
									<div class="form-group col-sm-6">
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

								<div class="performance-box">
									<div class="table-responsive">
										<table class="table performance-table perform-align" style="table-layout: fixed;width:initial;">
											<thead>
												<tr>
													<th class="goalCount com-goal-width">Goal 1</th>
													<th class="text-center" style="min-width: 140px;width:140px;max-width:140px;">Status</th>
													<th class="text-center com-self-rating">Self Rating</th>
													<th class="text-center com-rating">Rating</th>
													<th class="text-center" style="width: 110px;max-width:110px;">Feedback</th>
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
													<td>
														<?php $ratings = $this->db->get_where('competency_ratings')->row_array() ; ?>
														<select class="form-control " name="self_rating" >
															<option value="">Select</option>
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
													<td>
														<select class="form-control " name="rating" disabled="disabled">
															<option value="">Select</option>
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
													<td class="table-task-td-goal1">
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

                                                    <div class="m-t-20 text-right">
																	<button class="btn btn-primary" type="submit" id="create_offers_submit">Create 360 Performance</button>
																</div>
														<!-- /Goal Actions -->
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								</form>
								<div class="add-another-goal">
									<a href="javascript:void(0);" id="add_another_goal" ><i class="fa fa-plus"></i> Add Another Goal</a>
									<input type="hidden" id="count" value="2">
								</div>
								
							
							</div>
						<?php } ?>
						
							
							<div class="performance-wrap">
								<h3 class="m-b-20">Competencies</h3>
								<div class="performance-box comp-box m-b-0">
									
										<form class="form-horizontal" id="create_offers" action="<?=base_url()?>performance_three_sixty/create_competencies" method="POST">
											<div class="table-responsive">
										<table class="table performance-table" style="width:100%;">
											<thead>
												<tr>
													<th class="com-goal-width1">Competencies</th>
													<th class="text-center com-self-rating">Self Rating</th>
													<th class="text-center com-rating">Rating</th>
													<th class="text-center" style="min-width: 110px;width:110px;">Feedback</th>
													<th class="text-center" style="min-width: 90px;"></th>
												</tr>
											</thead>

											<?php if(!empty($competencies)){
												$competence_id = array();
												
											foreach ($competencies as $competence) { 

												$competence_id[] = $competence['competencies'];
												
											}

											
										}
										?>
											
											<?php $performance_competency = $this->db->order_by('competency','ASC')->get('performance_competency')->result_array(); 
											// echo "<pre>"; print_r($performance_competency); exit;
											?>
											<tbody>
												<?php if(! empty($performance_competency)){ 
													$c_feed_backs = '';
																foreach ($performance_competency as $performance_competencies) {

																	
																	?>
												<tr>
													<td>
														<input type="text" class="form-control" name="competencies[]" value="<?php echo $performance_competencies['competency'];?>" required="" readonly>
														<input type="hidden" class="form-control" name="teamlead_id[]" value="<?php echo $employee_details['teamlead_id']?>" id="teamlead_id">
													</td>
													<td>
														<?php
														
														?>
														<select class="form-control select employee_competence_rating" name="self_rating[]" data-id="<?php echo $performance_competencies['id'] ?>">
															<option value="">Not rated</option>
															<?php $ratings = $this->db->get_where('competency_ratings')->row_array() ; ?>
															<?php if(isset($ratings) && !empty($ratings)){ 
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															$selected='';
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
																	
																	
														             	$self_rating_id=$this->db->where('competencies',$performance_competencies['id'])->where('user_id',$employee_details['id'])->get('dgt_competencies')->row_array();
														             	
														             	if(!empty($self_rating_id['self_rating'])){
														             		if($rating_no[$i]==$self_rating_id['self_rating'])
																			{
																				$selected='selected';
																			}
																			else
																			{
																				$selected='';
																			}
														             	} else{
														             		$selected='';
														             	}
																		
																   
																	
															  ?>
															<option value="<?php echo $rating_no[$i];?>" <?php echo $selected;?>><?php echo $rating_no[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>

														</select>
														
													</td>
													<td>
														<select class="form-control select" name="rating[]" disabled="disabled">
															<option value="">Not rated</option>
															<?php if(isset($ratings) && !empty($ratings)){ 
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															$selected='';
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
																	$rating_id=$this->db->where('competencies',$performance_competencies['id'])->where('user_id',$employee_details['id'])->get('dgt_competencies')->row_array();
														             	
														             	if(!empty($rating_id['rating'])){
														             		if($rating_no[$i]==$rating_id['rating'])
																			{
																				$selected='selected';
																			}
																			else
																			{
																				$selected='';
																			}
														             	} else{
														             		$selected='';
														             	}
															  ?>
															<option value="<?php echo $rating_no[$i];?>" <?php echo $selected;?>><?php echo $rating_no[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>

														</select>
													</td>
													<?php $feedback=$this->db->where('competencies',$performance_competencies['id'])->get('dgt_competencies')->row_array(); 
														$c_feed_backs = $this->db->where('competencies_id',$feedback['id'])->where('feedback_to',$user_id)->get('competencies_feedback')->num_rows(); 														 
													?>
													<td class="text-center">
														<button style="min-width:50px;padding:10px;font-size:16px;" class="btn <?php echo ($c_feed_backs !='')?'btn-success':'btn-white';?>" type="button" data-toggle="modal" data-target="#opj_comp_feedback<?php echo $feedback['id'];?>"><i class="fa fa-commenting"></i></button>
													</td>
													<!-- <td>
														<button type="button" class="btn btn-white add_competency" data-toggle="tooltip" data-original-title="Add Competency"><i class="fa fa-plus-circle"></i></button>
													</td> -->
												</tr>
											<?php } }  ?>
											</tbody>
										</table>
									
										<!-- <div class="m-t-30 text-center">
									<button class="btn btn-primary" type="submit" id="create_offers_submit">Create Competencies</button>
								</div> -->
									</form>
									</div>
								</div>
							</div>
							
						</div>
					</div>
                </div>
				<!-- / Content -->
				<?php $performances_360 = $this->db->select()
							->from('performance_360')
							->get()->result_array();
					
			foreach ($performances_360 as $performance_360) { ?>
				<div id="opj_feedback<?php echo $performance_360['id']?>" class="modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<div class="modal-body">
						
						<ul class="review-list">
							<?php $feed_backs = $this->db->select()
							->from('three_sixty_feedback')
							->join('account_details','account_details.user_id = three_sixty_feedback.user_id')
							->where('goal_id',$performance_360['id'])
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

		<!-- Competencies feedback Modal -->

		<?php $competencies = $this->db->select()
							->from('competencies')
							->get()->result_array();
							// echo $competencies; exit
					if(!empty($competencies)){
			foreach ($competencies as $competence) { ?>
				<div id="opj_comp_feedback<?php echo $competence['id']?>" class="modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<div class="modal-body">
						
						<ul class="review-list">
							<?php $feed_backs = $this->db->select()
							->from('competencies_feedback')							
							->join('account_details','account_details.user_id = competencies_feedback.user_id')
							->where('competencies_feedback.competencies_id',$competence['id'])
							->where('competencies_feedback.feedback_to',$user_id)
							->get()->result_array();  
							// echo ($this->db->last_query());
													
							if(!empty($feed_backs)){
							
							 foreach ($feed_backs as $feed_back) { ?>
								
											
							<li class="res-activity-list">
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
	<?php }
} ?>
		<!-- Competencies feedback Modal End -->
		
		<!-- Add Feedback Modal -->
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


		 <div id="goal-template">
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
        </div>