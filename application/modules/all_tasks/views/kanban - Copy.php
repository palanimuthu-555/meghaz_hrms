			<div class="chat-main-row">

			
				<div class="chat-main-wrapper">
					<div class="col-7 message-view task-view">
							<div class="chat-window">
								<div class="chat-header">
									<div class="navbar">
										<div class="float-left">
											
											<!-- <div class="add-task-btn-wrapper">
											    <a href="<?php echo base_url(); ?>all_tasks/add/<?php echo $project_id['project_id']; ?>" data-toggle="ajaxModal" class="add-task-btn btn btn-white">
    													Add Task
												</a>
											</div>	
 -->
											<div class="add-task-btn-wrapper">
												<span class="add-task-btn btn btn-white">
													Add Task
												</span>
											</div>
										</div>
										<div class="float-left m-l-10">
											
											<div class="add-task-btn-wrapper">
												<a href="<?php echo base_url(); ?>all_tasks/milestone_add/<?php echo $project_id['project_id']; ?>" data-toggle="ajaxModal" class="add-task-btn btn btn-white">
													Add Milestone
												</a>
											</div>
										</div>
										<ul class="nav navbar-nav float-right chat-menu">
											<li class="dropdown">
												<a href="" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>
												<div class="dropdown-menu">
													<a class="dropdown-item" href="javascript:void(0)">Pending Tasks</a>
													<a class="dropdown-item" href="javascript:void(0)">Completed Tasks</a>
													<a class="dropdown-item" href="javascript:void(0)">All Tasks</a>
												</div>
											</li>
										</ul>
										<a class="task-chat profile-rightbar float-right" href="#task_window"><i class="fa fa fa-comment"></i></a>
										<div class="float-right board-view-header">
											
											<div class="view-icons">
												 <a href="javascript:void(0);" class="grid-view btn btn-link " style=" pointer-events: none;cursor: default;"   title="kanban View"><i class="fa fa-th"></i></a>
												<a href="<?php echo base_url().'all_tasks/view/'.$project_id['project_id']; ?>"  class="list-view btn btn-link active" title="List View"><i class="fa fa-bars"></i></a>
											</div>
										</div>
									</div>
								</div>
													<div class="chat-contents">
									<div class="chat-content-wrap">
										<div class="chat-wrap-inner">
											<div class="chat-box">
												<div class="task-wrapper">
													<div class="task-list-container">
														<div class="task-list-body">
															<ul id="task-list">

															

																			<?php $all_tasks = $this->db->get_where('tasks',array('project'=>$project_id['project_id']))->result_array(); 
																	if(count($all_tasks) != 0)
																	{
																		foreach($all_tasks as $tasks){
																			if($tasks['task_progress'] == 100)
																			{
																				$cls = 'completed';
																				$btn_actions='task_uncompletes';
																			}else{
																				$cls = '';
																				$btn_actions='task_completes';
																			}

																			/* $assign_member = $this->db->select('*')
																	         ->from('assign_tasks PA')
																	         ->join('account_details AD','PA.assigned_user = AD.user_id')
																	         ->where('PA.task_assigned',$tasks['t_id'])
																	         ->get()->row_array(); 

																	         
  																	         	if($assign_member['avatar'] == '' )
																	         {
																	         	$pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
																	         }else{
																	         	$pro_pic_teams= base_url().'assets/avatar/'.$assign_member['avatar'];
																	         }

																	         $assignrds_name=$assign_member['fullname'];
																	         */
																	// print_r($all_tasks);exit;
																//	if($board['task_board_id'] == $tasks['status']){?>

									<div class="kanban-wrap" id="<?php echo $board['task_board_id'];?>"  data-id="<?php echo $tasks['t_id']?>">								         		
									<div class="card panel">
										<div class="kanban-box">
											<div class="task-board-header">
												<span class="status-title"><a href="<?php echo base_url().'all_tasks/kanban_task_view/'.$project_id['project_id'].'/'.$tasks['t_id'].'/'.$tasks['status']; ?>"><?php echo $tasks['task_name']?></a></span>
												<div class="dropdown kanban-task-action">
													<a href="" data-toggle="dropdown">
														<i class="fa fa-angle-down"></i>
													</a>
													<div class="dropdown-menu float-right">

														<!-- <li><a class="edit_task_modal" data-toggle="modal" data-target="#edit_task_modal" href="javascript:void(0);" onclick="edit_model(<?php echo $tasks['t_id']?>);">Edit</a></li> -->
														<a class="edit_task_modal dropdown-item" data-toggle="modal" data-target="#edit_task_modal<?php echo $tasks['t_id']; ?>" href="javascript:void(0);" onclick="edit_model(<?php echo $tasks['t_id']?>);">Edit</a>
														<a class="dropdown-item" data-toggle="ajaxModal" href="<?=base_url()?>all_tasks/task_kanban_delete/<?php echo $tasks['t_id']; ?>/<?php echo $tasks['project']?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
													</div>
												</div>
											</div>
											<div class="task-board-body">
												<div class="kanban-info">
													<div class="progress progress-xs">
														<div class="progress-bar"  role="progressbar" style="width: <?php echo $tasks['task_progress'];?>%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" value="<?php echo $tasks['task_progress'];?>"></div>
													</div>
													<span><?php echo $tasks['task_progress'];?></span>
												</div>

												<?php
													$class ="";
												 if($tasks['priority'] == "High"){
													$class ="danger";
												} else if($tasks['priority'] == "Normal") {
														$class ="warning";
												} else {
													$class ="success";
												}?>
												<div class="kanban-footer">
													<span class="task-info-cont">
														<span class="task-date"> <?php echo ($tasks['due_date'] !='')?'<i class="fa fa-clock-o"></i>'.date('M d',strtotime($tasks['due_date'])):'';?></span>
														<span class="task-priority badge badge-<?php echo $class;?>" ><?php echo $tasks['priority']?></span>
													</span>
													<div class="pro-teams">	

																<div class="pro-team-members">
																	
																	<div class="avatar-group">
																		  <?php $all_members = $this->db->get_where('assign_tasks',array('task_assigned'=>$tasks['t_id']))->result_array(); 

																	    foreach($all_members as $members){

								                                            $member_details = $this->db->select('*')

								                                                                        ->from('users U')

								                                                                        ->join('account_details AD','U.id = AD.user_id')

								                                                                        ->where('U.id',$members['assigned_user'])

								                                                                        ->get()->row_array();
																									         
																							         	if($member_details['avatar'] == '' )
																								         {
																								         	$pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
																								         }else{
																								         	$pro_pic_teams= base_url().'assets/avatar/'.$member_details['avatar'];
																								         }
																								          $assignrds_name=$member_details['fullname'];

								                                            $designation = $this->db->get_where('designation',array('id'=>$member_details['designation_id']))->row_array();



																	    ?>

																		<div class="avatar">
																			<img class="avatar-img rounded-circle border border-white" alt="User Image" title="<?php echo $assignrds_name;?>" src="<?php echo $pro_pic_teams;?>">
																		</div>
																	<?php }?>
																		<!-- <div class="avatar"> -->
																			<!-- <a class="float-right btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?=base_url()?>projects/team/<?=$p->project_id?>"> -->
																			<!-- <a class="avatar-title rounded-circle border border-white" data-toggle="ajaxModal" href="<?=base_url()?>all_tasks/assign_user/<?=$project_id['project_id']?>/<?=$tasks_id?>/Assign"><i class="fa fa-plus"></i></a>
																		</div> -->
																	</div>
																</div>
															</div>
													<!-- <span class="task-users"> -->
														<!-- <?php if($assign_member['assigned_user'] ==''){?>
																<img src="<?php echo $pro_pic_teams;?>" class="task-avatar" title="<?php echo $assignrds_name;?>" width="24" height="24">
														<?php } else {?>
															<a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $assign_member['assigned_user']; ?>">
														<img src="<?php echo $pro_pic_teams;?>" class="task-avatar" title="<?php echo $assignrds_name;?>" width="24" height="24">
													</a>
													<?php } ?> -->
													<!-- <img src="<?php echo $pro_pic_teams;?>" class="task-avatar" title="<?php echo $assignrds_name;?>" width="24" height="24">	
													</span> -->
												</div>
											</div>
										</div>
									</div>
									</div>
								<?php //}
							} } else{ ?>
																	<li class="task">
																		<div class="task-container">
																			<span class="task-label" contenteditable="true"><h5>No Tasks</h5></span>
																		</div>
																	</li>
																<?php } ?>
															</ul>
														</div>
														<div class="task-list-footer">
															<div class="new-task-wrapper" style="display: none;">
																<input type="hidden" id="project_id" value="<?php echo $project_id['project_id']; ?>" placeholder="Enter new task here. . .">
																<textarea id="new_task" placeholder="Enter new task here. . ."></textarea>
																<!-- <span class="error-message hidden">You need to enter a task first</span>
																<span class="add-new-task-btn btn" id="add-task">Add Task</span>
																<span class="cancel-btn btn" id="close-task-panel">Close</span> -->
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
			
							</div>
				
					</div>
						
				</div>
						
			</div>