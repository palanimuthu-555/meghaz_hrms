<script src="<?=base_url()?>assets/js/apexcharts.js"></script>
                <div class="content container-fluid">
				
					<div class="page-header">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="page-title">Welcome Admin!</h3>
								<!-- <ul class="breadcrumb">
									<li class="breadcrumb-item active">Dashboard</li>
								</ul> -->
							</div>
						</div>
					</div>
					
					<div class="row admin-dash">
						<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
							<div class="card dash-widget">
								<div class="card-body">
									<span class="dash-widget-icon mt-2 bg-warning-light"><i class="fa fa-users" aria-hidden="true"></i></span>
									<div class="dash-widget-info">
										<?php $users_count = $this->db->get_where('users',array('role_id '=>3,'status'=>1))->result_array(); 
										$inactive_user = $this->db->get_where('users',array('role_id '=>3,'status'=>0))->result_array();	
										?>
										<span>Employees</span>
										<h3><?php echo count($users_count); ?></h3>
										<div><span class="badge bg-success-light">Active <?php echo count($users_count); ?></span> <span class="badge bg-danger-light">Inactive <?php echo count($inactive_user); ?></span></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
							<div class="card dash-widget">
								<div class="card-body">
									<span class="dash-widget-icon mt-2 bg-warning-light"><i class="fa fa-lock"></i></span>
									<div class="dash-widget-info">
										<span>Permission</span>
										<h3>Roles</h3>
										<small>Set Roles</small>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
							<div class="card dash-widget">
								<div class="card-body">
									<span class="dash-widget-icon mt-2 bg-warning-light"><i class="fa fa-calendar"></i></span>
									<div class="dash-widget-info">
										<span>Management</span>
										<h3>Leave</h3>
										<small><a href="<?php echo base_url()?>leaves">View Application</a></small>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
							<div class="card dash-widget">
								<div class="card-body">
									<span class="dash-widget-icon mt-2 bg-warning-light"><i class="fa fa-cog"></i></span>
									<div class="dash-widget-info">
										<span>Theme</span>
										<h3>Settings</h3>
										<small><a href="<?php echo base_url()?>settings">Configuration</a></small>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-xl-6 d-flex text-center">
							<div class="card flex-fill">
								<div class="card-body">
									<h3 class="card-title">Invoices</h3>
									<!-- <canvas id="canvas"></canvas> -->
									<div id="chart">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-6 d-flex">
							<div class="card flex-fill chart-div">
								<div class="card-body">
									<h3 class="card-title text-center">Overview</h3>
									<div class="row">
										<div class="col-md-12 m-b-10 d-flex justify-content-center align-items-center" >
											<!-- <canvas id="gas2"></canvas> -->
											<div id="chart-pie" class="mt-3">
									</div>
										</div>
										<!-- <div class="col-md-6">
											<ul class="list chart-list">
												<li class="list-item"><span class="m-r-10" style="color:#ffc999"><i class="fa fa-circle" aria-hidden="true"></i> </span>Projects</li>
												<li class="list-item"><span class="m-r-10" style="color:#fc6075"><i class="fa fa-circle" aria-hidden="true"></i> </span> Clients</li>
												<li class="list-item"><span class="m-r-10" style="color:#ff9b44"><i class="fa fa-circle" aria-hidden="true"></i> </span> Tasks</li>
												<li class="list-item"><span class="m-r-10" style="color:#fd9ba8"><i class="fa fa-circle" aria-hidden="true"></i> </span> Employees</li>
											</ul>
										</div> -->
									</div>
								</div>
							</div>
						</div>
						
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<div class="card-group m-b-30">
								<div class="card dash-widget">
									<div class="card-body">
									<span class="dash-widget-icon"><i class="fa fa-cubes" aria-hidden="true"></i></span>
									<div class="dash-widget-info m-b-15">
										<?php $projects_count = $this->db->get_where('projects',array('proj_deleted'=>'No'))->result_array(); 
										$projects_completed_count = $this->db->get_where('tasks',array('task_progress'=>100))->result_array(); 
										?>
										<h3><?php echo count($projects_count); ?></h3>
										<span>Projects</span>
									</div>
									<?php $all_progress = Project::get_all_progress(); ?>
									<div class="progress mb-2" style="height: 5px">
										<div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $all_progress.'%'; ?>" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<p class="m-b-0">Completed <span class="float-right"><?php echo (!empty($all_progress))?$all_progress.'%':'0%'; ?></span></p>
									</div>
								</div>
								<div class="card dash-widget">
									<div class="card-body">
									<span class="dash-widget-icon"><i class="fa fa-diamond"></i></span>
									<div class="dash-widget-info m-b-15">
										<?php $tasks_count = $this->db->get('tasks')->result_array(); ?>
										<h3><?php echo count($tasks_count); ?></h3>
										<span>Tasks</span>
									</div>
									<div class="progress mb-2" style="height: 5px">
										<div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $all_progress.'%'; ?>" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<p class="m-b-0">Completed <span class="float-right"><?php echo (!empty($all_progress))?$all_progress.'%':'0%'; ?></span></p>
									</div>
								</div>
								<div class="card dash-widget">
									<div class="card-body">
									<span class="dash-widget-icon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
									<div class="dash-widget-info m-b-15">
										
										<?php 
										$today = date("Y-m-d");
										$today_absents = $this->db->select("*")
										 		 ->from('dgt_user_leaves')
												 ->where("leave_from <=",$today)
								                 ->where("leave_to >=",$today)
								                 ->get()->result_array();
								        $users_count = $this->db->get_where('users',array('role_id '=>3,'status'=>1))->result_array(); 

								        $today_present = count($users_count) - count($today_absents);

							         	$today_present_per = ($today_present / count($users_count))*100;
							         	$present_per = Applib::format_deci($today_present_per);

											$today_absents_per = (count($today_absents) / count($users_count))*100;
											$absents_per = Applib::format_deci($today_absents_per);
											
											?>
											<h3><?php echo $present_absent_count['today_absent']; ?></h3>
											<span>Today Absent</span>
										</div>
										<?php $absent_progress = ($present_absent_count['today_absent']/count($users_count))*100; ?>
										<?php $present_progress = ($present_absent_count['today_present']/count($users_count))*100; ?>
										<div class="progress mb-2" style="height: 5px">
											<div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo round($absent_progress)?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
										<p class="m-b-0">Absent Status <span class="float-right"><?php echo round($absent_progress)?>%</span></p>
									</div>
								</div>
								<div class="card dash-widget">
									<div class="card-body">
										<span class="dash-widget-icon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
										<div class="dash-widget-info m-b-15">
											<h3><?php echo $present_absent_count['today_present']; ?></h3>
											<span>Today Present</span>
										</div>
										<div class="progress mb-2" style="height: 5px">
											<div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo round($present_progress)?>%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
										<p class="m-b-0">Present Status <span class="float-right"><?php echo round($present_progress)?>%</span></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				<div class="row">
                    <div class="col-md-6 col-sm-6 col-lg-4">
                        <div class="dash-widget ticket-widget">
                            <span class="dash-widget-icon bg-info-light"><i class="fa fa-ticket"></i></span>
                            <div class="dash-widget-info">
                            	<?php 
                            	$total_tickets = $this->db->select("*")
												   ->from('dgt_tickets')
												   ->get()->result_array();
								$open_tickets = $this->db->select("*")
												   ->from('dgt_tickets')
												   ->where('status',open)
												   ->get()->result_array();
								$closed_tickets = $this->db->select("*")
												   ->from('dgt_tickets')
												   ->where('status',Closed)
												   ->get()->result_array();
        //                     	 	$allticket=$this->db->select('count(*) as id')->where(['status' => 1])->get('dgt_tickets')->row()->id;
								// $solved=$this->db->select('count(*) as id')->where(['status' => 1,'ticket_status' => 4])->get('dgt_tickets')->row()->id;
								// $hold=$this->db->select('count(*) as id')->where(['status' => 1,'ticket_status' => 3])->get('dgt_tickets')->row()->id;
								// $process=$this->db->select('count(*) as id')->where(['status' => 1,'ticket_status' => 5])->get('dgt_tickets')->row()->id;
                        	 	$pending_ticket_per = (count($open_tickets) / count($total_tickets))*100;
					         	$pending_per = Applib::format_deci($pending_ticket_per);
					         	$solved_ticket_per = (count($closed_tickets) / count($total_tickets))*100;
					         	$solved_per = Applib::format_deci($solved_ticket_per);
                            	?>
                                <h3><?php echo count($total_tickets)?></h3>
                                <span>Total Tickets</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-4">
                        <div class="dash-widget ticket-widget">
                            <span class="dash-widget-icon bg-danger-light"><i class="fa fa-server"></i></span>
                            <div class="dash-widget-info">
                                <h3><?php echo count($open_tickets)?></h3>
                                <span>Open Tickets</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-4">
                        <div class="dash-widget ticket-widget">
                            <span class="dash-widget-icon bg-success-light"><i class="fa fa-thumbs-up"></i></span>
                            <div class="dash-widget-info">
                                <h3><?php echo count($closed_tickets)?></h3>
                                <span>Closed Tickets</span>
                            </div>
                        </div>
                    </div>
                </div>
                 <?php $total_invoice = $this->db->select("*")
												   ->from('dgt_invoices')
												   ->order_by('inv_id',desc)	
												   ->get()->result_array();
					   $pending_invoice = $this->db->select("*")
												   ->from('dgt_invoices')
												   ->where('status',Unpaid)
												   ->order_by('inv_id',desc)
												   ->get()->result_array();

					   $pending_invoice_per = (count($pending_invoice) / count($total_invoice))*100;
		         		$pen_invoice_per = Applib::format_deci($pending_invoice_per);
												   ?>
					<!-- Statistics Widget -->
					<div class="row">
						<div class="col-md-12 col-lg-12 col-xl-4 d-flex">
							<div class="card flex-fill dash-statistics">
								<div class="card-body">
									<h5 class="card-title">Statistics</h5>
									<div class="stats-list">
										<div class="stats-info">
											<p>Today Leave <strong><?php echo $present_absent_count['today_absent']; ?> <small>/  <?php echo count($users_count); ?></small></strong></p>
											<div class="progress">
												<div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo round($absent_progress)?>%" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
										</div>
										<div class="stats-info">
											<p>Pending Invoice <strong><?php echo count($pending_invoice); ?> <small>/ <?php echo count($total_invoice); ?></small></strong></p>
											<div class="progress">
												<div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo round($pen_invoice_per)?>%" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
										</div>
										<!-- <div class="stats-info">
											<p>Completed Projects <strong><?php echo count($projects_completed_count);?> <small>/ <?php echo count($projects_count); ?></small></strong></p>
											<div class="progress">
												<div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $all_progress.'%'; ?>" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
										</div> -->

										<div class="stats-info">
											<p>Open Tickets <strong><?php echo count($open_tickets)?> <small>/ <?php echo count($total_tickets)?></small></strong></p>
											<div class="progress">
												<div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $pending_per;?>%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
										</div>
										<div class="stats-info">
											<p>Closed Tickets <strong><?php echo count($closed_tickets)?> <small>/ <?php echo count($total_tickets)?></small></strong></p>
											<div class="progress">
												<div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $solved_per;?>%" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php 
						$today = date("Y-m-d");
						$overdue = $this->db->get_where('tasks',array('due_date >'=>$today))->result_array();
						$tasks_completed=  $this->db->get_where('tasks',array('task_progress'=>100))->result_array(); 
						$tasks_pending=  $this->db->get_where('tasks',array('task_progress !='=>100))->result_array(); 
						$total_tasks=  $this->db->get_where('tasks')->result_array(); 

						$tasks_completed_per = (count($tasks_completed) / count($total_tasks))*100;
		         		$tasks_comp_per = Applib::format_deci($tasks_completed_per);
			         	$tasks_pending_per = (count($tasks_pending) / count($total_tasks))*100;
			         	$tasks_pen_per = Applib::format_deci($tasks_pending_per);
						?>
						<div class="col-md-12 col-lg-6 col-xl-4 d-flex">
							<div class="card flex-fill">
								<div class="card-body">
									<h4 class="card-title">Task Statistics</h4>
									<div class="statistics">
										<div class="row">
											<div class="col-md-6 col-6 text-center">
												<div class="stats-box mb-4">
													<p>Total Tasks</p>
													<h3><?php echo count($tasks_count); ?></h3>
												</div>
											</div>
											<div class="col-md-6 col-6 text-center">
												<div class="stats-box mb-4">
													<p>Overdue Tasks</p>
													<h3><?php echo count($overdue); ?></h3>
												</div>
											</div>
										</div>
									</div>
									<div class="progress mb-4">
										<div class="progress-bar bg-purple" role="progressbar" style="width: <?php echo $tasks_comp_per;?>%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"><?php echo $tasks_comp_per;?>%</div>
										<div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $tasks_pen_per;?>%" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"><?php echo $tasks_pen_per;?>%</div>
										<!-- <div class="progress-bar bg-success" role="progressbar" style="width: 24%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100">24%</div>
										<div class="progress-bar bg-danger" role="progressbar" style="width: 26%" aria-valuenow="14" aria-valuemin="0" aria-valuemax="100">21%</div>
										<div class="progress-bar bg-info" role="progressbar" style="width: 10%" aria-valuenow="14" aria-valuemin="0" aria-valuemax="100">10%</div> -->
									</div>
									<div>
										<p><i class="fa fa-dot-circle-o text-purple mr-2"></i>Completed Tasks <span class="float-right"><?php echo count($tasks_completed);?></span></p>
										<p><i class="fa fa-dot-circle-o text-warning mr-2"></i>Inprogress Tasks <span class="float-right"><?php echo count($tasks_pending);?></span></p>
										<!-- <p><i class="fa fa-dot-circle-o text-success mr-2"></i>On Hold Tasks <span class="float-right">31</span></p>
										<p><i class="fa fa-dot-circle-o text-danger mr-2"></i>Pending Tasks <span class="float-right">47</span></p>
										<p class="mb-0"><i class="fa fa-dot-circle-o text-info mr-2"></i>Review Tasks <span class="float-right">5</span></p> -->
									</div>
								</div>
							</div>
						</div>
						
						<?php $today = date("Y-m-d");
									$today_absent = $this->db->select("*")
									 		 ->from('dgt_user_leaves')
											 ->where("leave_from <=",$today)
							                 ->where("leave_to >=",$today)
							                 // ->where("status",1)
							                 ->get()->result_array(); ?>
						<div class="col-md-12 col-lg-6 col-xl-4 d-flex">
							<div class="card flex-fill">
								<div class="card-body">
									<h4 class="card-title">Today Absent <span class="badge bg-inverse-danger ml-2"><?php echo count($today_absents)?></span></h4>
									<?php 
										if(!empty($today_absent)){
									foreach ($today_absent as $key => $absent) {
										if($absent['status'] == 1){
											$status = 'Approved';
											$class = 'success';
										}elseif($absent['status'] == 0){
											$status = 'Pending';
											$class = 'warning';
										}else{
											$status = 'Rejected';
											$class = 'danger';
										}	
										?>
									<div class="leave-info-box">
										<div class="media align-items-center">
											<a href="<?php echo base_url()?>Profile_view/<?php echo $absent['user_id'];?>" class="avatar"><img alt="" src="assets/img/user.jpg"></a>
											<div class="media-body">
												<div class="text-sm my-0"><?php echo User::displayName($absent['user_id']);?></div>
											</div>
										</div>
										<div class="row align-items-center mt-3">
											<div class="col-6">
												<h6 class="mb-0"><?php echo date('d M Y');?></h6>
												<span class="text-sm text-muted">Leave Date</span>
											</div>
											<div class="col-6 text-right">
												<span class="badge bg-inverse-<?php echo $class;?>"><?php echo $status;?></span>
											</div>
										</div>
									</div>
									
								<?php } 
							}?>
									<div class="load-more text-center">
										<a class="text-dark" href="<?php echo base_url()?>leaves">Load More</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /Statistics Widget -->
					<div class="row">
						<div class="col-md-6 d-flex">
							<div class="card card-table flex-fill">
								<div class="card-header">
									<h3 class="card-title mb-0">Invoices</h3>
								</div>
								<div class="card-body">
									<div class="dash-invoice-content">
									<div class="table-responsive">
										<?php 
										$invoice = $this->db->select("*")
												   ->from('dgt_invoices')
												   ->order_by('inv_id',desc)
												   ->limit(7)
												   ->get()->result_array();
												  
										?>
										<table class="table table-striped custom-table m-b-0">
											<thead>
												<tr>
													<th>Invoice ID</th>
													<th>Client</th>
													<th>Due Date</th>
													<th>Total</th>
													<th>Status</th>
												</tr>
											</thead>
											<tbody>
												<?php 
												foreach($invoice as $invoice_details) {
												$status = Invoice::payment_status($invoice_details['inv_id']);
												switch ($status) {
												case 'fully_paid': $label2 = 'bg-inverse-success';  break;
												case 'partially_paid': $label2 = 'bg-inverse-warning'; break;
												case 'not_paid': $label2 = 'bg-inverse-danger'; break;
												case 'cancelled': $label2 = 'bg-inverse-danger'; break;
												}
												$client_details = $this->db->get_where('companies',array('co_id'=>$invoice_details['client']))->row_array();	
												?>
												<tr>
													<td><a href="<?php echo base_url(); ?>invoices/view/<?php echo $invoice_details['inv_id']; ?>"><?php echo $invoice_details['reference_no']?></a></td>
													<td>
														<h2><a href="<?php echo base_url(); ?>companies/view/<?php echo $invoice_details['client']; ?>"><?php echo $client_details['company_name']; ?></a></h2>
													</td>
														<td><?php echo date('d-M-Y',strtotime($invoice_details['due_date'])); ?></td>
													 <td><?=Applib::format_currency($invs->currency, Invoice::get_invoice_subtotal($invoice_details['inv_id']))?></td>

													<td> 
														<span class="badge <?php echo $label2; ?>"><?=lang($status)?></span>
													</td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
								</div>
								<div class="card-footer">
									<a href="<?php echo base_url()?>companies">View all clients</a>
								</div>
							</div>
						</div>
						<div class="col-md-6 d-flex">
							<div class="card card-table flex-fill">
								<div class="card-header">
									<h3 class="card-title mb-0">Recent Projects</h3>
								</div>
								<div class="card-body">
									<div class="dash-invoice-content">
									<div class="table-responsive">
										<table class="table table-striped custom-table m-b-0">
											<thead>
												<tr>
													<th class="col-md-3">Project Name </th>
													<th class="col-md-3">Progress</th>
													<th class="text-right col-md-1">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php 
												$this->db->limit(5);
												$this->db->order_by('project_id',asc);
												$all_projects = $this->db->get('projects')->result_array(); 
												foreach($all_projects as $project){
												?>
												<tr>
													<td>
														<h2><a href="<?php echo base_url(); ?>projects/view/<?php echo $project['project_id']; ?>"><?php echo $project['project_title']; ?></a></h2>
														<small class="block text-ellipsis">
															<?php 
															$completed_task_count = $this->db->get_where('tasks',array('project'=>$project['project_id'],'task_progress'=>'100'))->result_array();
															$open_task_count = $this->db->get_where('tasks',array('project'=>$project['project_id'],'task_progress !='=>'100'))->result_array(); ?>
															<span class="text-xs"><?php echo count($open_task_count); ?></span> <span class="text-muted">open tasks, </span>
															<span class="text-xs"><?php echo count($completed_task_count); ?></span> <span class="text-muted">tasks completed</span>
														</small>
													</td>
													<?php $progress = Project::get_progress($project['project_id']); ?>
													<td>
														<div class="progress progress-xs progress-striped">
															<div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="<?php echo $progress.'%'; ?>" style="width: <?php echo $progress.'%'; ?>"></div>
														</div>
													</td>
													<td class="text-right">
														<div class="dropdown dropdown-action">
															<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
															<div class="dropdown-menu dropdown-menu-right">
																<a class="dropdown-item" href="<?php echo base_url()?>projects/edit/<?php echo $project['project_id']?>" title="Edit"><i class="fa fa-pencil m-r-5"></i> Edit</a>
																<a class="dropdown-item" href="<?php echo base_url()?>projects/delete/<?php echo $project['project_id']?>" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
															</div>
														</div>
													</td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
								</div>
								<div class="card-footer">
									<a href="<?php echo base_url()?>projects">View all projects</a>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row d-none">
						<div class="col-sm-12">
							<div class="card m-b-0">
								<div class="card-header">
									<h3 class="card-title mb-0">Calender</h3>
								</div>
								<div class="card-body">
									<div id="calendar"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="notification-box">
					<div class="msg-sidebar notifications msg-noti">
						<div class="topnav-dropdown-header">
							<span>Messages</span>
						</div>
						<div class="drop-scroll msg-list-scroll">
							<ul class="list-box">
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">R</span>
											</div>
											<div class="list-body">
												<span class="message-author">Richard Miles </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item new-message">
											<div class="list-left">
												<span class="avatar">J</span>
											</div>
											<div class="list-body">
												<span class="message-author">John Doe</span>
												<span class="message-time">1 Aug</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">T</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Tarah Shropshire </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">M</span>
											</div>
											<div class="list-body">
												<span class="message-author">Mike Litorus</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">C</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Catherine Manseau </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">D</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Domenic Houston </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">B</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Buster Wigton </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">R</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Rolland Webber </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">C</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Claire Mapes </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">M</span>
											</div>
											<div class="list-body">
												<span class="message-author">Melita Faucher</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">J</span>
											</div>
											<div class="list-body">
												<span class="message-author">Jeffery Lalor</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">L</span>
											</div>
											<div class="list-body">
												<span class="message-author">Loren Gatlin</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">T</span>
											</div>
											<div class="list-body">
												<span class="message-author">Tarah Shropshire</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
							</ul>
						</div>
						<div class="topnav-dropdown-footer">
							<a href="chat.html">See all messages</a>
						</div>
					</div>
				</div>			
		