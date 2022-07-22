<!-- Modal Dialog -->
<div class="modal-dialog modal-dialog-centered" role="dialog">
	<!-- Modal Content -->
    <div class="modal-content">
        <div class="modal-header">
			<h4 class="modal-title">Edit Category</h4>
			<button type="button" class="close" data-dismiss="modal">×</button>
		</div>

		<div class="modal-body">
				<?php $attributes = array('id' => 'category_submit'); echo form_open(base_url().'forms/addCategory',$attributes); ?>
					<div class="row">
						<input class="form-control" type="hidden" id="category_id" name="category_id" value="<?php echo $category_details->id; ?>">
						<div class="col-md-12">
						<div class="form-group">
							<label><?php echo lang('category_name'); ?><span class="text-danger">*</span></label>
							<input class="form-control" type="text" name="category_name" id="category_name" value="<?php echo $category_details->category_name; ?>">
						</div>
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" id="submit_category"><?php echo lang('submit'); ?></button>
					</div>
				</form>
			</div>
        	
    </div>
    <!-- /Modal Content -->
</div>
<!-- /Modal Dialog -->