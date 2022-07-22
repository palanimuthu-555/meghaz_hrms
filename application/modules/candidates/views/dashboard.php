<?php
	$jtype=array(0=>'unassigned');
	    foreach ($offer_jobtype as $jkey => $jvalue) {
	            $jtype[$jvalue->id]=$jvalue->job_type;                        
	     }
	
	?>
<div class="content p-t-10">
	<div class="page-header">
<div class="row">
	<div class="col-12">
		<h4 class="page-title"><?php echo lang('my_jobs');?></h4>
		 <ul class="breadcrumb p-l-0" style="background:none; border:none;">
			<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
			<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/dashboard">Recruiting Process</a></li>
			<li class="breadcrumb-item">Dashboard</li>
			</ul> 
	</div>
</div>
</div>
<div class="row admin-dash">
	<div class="col-md-6 col-sm-6 col-lg-3 d-flex">
		<div class="dash-widget card flex-fill">
		<div class="card-body">
			<span class="dash-widget-icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
			<div class="dash-widget-info">
				<span><?php echo lang('offered');?></span>
				<h3><?php echo count($offered_jobs);?></h3>
			</div>
		</div>
	</div>
	</div>
	<div class="col-md-6 col-sm-6 col-lg-3 d-flex">
		<div class="dash-widget card flex-fill">
			<div class="card-body">
				<span class="dash-widget-icon"><i class="fa fa-clipboard icon" aria-hidden="true"></i></span>
				<div class="dash-widget-info">
					<span><?php echo lang('applied');?></span>
					<h3><?php if(isset($job_counts[1])){ echo $job_counts[1]; }else{ echo 0;}?></h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-6 col-lg-3 d-flex">
		<div class="dash-widget card flex-fill">
			<div class="card-body">
				<span class="dash-widget-icon"><i class="fa fa-retweet" aria-hidden="true"></i></span>
				<div class="dash-widget-info">
					<span><?php echo lang('visited');?></span>
					<h3><?php if(isset($total_visited)){ echo $total_visited['count']; }else{ echo 0;}?></h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-6 col-lg-3 d-flex">
		<div class="dash-widget card flex-fill">
			<div class="card-body">
				<span class="dash-widget-icon"><i class="fa fa-floppy-o" aria-hidden="true"></i></span>
				<div class="dash-widget-info">
					<span><?php echo lang('saved');?></span>
					<h3><?php if(isset($job_counts[2])){ echo $job_counts[2]; }else{ echo 0;}?></h3>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6 d-flex">
		<div class="card chart-div flex-fill">
			<div class="card-body">
				<h3 class="card-title"><?php echo lang('overview');?></h3>
				<div class="row">
					<div class="col-md-12 m-b-10">
						<canvas id="canvas_rec_user"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 d-flex">
		<div class="card flex-fill">
		<div class="card-body">
			<h3 class="card-title"><?php echo lang('latest_jobs'); ?></h3>
			<div class="list-group" style="min-height:245px;height:245px;overflow:auto;">
				<?php foreach($latest_jobs as $key => $latest){?>
<a href="<?php echo base_url(); ?>candidates/job_view/<?php echo $latest['id'];?>" class="text-capitalize list-group-item"><?php echo $latest['job_title'];?><span class="float-right" style="font-size:12px; color: #a1a1a1;"><?php if($latest['days']>0){ echo $latest['days'].' days ago';}else{ echo $latest['hour'].' hours ago';}?></span></a>
<?php
					} ?>
			</div>
		</div>
	</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<h3 class="card-title"><?php echo lang('offered_jobs');?></h3>
				<div class="table-responsive">
					<div class="table-responsive">
						<table class="table table-striped custom-table mb-0 datatable">
							<thead>
								<tr>
									<th>#</th>
									<th><?php echo lang('job_title');?></th>
									<th><?php echo lang('department');?></th>
									<th class="text-center"><?php echo lang('job_types');?></th>
									<th class="text-center"><?php echo lang('status');?></th>
									<th class="text-center"><?php echo lang('action')?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i=1;
								foreach ($offered_jobs as $key=>$offer) {
								?>
								<tr>
									<td><?php echo $i++; ?></td>
									<td class="text-capitalize"><a href="<?php echo base_url(); ?>candidates/job_view/<?php echo $offer->id;?>"><?php echo $offer->job_title;?></a></td>
									<td><?php echo $offer->deptname ?></td>
									<td class="text-center">
										<div class="action-label">
											<a class="btn btn-white btn-sm btn-rounded" href="#">
											<i class="fa fa-dot-circle-o text-danger"></i> <?php echo $offer->job_type;?>
											</a>
										</div>
									</td>
									<td class="text-center">
										<div class="action-label">
											<a class="btn btn-success btn-sm btn-rounded" href="#" >
											<?php 
											if($offer->user_job_status==7){ echo lang('offered'); }
											if($offer->user_job_status==8){ echo lang('offer_accepted'); }
											if($offer->user_job_status==9){ echo lang('offer_rejected'); }
											if($offer->user_job_status==10){ echo lang('offer_declined'); }
											?>
											</a>
										</div>
									</td>
									<td class="text-center">
										<a href="<?php echo site_url('candidates/download_offer/').$offer->id;?>" class="btn btn-sm btn-info download-offer"><span style="font-size:14px;"><i class="fa fa-download m-r-5"></i><?php echo lang('download_offer')?></span></a>
									</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<h3 class="card-title"><?php echo lang('applied_jobs')?></h3>
					<div class="table-responsive">
						<table class="table table-striped custom-table mb-0 datatable">
							<thead>
								<tr>
									<th>#</th>
									<th><?php echo lang('job_title');?></th>
									<th><?php echo lang('department');?></th>
									<th><?php echo lang('start_date');?></th>
									<th><?php echo lang('expire_date');?></th>
									<th class="text-center"><?php echo lang('job_types');?></th>
									<th class="text-center"><?php echo lang('status'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(count($applied_jobs)>0){
								$i=1;
								foreach ($applied_jobs as $key => $list) {
								 	?>
								 	<tr>
									<td><?php echo $i++;?></td>
									<td class="text-capitalize"><a href="<?php echo base_url(); ?>candidates/job_view/<?php echo $list->id;?>"><?php echo $list->job_title;?></a></td>
									<td><?php echo $list->deptname;?></td>
									<td><?php echo date('d M Y',strtotime($list->start_date)); ?></td>
									<td><?php echo date('d M Y',strtotime($list->expire_date)); ?></td>
									
									<td class="text-center">
										<div class="action-label">
											<a class="btn btn-white btn-sm btn-rounded" href="#">
											<i class="fa fa-dot-circle-o text-danger"></i><?php  echo $list->job_type;?>
											</a>
										</div>
									</td>
									<td class="text-center">
										<div class="action-label">
											<a class="btn btn-white btn-sm btn-rounded" href="#" >
											<i class="fa fa-dot-circle-o text-danger"></i> <?php if($list->job_status==0){ echo lang('open');}else{ echo lang('close');}?>
											</a>
										</div>
									</td>
								</tr>
								 	<?php 
								 }
								 } ?>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>