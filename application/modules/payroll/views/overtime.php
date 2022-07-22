		 <div class="content container-fluid">
				<div class="page-header">
					<div class="row">

						<div class="col-8">

							<h4 class="page-title">Overtime</h4>
							<ul class="breadcrumb">
				                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
				                <li class="breadcrumb-item active">Overtime</li>
				            </ul>
						</div>

						

					</div>
				</div>

				
						<div class="payroll-table">
							<div class="table-responsive">
								<table id="table-overtime" class="table table-striped custom-table m-b-0">
									<thead>
										<tr>
											<th>#</th>
											<th>Username</th>
											<th>RO</th>
											<th>Description</th>
											<th>Date</th>
											<th>Hours</th>
											<th>Status</th>
											
										</tr>
									</thead>
									<tbody>
										<?php

										$overtime=$this->db->order_by('id',DESC)->get('overtime')->result_array();
										 
										if(!empty($overtime))
										{
											$o=1;
											foreach ($overtime as $o_row) {
												

										?>
										<tr>
											<th><?php echo $o++;?></th>
											<?php  $user_details = $this->db->get_where('account_details',array('user_id'=>$o_row['user_id']))->row_array(); ?>
								            <td><?=$user_details['fullname']?></td> 
								            <?php  $ro_details = $this->db->get_where('account_details',array('user_id'=>$o_row['teamlead_id']))->row_array(); ?>
								            <td><?=$ro_details['fullname']?></td>
											<th><?php echo $o_row['ot_description'];?></th>
											<td><?php echo date('d M Y',strtotime($o_row['ot_date']));?></td>
											<td><?php echo $o_row['ot_hours'];?></td>
											<td>
								<?php
									if($o_row['status'] == 0){

										
										// echo '<span class="badge text-white" style="background:#D2691E"> Pending </span>'
										echo'<div class="action-label">
													<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
														<i class="fa fa-dot-circle-o text-warning"></i> Pending
													</a>
												</div>';
									}else if($o_row['status'] == 1){
										echo '<div class="action-label">
													<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
														<i class="fa fa-dot-circle-o text-success"></i> Approved
													</a>
												</div> ';
									}else if($o_row['status'] == 2){
										//echo '<span class="badge badge-danger"> Rejected</span>';
										echo '<div class="action-label">
													<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
														<i class="fa fa-dot-circle-o text-danger"></i> Rejected
													</a>
												</div>';
									}else if($o_row['status'] == 3){
									//	echo '<span class="badge badge-danger"> Cancelled</span>';
										echo '<div class="action-label">
													<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
														<i class="fa fa-dot-circle-o text-danger"></i> Cancelled
													</a>
												</div>';

									}
								?>
								</td>
								
										</tr>

									<?php } } ?>
									</tbody>
								</table>
							</div>
							</div>





					
                </div>



                


