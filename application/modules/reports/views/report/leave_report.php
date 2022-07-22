<script src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="<?=base_url()?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/daterangepicker/daterangepicker.css"/> 
				<!-- Page Content -->
                <div class="content container-fluid">
					<div class="page-header">
			<div class="row">
				<div class="col-sm-8">
					<h4 class="page-title m-b-0"><?=lang('leave_report')?></h4>
					<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>reports">Dashboard</a></li>
									<li class="breadcrumb-item active">Leave Reports</li>
								</ul>
				</div>
				<div class="col-sm-4 text-right">
					<!-- <a class="btn btn-white m-r-5" href="javascript: void(0);" id="filter_search">
						<i class="fa fa-filter m-r-0"></i>
					</a> -->
					 <div class="btn-group">
			            <button class="btn btn-light m-b-10 dropdown-toggle" data-toggle="dropdown"><?=lang('export')?></button>
			           
			            <ul class="dropdown-menu export" style="left:auto; right:0px !important; min-width: 93px !important">  
			              <li>
			                <form method="post" action="">
			                    <input type="hidden" class="form-control" name = "pdf" value="1">
			                    
			                    <input type="hidden" class="form-control department_id_excel" name = "department_id" value="<?php echo (isset($_POST['department_id']) && !empty($_POST['department_id']))?$_POST['department_id']:'';?>">
			                    <input type="hidden" class="form-control teamlead_id_excel" name = "teamlead_id" value="<?php echo (isset($_POST['teamlead_id']) && !empty($_POST['teamlead_id']))?$_POST['teamlead_id']:'';?>">
			                    <input type="hidden" class="form-control range_excel" name = "range" value="<?php echo (isset($_POST['range']) && !empty($_POST['range']))?$_POST['range']:'';?>">
			                    <input type="hidden" class="form-control user_id_excel" name = "user_id" value="<?php echo (isset($_POST['user_id']) && !empty($_POST['user_id']))?$_POST['user_id']:'';?>">
			                    <button class=" btn  btn-block" type="submit" style="text-align: left;"> <span style="font-size: 18px;text-align: left;"><i class="fa fa-file-pdf-o"></i></span> <span><?=lang('pdf')?></span></button>
			                     <!-- <a href="#" class="pull-right" id="attendance_report_pdf1" type="submit"> -->
			                     
			                      <!-- </a> -->
			                </form>
			               
			              </li>
			              <li>
                <?php  $report_name = lang('leave_report');?>
                 <button class="btn  btn-block" onclick="leave_report_excel('<?php echo $report_name;?>','excel_export_id');" style="text-align: left;"> <span style="font-size: 18px;text-align: left;"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span><span><?=lang('excel')?></span> </button>
              </li>
			            </ul>
			          </div>
					
					<?=$this->load->view('report_header');?>
					<?php if($this->uri->segment(3) && count($employees)> 0 ){ ?>
					<a href="<?=base_url()?>reports/employeepdf/<?=$company_id;?>" class="btn btn-primary pull-right">
						<i class="fa fa-file-pdf-o"></i> <?=lang('pdf')?>
					</a>
					<?php } ?>
				</div>
			</div>

		</div>
					<!-- Page Header -->
					<!-- <div class="page-header">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="page-title">Leave Reports</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
									<li class="breadcrumb-item active">Leave Reports</li>
								</ul>
							</div>
						</div>
					</div> -->
					<!-- /Page Header -->
					
						<!-- Content Starts -->
						<!-- Search Filter -->
					
			<form method="post" action="" class="filter-form" id="filter_inputs">
				<div class="row filter-row">
				
		          <div class="col-md-3 ">
		            <div class="form-group form-focus select-focus">
		              <label class="control-label"><?=lang('employees')?></label>
		              <select class="select2-option form-control floating" name="user_id" id="user_name">
		                    <optgroup label="">
		                    <option value=""><?php echo lang('select_employee');?></option> 
		                        <?php 
		                        

		                        $employee = $this->db->get_where('users',array('role_id'=>3,'activated'=>1,'banned'=>0))->result();


		                        foreach ($employee as $c): 
		                        ?>

		                            <option value="<?php echo $c->id;?>" <?php echo(isset($_POST['user_id']) && $_POST['user_id'] == $c->id)?"selected":"";?>><?php echo User::displayName($c->id);?></option>
		                        <?php endforeach;  ?>
		                    </optgroup>
		                </select>
		            </div>
		          </div>
		          <?php $departments = $this->db->order_by("deptname", "asc")->get_where('departments')->result(); ?>
		          <div class="col-md-3 col-lg-2">
		            <div class="form-group form-focus select-focus">
		              <label class="control-label"><?=lang('department')?></label>
		              <select class="select2-option form-control floating" name="department_id" id="department" >
		                    <option value="" selected ><?php echo lang('select_department');?></option>
		                    <?php
		                    if(!empty($departments))  {
		                    foreach ($departments as $department){ ?>
		                    <option value="<?=$department->deptid?>" <?php echo (isset($_POST['department_id']) && ($_POST['department_id'] == $department->deptid))?"selected":""?>><?=$department->deptname?></option>
		                    <?php } ?>
		                    <?php } ?>
		                  </select>
		            </div>
		          </div>
		         
		          <?php 
		          	
		          $teamlead_id = $this->db->where(array('role_id'=>3,'activated'=>1,'banned'=>0,'is_teamlead'=>'yes')) -> get('users')->result(); ?>
		          <div class="col-md-3 col-lg-2">
		            <div class="form-group select-focus form-focus">
		              <label class="control-label"><?=lang('employees_boss')?></label>
		              <select class="select2-option form-control floating" name="teamlead_id" id="teamlead_id" >
		                    <option value="" selected ><?php echo lang('select_boss');?></option>
		                    <?php
		                    if(!empty($teamlead_id))  {
		                    foreach ($teamlead_id as $teamlead){ ?>
		                    <option value="<?=$teamlead->id?>" <?php echo (isset($_POST['teamlead_id']) && ($_POST['teamlead_id'] == $teamlead->id))?"selected":""?>><?php echo User::displayName($teamlead->id);?></option>
		                    <?php } ?>
		                    <?php } ?>
		                  </select>
		            </div>
		          </div>
		          <div class="col-md-3 col-lg-2">
		            <div class="form-group form-focus">
		              <label class="control-label"><?=lang('rangeof_time')?></label>
		              <input type="text" name="range" id="reportrange" class="pull-right floating form-control" value="<?php echo (isset($_POST['range']) && !empty($_POST['range']))?$_POST['range']:'';?>">
		              
		            </div>
		          </div>
					<!-- <div class="col-md-3">
						<div class="form-group">
							<label><?=lang('rangeof_time')?></label>
							<div id="reportrange">
								<i class="fa fa-calendar"></i>&nbsp;
								<span></span> <i class="fa fa-caret-down"></i>
							</div>
						</div>
					</div> -->
					
					<div class="col-md-2">   
						
						<button class="btn btn-success btn-block" type="submit"><?=lang('run_report')?></button>
						<label class="d-block">&nbsp;</label>
					</div>
				</div>
			</form>
		
			<?php 

			if(!empty($_POST['user_id']) || !empty($_POST['department_id']) || !empty($_POST['teamlead_id']) || !empty($_POST['range']))
                { 
                 
                

                  if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                     $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
										FROM `dgt_user_leaves` ul
										left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
										left join dgt_users U on U.id = ul.user_id 
										left join dgt_account_details ad on ad.user_id = U.id
										where ul.user_id = '".$_POST['user_id']."' AND DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  and ul.status =1 order by ul.id  DESC ")->result_array();
                  }
                  if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                    //$dept_users= $this->db->get_where('users',array('subdomain_id'=>$subdomain_id,'department_id'=>$_POST['department_id']))->result_array();
                    $dept_id = $_POST['department_id'];
                   $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
										FROM `dgt_user_leaves` ul
										left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
										left join dgt_users U on U.id = ul.user_id 
										left join dgt_account_details ad on ad.user_id = ul.user_id 
										where U.department_id = '".$dept_id."' AND DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  and ul.status =1 order by ul.id  DESC ")->result_array();
                  }
                  if(isset($_POST['teamlead_id']) && !empty($_POST['teamlead_id'])){
                     $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
										FROM `dgt_user_leaves` ul
										left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
										left join dgt_users U on U.id = ul.user_id 
										left join dgt_account_details ad on ad.user_id = ul.user_id 
										where U.teamlead_id = '".$_POST['teamlead_id']."' AND DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  and ul.status =1 order by ul.id  DESC ")->result_array();
                  }

                  if(isset($_POST['range']) && !empty($_POST['range'])){
                   
                    $date_range = explode('-', $_POST['range']);
                    $start_date = $date_range[0];
                    $end_date = $date_range[1];
                    $start_time=strtotime($start_date);
                    $start_day=date("d",$start_time);
                    $start_month=date("m",$start_time);
                    $start_year=date("Y",$start_time);
                    $end_date=strtotime($end_date);
                    $end_day=date("d",$end_date);
                    $end_month=date("m",$end_date);
                    $end_year=date("Y",$end_date);
                   
                    $from_date = date("Y-m-d", $start_time);       
                      $to_date = date("Y-m-d", $end_date);
                      $earlier = new DateTime($from_date);
                      $later = new DateTime($to_date);

                      $col_count = $later->diff($earlier)->format("%a");

                      $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
										FROM `dgt_user_leaves` ul
										left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
										left join dgt_users U on U.id = ul.user_id 
										left join dgt_account_details ad on ad.user_id = ul.user_id 
										where ((ul.leave_from >= '".$from_date."' AND ul.leave_from >= '".$to_date."') || (ul.leave_to <= '".$from_date."' AND ul.leave_to <= '".$to_date."'))  and ul.status =1 order by ul.id  DESC ")->result_array();
                  } 
              	} else{            	 
	               $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
										FROM `dgt_user_leaves` ul
										left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
										left join dgt_account_details ad on ad.user_id = ul.user_id 
										where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and ul.status =1 order by ul.id  DESC ")->result_array();
          	}?>

					<!-- /Search Filter -->
							<div class="">
								<div class="">
									<div class="table-responsive">
										<table class="table table-hover custom-table table-striped table-center mb-0" id="excel_export_id">
											<thead>
												<tr>
													<th>Employee</th>
													<th>Date</th>
													<th>Department</th>
													<th class="text-center">Leave Type</th>
													<th class="text-center">No of Days</th>
													<th class="text-center">Remaining Leave</th>
													<th class="text-center">Total Leaves</th>
													<th class="text-center">Total Leave Taken</th>
													<th class="text-center">Leave Carry Forward</th>
												</tr>
											</thead>
											<tbody>
											<?php if(!empty($leave_list)){
												$leave_count = array();
												$job_experience = 0;
												$doj = '';
					 							foreach($leave_list as $key => $levs){ 

					 								$doj = $levs['doj'];
													$cr_date = date('Y-m-d');

													$ts1 = strtotime($doj);
													$ts2 = strtotime($cr_date);
													$year1 = date('Y', $ts1);
													$year2 = date('Y', $ts2);
													$month1 = date('m', $ts1);
													$month2 = date('m', $ts2);
													$job_experience = (($year2 - $year1) * 12) + ($month2 - $month1);
													$this->db->select_sum('leave_days');
													if($job_experience < 3){
														$this->db->where('leave_id !=','6');
														$this->db->where('leave_id !=','7');
														$this->db->where('leave_id !=','8');
														$this->db->where('leave_id !=','9');
													} else {
														if($levs['gender'] =='male'){
															$this->db->where('leave_id !=','6');
														}
														if($levs['gender'] =='female'){
															$this->db->where('leave_id !=','7');
														}
													}
													$this->db->where('status','0');
													$total_leave = $this->db->get('dgt_common_leave_types')->row_array();

					 								$this->db->select_sum('leave_days');
													$leave_count = $this->db->get_where('user_leaves',array('user_id'=> $levs['user_id'],'status'=>1,"DATE_FORMAT(leave_from,'%Y')" => date('Y')))->row_array();  
													$carry_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id '=>'2'))->row_array();

													$last_yr = date("Y",strtotime("-1 year"));
// echo $last_yr; exit;
													$carry_days = $this->db->select_sum('leave_days')
																		   ->from('dgt_user_leaves')
																		   ->where('user_id',$levs['user_id'])
																		   ->like('leave_from',$last_yr)
																		   ->like('leave_to',$last_yr)
																		   ->get()->row()->leave_days;

												   	if($carry_days != '')
													{
														$bl_leaves = $carry_days - $total_leave['leave_days']; 
													}else{
														$bl_leaves = 0; 
													}
													if($bl_leaves < 0){			
														$ext_leaves = abs($bl_leaves);
														$ext_leaves = $carry_leaves;
													}else{
														$ext_leaves = 0;
													}
				 									$user = $this->db->get_where('users',array('id'=>$levs['user_id']))->row_array();
						
													if(!empty($user['designation_id'])){
														$designation = $this->db->get_where('designation',array('id'=>$user['designation_id']))->row_array();
														$designation_name = $designation['designation'];
														
													}else{
														$designation_name = '-';
													}
													if(!empty($user['department_id'])){
														$department = $this->db->get_where('departments',array('deptid'=>$user['department_id']))->row_array();
														$department_name = $department['deptname'];
														
													}else{
														$department_name = '-';
													}
													$imgs = '';
									                if($levs['avatar'] != 'default_avatar.jpg'){
									                    $imgs = $levs['avatar'];
									                    
									                }else{
									                    $imgs = "default_avatar.jpg";
									                }
				 								?>
												<tr>
													<td>
														<h2 class="table-avatar">
															<a href="<?php echo base_url().'employees/profile_view/'.$levs['user_id'];?>" class="avatar avatar-sm mr-2"> <img class="avatar" class="avatar-img rounded-circle" src="<?php echo base_url();?>assets/avatar/<?php echo  $imgs;?>" alt="User Image"></a>
															<a class="text-info" href="<?php echo base_url()?>leaves/show_leave/<?=$levs['user_id']?>"><?php echo user::displayName($levs['user_id']);?>
															</a>
														</h2>
													</td>
													<td><?=date('d-m-Y',strtotime($levs['leave_from']))?></td>
													<td><?php echo ucfirst($department_name);?></td>
													<td class="text-center">
														<button class="btn btn-outline-info btn-sm"><?php echo (!empty($levs['l_type']))?$levs['l_type']:''?></button>
													</td>
													<td class="text-center"><span class="btn btn-danger btn-sm"><?php 
							echo $levs['leave_days'];
							if($levs['leave_day_type'] == 1){
								echo ' ( Full Day )';
							}else if($levs['leave_day_type'] == 2){
								echo ' ( First Half )';
							}else if($levs['leave_day_type'] == 3){
								echo ' ( Second Half )';
							}?></span></td>
													<td class="text-center"><span class="btn btn-warning btn-sm"><b><?php echo $total_leave['leave_days'] - $leave_count['leave_days'];?></b></span></td>
													<td class="text-center"><span class="btn btn-success btn-sm"><b><?php echo($total_leave['leave_days'] != 0)?$total_leave['leave_days']:0;?></b></span></td>
													<td class="text-center"><?php echo ($leave_count['leave_days'] !='')?$leave_count['leave_days']:0;?></td>
													<td class="text-center"><?php echo $ext_leaves;?></td>
												</tr>
											
											<?php }
										}?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
					
                
					<!-- /Content End -->
					
                </div>
				<!-- /Page Content -->
				
    <script>
	// var start = moment().subtract(29, 'days');
	var start = moment();
	var end = moment();

	$('#reportrange').daterangepicker({
		// startDate: start,
		// endDate: end,
		ranges: {
		   'Today': [moment(), moment()],
		   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
		   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		   'This Month': [moment().startOf('month'), moment().endOf('month')],
		   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		}
	});
	
// var present= '<?php echo $present;?>';	
// var absent= '<?php echo $absents;?>';	
// var late=  '<?php echo $late;?>';	
// alert(present);
// alert(absent);
// alert(late);


</script>