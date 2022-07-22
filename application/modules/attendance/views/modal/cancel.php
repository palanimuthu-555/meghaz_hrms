<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
	
		<div class="modal-body">
			<div class="form-header">
				<h3>Cancel Leave</h3>
				<p>Are you sure want cancel this leave Request?</p>
			</div>
			<div class="modal-btn delete-action">
				<?php echo form_open(base_url().'leaves/cancel'); ?>
				<input type="hidden" name="req_leave_tbl_id" value="<?=$req_leave_tbl_id?>"> 
					<div class="row">
						<div class="col-6">
							<button type="submit" class="btn btn-primary continue-btn"> Cancel </button>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Close</a>
						</div>
					</div>
				</form>
			</div>
		</div>

	</div>
</div>