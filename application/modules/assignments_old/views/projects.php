<?php //echo "<pre>"; print_r($projects); exit; ?>

<div class="content">
	 <div class="page-header">
	<div class="row">
		<div class="col-sm-5 col-12">
			<h4 class="page-title"><?=lang('assignments');?></h4>
				<ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active"><?=lang('assignments');?></li>
            </ul>
		</div>
		<div class="col-sm-7 col-12 text-right m-b-20">
			<a href="<?=base_url()?>assignments/add" class="btn view-btn1 add-btn"><i class="fa fa-plus"></i> Create Assignments</a>
			<!-- <div class="view-icons">
				<a href="<?php echo base_url(); ?>assignments" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>
				<a href="<?php echo base_url(); ?>assignments/grid_view" class="grid-view btn btn-link "><i class="fa fa-th"></i></a>
			</div> -->
		</div>
	</div>
</div>
			
					<div class="row filter-row">
						<div class="col-lg-4 col-sm-6 col-12">  
							<div class="form-group form-focus">
							<label class="control-label">Assignment Name</label>
							<input type="text" class="form-control floating project_search" id="assignment_name" name="assignment_name">
							</div>
						</div>
						<div class="col-lg-4 col-sm-6 col-12">  
							<div class="form-group form-focus">
								<label class="control-label">Resource Name</label>
								<input type="text" class="form-control floating project_search"  id="resource_name" name="resource_name">
							</div>
						</div>
						<div class="col-lg-4 col-sm-6 col-12">  
							<a href="javascript:void(0)" id="project_search_btn" class="btn btn-success rounded btn-block form-control project_search"> Search </a>  
						</div>     
                    </div>
                </div>
            
			
			<div class="card">
	        	<div class="card-body">
 

    <div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
		<table id="table-projects<?=($archive ? '-archive':'')?>" class="table table-striped text-capitalize custom-table m-b-0">
			<thead>
				<tr>
					<th style="width:5px; display:block;">#</th>
					<th class="col-title">Assignment Name</th>
					<th class="col-title">Resource Name</th>
					<th class="col-title">Employment Type</th>
					<th class="col-title">Start Date</th>
					<th class="col-title">End Date</th>
					<th class="col-title">Status</th>
					<th class="col-title">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$j =1;
				 foreach ($projects as $key => $p) { 
				$progress = Project::get_progress($p->project_id); ?>
				<tr class="<?php if (Project::timer_status('project',$p->project_id) == 'On') { echo "text-danger"; } ?>">
					<td style="display:block;"><?=$j?></td>
					<td>
						<?php  $no_of_tasks = App::counter('tasks',array('project' => $p->project_id)); ?>
						<a class="text-info" data-toggle="tooltip" data-original-title="<?=$no_of_tasks?> <?=lang('tasks')?> | <?=$progress?>% <?=lang('done')?>" href="<?php if(App::is_permit('menu_projects','read')){?><?php echo base_url()?>projects/view/<?=$p->project_id?><?php }else{ echo '#';}?>">
							<?=$p->project_title?>
						</a>
						<?php if (Project::timer_status('project',$p->project_id) == 'On') { ?>
						<i class="fa fa-spin fa-clock-o text-danger"></i>
						<?php } ?>
						<!-- <?php 
						if (time() > strtotime($p->due_date) AND $progress < 100){
						$color = (valid_date($p->due_date)) ? 'danger': 'default';
						echo '<span class="badge badge-'.$color.' float-right">';
						echo (valid_date($p->due_date)) ? lang('overdue') : lang('ongoing'); 
						echo '</span>'; 
						} ?> -->
						<!-- <div class="progress-xxs not-rounded mb-0 inline-block progress" style="width: 100%; margin-right: 5px">
							<div class="progress-bar progress-bar-<?php echo ($progress >= 100 ) ? 'success' : 'danger'; ?>" role="progressbar" aria-valuenow="<?=$progress?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progress?>%;" data-toggle="tooltip" data-original-title="<?=$progress?>%"></div>
						</div> -->
						<div class="progress-split">
								
								<div class="progress mb-2" style="height: 4px;width:31.33%; float: left;margin-right:2px;">
									<span class="progress-bar bg-<?php echo ($progress >= 100 ) ? 'success' : 'danger'; ?>" role="progressbar" style="width: <?php if($progress > 33){ echo '100%';}else{ echo $progress .'%';} ?>"></span>
								</div>
								<div class="progress mb-2" style="height: 4px;width:31.33%;margin-right: 2px;float: left;">
									<span class="progress-bar bg-<?php echo ($progress >= 100 ) ? 'success' : 'danger'; ?>" role="progressbar" style="width:  <?php if($progress > 66){ echo '100%';}
									if($progress < 66 && $progress > 33){ echo $progress .'%';} ?>" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></span>
								</div>
								<div class="progress mb-2" style="height: 4px;width:31.33%;float: right;">
									<span class="progress-bar bg-<?php echo ($progress >= 100 ) ? 'success' : 'danger'; ?>" role="progressbar" style="width: <?php if($progress > 99){ echo '100%';}if($progress < 99 && $progress > 66){ echo $progress .'%';} ?>" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></span>
								</div>
							</div> 
					
					</td>
					<?php if (User::is_admin()) { ?>
					<td class="">
						<?=($p->client > 0) ? Client::view_by_id($p->client)->company_name : 'N/A'; ?>
					</td>
					<?php } ?>

					<?php if (User::login_role_name() != 'client') { ?>
					<?php 
						switch ($p->status) {
							case 'Active': $label = 'info'; break;
							case 'On Hold': $label = 'warning'; break;
							case 'Done': $label = 'success'; break;
						}
					?>
					<td>
						<!-- <span class="badge badge-<?=$label?>"><?=lang(str_replace(" ","_",strtolower($p->status)))?></span> -->
					<div class="dropdown action-label">
					<a href="" class="badge bg-<?php echo $label?> text-white"> <?php echo $p->status;?> </a>

					</div>
					</td>
					<?php } ?>

					<td class="text-muted">
						<ul class="team-members">
							 
							<li>
								<a>
									<img src="<?php echo User::avatar_url($p->assign_lead); ?>" class="rounded-circle" data-toggle="tooltip" data-title="<?=User::displayName($p->assign_lead); ?>" data-placement="top">
								</a>
							</li>
							 
						</ul>
					</td>

					<td class="text-muted">
						<ul class="team-members">
							<?php 
								$i=0;
							foreach (Project::project_team($p->project_id) as $user) 
							{ 
								if($i<3)
								{
								?>
							<li>
								<a>
									<img src="<?php echo User::avatar_url($user->assigned_user); ?>" class="rounded-circle" data-toggle="tooltip" data-title="<?=User::displayName($user->assigned_user); ?>" data-placement="top">
								</a>
							</li>
							<?php
							} 
								$i++;
							} 


							if($i>3)
							{?>


								<li class="dropdown avatar-dropdown">
												<a href="#" class="all-users dropdown-toggle text-white" data-toggle="dropdown" aria-expanded="false">+<?=$i-3?></a>
												<div class="dropdown-menu dropdown-menu-right">
													
													<div class="avatar-group">
														<ul class="team-members">
														<?php 
														foreach (Project::project_team($p->project_id) as $user) 
															{ ?>
														<li>
														<a class="" >														
														<img src="<?php echo User::avatar_url($user->assigned_user); ?>" class="rounded-circle" data-toggle="tooltip" data-title="<?=User::displayName($user->assigned_user); ?>">
													 		</a>
															</li>
														
														<?php 
															
															} 
														?>
															</ul>
														</div>	

														<!--  -->
														
													
												<!-- 	<div class="avatar-pagination">
														<ul class="pagination justify-content-center">
															<li class="page-item">
																<a class="page-link" href="#" aria-label="Previous">
																	<span aria-hidden="true">«</span>
																	<span class="sr-only">Previous</span>
																</a>
															</li>
															<li class="page-item"><a class="page-link" href="#">1</a></li>
															<li class="page-item"><a class="page-link" href="#">2</a></li>
															<li class="page-item">
																<a class="page-link" href="#" aria-label="Next">
																	<span aria-hidden="true">»</span>
																<span class="sr-only">Next</span>
																</a>
															</li>
														</ul>
													</div> -->
												</div>
											</li>

						<?php	
							}
						?>
									  
						</ul>
					</td>
					<!-- <?php $hours = Project::total_hours($p->project_id);
						if($p->estimate_hours > 0){
							$used_budget = round(($hours / $p->estimate_hours) * 100,2);
						} else{ $used_budget = NULL; }
					?>
					<td>
						<strong class="<?=($used_budget > 100) ? 'text-danger' : 'text-success'; ?>"><?=($used_budget != NULL) ? $used_budget.' %': 'N/A'?></strong>
					</td> -->
					<?php if (!User::is_admin()) {  
						$check_task_hours = $this->db->get_where('timesheet',array('project_id'=>$p->project_id))->result_array();    
                            $hrs = 0;
                            foreach($check_task_hours as $h)
                            {
                              $hrs += $h['hours'];
                            }

						?>
					<!-- <td class="text-muted"><?=$hrs?> <?=lang('hours')?></td> -->
					<?php } ?>
					<?php if(User::login_role_name() != 'staff' || User::perm_allowed(User::get_id(),'view_project_cost')){ ?>
					<?php $cur = ($p->client > 0) ? Client::client_currency($p->client)->code : $p->currency; ?>
					<td class="col-currency">
						<!-- <strong><?=Applib::format_currency($cur, Project::sub_total($p->project_id))?></strong> -->
						<span class="" data-toggle="tooltip" data-title="<?=lang('expenses')?>"> <?=Applib::format_currency($cur, Project::total_expense($p->project_id))?> </span>
					</td>
					<?php } ?>

					<!-- Gannt Chart -->
					<!-- <td><a href="<?php echo base_url(); ?>projects/project_chart/<?php echo $p->project_id; ?>"><i class="fa fa-bar-chart" aria-hidden="true"></i></a></td> -->

					<?php
					if(App::is_permit('menu_projects','read')==true ||App::is_permit('menu_projects','write')==true|| App::is_permit('menu_projects','delete')==true)
					{
					?>
					<td class="text-right">
						<div class="dropdown">
							<a data-toggle="dropdown" class="action-icon" href="#"><i class="fa fa-ellipsis-v"></i></a>
							<div class="dropdown-menu float-right dropdown-menu-right">

								<?php
								if(App::is_permit('menu_projects','read')){
								?>
									<a class="dropdown-item" href="<?=base_url()?>projects/view/<?=$p->project_id?>"><i class="fa fa-eye m-r-5"></i><?=lang('preview_project')?></a>
								<?php
								}
								?>
								<?php 
								if(App::is_permit('menu_projects','write')){
								?>   
		
									<a class="dropdown-item" href="<?=base_url()?>projects/edit/<?=$p->project_id?>"><i class="fa fa-pencil m-r-5"></i><?=lang('edit_project')?></a>
								
								<?php if ($archive) : ?>
								<a class="dropdown-item" href="<?=base_url()?>projects/archive/<?=$p->project_id?>/0"><?=lang('move_to_active')?></a>  
								<?php else: ?>
								
									<a class="dropdown-item" href="<?=base_url()?>projects/archive/<?=$p->project_id?>/1"><i class="fa fa-archive m-r-5" aria-hidden="true"></i><?=lang('archive_project')?></a>
								       
								<?php endif; ?>
								<?php  
								}
								?>  
								<?php 
								if(App::is_permit('menu_projects','delete')){
								?> 
							
									<a class="dropdown-item" href="<?=base_url()?>projects/delete/<?=$p->project_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i><?=lang('delete_project')?></a>
								
								<?php
								}
								?>
							</div>
						</div>
					</td>
					<?php
					}
					?>
				</tr>
				<?php $j++; } ?>

				<?php $k =1; 
				if(($this->session->userdata('role_id') != 1) && ($this->session->userdata('role_id') != 4)) {
				 $created_check = $this->db->get_where('projects',array('created_by'=>$this->session->userdata('user_id'),'archived '=>'0'))->result(); foreach ($created_check as $key => $p1) { 
				$progress = Project::get_progress($p1->project_id); ?>
				<tr class="<?php if (Project::timer_status('project',$p1->project_id) == 'On') { echo "text-danger"; } ?>">
					<td style="display:none;"><?=$k?></td>
					<td>
						<?php  $no_of_tasks = App::counter('tasks',array('project' => $p1->project_id)); ?>
						<a class="text-info" data-toggle="tooltip" data-original-title="<?=$no_of_tasks?> <?=lang('tasks')?> | <?=$progress?>% <?=lang('done')?>" href="<?=base_url()?>projects/view/<?=$p1->project_id?>">
							<?=$p1->project_title?>
						</a>
						<?php if (Project::timer_status('project',$p1->project_id) == 'On') { ?>
						<i class="fa fa-spin fa-clock-o text-danger"></i>
						<?php } ?>
						<?php 
						if (time() > strtotime($p1->due_date) AND $progress < 100){
						$color = (valid_date($p1->due_date)) ? 'danger': 'default';
						echo '<span class="badge badge-'.$color.' float-right">';
						echo (valid_date($p1->due_date)) ? lang('overdue') : lang('ongoing'); 
						echo '</span>'; 
						} ?>
						<div class="progress-xxs not-rounded mb-0 inline-block progress" style="width: 100%; margin-right: 5px">
							<div class="progress-bar progress-bar-<?php echo ($progress >= 100 ) ? 'success' : 'danger'; ?>" role="progressbar" aria-valuenow="<?=$progress?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progress?>%;" data-toggle="tooltip" data-original-title="<?=$progress?>%"></div>
						</div>
					
					</td>
					<?php if (User::is_admin()) { ?>
					<td class="">
						<?=($p1->client > 0) ? Client::view_by_id($p1->client)->company_name : 'N/A'; ?>
					</td>
					<?php } ?>

					<?php if (User::login_role_name() != 'client') { ?>
					<?php 
						switch ($p1->status) {
							case 'Active': $label = 'success'; break;
							case 'On Hold': $label = 'warning'; break;
							case 'Done': $label = 'default'; break;
						}
					?>
					<td>
						<span class="badge badge-<?=$label?>"><?=lang(str_replace(" ","_",strtolower($p1->status)))?></span>
					</td>
					<?php } ?>

					<td class="text-muted">
						<ul class="team-members">
							 
							<li>
								<a>
									<img src="<?php echo User::avatar_url($p1->assign_lead); ?>" class="rounded-circle" data-toggle="tooltip" data-title="<?=User::displayName($p1->assign_lead); ?>" data-placement="top">
								</a>
							</li>
							 
						</ul>
					</td>

					<td class="text-muted">
						<ul class="team-members">
							<?php foreach (Project::project_team($p1->project_id) as $user) { ?>
							<li>
								<a>
									<img src="<?php echo User::avatar_url($user->assigned_user); ?>" class="rounded-circle" data-toggle="tooltip" data-title="<?=User::displayName($user->assigned_user); ?>" data-placement="top">
								</a>
							</li>
							<?php } ?>
						</ul>
					</td>
					<!-- <?php $hours = Project::total_hours($p1->project_id);
						if($p1->estimate_hours > 0){
							$used_budget = round(($hours / $p1->estimate_hours) * 100,2);
						} else{ $used_budget = NULL; }
					?> -->
					<!-- <td>
						<strong class="<?=($used_budget > 100) ? 'text-danger' : 'text-success'; ?>"><?=($used_budget != NULL) ? $used_budget.' %': 'N/A'?></strong>
					</td> -->
					<?php if (!User::is_admin()) {  
						$check_task_hours = $this->db->get_where('timesheet',array('project_id'=>$p1->project_id))->result_array();    
                            $hrs = 0;
                            foreach($check_task_hours as $h)
                            {
                              $hrs += $h['hours'];
                            }

						?>
					<!-- <td class="text-muted"><?=$hrs?> <?=lang('hours')?></td> -->
					<?php } ?>
					<?php if(User::login_role_name() != 'staff' || User::perm_allowed(User::get_id(),'view_project_cost')){ ?>
					<?php $cur = ($p1->client > 0) ? Client::client_currency($p1->client)->code : $p1->currency; ?>
					<td class="col-currency">
						<!-- <strong><?=Applib::format_currency($cur, Project::sub_total($p1->project_id))?></strong> -->
						<span class="" data-toggle="tooltip" data-title="<?=lang('expenses')?>"> <?=Applib::format_currency($cur, Project::total_expense($p1->project_id))?> </span>
					</td>
					<?php } ?>
					<td class="text-right">
						<div class="dropdown">
							<a data-toggle="dropdown" class="action-icon" href="#"><i class="fa fa-ellipsis-v"></i></a>
							<div class="dropdown-menu float-right dropdown-menu-right">
								
									<a class="dropdown-item" href="<?=base_url()?>projects/view/<?=$p1->project_id?>"><?=lang('preview_project')?></a>
							
								<?php if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_projects')){ ?>   
							
									<a class="dropdown-item" href="<?=base_url()?>projects/edit/<?=$p1->project_id?>"><?=lang('edit_project')?></a>
							
								<?php if ($archive) : ?>
								<a class="dropdown-item" href="<?=base_url()?>projects/archive/<?=$p1->project_id?>/0"><?=lang('move_to_active')?></a>
								<?php else: ?>
								
									<a class="dropdown-item" href="<?=base_url()?>projects/archive/<?=$p1->project_id?>/1"><?=lang('archive_project')?></a>
								        
								<?php endif; ?>
								<?php } ?>  
								<?php if (User::is_admin() || User::perm_allowed(User::get_id(),'delete_projects')){ ?> 
							
									<a class="dropdown-item" href="<?=base_url()?>projects/delete/<?=$p1->project_id?>" data-toggle="ajaxModal"><?=lang('delete_project')?></a>
							
								<?php } ?>
							</div>
						</div>
					</td>
				</tr>
				<?php $k++; } } ?>
			</tbody>
		</table>
								</div>
								</div>
	
</div>
</div>