<ul class="nav">
	<?php foreach ($estimates as $key => $estimate) { 
	$status = 'DRAFT'; $label = 'default';	
	if ($estimate->invoiced == 'Yes') {	$status = 'INVOICED'; $label = 'success'; }
	if ($estimate->emailed == 'Yes') { $status = 'SENT'; $label = 'info';	}
	?>
	<li class="b-b b-light <?php if($estimate->est_id == $this->uri->segment(3)){ echo "bg-light dk"; } ?>"><a href="<?=base_url()?>estimates/timeline/<?=$estimate->est_id?>">
	<?php
		if ($estimate->client == '0') { ?>
		<span class="badge badge-success">General Estimate</span>
		<?php }else{ ?>
		<?=ucfirst(Client::view_by_id($estimate->client)->company_name); ?>
		<?php } ?>
		<div class="float-right">
			<?=Applib::format_currency($estimate->currency, Estimate::due($estimate->est_id)); ?>
		</div> <br>
		<small class="block small text-muted"><?=$estimate->reference_no?> <span class="badge badge-<?=$label?>"><?=$status?></span></small>
		</a>
	</li>
	<?php } ?>
</ul>