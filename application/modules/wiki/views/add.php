<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
            <?php 
            $form_type = 'Add';
            if(isset($wiki['id'])&&!empty($wiki['id'])) 
            {  
				$form_type = 'Edit'; ?> 
     <?php  }
            ?>
			<h4 class="modal-title"><?php echo $form_type; ?> Wiki</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<?php 
			$attributes = array('class' => 'bs-example'); echo form_open_multipart('wiki/add', $attributes); 
			if(isset($wiki['id'])&&!empty($wiki['id'])) 
            {    ?>
                <input type = "hidden" name="edit" value="true">
                <input type = "hidden" name="id" value="<?php echo $wiki['id']; ?>" id="wiki_id">
     <?php  } ?>
			<div class="modal-body">
				<div class="form-group">
					<label><?=lang('title')?> <span class="text-danger">*</span><span id="check_wiki_title" style="display: none;color:red;">Title Already Exist!</span></label>
					<input type="text" name="title" class="form-control" value="<?php echo isset($wiki['title'])?$wiki['title']:''; ?>" required id="wiki_title_check">
				</div>
				<div class="form-group">
					<label><?=lang('description')?> <span class="text-danger">*</span></label>
					<textarea name ="description" class="form-control" required><?php echo isset($wiki['description'])?$wiki['description']:''; ?></textarea>
				</div>

				

				<div class="submit-section">
					<button class="btn btn-primary submit-btn wiki_add_submit">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	 $('#wiki_title_check').change(function(){
        var wiki_title = $(this).val();
        var wiki_id = $("#wiki_id").val();
        if(wiki_title)
        {            
            $('.wiki_add_submit').removeAttr('disabled');
            $.post(base_url+'wiki/check_wiki_title/',{wiki_title:wiki_title,wiki_id:wiki_id},function(res){

                if(res == 'yes'){
                    $('#check_wiki_title').css('display','');
                    $('.wiki_add_submit').attr('disabled','disabled');
                }else{
                    $('#check_wiki_title').css('display','none');
                    $('.wiki_add_submit').removeAttr('disabled');

                }
            });
        }else{
           
            $('.wiki_add_submit').attr('disabled','disabled');
        }
    });
</script>