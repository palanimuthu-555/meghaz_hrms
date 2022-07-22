<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-12">
			<h4 class="page-title"><?=lang('templates')?></h4>
			<ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active"><?=lang('templates')?></li>
            </ul>
		</div>
	</div>
</div>

	<div class="row row-eq-height">
		<!-- Project Tasks -->
		<!-- <div class="col-lg-6 row-eq-height">
			<div class="card panel-white panel-eq-dash">
				<div class="card-header">
					<div class="row">
						<div class="col-6">
							<h3 class="card-title m-l-10"><?=lang('project_tasks')?></h3>
						</div>
						<?php
						if(App::is_permit('menu_items','create'))
						{
						?>
						<div class="col-6">
							<a href="<?=base_url()?>items/save_task" class="btn btn-sm btn-success float-right m-r-10" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('add_task')?></a>
						</div>
						<?php
						}
						?>
					</div>
				</div>
				<div class="table-responsive card-body">
					<table id="table-templates-1" class="table table-striped table-bordered AppendDataTables">
						<thead>
							<tr>
								<th><?=lang('task_name')?></th>
								<th><?=lang('visible')?> </th>
								<th><?=lang('estimated_hours')?> </th>
								<?php
								if(App::is_permit('menu_items','write')==true|| App::is_permit('menu_items','delete')==true)
								{
								?>
								<th class="col-options no-sort text-right"><?=lang('action')?></th>
								<?php
								}
								?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($project_tasks as $key => $task) { ?>
							<tr>
								<td>
									<a class="text-muted" href="#" data-original-title="<?=$task->task_desc?>" data-toggle="tooltip" data-placement="right"><?=$task->task_name?></a>
								</td>
								<td><?=$task->visible?></td>
								<td><strong><?=$task->estimate_hours?> <?=lang('hours')?></strong></td>
								<?php
								if(App::is_permit('menu_items','write')==true|| App::is_permit('menu_items','delete')==true)
								{
								
								?>
								<td class="text-right">
									<?php
									if(App::is_permit('menu_items','write'))
									{
									?>
									<a href="<?=base_url()?>items/edit_task/<?=$task->template_id?>" class="btn btn-success btn-sm" data-toggle="ajaxModal">
										<i class="fa fa-edit"></i>
									</a>
									<?php
									}
									if(App::is_permit('menu_items','delete'))
									{
									?>
									<a href="<?=base_url()?>items/delete_task/<?=$task->template_id?>" class="btn btn-danger btn-sm" data-toggle="ajaxModal">
										<i class="fa fa-trash-o"></i>
									</a>
									<?php
									}
									?>
								</td>
								<?php
								}
								?>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div> -->
		<!-- End Project Tasks -->
		<!-- Invoice Items -->
		<div class="col-lg-12 row-eq-height">
			
					<div class="row">
						<div class="col-sm-6">
							<h3 class="card-title m-l-10"><?=lang('invoice_items')?> </h3>
						</div>
						<?php
						if(App::is_permit('menu_items','create'))
						{
						?>
						<div class="col-sm-6">
							<a href="<?=base_url()?>items/add_item" class="btn add-btn float-right m-r-10" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('new_item')?></a>
						</div>
						<?php
						}
						?>
					</div>
				
				
					<div class="table-responsive">
						<table id="table-templates-2" class="table table-striped custom-table AppendDataTables">
							<thead>
								<tr>
									<th><?=lang('item_name')?></th>
									<th><?=lang('unit_price')?> </th>
									<th><?=lang('qty')?> </th>
										<?php
										if(App::is_permit('menu_items','write')==true|| App::is_permit('menu_items','delete')==true)
										{
										?>
									<th class="col-options no-sort text-right"><?=lang('action')?></th>
									<?php
										}
									?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($invoice_items as $key => $item) 
								{ ?>
								<tr>
									<td>
										<a class="text-muted" href="#" data-original-title="<?=$item->item_desc?>" data-toggle="tooltip" data-placement="left" title = "">
											<?=$item->item_name?>
										</a>
									</td>
									<td><?=Applib::format_currency(config_item('default_currency'), $item->unit_cost)?></td>
									<td><?=$item->quantity?></td>
									<?php
									if(App::is_permit('menu_items','write')==true|| App::is_permit('menu_items','delete')==true)
									{
									?>
									<td class="text-right">

										<div class="dropdown dropdown-action">
										<a href="#" class="action-icon" data-toggle="dropdown"><i class="material-icons">more_vert</i></a>
											<div class="dropdown-menu dropdown-menu-right">
											<?php
											if(App::is_permit('menu_items','write'))
											{
											?>
											<a href="<?=base_url()?>items/edit_item/<?=$item->item_id?>" class="dropdown-item" data-toggle="ajaxModal"><i class="fa fa-edit m-r-5"></i>Edit
											
											</a>
											<?php
											}
											if(App::is_permit('menu_items','delete'))
											{
											?>
											<a href="<?=base_url()?>items/delete_item/<?=$item->item_id?>" class="dropdown-item" data-toggle="ajaxModal">
											<i class="fa fa-trash-o m-r-5"></i>Delete
											</a>
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
								<?php  } ?>
							</tbody>
						</table>
					
				</div>			</div>
		</div>
		<!-- End Invoice Items -->
	</div>
</div>