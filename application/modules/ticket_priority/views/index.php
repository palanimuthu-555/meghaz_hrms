<div class="content">

	<!-- Page Header -->
	<div class="page-header">
		<div class="row align-items-center">
			<div class="col-sm-7">
				<h3 class="page-title"><?=lang('priority')?></h3>
				 <ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
					<li class="breadcrumb-item active"><?=lang('priority')?></li>
				</ul> 
			</div>
			<div class="col-sm-5 text-right m-b-20">
			<?php if(App::is_permit('menu_priority','create')){
			?>
				<a class="btn add-btn" href="<?=base_url()?>ticket_priority/add/" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Priority</a>
			<?php 
			}
			?>
			</div>
		</div>
	</div>
	<!-- /Page Header -->

        
    <div class="row">
        <div class="col-lg-12">
			<div class="table-responsive">
				<table id="wiki" class="table table-striped custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>                                 
							<th>Hours</th>
							<?php if(App::is_permit('menu_priority','write')==true || App::is_permit('menu_priority','delete')==true)
							{
							?>
							<th class="col-options no-sort text-right"><?=lang('action')?></th>
							<?php
							}
							?>
						</tr>
					</thead>
					<tbody>
						<?php                                      
							if (!empty($priorities)) 
							{
								$j =1;
								foreach ($priorities as $key => $priority) 
								{ ?>
									<tr>
										<td> <?php echo $j; ?> </td>
										<td> <?=ucfirst($priority->priority);?> </td> 
										<td> <?=$priority->hour?> </td>
										<?php if(App::is_permit('menu_priority','write')==true || App::is_permit('menu_priority','delete')==true)
										{
										?>             
										<td class="text-right"> 
											<!-- <a href="<?=base_url()?>ticket_priority/edit/<?=$priority->id?>" class="btn btn-success btn-sm" data-toggle="ajaxModal">
												<i class="fa fa-edit"></i>
											</a>
											<a href="<?=base_url()?>ticket_priority/delete/<?=$priority->id?>" class="btn btn-danger btn-sm" data-toggle="ajaxModal" title="" data-original-title="Delete">
												<i class="fa fa-trash-o"></i>
											</a> -->
											
											<div class="dropdown dropdown-action">
												<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>

												<div class="dropdown-menu dropdown-menu-right">
													<?php if(App::is_permit('menu_priority','write')){?><a class="dropdown-item" href="<?=base_url()?>ticket_priority/edit/<?=$priority->id?>"  data-toggle="ajaxModal"><i class="fa fa-pencil m-r-5"></i><?=lang('edit')?></a><?php }?>
													<?php if(App::is_permit('menu_priority','delete')){?><a class="dropdown-item" href="<?=base_url()?>ticket_priority/delete/<?=$priority->id?>"  data-toggle="ajaxModal" data-original-title="Delete"><i class="fa fa-trash-o m-r-5"></i><?=lang('delete')?></a><?php }?>
												</div>

											</div>                            
										</td>
										<?php
										}
										?>
									</tr>
									<?php $j++; 
								} 
							} 
							else
							{ ?>
								<tr><td colspan="4" class="text-center">No Results</td></tr>
							<?php 
							} ?>
					</tbody>
				</table>
			</div>
        </div>
    </div>


</div>