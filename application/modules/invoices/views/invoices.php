<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-sm-4 col-3">
			<h4 class="page-title"><?=lang('invoices')?></h4>
			<ul class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
				<li class="breadcrumb-item active"><?=lang('invoices')?></li>
			</ul>
		</div>
		<div class="col-sm-8 col-9 text-right m-b-0">
			<?php

			if(App::is_permit('menu_invoices','create'))
			{
			?>
			<a href="<?=base_url()?>invoices/add" class="btn btn-primary add-btn m-b-10 float-right"><i class="fa fa-plus"></i> <?=lang('create_invoice')?></a>
			<?php 
			}
			?>

			<div class="btn-group float-right m-r-10 m-b-10">
				<button class="btn btn-light dropdown-toggle" data-toggle="dropdown">
				  <?php
				  $view = isset($_GET['view']) ? $_GET['view'] : NULL;
				  switch ($view) {
					case 'paid':
					  echo lang('paid');
					  break;
					case 'unpaid':
					  echo lang('not_paid');
					  break;
					case 'partially_paid':
					  echo lang('partially_paid');
					  break;
					case 'recurring':
					  echo lang('recurring');
					  break;

					default:
					  echo lang('filter');
					  break;
				  }
				  ?>
				  </button>
				 
				<div class="dropdown-menu">
					<a class="dropdown-item" href="<?=base_url()?>invoices?view=paid"><?=lang('paid')?></a>
					<a class="dropdown-item" href="<?=base_url()?>invoices?view=unpaid"><?=lang('not_paid')?></a>
					<a class="dropdown-item" href="<?=base_url()?>invoices?view=partially_paid"><?=lang('partially_paid')?></a>
					<a class="dropdown-item" href="<?=base_url()?>invoices?view=recurring"><?=lang('recurring')?></a>
					<a class="dropdown-item" href="<?=base_url()?>invoices"><?=lang('all_invoices')?></a>
				</div>
			</div>
		</div>
	</div>
</div>

	<div class="row filter-row">
						<div class="col-sm-3 col-6">  
							<div class="form-group form-focus">
								<label class="control-label">From</label>
								<div class="cal-icon">
									<input class="form-control floating" id="invoice_date_from" type="text"></div>
									<label id="invoice_date_from_error" class="error display-none" for="invoice_date_from">From Date Shouldn't be empty</label>
							</div>
						</div>
						<div class="col-sm-3 col-6">  
							<div class="form-group form-focus">
								<label class="control-label">To</label>
								<div class="cal-icon">
									<input class="form-control floating" id="invoice_date_to" type="text"></div>
									<label id="invoice_date_to_error" class="error display-none" for="invoice_date_to">To Date Shouldn't be empty</label>
							</div>
						</div>
						<div class="col-sm-3 col-6"> 
							<div class="form-group form-focus select-focus ">
								<label class="control-label">Status</label>
								<select class="select floating form-control" id="invoices_status" name="invoices_status"> 
									<option value="" selected="selected">All Invoices</option>
									<option value="Fully">Paid</option>
									<option value="Not">Not Paid</option>
									<option value="Partial">Partial</option>
									<option value="Recurring">Recurring</option>
								</select> 
								<label id="invoices_status_error" class="error display-none" for="invoices_status">Please Select a status</label>
							</div>
						</div>
						<div class="col-sm-3 col-6">  
							<a href="javascript:void(0)" class="btn btn-success btn-block" id="tableinvoices_btn"> Search </a>  
						</div>     
                    </div>
                

	<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
		<table id="table-invoices" class="table table-striped custom-table m-b-0">
			<thead>
				<tr>
					<th style="width:5px; display:none;"></th>
					<th style="width:5px; display:none;"></th>
					<th style="width:5px; display:none;"></th>
					<th class=""><?=lang('invoice')?></th>
					<th class=""><?=lang('company_name')?></th>

					<th class="col-date"><?=lang('due_date')?></th>
					<th class="col-currency text-right"><?=lang('amount')?></th>
					<th class="col-currency text-right"><?=lang('due_amount')?></th>
					<th class="text-center"><?=lang('status')?></th>
					<th class="text-right" data-orderable="false">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($invoices as $key => &$inv) {
				$status = Invoice::payment_status($inv->inv_id);
				switch ($status) {
					case 'fully_paid': $label2 = 'success';  break;
					case 'partially_paid': $label2 = 'warning'; break;
					case 'not_paid': $label2 = 'danger'; break;
					case 'cancelled': $label2 = 'primary'; break;
				}
				?>
				<tr class="<?=($inv->status == 'Cancelled') ? 'text-danger' : '';?>">
					<td style="display:none;"><?=$inv->inv_id?></td>
					<td style="display:none;"><?php echo date('m/d/Y',strtotime($inv->due_date)); ?></td>
					<td style="display:none;"><?php
						$status1 = explode('_', $status);
						$status1 = current($status1);
					 echo ucfirst($status1); ?></td>
					<td><a class="text-info" href="<?php if(App::is_permit('menu_invoices','read')){?><?=base_url()?>invoices/view/<?=$inv->inv_id?><?php }else{ echo '#';}?>">
							<?=$inv->reference_no?>
						</a>
					</td>
					<td>
						<?php echo Client::view_by_id($inv->client)->company_name; ?>
					</td>
					
					<td><?=strftime(config_item('date_format'), strtotime($inv->due_date))?></td>
					<td class="col-currency text-right" style="text-align: right;">
						<?=Applib::format_currency($inv->currency, Invoice::get_invoice_subtotal($inv->inv_id))?>
					</td>
					<td class="col-currency text-right" style="text-align: right;">
						<?=Applib::format_currency($inv->currency, Invoice::get_invoice_due_amount($inv->inv_id));?>
					</td>
					<td class="text-center">
						<span class="badge bg-inverse-<?=$label2?>"><?=lang($status)?> <?php if($inv->emailed == 'Yes') { ?><i class="fa fa-envelope-o"></i><?php } ?></span>
						<?php if ($inv->recurring == 'Yes') { ?>
						<span class="badge badge-primary"><i class="fa fa-retweet"></i></span>
						<?php } ?>
					</td>
					<td class="text-right">
						<div class="dropdown">
							<a data-toggle="dropdown" class="action-icon" href="#"><i class="fa fa-ellipsis-v"></i></a>
							<div class="dropdown-menu float-right dropdown-menu-right">
								<?php
								if(App::is_permit('menu_invoices','read'))
								{
								?>
									<a class="dropdown-item" href="<?=base_url()?>invoices/view/<?=$inv->inv_id?>">
										<i class="fa fa-eye m-r-5" aria-hidden="true"></i><?=lang('preview_invoice')?>
									</a>
								<?php
								}
								 
								if(App::is_permit('menu_invoices','write'))
								{?>
								
									<a class="dropdown-item" href="<?=base_url()?>invoices/edit/<?=$inv->inv_id?>">
										<i class="fa fa-pencil m-r-5" aria-hidden="true"></i><?=lang('edit_invoice')?>
									</a>
								
								<?php  
								}
								?>
						
									<a class="dropdown-item" href="<?=base_url()?>invoices/timeline/<?=$inv->inv_id?>">
										<i class="fa fa-file-text-o m-r-5" aria-hidden="true"></i><?=lang('invoice_history')?>
									</a>
							
								<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'email_invoices')) { ?>
							
									<a class="dropdown-item" href="<?=base_url()?>invoices/send_invoice/<?=$inv->inv_id?>" data-toggle="ajaxModal" title="<?=lang('email_invoice')?>">
										<i class="fa fa-envelope-o m-r-5" aria-hidden="true"></i><?=lang('email_invoice')?>
									</a>
						
								<?php } ?>
								<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'send_email_reminders')) { ?>
							
									<a class="dropdown-item" href="<?=base_url()?>invoices/remind/<?=$inv->inv_id?>" data-toggle="ajaxModal" title="<?=lang('send_reminder')?>">
										<i class="fa fa-bell-o m-r-5" aria-hidden="true"></i><?=lang('send_reminder')?>
									</a>
							
								<?php } ?>
								<?php if(config_item('pdf_engine') == 'invoicr') : ?>
							
									<a class="dropdown-item" href="<?=base_url()?>fopdf/invoice/<?=$inv->inv_id?>"><i class="fa fa-file-pdf-o m-r-5" aria-hidden="true"></i> <?=lang('pdf')?></a>
							
								<?php elseif(config_item('pdf_engine') == 'mpdf') : ?>
							
									<a class="dropdown-item" href="<?=base_url()?>invoices/pdf/<?=$inv->inv_id?>"><i class="fa fa-file-pdf-o m-r-5" aria-hidden="true"></i><?=lang('pdf')?></a>
							
								<?php endif; ?>
								<?php 
								if(App::is_permit('menu_invoices','delete'))
								{
								 ?>
									<a class="dropdown-item" href="<?= base_url() ?>invoices/delete/<?= $inv->inv_id ?>" data-toggle="ajaxModal">
										<i class="fa fa-trash-o m-r-5" aria-hidden="true"></i><?=lang('delete_invoice')?>
									</a>
						
								<?php 
								}
								?>
								<!-- <li>
									<a data-invid="<?= $inv->inv_id ?>" data-target="#clone_invoice<?= $inv->inv_id ?>" data-toggle="modal">
										Clone Invoice
									</a>
								</li> -->
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

<?php foreach($invoices as $invoice){ ?>

<div class="modal fade" id="clone_invoice<?= $invoice->inv_id ?>" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Clone Invoice</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>Do you want clone this Invoice(<?= $invoice->reference_no ?>)?</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button class="btn btn-primary clone_invoice_btn" data-invoice="<?= $invoice->inv_id ?>">Yes</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>