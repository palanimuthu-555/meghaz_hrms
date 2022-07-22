<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title">Delete Leave Type</h4>
		</div><?php
			echo form_open(base_url().'leave_settings/delete_leave_types'); ?>
		<div class="modal-body">
			<p>This action will delete Leave Type from List. Proceed?</p>
 			<input type="hidden" name="leave_type_id" value="<?=$leave_type_id?>"> 
 			<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary continue-btn"><?=lang('close')?></a>
						</div>
						<div class="col-6">
							<button type="submit" class="btn btn-primary cancel-btn"><?=lang('delete_button')?></button>
						</div>
					</div>
				</div>
 		</div>
		<!-- <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button> -->
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->