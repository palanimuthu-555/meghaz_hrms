<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-body text-center">
			<?php echo form_open(base_url().'employees/delete'); ?>
				<div class="form-head">
					<h3><?=lang('delete_user')?></h3>
					<p>Are you sure want to delete?</p>
				</div>
				<p class="m-b-20"><?=lang('delete_user_warning')?></p>
				<ul class="list-group mb-4">
					<li class="list-group-item"><?=lang('tickets')?></li>
					<li class="list-group-item"><?=lang('bugs')?></li>
					<li class="list-group-item"><?=lang('comments')?></li>
					<li class="list-group-item"><?=lang('messages')?></li>
					<li class="list-group-item"><?=lang('activities')?></li>
				</ul>
				<input type="hidden" name="user_id" value="<?=$user_id?>">
				<?php
				$company = User::profile_info($user_id)->company;
				if ($company >= 1) {
					$redirect = 'companies/view/'.$company;
				}else{
					$redirect = 'employees';				
				}
				?>
				<input type="hidden" name="r_url" value="<?=base_url()?><?=$redirect?>">
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