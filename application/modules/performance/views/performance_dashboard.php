
                <div class="content container-fluid">
                	<div class="page-header">
					<div class="row">
						<div class="col-sm-8">
							<h4 class="page-title">Team Performance Dashboard</h4>
							<ul class="breadcrumb mb-2">
				                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
				                <li class="breadcrumb-item active">Team Performance Dashboard</li>
				            </ul>
						</div>
					</div>
					</div>
					<div class="row">
						<div class="col-sm-5">
							<div class="card-box text-center">
								<h4 class="card-title">Completed Performance Review</h4>
								<span class="perform-icon bg-success-light"><?php echo $completed_performance; ?></span>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="card-box text-center">
								<h4 class="card-title">Outstanding Reviews</h4>
								<span class="perform-icon bg-danger-light"><?php echo $outstanding_performance;?></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">								
								<table id="table-performance_dashboard" class="table custom-table m-b-0 table-striped" >
								<?php if($performance_status['okr'] ==1){?>
									<thead>
										<tr>
											<th style="width:30%;" >Team</th>
											<!-- <th>Self Review</th> -->
											<th >Progress</th>
											<!-- <th>Peer Reviews</th> -->
											<th style=" text-align: center;">OKRs Grading</th>
											
										</tr>
									</thead>
									<tbody>
										<?php 
											
										$rating_count= count($okr);
										$grade = 0;
										$progress =0;

								                foreach ($okr as $value) { 

								                	 // $grade = $value['peer_grade']/2;
								                	 $grade = ($value['key_grade']+$value['result_grade'])/2;
								                	$progress = ($value['k_progress']+$value['r_progress'])/2;

								                	$employee_details = $this->db->get_where('users',array('id'=>$value['user_id']))->row_array();
$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
$account_details = $this->db->get_where('account_details',array('user_id'=>$value['user_id']))->row_array();

								                	?>
								                    	
								                    
										<tr>
											<td>
												<a href="<?=base_url()?>employees/profile_view/<?php echo $value['user_id'];?>" class="avatar"><?php echo substr($account_details['fullname'],0,1);?></a>
												<h2><a style="color: #ff9800;" href="<?=base_url()?>performance/show_okrdetails/<?php echo $value['id'];?>"><?php echo $account_details['fullname']?> <span><?php echo $designation['designation']?></span></a></h2>
											</td>
											<!-- <td>
												<div class="rating">
													<?php for ($i=0; $i <5 ; $i++) {

														if($i < $value['self_ratings']){
														echo '<i class="fa fa-star rated"></i>';
														}else{
														echo '<i class="fa fa-star"></i>';
													} 
												}?> 
												</div>
											</td> -->
											<!-- <td>
												<div class="rating">
													<?php for ($i=0; $i <5 ; $i++) {

														if($i < $peer_rating){
														echo '<i class="fa fa-star rated"></i>';
														}else{
														echo '<i class="fa fa-star"></i>';
													} 
												}?> 
												</div>
											</td> -->
											<td>
												<div class="progress-wrap">
													<div class="progress progress-sm">
														<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $progress;?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $progress;?>%">
															<?php echo $progress;?>%
														</div>
													</div>
													<span><?php echo $progress;?>%</span>
												</div>
											</td>
											<td style=" text-align: center;">
												
												<button style="min-width:100px;padding:10px;" class="btn <?php if($grade <= 0.2){ echo 'btn-danger';}else if($grade <= 0.7){echo 'btn-warning';}else { echo 'btn-success'; }?>" type="button" 
												 name="progress[]"><?php if($grade != '') { ?><?php echo $grade?>%<?php } else {?>0%<?php } ?>
												</button>
											</td>
										
										</tr>
									<?php } ?>
									</tbody>
								<?php } ?>
								<?php if($performance_status['competency'] ==1){?>
									<thead>
										<tr>
											<th style="width:30%;">Team</th>											
											<th>Goals</th>
											<th>Self-Rating</th>
											<!-- <th>Peer Reviews</th> -->
											<th style=" text-align: center;">Your-Rating</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											
										$rating_count= count($performances_360);
										$peer_rating = 0;

								                foreach ($performances_360 as $value) { 

								                	$peer_rating = $value['peer_ratings']/2;

								                	$employee_details = $this->db->get_where('users',array('id'=>$value['user_id']))->row_array();
$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
$account_details = $this->db->get_where('account_details',array('user_id'=>$value['user_id']))->row_array();

								                	?>
								                    	
								                    
										<tr>
											<td>
												<a href="<?=base_url()?>employees/profile_view/<?php echo $value['user_id'];?>" class="avatar"><?php echo substr($account_details['fullname'],0,1);?></a>
												<h2><a style="color: #ff9800;" href="<?=base_url()?>performance_three_sixty/show_performance_three_sixty/<?php echo $value['user_id'];?>"><?php echo $account_details['fullname']?> <span><?php echo $designation['designation']?></span></a></h2>
											</td>
											<td>
												<div class="progress-wrap">
													<div class="progress progress-sm">
														<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $value['avg_progress']?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $value['avg_progress']?>%">
															<?php echo $value['avg_progress']?>%
														</div>
													</div>
													<span><?php echo $value['avg_progress']?>%</span>
												</div>
											</td>
											<td>
												<div class="rating">
													<?php for ($i=0; $i <5 ; $i++) {

														if($i < $value['self_ratings']){
														echo '<i class="fa fa-star rated"></i>';
														}else{
														echo '<i class="fa fa-star"></i>';
													} 
												}?> 
												</div>
											</td>
											<!-- <td>
												<div class="rating">
													<?php for ($i=0; $i <5 ; $i++) {

														if($i < $peer_rating){
														echo '<i class="fa fa-star rated"></i>';
														}else{
														echo '<i class="fa fa-star"></i>';
													} 
												}?> 
												</div>
											</td> -->
											<td style=" text-align: center;">
												<div class="rating">
													<?php for ($i=0; $i <5 ; $i++) {

														if($i < $value['your_ratings']){
														echo '<i class="fa fa-star rated"></i>';
														}else{
														echo '<i class="fa fa-star"></i>';
													} 
												}?> 
												</div>
											</td>
											
										</tr>
									<?php } ?>
									</tbody>
								<?php } ?>
								<?php if($performance_status['smart_goals'] ==1){?>
									<thead>
										<tr>
											<th style="width:30%;">Team</th>											
											<th>Goals</th>
											<th style=" text-align: center;">Your Reviews</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											
										$rating_count= count($smartgoal);
										

						                foreach ($smartgoal as $value) { 

					                	$employee_details = $this->db->get_where('users',array('id'=>$value['user_id']))->row_array();
										$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
										$account_details = $this->db->get_where('account_details',array('user_id'=>$value['user_id']))->row_array();
					                	?>								                    	
								                    
										<tr>
											<td>
												<a href="<?=base_url()?>employees/profile_view/<?php echo $value['user_id'];?>" class="avatar"><?php echo substr($account_details['fullname'],0,1);?></a>
												<h2><a style="color: #ff9800;" href="<?=base_url()?>smartgoal/show_smartgoal/<?php echo $value['user_id'];?>"><?php echo $account_details['fullname']?> <span><?php echo $designation['designation']?></span></a></h2>
											</td>
											<td>
												<div class="progress-wrap">
													<div class="progress progress-sm">
														<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $value['avg_progress']?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $value['avg_progress']?>%">
															<?php echo $value['avg_progress']?>%
														</div>
													</div>
													<span><?php echo $value['avg_progress']?>%</span>
												</div>
											</td>											
											<td style=" text-align: center;">
												<div class="rating">
													<?php for ($i=0; $i <5 ; $i++) {

														if($i < $value['your_ratings']){
														echo '<i class="fa fa-star rated"></i>';
														}else{
														echo '<i class="fa fa-star"></i>';
													} 
												}?> 
												</div>
											</td>
											
										</tr>
									<?php } ?>
									</tbody>
								<?php } ?>
								
								</table>

							</div>
						</div>
					</div>
				
                </div>