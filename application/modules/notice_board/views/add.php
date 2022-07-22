<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
            <?php 
            $form_type = 'Add';
            if(isset($notice_board['id'])&&!empty($notice_board['id'])) 
            {  
				$form_type = 'Edit'; ?> 
     <?php  }
            ?>
			<h4 class="modal-title"><?php echo $form_type; ?> Notice Board</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<?php 
			$attributes = array('class' => 'bs-example'); echo form_open_multipart('notice_board/add', $attributes); 
			if(isset($notice_board['id'])&&!empty($notice_board['id'])) 
            {    ?>
                <input type = "hidden" name="edit" value="true">
                <input type = "hidden" name="id" value="<?php echo $notice_board['id']; ?>" id="notice_id">
     <?php  } ?>
			<div class="modal-body">
				<div class="form-group">
					<label><?=lang('title')?> <span class="text-danger">*</span><span id="check_notice_title" style="display: none;color:red;">Title Already Exist!</span></label>
					<input type="text" name="title" class="form-control" value="<?php echo isset($notice_board['title'])?$notice_board['title']:''; ?>" id="notice_title_check" required>
				</div>
				<div class="form-group">
					<label><?=lang('description')?> <span class="text-danger">*</span></label>
					<textarea name ="description" class="form-control" required><?php echo isset($notice_board['description'])?$notice_board['description']:''; ?></textarea>
				</div>

				

				<div class="submit-section">
					<button class="btn btn-primary submit-btn notice_add_submit">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	 $('#notice_title_check').change(function(){
        var notice_title = $(this).val();
        var notice_id = $("#notice_id").val();
        if(notice_title)
        {            
            $('.notice_add_submit').removeAttr('disabled');
            $.post(base_url+'notice_board/check_notice_title/',{notice_title:notice_title,notice_id:notice_id},function(res){

                if(res == 'yes'){
                    $('#check_notice_title').css('display','');
                    $('.notice_add_submit').attr('disabled','disabled');
                }else{
                    $('#check_notice_title').css('display','none');
                    $('.notice_add_submit').removeAttr('disabled');

                }
            });
        }else{
           
            $('.notice_add_submit').attr('disabled','disabled');
        }
    });
</script>