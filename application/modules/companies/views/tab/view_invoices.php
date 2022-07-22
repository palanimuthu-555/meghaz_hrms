<div class="row">
	<div class="col-md-12">
		<section class="card panel-table">
			<div class="card-header panel-h-p1">
				<h3 class="card-title m-t-5 m-b-5"><?=lang('invoices')?></h3>
			</div>
			<div class="card-body">
			<div class="table-responsive">
				<table id="table-invoices" class="table table-striped custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th style="width:5px; display:none;"></th>
							<th><?=lang('invoice')?></th>
							<th class=""><?=lang('status')?></th>
							<th class="col-date"><?=lang('due_date')?></th>
							<th class="col-currency"><?=lang('amount')?></th>
							<th class="col-currency"><?=lang('due_amount')?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach (Invoice::get_client_invoices($company) as $key => $inv) { ?>
					<?php
					$status = Invoice::payment_status($inv->inv_id);
					switch ($status) {
						case 'fully_paid': $label2 = 'success';  break;
						case 'partially_paid': $label2 = 'warning'; break;
						case 'not_paid': $label2 = 'danger'; break;
					} ?>
						<tr>
							<td style="display:none;"><?=$inv->inv_id?></td>
							<td>
								<a class="text-info" href="<?=base_url()?>invoices/view/<?=$inv->inv_id?>">
									<?=$inv->reference_no?>
								</a>
							</td>
							<td class="">
								<span class="badge bg-inverse-<?=$label2?>"><?=lang($status)?> <?php if($inv->emailed == 'Yes') { ?><i class="fa fa-envelope-o"></i><?php } ?></span>
							  <?php if ($inv->recurring == 'Yes') { ?>
							   <span class="badge bg-inverse-primary m-l-5"><i class="fa fa-retweet"></i></span>
							  <?php }  ?>
							</td>
							<td><?=strftime(config_item('date_format'), strtotime($inv->due_date))?></td>
							<td class="col-currency">
							<?=Applib::format_currency($inv->currency, Invoice::get_invoice_subtotal($inv->inv_id))?>
							</td>
							<td class="col-currency">
								<strong><?=Applib::format_currency($inv->currency, Invoice::get_invoice_due_amount($inv->inv_id));?></strong>
							</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				</div>
			</div>
		</section>
	</div>
</div>
