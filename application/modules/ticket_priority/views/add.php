<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
            <?php 
            $form_type = 'Add';
            if(isset($priority['id'])&&!empty($priority['id'])) 
            {  
				$form_type = 'Edit'; ?> 
			<?php  }
            ?>
			<h5 class="modal-title"><?php echo $form_type; ?> Priority</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<?php 
				$attributes = array('class' => 'bs-example'); echo form_open_multipart('ticket_priority/add', $attributes); 
				if(isset($priority['id'])&&!empty($priority['id'])) 
				{ ?>
					<input type = "hidden" name="edit" value="true">
					<input type = "hidden" name="id" value="<?php echo $priority['id']; ?>" id="priority_id">
			<?php } ?>
				<div class="form-group">
					<label><?=lang('priority_name')?> <span class="text-danger">*</span><span id="check_priority_name" style="display: none;color:red;">Priority Already Exist!</span></label>
					<input type="text" name="priority" class="form-control" value="<?php echo isset($priority['priority'])?$priority['priority']:''; ?>" required placeholder="Priority Name" id="priority_name">
				</div>
				<div class="form-group">
					<label><?=lang('priority_hours')?> <span class="text-danger">*</span></label>
					<input type="text" name="hour" class="form-control" value="<?php echo isset($priority['hour'])?$priority['hour']:''; ?>" required placeholder="Priority Hours">
				</div>

				<div class="form-group">
					<label><?=lang('priority_color')?> <span class="text-danger">*</span></label>
					<!-- <input type="text" class="form-control" placeholder="#38354a" name="color"> -->
					<input type="text" id="event_cp" name="color" value="<?php echo isset($priority['hour'])?$priority['color']:'#00AABB'; ?>" class="form-control"> 
				</div>
				<div class="submit-section">
					<button class="btn btn-primary submit-btn priority_add_btn">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>


<script type="text/javascript">
	$('#event_cp').minicolors({
          control: $(this).attr('data-control') || 'hue',
          defaultValue: $(this).attr('data-defaultValue') || '',
          format: $(this).attr('data-format') || 'hex',
          keywords: $(this).attr('data-keywords') || '',
          inline: $(this).attr('data-inline') === 'true',
          letterCase: $(this).attr('data-letterCase') || 'lowercase',
          opacity: $(this).attr('data-opacity'),
          position: $(this).attr('data-position') || 'bottom left',
          swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
          change: function(value, opacity) {
            if( !value ) return;
            if( opacity ) value += ', ' + opacity;
            if( typeof console === 'object' ) {
              console.log(value);
            }
          },
          theme: 'bootstrap'
        });
	 $('#priority_name').change(function(){
        var check_priority_name = $(this).val();
        var priority_id = $("#priority_id").val();
        $.post(base_url+'ticket_priority/check_priority_name/',{check_priority_name:check_priority_name,priority_id:priority_id},function(res){
                if(res == 'yes'){
                    $('#check_priority_name').css('display','');
                    $('.priority_add_btn').attr('disabled','disabled');
                }else{
                    $('#check_priority_name').css('display','none');
                    $('.priority_add_btn').removeAttr('disabled');

                }
        });
    });
</script>