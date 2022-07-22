<?php $inf = Expense::view_by_id($id);?>
<div class="content">
	<div class="row">
		<div class="col-sm-8 col-6">
			<h4 class="page-title"><?=lang('view_expense')?></h4>
		</div>
		<div class="col-sm-4 col-6 text-right m-b-10">
			<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_expenses')) { ?>
			<a href="<?=base_url()?>expenses/edit/<?=$inf->id?>" title="<?=lang('edit_expense')?>" class="btn btn-primary rounded float-right" data-toggle="ajaxModal">
				<i class="fa fa-pencil text-white"></i> <?=lang('edit_expense')?>
			</a>
			<?php } ?>
		</div>
	</div>

	<!-- Start details -->

	<?php if($inf->project != '' || $inf->project != NULL){
	  $p = Project::by_id($inf->project);
	}else{
	  $p = NULL;
	}

	?>
	<div class="row">
		<div class="col-sm-9">
			<div class="card-box">
				<h3 class="card-title">
					<?php if($p != NULL){ ?> 
					<?=lang('project')?> : <a class="text-primary" style="font-size:16px;" href="<?=site_url()?>projects/view/<?=$p->project_id?>">
					<?=strtoupper($p->project_title)?></a>
					<?php } ?>
				</h3>
				<ul class="list-group no-radius m-b-0">
					<li class="list-group-item">
						<span class="text-muted"><?=lang('expense_date')?></span>
						<span class="float-right"><?=strftime(config_item('date_format'), strtotime($inf->expense_date));?></span>
					</li>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('category')?></span>
						<span class="float-right"><?php echo App::get_category_by_id($inf->category); ?></span>
					</li>
					<?php if(User::is_admin() || (User::is_staff() && User::perm_allowed(User::get_id(),'view_project_clients'))) { ?>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('client')?></span>
						<span class="float-right"><strong><a href="<?=base_url()?>companies/view/<?=$inf->client?>"><?=ucfirst(Client::view_by_id($inf->client)->company_name)?></a></strong></span>
					</li>
					<?php } ?>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('added_by')?></span>
						<span class="float-right"><?php echo User::displayName($inf->added_by); ?></span>
					</li>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('billable')?></span>
						<span class="float-right"><?=($inf->billable == '1') ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>';?></span>
					</li>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('invoiced')?></span>
						<span class="float-right"><?=($inf->invoiced == '1') ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>';?></span>
					</li>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('date_saved')?></span>
						<span class="float-right"><?=strftime(config_item('date_format')." %H:%M:%S", strtotime($inf->saved));?></span>
					</li>
					<?php if($inf->invoiced_id > 0) { ?>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('invoiced_in')?></span>
						<span class="float-right">
							<a href="<?=site_url()?>invoices/view/<?=$inf->invoiced_id?>">
								#<?php echo Invoice::view_by_id($inf->invoiced_id)->reference_no; ?>
							</a>
						</span>
					</li>
					<?php } ?>
					<?php if($inf->receipt) { ?>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('attachment')?></span>
						<span class="float-right">
							<a href="<?=base_url()?>assets/uploads/<?=$inf->receipt?>" target="_blank">
								<?=$inf->receipt?>
							</a>
						</span>
					</li>
					<?php } ?>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('notes')?></span><br>
						<span><?=($inf->notes) ? $inf->notes : 'NULL'; ?></span>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="payment-info-box">
				<h5><?=lang('expense_cost')?></h5>
				<h3>
					<?php 
					$cur = ($p != NULL) ? $p->currency : 'USD';
					$cur = ($inf->client > 0) ? Client::client_currency($inf->client)->code : $cur;
					if($inf->currency_symbol != ''){
								$currency = explode('-', $inf->currency_symbol);
								echo $currency[0]. ''.$inf->amount.' '.$currency[1];
						} else{ 
							echo Applib::format_currency($cur, $inf->amount); }
						
					?>
				</h3>
			</div>
			<?php if($inf->added_by != $this->session->userdata('user_id')){ ?>
			<div class="row">
				<div class="col-lg-6">							
					<button class="btn btn-success btn-block m-b-5" onclick="accept_expense(<?=$inf->id?>)" title="<?=lang('accept_expense')?>"><?=lang('approve')?></button>
				</div>		
				<div class="col-lg-6">							
					<button class="btn btn-danger btn-block m-b-5" onclick="decline_expense(<?=$inf->id?>)" title="Reject Expense"><?=lang('reject')?></button>
				</div>	
			<?php } ?>

			</div>
		</div>
	</div>
	<!-- End expense details -->
</div>