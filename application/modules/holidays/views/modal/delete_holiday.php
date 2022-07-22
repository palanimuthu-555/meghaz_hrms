<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"><?='Delete Holiday'?></h4>
		</div>
		<?php echo form_open(base_url().'holidays/delete'); ?>
			<div class="modal-body text-center">
				<p class="mb-0"><?='This action will delete Holiday from List. Proceed?'?></p>
				<input type="hidden" name="holiday_tbl_id" value="<?=$holiday_id?>"> 
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
			</div>
		</form>
	</div>
</div>