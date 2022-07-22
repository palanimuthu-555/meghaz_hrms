<!-- Start Notebook -->
<?php if(User::is_admin() || (User::is_staff() && User::perm_allowed(User::get_id(),'view_project_notes'))){ ?>
<div class="card panel-white">
	<div class="card-header">
		<h3 class="card-title"><?=lang('project_notes')?></h3>
	</div>
	<?php echo form_open(base_url().'projects/notebook/savenote'); ?>
		<div class="card-body">
			<input type="hidden" name="project" value="<?=$project_id?>">
			<div class="foeditor-noborder">
				<textarea type="text" class="form-control  foeditor-500" name="notes" placeholder="<?=lang('type_your_note_here')?>"><?php echo Project::by_id($project_id)->notes;?></textarea>
			</div>
			<div class="m-t-20">
				<button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
			</div>
		</div>
	</form>
</div>
<!-- End Notebook -->
<?php } ?>