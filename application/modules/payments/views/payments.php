<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-sm-12">
			<h4 class="page-title"><?=lang('payments')?></h4>
			<ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active">Payments</li>
            </ul>
		</div>
	</div>
</div>
	
	<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
		<table id="table-payments" class="table table-striped custom-table m-b-0 AppendDataTables">
			<thead>
				<tr>
					<th style="width:5px; display:none;"></th>
					<th class=""><?=lang('invoice')?></th>
					<th class=""><?=lang('client')?></th>
					<th class="col-date"><?=lang('payment_date')?></th>
					<th class="col-date"><?=lang('invoice_date')?></th>
					<th class="col-currency"><?=lang('amount')?></th>
					<th class=""><?=lang('payment_method')?></th>
					<th class="">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($payments as $key => $p) { ?>
				<tr>
				<?php
				$currency = Invoice::view_by_id($p->invoice)->currency;
				$invoice_date = Invoice::view_by_id($p->invoice)->date_saved;
				$invoice_date = strftime(config_item('date_format'), strtotime($invoice_date));
				?>
					<td style="display:none;"><?=$p->p_id?></td>
					<td>
						<a class="text-info" href="<?php if(App::is_permit('menu_payments','read')){?><?=base_url()?>payments/view/<?=$p->p_id?><?php }else{ echo '#';}?>">
							<?php echo Invoice::view_by_id($p->invoice)->reference_no; ?>
						</a>
					</td>
					<td>
						<?php echo Client::view_by_id($p->paid_by)->company_name; ?>
					</td>
					<td><?=strftime(config_item('date_format'), strtotime($p->payment_date));?></td>
					<td><?=$invoice_date?></td>
					<td class="col-currency <?=($p->refunded == 'Yes') ? 'text-lt text-danger' : '' ; ?>">
						<strong><?=Applib::format_currency($currency, $p->amount)?></strong>
					</td>
					<td><?php echo App::get_method_by_id($p->payment_method); ?></td>
					<td class="text-center">
						<div class="dropdown">
							<a href="#" class="action-icon" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>


							<div class="dropdown-menu float-right">                      
									<?php
								if(App::is_permit('menu_payments','read'))
								{
								?>
									<a class="dropdown-item" href="<?=base_url()?>payments/view/<?=$p->p_id?>"><i class="fa fa-eye m-r-5" aria-hidden="true"></i> <?=lang('view_payment')?></a>
								<?php
								}
								?>
									<a class="dropdown-item" href="<?=base_url()?>payments/pdf/<?=$p->p_id?>">
										<i class="fa fa-file-pdf-o m-r-5" aria-hidden="true"></i> <?=lang('pdf')?> <?=lang('receipt')?>
									</a>
								<?php 
								if(App::is_permit('menu_payments','write'))
								{
								?>
								
								<a class="dropdown-item" href="<?=base_url()?>payments/edit/<?=$p->p_id?>"><i class="fa fa-pencil m-r-5" aria-hidden="true"></i><?=lang('edit_payment')?></a>
								<?php
								}
								?>
								<?php if($p->refunded == 'No'){ ?>
								
									<a class="dropdown-item" href="<?=base_url()?>payments/refund/<?=$p->p_id?>" data-toggle="ajaxModal">
										<i class="fa fa-retweet m-r-5" aria-hidden="true"></i> <?=lang('refunded')?>
									</a>
							
								<?php } 
								if(App::is_permit('menu_payments','delete'))
								{
								?>
								<a class="dropdown-item" href="<?=base_url()?>payments/delete/<?=$p->p_id?>" data-toggle="ajaxModal"><i class="fa fa-trash m-r-5" aria-hidden="true"></i><?=lang('delete_payment')?></a>
								<?php
								}
								?>
								
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