				<!-- Page Content -->
                <div class="content container-fluid">
				<div class="page-header">
					<!-- Page Title -->
					<div class="row">
						<div class="col-sm-5 col-5">
							<h4 class="page-title">Resignation</h4>
							<ul class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active">Resignation</li>
            </ul>
						</div>
						<div class="col-sm-7 col-7 text-right m-b-30">
							<?php
							if(App::is_permit('menu_resignation','create'))
							{
								if($this->session->userdata('role_id') == 3){
									$resignation = $this->db->get_where('resignation',array('employee'=>$this->session->userdata("user_id")))->row_array();
									if(empty($resignation)){?>
										<a href="#" class="btn add-btn" onclick="add_resignation()"><i class="fa fa-plus"></i> Add Resignation</a>
									<?php }
								}else{?>
									<a href="#" class="btn add-btn" onclick="add_resignation()"><i class="fa fa-plus"></i> Add Resignation</a>
							<?php 	}
							?>
							
							<?php
							}
							?>
						</div>
					</div>
				</div>
					<!-- /Page Title -->
					
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
							
								<table class="table table-striped custom-table mb-0" id="resignation_table">
									<thead>
										<tr>
											<th style="width: 30px;">#</th>
											<th>Resigning Employee </th>
											<th>Department </th>
											<th>Reason </th>
											<th>Notice Date </th>
											<th>Resignation Date </th>
											<?php
											if(App::is_permit('menu_resignation','write')==true || App::is_permit('menu_resignation','delete')==true)
											{
											?>
											<th class="text-right">Action</th>
											<?php
											}
											?>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>

							</div>
						</div>
					</div>
				
                </div>
				<!-- /Page Content -->
				
				<!-- Add Resignation Modal -->
				<div id="add_resignation" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Resignation</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form action="#"  id="add_resignations" method="post" enctype="multipart/form-data" data-parsley-validate novalidate > 
									<input type="hidden" name="id">
									<?php if($this->session->userdata('role_id') == 3){?>
										<input type="hidden" name="employee" value="<?php echo $this->session->userdata('user_id')?>" id="employee_id" >
										<input type="hidden" name="resignationdate" value="<?php echo date('Y-m-d');?>" id="resignationdate">
								   	<?php } else{?>
										<div class="form-group">
											<label>Resigning Employee <span class="text-danger">*</span></label>											
									   		<select class="select2-option" style="width:100%;" name="employee" id="employee_id" > 
											<?php foreach (User::employee() as $key => $user) { ?>
											<option value="<?php echo $user->id;?>"><?=ucfirst(User::displayName($user->id))?></option>
											<?php } ?>
										   </select>									   
										</div>	

									<div class="form-group">
										<label>Notice Date <span class="text-danger">*</span></label>
										<div class="cal-icon">
											<input type="text" name="noticedate" id="noticedate" class="form-control datetimepicker">
										</div>
									</div>
									<div class="form-group">
										<label>Resignation Date <span class="text-danger">*</span></label>
										<div class="cal-icon">
											<input type="text" name="resignationdate" id="resignationdate" class="form-control datetimepicker">
										</div>
									</div>
									<?php } ?>	
									<div class="form-group">
										<label>Reason <span class="text-danger">*</span></label>
										<textarea class="form-control" name="reason" id="reason" rows="4"></textarea>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn" id="btnSave">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Resignation Modal -->
				
				
				
				<!-- Delete Resignation Modal -->
				<div class="modal custom-modal fade" id="delete_resignation" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body text-center">
								<div class="form-head">
									<h3>Delete Resignation</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
											<a href="javascript:void(0);" id="delete_resignations" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary continue-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Resignation Modal -->