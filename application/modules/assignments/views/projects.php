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
			<div class="view-icons">
				<a href="<?php echo base_url(); ?>assignments" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>
				<a href="<?php echo base_url(); ?>assignments/grid_view" class="grid-view btn btn-link "><i class="fa fa-th"></i></a>
			</div>
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
				// $progress = Project::get_progress($p->project_id); ?>
				<tr >
					<td style="display:block;"><?=$j?></td>
					<td><?=$p->assignment_name?></td>
					<td><?=$p->fullname?></td>
					<td><?php
					
					echo 'W2';
					?></td>
					<td><?=date("d-m-Y", strtotime($p->start_date));?></td>
					<td><?=date("d-m-Y", strtotime($p->due_date));?></td>
					<td>Active</td>
					<td class="text-right">
						<div class="dropdown">
							<a data-toggle="dropdown" class="action-icon" href="#"><i class="fa fa-ellipsis-v"></i></a>
							<div class="dropdown-menu float-right dropdown-menu-right">

								<?php
								if(App::is_permit('menu_projects','read')){
								?>
									<a class="dropdown-item" href="<?=base_url()?>projects/view/<?=$p->project_id?>"><i class="fa fa-eye m-r-5"></i>Preview Assignment</a>
								<?php
								}
								?>
								<?php 
								if(App::is_permit('menu_projects','write')){
								?>   
		
									<a class="dropdown-item" href="<?=base_url()?>projects/edit/<?=$p->project_id?>"><i class="fa fa-pencil m-r-5"></i>Edit Assignment</a>
								
								<?php if ($archive) : ?>
								<a class="dropdown-item" href="<?=base_url()?>projects/archive/<?=$p->project_id?>/0">Archive Assignment</a>  
								<?php else: ?>
								
									<a class="dropdown-item" href="<?=base_url()?>projects/archive/<?=$p->project_id?>/1"><i class="fa fa-archive m-r-5" aria-hidden="true"></i>Archive Assignment</a>
								       
								<?php endif; ?>
								<?php  
								}
								?>  
								<?php 
								if(App::is_permit('menu_projects','delete')){
								?> 
							
									<a class="dropdown-item" href="<?=base_url()?>projects/delete/<?=$p->project_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i>Delete Assignment</a>
								
								<?php
								}
								?>
							</div>
						</div>
					</td>
				</tr>
				<?php $j++;  } ?>
			</tbody>
		</table>
								</div>
								</div>
	
</div>
</div>