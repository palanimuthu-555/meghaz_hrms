<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-sm-4 col-3">
			<h4 class="page-title"><?=lang('estimates')?></h4>
			<ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active">Estimates</li>
            </ul>
		</div>
		<div class="col-sm-8 col-9 text-right m-b-0">
			
			<?php
			if(App::is_permit('menu_estimates','create'))
			{
			 ?> 
			<a href="<?=base_url()?>estimates/add" class="btn btn-primary m-b-10 add-btn float-right"><i class="fa fa-plus"></i> <?=lang('create_estimate')?></a>
			<?php 
			}
			?>

		</div>
	</div>
	</div>
	
			<div class="row filter-row">
						<div class="col-sm-3 col-6">  
							<div class="form-group form-focus">
								<label class="control-label">From</label>
								<div class="cal-icon"><input class="form-control floating" type="text" id="estimates_from" name="estimates_from"></div>
								<label id="estimates_from_error" class="error display-none" for="estimates_from">From Date Shouldn't be empty</label>
							</div>
						</div>
						<div class="col-sm-3 col-6">  
							<div class="form-group form-focus">
								<label class="control-label">To</label>
								<div class="cal-icon"><input class="form-control floating" id="estimates_to" name="estimates_to" type="text"></div>
								<label id="estimates_to_error" class="error display-none" for="estimates_to">To Date Shouldn't be empty</label>
							</div>
						</div>
						<div class="col-sm-3 col-6"> 
							<div class="form-group form-focus select-focus">
								<label class="control-label">Status</label>
								<select class="select floating form-control" id="estimates_status" name="estimates_status"> 
									<option value="" selected="selected">All Status</option>
									<option value="Pending">Pending</option>
									<option value="Accepted">Accepted</option>
								</select> 
								<label id="estimates_status_error" class="error display-none" for="estimates_status">Please Select a status</label>
							</div>
						</div>
						<div class="col-sm-3 col-6">  
							<a href="javascript:void(0)" class="btn btn-primary btn-block" id="search_estimates_btn"> Search </a>  
						</div>     
                    </div>
                

	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table id="table-estimates" class="table table-striped custom-table m-b-0">
					<thead>
				<tr>
					<th style="width:5px; display:none;"></th>
					<th style="width:5px; display:none;"></th>
					<th class=""><?=lang('estimate')?></th>
					<th class=""><?=lang('client_name')?></th>
					<th class=""><?=lang('status')?></th>
					<th class="col-date"><?=lang('due_date')?></th>
					<th class="col-date"><?=lang('created')?></th>
					<th class="col-currency"><?=lang('amount')?></th>
					<th class="text-right">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($estimates as $key => $e) {
					$label = 'danger';
					if ($e->status == 'Pending'){ $label = "info"; }
					if($e->status == 'Accepted') { $label = "success";  }
				?>
				<tr>
					<td style="display:none;"><?=$e->est_id?></td>
					<td style="display:none;"><?=date('m/d/Y',strtotime($e->due_date));?></td>
					<td>
						<a class="text-info" href="<?php if(App::is_permit('menu_estimates','read')){?><?=base_url()?>estimates/view/<?=$e->est_id?><?php }else{ echo '#';}?>">
							<?=$e->reference_no?>
						</a>
					</td>
					<td>
						<?php echo Client::view_by_id($e->client)->company_name; ?>
					</td>
					<td><span class="badge bg-inverse-<?=$label?>"><?=lang(strtolower($e->status))?> <?php if($e->emailed == 'Yes') { ?><i class="fa fa-envelope-o"></i><?php } ?></span></td>
					<td><?=strftime(config_item('date_format'), strtotime($e->due_date))?></td>
					<td><?=strftime(config_item('date_format'), strtotime($e->date_saved))?></td>
					<td class="col-currency">
						<?=Applib::format_currency($e->currency, Estimate::due($e->est_id));?>
					</td>
					<td class="text-right">
						<div class="dropdown">
							<a data-toggle="dropdown" class="action-icon" href="#"><i class="fa fa-ellipsis-v"></i></a>

							<div class="dropdown-menu float-right">
								<?php if(App::is_permit('menu_estimates','write')) { ?>  
								<a class="dropdown-item" href="<?=base_url()?>estimates/edit/<?=$e->est_id?>"><i class="fa fa-pencil m-r-5" aria-hidden="true"></i><?=lang('edit_estimate')?></a>
								<a class="dropdown-item" href="<?=base_url()?>estimates/timeline/<?=$e->est_id?>"><i class="fa fa-history m-r-5" aria-hidden="true"></i><?=lang('estimate_history')?></a>
								<?php } ?>
								
								
								<a class="dropdown-item" href="<?=base_url()?>estimates/email/<?=$e->est_id?>" data-toggle="ajaxModal" 
								title="<?=lang('email_estimate')?>"><i class="fa fa-envelope-o m-r-5" aria-hidden="true"></i><?=lang('email_estimate')?></a>
								
								
								<?php
								if(App::is_permit('menu_estimates','read'))
								{
								?>  
								<a class="dropdown-item" href="<?=base_url()?>estimates/view/<?=$e->est_id?>"><i class="fa fa-eye m-r-5" aria-hidden="true"></i> <?=lang('view_estimate')?></a>
								<?php
								}
								?>
								<?php if (config_item('pdf_engine') == 'invoicr') : ?>
								<a class="dropdown-item" href="<?=base_url()?>fopdf/estimate/<?=$e->est_id?>"><i class="fa fa-file-pdf-o m-r-5" aria-hidden="true"></i> <?=lang('pdf')?></a>
								<?php elseif(config_item('pdf_engine') == 'mpdf') : ?>
								<a class="dropdown-item" href="<?=base_url()?>estimates/pdf/<?=$e->est_id?>"><i class="fa fa-file-pdf-o m-r-5" aria-hidden="true"></i><?=lang('pdf')?></a>

								<?php endif; ?>
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
</div>
