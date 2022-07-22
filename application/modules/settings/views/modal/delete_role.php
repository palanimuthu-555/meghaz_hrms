<div class="modal-dialog  modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title">Delete Reporter</h4>
		</div><?php $attributes = array('class' => 'bs-example form-horizontal','method' => 'post');
			echo form_open(base_url().'settings/roles_delete/'.$role_id); ?>
		<div class="modal-body">
			<p>This action will delete Role from List. Proceed?</p>
 			<input type="hidden" name="role_id" value="<?=$role_id?>"> 
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