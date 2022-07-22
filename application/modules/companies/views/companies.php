
<style type="text/css">
	.form-focus.focused .control-label {top: -20px !important;}
</style>
<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-sm-4 col-12">
			<h4 class="page-title"><?=lang('companies')?></h4>
			<ul class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
				<li class="breadcrumb-item active"><?=lang('companies')?></li>
			</ul>
		</div>
		<div class="col-sm-8 col-12 text-right m-b-20">
			<?php
			if(App::is_permit('menu_clients','create'))
			{
			?>
			<a class="btn add-btn" href="<?=base_url()?>companies/create" data-toggle="ajaxModal" title="<?=lang('new_company')?>" data-placement="bottom">
				<i class="fa fa-plus"></i> <?=lang('new_company')?>
			</a>
			<?php
			}
			if(App::is_permit('menu_clients','read'))
			{
			?>
			<div class="view-icons">
				<a href="<?php echo base_url(); ?>companies" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>
				<a href="<?php echo base_url(); ?>companies/grid_companies" class="grid-view btn btn-link "><i class="fa fa-th"></i></a>
			</div>
			<?php
			}
			?>
		</div>
	</div>
</div>

		<div class="row filter-row">
			<div class="col-lg-4 col-sm-6">  
				<div class="form-group form-focus">
					<label class="control-label">Company</label>
					<input type="text" id="client_name" name="client_name" class="form-control floating client_submit_search">
					<label id="client_name_error" class="error display-none" for="client_name">Company field Shouldn't be empty</label>
				</div>
			</div>
			<div class="col-lg-4 col-sm-6">  
				<div class="form-group form-focus">
					<label class="control-label">Email</label>
					<input type="text" id="client_email" name="client_email" class="form-control floating client_submit_search">
					<label id="client_email_error" class="error display-none" for="client_email">Email field Shouldn't be empty</label>
				</div>
			</div>
			<div class="col-lg-4 col-sm-6">  
				<a href="javascript:void(0)" id="client_search" class="btn btn-primary btn-block form-control" > Search </a>  
			</div>     
        </div>
   

	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive p-0">
			
				<table id="table-clients-compaines" class="table table-striped custom-table m-b-0 ">
					<thead>
						<tr>
							<th># </th>
							<th><?=lang('companies')?> </th>
							<th><?=lang('due_amount')?></th>
							<th><?=lang('expense_cost')?> </th>
							<th class="hidden-sm"><?=lang('primary_contact')?></th>
							<th><?=lang('email')?> </th>

							<?php
							if(App::is_permit('menu_clients','delete')==true || App::is_permit('menu_clients','write')==true) 
							{

							?>
							<th class="col-options no-sort text-right" style="text-align: center;"><?=lang('action')?></th>
						<?php
							}
						?>

						</tr>
					</thead>
					<tbody>
						<?php
						if (!empty($companies)) {
							$i = 1;
						foreach ($companies as $client) { 
						$client_due = Client::due_amount($client->co_id);
						?>
						<tr>
							<td><?php echo $i;?></td>
							<td>
								<h2>
									<a href="<?php if(App::is_permit('menu_clients','read')){?><?=base_url()?>companies/view/<?=$client->co_id?><?php }else{ echo '#';}?>" class="text-info">
										<?=($client->company_name != NULL) ? $client->company_name : '...'; ?>
									</a>
								</h2>
							</td>
							<td>
								<strong><?=Applib::format_currency($client->currency, $client_due)?></strong>
							</td>
							<td>
								<strong <?=(Expense::total_by_client($client->co_id) > 0) ? 'class="text-danger"' : 'class="text-success"';?>>
									<?=Applib::format_currency($client->currency, Expense::total_by_client($client->co_id))?>
								</strong>
							</td>
							<td class="hidden-sm">
								<?php if ($client->individual == 0) { 
									echo ($client->primary_contact) ? User::displayName($client->primary_contact) : 'N/A'; 
								} ?>
							</td>
							<td><?=$client->company_email?></td>
							<?php
								if(App::is_permit('menu_clients','delete') || App::is_permit('menu_clients','write'))
								{
								?>
									<td class="text-right" style="text-align: center;">
										<?php
								if(App::is_permit('menu_clients','write'))
								{
								?>
										<a href="<?=base_url()?>companies/update/<?=$client->co_id?>" class="btn btn-success btn-sm" data-toggle="ajaxModal" title="<?=lang('edit')?>">
											<i class="fa fa-pencil"></i>
									</a>
								<?php } ?>
								<?php
								if(App::is_permit('menu_clients','delete'))
								{
								?>
									<a href="<?=base_url()?>companies/delete/<?=$client->co_id?>" class="btn btn-danger btn-sm" data-toggle="ajaxModal" title="<?=lang('delete')?>">
									<i class="fa fa-trash-o"></i>
									</a>
								<?php } ?>

									</td>
							<?php
								}
								?>
						</tr>
                    <?php $i++; } } ?>
					</tbody>
                </table>
				
			</div>        
		</div>
	</div>

</div>