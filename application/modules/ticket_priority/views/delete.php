<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-body">
			<?php echo form_open(base_url().'ticket_priority/delete'); ?>
				<div class="form-head">
					<h3>Delete Priority
						<!-- <?=lang('delete_priority')?> -->
							
						</h3>
					<p>Are you sure want to delete?</p>
				</div>
				<!-- <p><?=lang('delete_jobtypes_warning')?></p> -->
				<input type="hidden" name="id" value="<?php echo isset($id)?$id:''; ?>">
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