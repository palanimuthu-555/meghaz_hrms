<?php //echo 'faq------'.$faq_id; exit;?>
<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		
		<?php echo form_open(base_url().'deleteFaq/'.$faq_id); ?>
			<div class="modal-body text-center">
				<div class="form-head">
				<h3 class="modal-title">Delete FQA</h3>
			 
				<p>Are you sure want to delete this FAQ?</p>
			</div>
				<input type="hidden" name="faq_id" id="faq_id" value="<?php echo $faq_id?>"> 
			
			<div class="modal-btn delete-action"> 
				<div class="row">
						<div class="col-6">
				<button type="submit" class="btn continue-btn">Delete</button>
			</div>
			<div class="col-6">
				<a href="#" class="btn continue-btn" data-dismiss="modal"><?=lang('close')?></a>
			</div>
		</div>
			</div>
			</div>
		</form>
	</div>
</div>