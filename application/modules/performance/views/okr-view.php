<?php

$user_details = $this->session->userdata();

$employee_details = $this->db->get_where('users',array('id'=>$user_details['user_id']))->row_array();
$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
$account_details = $this->db->get_where('account_details',array('user_id'=>$user_details['user_id']))->row_array();
$team_lead = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array();

$teamlead = $this->db->get_where('account_details',array('user_id'=>$team_lead['user_id']))->row_array();
$okr_id = $this->session->userdata('user_id');
$okrdetails = $this->db->get_where('okrdetails',array('user_id'=>$okr_id))->row_array();
?>
          
                <div class="content container-fluid">
					<div class="row">
						<div class="col-sm-8">
							<h4 class="page-title">OKR Performance</h4>
							<!-- <ol class="breadcrumb page-breadcrumb">
								<li><a href="#">Offer Accepted</a></li>
								<li><a href="#">Completed Forms</a></li>
								<li class="active">Set Your Goal</li>
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
							
								
							</div>
							<?php if(!empty($okrdetails)){?>
							<div class="row">		
							<div class="form-group col-md-6">
								<label>Goal Duration</label>
								<div class="radio_input">
									<label class="radio-inline custom_radio">
										
									<input type="radio" name="goal_duration" <?php echo ($okrdetails['goal_duration'] =='90 days')? 'checked':'' ?> value="90 days">90 days<span class="checkmark"  ></span>
									</label>
									<label class="radio-inline custom_radio">
										
										<input type="radio" name="goal_duration"  <?php echo ($okrdetails['goal_duration'] =='6 month')? 'checked':'' ?>  value="6 month">6 Month<span class="checkmark"></span>
									</label>
									<label class="radio-inline custom_radio">
										
										<input type="radio" name="goal_duration" <?php echo ($okrdetails['goal_duration'] =='1 year')? 'checked':'' ?> value="1 year">1 year<span class="checkmark" ></span>
									</label> 
								</div>
							</div>
							

								<div class="form-group col-md-6">									
									<div class="join-year">
										<span class="m-r-10">Year</span>
										<select class="select form-control" name="goal_year">
											<option value="2019" <?php if($okrdetails['goal_year'] == "2019") echo 'selected';?>>2019</option>
											<option value="2020" <?php if($okrdetails['goal_year'] == "2020") echo 'selected';?>>2020</option>
											<option value="2021" <?php if($okrdetails['goal_year'] == "2021") echo 'selected';?>>2021</option>
											<option value="2022" <?php if($okrdetails['goal_year'] == "2022") echo 'selected';?>>2022</option>
											<option value="2023" <?php if($okrdetails['goal_year'] == "2023") echo 'selected';?>>2023</option>
											<option value="2024" <?php if($okrdetails['goal_year'] == "2024") echo 'selected';?>>2024</option>
										</select>
									</div>
								</div>
								</div>		
							<?php 
							}
							$okr_objectives = $this->db->get_where('okr_key_results',array('okrdetailsid'=>$okrdetails['id']))->result_array();
							// echo "<pre>";
							// print_r($okr_objectives);
							if(!empty($okr_objectives)){ 


								$total_count = count($okr_objectives)+1;
								$count =1 ;
								foreach ($okr_objectives as $okr_objective) {

									 $okr_results = $this->db->get_where('okr_results',array('objective_id'=>$okr_objective['id']))->result_array();
									 $object_feedback = $this->db->where('okr_objective_id',$okr_objective['id'])->get('okr_feedback')->row_array(); 

								// $actions = unserialize($performance_360['action']);
								
								?>
							<form action="<?php echo base_url()?>performance/add_goals" method="post">
								
							<input type="hidden" class="grade_val" name="objective_id" value="<?php echo $okr_objective['id']?>">
							<input type="hidden" class="grade_val" name="okrdetailsid" value="<?php echo $okrdetails['id']?>">
							<div class="performance-wrap">
								<div class="performance-box">
									<div class="table-responsive">
										<table class="table performance-table">
											<thead>
												<tr>
													<th class="okr-show-obj">Objective/Key Result</th>
													<th class="text-center okr-status-wid">Status</th>
													<th class="text-center okr-pgss" style="">Progress</th>
													<th class="text-center okr-gradig" style="">Grading</th>
													<th class="text-center okr-feedack" style="">Feedback</th>
													
												</tr>
											</thead>
											<tbody>
												<?php 
												// $objective = json_decode($okrdetails['objective']);
												
												if($okr_objective['okr_status'] == "Approved")  
												{
													$btn_style = 'btn-success';
												}
												elseif($okr_objective['okr_status'] == "Pending")
												{
													$btn_style = 'btn-info';
												} else if($okr_objective['okr_status'] == "Rejected"){
													$btn_style = 'btn-danger';
												}?>
												<tr>
												<td>
													<div class="label-input">
														<label class="goalCount">Objective <?php echo $count;?></label>
														<input type="text" class="form-control" name="objective" value="<?php echo $okr_objective['objective'];?>">
													</div>
													</td>
													<td class="text-center">
													 <button style="min-width:100px; padding:10px;" class="btn <?=$btn_style?>" name="status"><?php echo $okr_objective['okr_status']; ?></button>
													 <input type="hidden" class="okr_status" name="okr_status" value="<?php echo $okr_objective['okr_status']; ?>">
													</td>
													<td class="text-center">
														<!-- <button class="btn btn-warning demo" type="button" id="demo" 

														data-toggle="modal" data-target="#progress_bar" name="progress">
															

														</button> -->

														<button style="min-width:70px;padding:10px;" class="btn btn-warning " type="button" 
														 name="progress[]" data-title="Objective Progress" data-value="<?php echo ($okr_objective['progress_value'] != '')?$okr_objective['progress_value']:"0";?>" onclick="show_progress_bar(this)"><?php echo ($okr_objective['progress_value'] != '')?$okr_objective['progress_value']:"0";?>%
															
															
														</button>

														<input type="hidden" class="progress_value" name="progress_value" id="progress_value" value="<?php echo $okr_objective['progress_value'];?>">
													</td>
													 <td class=""> 
														<!-- <strong class="grade" name="grade"></strong>
														<input type="hidden" class="grade_val" name="grade_value[]" value=""> -->
														 <select class="form-control select" name="grade_value" disabled="disabled">
															<?php $ratings = $this->db->get_where('okr_ratings')->row_array() ; ?>
															<?php if(isset($ratings) && !empty($ratings)){ 
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
															  ?>
															<option value="<?php echo $rating_no[$i];?>" <?php echo ($okr_objective['grade_value'] == $rating_no[$i])?"selected":"";?>><?php echo $rating_no[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>

														</select>
													</td> 
													<td class="text-center">
														<button style="min-width:50px;padding:10px;font-size:16px;" class="btn <?php echo ($object_feedback['feed_back'] !='')?'btn-success':'btn-white';?>" type="button" data-toggle="modal" data-target="#opj_feedback<?php echo $okr_objective['id'];?>"><i class="fa fa-commenting"></i></button>
													</td>
													
												</tr>
											</tbody>
											<tbody class="key_result_container">
												<?php

									 $okr_results = $this->db->get_where('okr_results',array('objective_id'=>$okr_objective['id']))->result_array();
												 if(!empty($okr_results)){?> 
												 	<input type="hidden" class="keyres_value" name="okr_result_count" value="<?php echo count($okr_results);?>">

								<?php $total_result_count = count($okr_results)+1;
								$result_count =1 ;
								foreach ($okr_results as $okr_result) {

									 $result_feedback = $this->db->where('okr_result_id',$okr_result['id'])->get('okr_result_feedback')->row_array(); 

								
												if($okr_result['key_status'] == "Approved")  
												{
													$btn_pending = 'btn-success';
												}
												elseif($okr_result['key_status'] == "Pending")
												{
													$btn_pending = 'btn-info';
												} elseif($okr_result['key_status'] == "Rejected"){
													$btn_pending = 'btn-danger';
												}
												?>	
												<input type="hidden" class="keyres_value" name="okr_result_id[]" value="<?php echo $okr_result['id'];?>">
												
												<tr>
													<td>
														<div class="label-input">
															<label>Key Result <?php echo $result_count;?></label>
															<input type="text" class="form-control" name="key_result[]" value="<?php echo $okr_result['key_result'];?>">
															<?php 
															 if($result_count == count($okr_results)){?>
															 	<button type="button" class="btn btn-white add_key_result" data-arrayval="<?php echo count($okr_results);?>" data-toggle="tooltip" data-original-title="Add Key Result"><i class="fa fa-plus-circle"></i></button>
															 <?php } ?>
															
															<!-- <button type="button" class="btn btn-white add_key_result" data-toggle="tooltip" data-arrayval="<?php echo $result_count-1;?>" data-original-title="Add Key Result"><i class="fa fa-plus-circle"></i></button> -->

														</div>
													</td>
													<td class="text-center">
														<button style="min-width:100px;padding:10px;" class="btn <?=$btn_pending?>"><?php echo $okr_result['key_status'];?></button>
														<input type="hidden" class="key_status" name="key_status[]" value="<?php echo $okr_result['key_status'];?>">
													</td>
													<td class="text-center">
														<!-- <button class="btn btn-success keyres_progress" type="button" data-toggle="modal" data-target="#key_progress"></button> -->


													<button style="min-width:70px;padding:10px;" class="btn btn-warning" type="button" name="key_progress[]" data-title="Key Result Progress" data-value="<?php echo ($okr_result['keyprog_value'] != '')?$okr_result['keyprog_value']:"0";?>" onclick="show_keyprogress_bar(this)"><?php if($okr_result['keyprog_value'] != '') { ?><?php echo $okr_result['keyprog_value']?>%<?php } else {?>0%<?php } ?>
													</button>
													<input type="hidden" class="keyres_value" name="keyres_value[]" value="<?php echo $okr_result['keyprog_value']?>">
													</td>
													<td class=""> 
														<!-- <strong class="key_grade" name="key_grade"></strong>
														<input type="hidden" class="key_gradeval" name="key_gradeval[]" value=""> -->
														 <select class="form-control select" name="key_gradeval[]" disabled="disabled">
															<?php $ratings = $this->db->get_where('okr_ratings')->row_array() ; ?>
															<?php if(isset($ratings) && !empty($ratings)){ 
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
															  ?>
															<option value="<?php echo $rating_no[$i];?>"<?php echo ($okr_result['key_gradeval'] == $rating_no[$i])?"selected":"";?>><?php echo $rating_no[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>

														</select>
													</td> 
													<td class="text-center">
														<button style="min-width:50px;padding:10px;font-size:16px;" class="btn <?php echo ($result_feedback['feed_back'] !='')?'btn-success':'btn-white';?>" type="button" data-toggle="modal" data-target="#result_feedback<?php echo $okr_result['id'];?>"><i class="fa fa-commenting"></i></button>
														
													</td>

													
												</tr>
												
											<?php $result_count++; }


										}?>
												
											</tbody>
										</table>
										<div class="m-t-20 m-b-10 text-center">
											<button class="btn btn-primary" type="submit" id="create_offers_submit">Update OKR Performance</button>
										</div>
									</div>
								</div>
								</div>
							    </form>
							    <input type="hidden" name="" id="key_result_<?php echo count($okr_results);?>" value="<?php echo $total_result_count;?>">
								<input type="hidden" name="" id="def_result_count" value="<?php echo count($okr_results);?>">



								<input type="hidden" name="" id="count" value="<?php echo $total_count;?>">
								<input type="hidden" name="" id="task_count" value="0">
								<input type="hidden" name="" id="result_count" value="2">
								<input type="hidden" name="" id="result_task_count" value="0">
							    <?php $count++; } ?>
								<div class="add-another-obj">
									<a href="javascript:void(0);" id="add_another_objective"><i class="fa fa-plus"></i> Add Another Objective</a>
								</div>
								
								<!-- <input type="hidden" name="" id="key_result_0" value="<?php echo $total_result_count;?>"> -->
							<?php } else { ?>
								<form action="<?php echo base_url()?>performance/add_goals" method="post">
								<input type="hidden" name="user_id" value="<?php echo $account_details['user_id']?>">
								<input type="hidden" name="position" value="<?php echo $designation['designation']?>">	
								<input type="hidden" name="lead" value="<?php echo $teamlead['user_id']?>">
								<input type="hidden" name="fullname" value="<?php echo $account_details['fullname']?>">

										<div class="form-group">									
										<div class="join-year">
										<span>Year</span>
										<select class="select form-control" name="goal_year">
											<option value="2019">2019</option>
											<option value="2020">2020</option>
											<option va;ue="2021">2021</option>
											<option value="2022">2022</option>
											<option value="2023">2023</option>
											<option value="2024">2024</option>
										</select>
									</div>
								</div>
												
							<div class="form-group">
								<label>Goal Duration</label>
								<div class="radio_input">
									<label class="radio-inline custom_radio">
										<input type="radio" name="goal_duration" value="90 days" checked>90 Days <span class="checkmark"></span>
									</label>
									<label class="radio-inline custom_radio">
										<input type="radio" name="goal_duration" value="6 month">6 Month <span class="checkmark"></span>
									</label>
									<label class="radio-inline custom_radio">
										<input type="radio" name="goal_duration" value="1 year">1 Year <span class="checkmark"></span>
									</label> 
								</div>
							</div>
					
							<div class="performance-wrap">
								<div class="performance-box">
									<div class="table-responsive">
										<table class="table performance-table">
											<thead>
												<tr>
													<th class="okr-show-obj">Objective/Key Result</th>
													<th class="text-center okr-status-wid">Status</th>
													<th class="text-center okr-pgss" style="">Progress</th>
													<th class="text-center okr-gradig" style="">Grading</th>
													<th class="text-center okr-feedack" style="">Feedback</th>
													
												</tr>
											</thead>
											<tbody>
												<tr>
												<td>
													<div class="label-input">
														<label class="goalCount">Objective 1</label>
														<input type="text" class="form-control" name="objective[]" required="required">
													</div>
													</td>
													<td class="text-center">
													 <span style="min-width:100px; padding:10px;" class="btn btn-info" name="status">Pending</span>
													 <input type="hidden" class="okr_status" name="okr_status[]" value="Pending">
													</td>
													<td class="text-center">
														<!-- <button class="btn btn-warning demo" type="button" id="demo" 

														data-toggle="modal" data-target="#progress_bar" name="progress">
															

														</button> -->

														<button style="min-width:70px;padding:10px;" class="btn btn-warning demo" type="button" id="demo" 

														data-title="Objective Progress" data-value="0" onclick="show_progress_bar(this)" name="progress[]"  >
															
															
														</button>

														<input type="hidden" class="progress_value" name="progress_value[]" id="progress_value" value="">
													</td>
													 <td class=""> 
														<!-- <strong class="grade" name="grade"></strong>
														<input type="hidden" class="grade_val" name="grade_value[]" value=""> -->
														 <select class="form-control select" name="grade_value[]" disabled="disabled">
															<?php $ratings = $this->db->get_where('okr_ratings')->row_array() ; ?>
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
											<tbody class="key_result_container">
												<tr>
													<td>
														<div class="label-input">
															<label>Key Result 1</label>
															<input type="text" class="form-control" name="key_result[]" required="required">
															<button type="button" class="btn btn-white add_key_result" data-arrayval="0" data-toggle="tooltip" data-original-title="Add Key Result"><i class="fa fa-plus-circle"></i></button>
															<!-- <button type="button" class="btn btn-white add_key_result" data-toggle="tooltip" data-original-title="Add Key Result"><i class="fa fa-plus-circle"></i></button> -->

														</div>
													</td>
													<td class="text-center">
														<span style="min-width:100px; padding:10px;" class="btn btn-info">Pending</span>
														<input type="hidden" class="key_status" name="key_status[]" value="Pending">
													</td>
													<td class="text-center">
														<!-- <button class="btn btn-success keyres_progress" type="button" data-toggle="modal" data-target="#key_progress"></button> -->


													<button style="min-width:70px;padding:10px;" class="btn btn-warning keyres_progress" type="button" id="keyres_progress" onclick="show_keyprogress_bar(this)" name="key_progress[]">
													</button>
													<input type="hidden" class="keyres_value" name="keyres_value[]" value="">
													</td>
													<td class=""> 
														<!-- <strong class="key_grade" name="key_grade"></strong>
														<input type="hidden" class="key_gradeval" name="key_gradeval[]" value=""> -->
														 <select class="form-control select" name="key_gradeval[]" disabled="disabled">
															<?php $ratings = $this->db->get_where('okr_ratings')->row_array() ; ?>
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
										</table>
										<div class="m-t-30 text-center">
											<button class="btn btn-primary" type="submit" id="create_offers_submit">Create OKR Performance</button>
										</div>
									</div>
								</div>
							    </form>
								<div class="add-another-obj">
									<a href="javascript:void(0);" id="add_another_objective"><i class="fa fa-plus"></i> Add Another Objective</a>
								</div>

								<input type="hidden" name="" id="count" value="2">
								<input type="hidden" name="" id="task_count" value="0">
								<input type="hidden" name="" id="result_count" value="2">
								<input type="hidden" name="" id="result_task_count" value="0">
								<input type="hidden" name="" id="key_result_0" value="2">

								<div>
								<!-- <input type="submit" value="Submit" class="btn btn-primary submit-btn" style="display:block;margin:auto;margin-top:15px"> -->
								</div>
							<?php } ?>
								
							</div>
						</div>
					</div>
                </div>

            </div>
    

		<!-- View Feedback Modal -->
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
										<!-- <img class="avatar" alt="User Image" src="assets/img/user.jpg"> -->
									</div>
									<div class="review-block">
										<!-- <div class="review-by">
											<span class="review-author-name">Mark Boydston</span>
										</div> -->
										<p>No Feedback found</p>
										<!-- <span class="review-date">Feb 6, 2019</span> -->
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- /View Feedback Modal -->

		<!-- View progress Modal -->
		<div id="progress_bar" class="modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title title">Objective Progress</h4>
					</div>
					<div class="modal-body">
						<div class="range">
							<input type="range" min="0" max="100" value="0"  class="okr_progress" id="myRange">
							<span class="setyear">0</span>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- /View progress Modal -->


		<!-- View key progress Modal -->
		<div id="key_progress" class="modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Key Result Progress</h4>
					</div>
					<div class="modal-body">
						<div class="range">
							<input type="range" min="0" max="100"  value="0" class="okr_key key_progress" id="key_range">
							<span class="setyearkey" style="">0</span>
						</div>

					</div>
				</div>
			</div>
		</div>
		<!-- /View key progress Modal -->

		<!-- Add Feedback Modal -->
		<div id="add_opj_feedback" class="modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>Write Feedback</label>
							<textarea rows="4" class="form-control"></textarea>
						</div>
						<div class="submit-section">
							<input type="submit" value="Submit" class="btn btn-primary submit-btn">
						</div>
					</div>
				</div>
			</div>
		</div>
		

		<?php $okr_objectives = $this->db->select()
							->from('okr_key_results')
							->get()->result_array();
					
			foreach ($okr_objectives as $okr_objective) { ?>
				<div id="opj_feedback<?php echo $okr_objective['id']?>" class="modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<div class="modal-body">
						
						<ul class="review-list">
							<?php $feed_backs = $this->db->select()
							->from('okr_feedback')
							->join('account_details','account_details.user_id = okr_feedback.user_id')
							->where('okr_feedback.okr_objective_id',$okr_objective['id'])
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
										<p>No Feedback Found</p>
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
		<!-- /object Feedback Modal -->
		<!-- /result Feedback Modal -->
		<?php $okr_results = $this->db->select()
							->from('okr_results')
							->get()->result_array();
					
			foreach ($okr_results as $okr_result) { ?>
				<div id="result_feedback<?php echo $okr_result['id']?>" class="modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<div class="modal-body">
						
						<ul class="review-list">
							<?php $feed_backs = $this->db->select()
							->from('okr_result_feedback')
							->join('account_details','account_details.user_id = okr_result_feedback.user_id')
							->where('okr_result_feedback.okr_result_id',$okr_result['id'])
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
										<p>No Feedback Found</p>
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
		<!-- /result Feedback Modal -->

		
		
		<!-- Add Feedback Modal -->
		<!-- <div id="opj_feedback" class="modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<textarea rows="4" class="form-control">No Feedback Found</textarea>
						</div>
						<div class="submit-section">
							<input type="submit" value="Submit" class="btn btn-primary submit-btn">
						</div> -->
					<!-- </div>
				</div>
			</div>
		</div> -->

		<!-- /Add Feedback Modal -->
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
