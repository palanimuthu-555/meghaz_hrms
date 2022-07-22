<?php //echo 'ssd<pre>'; print_r($editor); ?>
<!-- Modal Dialog -->
<div class="modal-dialog modal-dialog-centered" role="dialog">
	<!-- Modal Content -->
    <div class="modal-content">
        <div class="modal-header">
			<h4 class="modal-title">Edit FAQ</h4>
			<button type="button" class="close" data-dismiss="modal">×</button>
		</div>

		<div class="modal-body">
			<?php $attributes = array('id' => 'faq_submit'); echo form_open(base_url().'addFaq',$attributes); ?>
				<input type="hidden" class="form-control" name="faq_id" id="faq_id" value="<?php echo $faq->id; ?>">
				<div class="form-group">
					<label>Add Question <span class="text-danger">*</span></label>
					<input class="form-control" type="text" name="question" id="question" value="<?php echo $faq->question; ?>">
				</div>
				<div class="form-group">
					<label>Answer <span class="text-danger">*</span></label>
					<textarea name="answer" id="answer" class="form-control foeditor-faq-add" placeholder="" value=""></textarea>
				</div>
				<div class="submit-section">
					<button class="btn btn-primary submit-btn" id="submit_faq">Submit</button>
				</div>
			</form>
		</div>
    </div>
    <!-- /Modal Content -->
</div>
<!-- /Modal Dialog -->