<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-5">
			<h4 class="page-title"><?=lang('tax_rates')?></h4>
			<ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active"><?=lang('tax_rates')?></li>
            </ul>
		</div>
		<div class="col-7 text-right m-b-0">
		<?php
		if(App::is_permit('menu_tax_rates','create'))
		{
		?>
			<a href="<?=base_url()?>invoices/tax_rates/add" data-toggle="ajaxModal" class="btn btn-primary m-b-10 btn-rounded float-right"><i class="fa fa-plus"></i> <?=lang('new_tax_rate')?></a>
		<?php
		}
		?>
		</div>
	</div>
	</div>
	
	<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
		<table id="table-rates" class="table table-striped custom-table m-b-0 AppendDataTables">
			<thead>
				<tr>
					<th class="no-sort"><?=lang('tax_rate_name')?></th>
					<th class="no-sort"><?=lang('tax_rate_percent')?></th>
					<?php
					if(App::is_permit('menu_tax_rates','write')==true|| App::is_permit('menu_tax_rates','delete')==true){

					?>
					<th class="col-options no-sort text-right"><?=lang('options')?></th>
					<?php
					}
					?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($rates as $key => $r) { ?>
				<tr>
					<td><?=$r->tax_rate_name?></td>
					<td><?=$r->tax_rate_percent?> %</td>
				
					<?php
					if(App::is_permit('menu_tax_rates','write')==true|| App::is_permit('menu_tax_rates','delete')==true)
					{
					?>
					
					<td class="text-right">

						<div class="dropdown dropdown-action">
							<a href="#" class="action-icon" data-toggle="dropdown"><i class="material-icons">more_vert</i></a>
							<div class="dropdown-menu dropdown-menu-right">
								<?php if(App::is_permit('menu_tax_rates','write')){?><a class="dropdown-item" href="<?=base_url()?>invoices/tax_rates/edit/<?=$r->tax_rate_id?>" data-toggle="ajaxModal" title="<?=lang('edit_rate')?>"><i class="fa fa-pencil m-r-5"></i> Edit</a><?php }?>
								<?php if(App::is_permit('menu_tax_rates','delete')){?><a class="dropdown-item" href="<?=base_url()?>invoices/tax_rates/delete/<?=$r->tax_rate_id?>" data-toggle="ajaxModal" title="<?=lang('delete_rate')?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a><?php }?>
							</div>
						</div>
					
					</td>
					<?php
					}
					?>
				</tr>
				<?php }  ?>
			</tbody>
		</table>
		</div>
		</div>
	</div>
</div>
