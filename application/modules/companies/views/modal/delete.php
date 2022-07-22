<div class="modal-dialog  modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-body text-center">
			<?php echo form_open(base_url().'companies/delete'); ?>
				<div class="form-head">
					<h3><?=lang('delete_company')?></h3>
					<p>Are you sure want to delete?</p>
				</div>
				<p class="m-b-20"><?=lang('delete_company_warning')?></p>
				<ul class="list-group">
					<li class="list-group-item"><?=lang('invoices')?></li>
					<li class="list-group-item"><?=lang('payments')?></li>
					<li class="list-group-item"><?=lang('estimates')?></li>
					<li class="list-group-item"><?=lang('expenses')?></li>
					<li class="list-group-item"><?=lang('activities')?></li>
				</ul>
				<input type="hidden" name="company" value="<?=$company_id?>">
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<button type="submit" class="btn continue-btn">Delete</button>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn continue-btn">Cancel</a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>