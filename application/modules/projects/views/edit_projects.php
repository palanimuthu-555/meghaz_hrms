				<!-- Page Content -->
                <div class="content container-fluid">
                	 <div class="page-header">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title"><?=lang('edit_project')?></h4>
			<ul class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
				<li class="breadcrumb-item active"><a href="<?=base_url()?>/projects"><?=lang('projects')?></a></li>
				<li class="breadcrumb-item active">Edit Project</li>
			</ul>
		</div>
	</div>
</div>
		
<!-- Start create project -->
<div class="row">
<div class="col-md-12">
	<section class="card panel-white">
		
		<div class="card-body">

			<?php if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_projects')){

				$project = Project::by_id($project_id);
						$attributes = array('class' => 'bs-example form-horizontal','id' => 'projectEditForm');
						echo form_open(base_url().'projects/edit',$attributes); ?>
						<?php echo validation_errors('<span style="color:red">', '</span><br>'); ?>
						<input type="hidden" name="project_id" value="<?=$project->project_id?>">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label class="col-lg-12 control-label"><?=lang('status')?> </label>
									<div class="col-lg-12">
										<select class="form-control" name="status">
											<option value="Active"<?=($project->status == 'Active' ? ' selected="selected"':'')?>><?=lang('active')?></option>
											<option value="On Hold"<?=($project->status == 'On Hold' ? ' selected="selected"':'')?>><?=lang('on_hold')?></option>
											<option value="Done"<?=($project->status == 'Done' ? ' selected="selected"':'')?>><?=lang('done')?></option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
						<div class="form-group">
							<label class="col-lg-12 control-label"><?=lang('project_code')?> <span class="text-danger">*</span></label>
							<div class="col-lg-12">
								<input type="text" class="form-control" value="<?=$project->project_code?>" name="project_code" readonly>
							</div>
						</div>
						</div>
							<div class="col-lg-6">
						<div class="form-group">
							<label class="col-lg-12 control-label"><?=lang('project_title')?> <span class="text-danger">*</span><span id="check_edit_project_name" style="display: none;color:red;">Project Already Exist!</span></label>
							<div class="col-lg-12">
								<input type="text" class="form-control" value="<?=$project->project_title?>" name="project_title" id="edit_project_name">
							</div>
						</div>		
						</div>
							<div class="col-lg-6">
						<div class="form-group">
							<label class="col-lg-12 control-label">Company<span class="text-danger">*</span> </label>
							<div class="col-lg-12">
								<div class="m-b"> 
									<select  style="width:100%;" class="form-control" name="client" >
									<?php if($project->client > 0) { ?>
						<option value="<?=$project->client?>">
						<?=ucfirst(Client::view_by_id($project->client)->company_name)?>
						</option>
									<?php } ?>
										<?php foreach (Client::get_all_clients() as $key => $c) { ?>
											<option value="<?=$c->co_id?>"><?=ucfirst($c->company_name)?></option>
											<?php } ?>
										</select> 
									</div> 
								</div>
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group">
								<label class="col-lg-12 control-label"><?=lang('start_date')?> <span class="text-danger">*</span></label> 
								<div class="col-lg-12">
									<input class="datepicker-input form-control"  type="text" data-date-format="dd-mm-yyyy" name="start_date" value="<?php echo $project->start_date?date('d-m-Y',strtotime($project->start_date)):date('d-m-Y'); ?>" size="16">
								</div> 
							</div> 
							</div>
							<div class="col-lg-6">
							<div class="form-group">
								<label class="col-lg-12 control-label"><?=lang('due_date')?> <span class="text-danger">*</span></label> 
								<div class="col-lg-12">
									<input class="datepicker-input form-control"  type="text" data-date-format="dd-mm-yyyy" name="due_date" value="<?php echo $project->due_date?date('d-m-Y',strtotime($project->due_date)):date('d-m-Y'); ?>" size="16">
								</div> 
							</div> 
							</div>
							<div class="col-lg-6">
							 <!-- <div class="form-group"> 
								<label class="col-lg-3 control-label"><?=lang('progress')?></label>
								<div class="col-lg-5"> 
									<div id="progress-slider"></div>
									<input id="progress" type="hidden" value="<?=$project->progress?>" name="progress"/>
								</div>
							</div>   -->

							<div class="form-group">
								<label class="col-lg-12 control-label">Lead Name <span class="text-danger">*</span></label>
								<div class="col-lg-12">

									<select class="select2-option form-control" name="assign_lead" > 
										<optgroup label="Staff">
											<?php foreach (User::team() as $user): ?>
												<option value="<?=$user->id?>"  <?php 
													if ($user->id == $project->assign_lead) { ?> selected = "selected" <?php }   ?>>
													<?=ucfirst(User::displayName($user->id))?>
												</option>
											<?php endforeach ?>
										</optgroup> 
									</select>
								</div>
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group ">
								<label class="col-lg-12 control-label"><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
								<div class="col-lg-12">

									<select class="select2-option form-control" multiple="multiple" name="assign_to[]" > 
										<optgroup label="Staff">
											<?php foreach (User::team() as $user): ?>
												<option value="<?=$user->id?>" <?php foreach (Project::project_team($project->project_id) as $value) {
													if ($user->id == $value->assigned_user) { ?> selected = "selected" <?php } } ?>>
													<?=ucfirst(User::displayName($user->id))?>
												</option>
											<?php endforeach ?>
										</optgroup> 
									</select>
								</div>
							</div>
							</div>
							<div class="col-lg-12">
							<div class="form-group">
								<label class="col-lg-12 control-label"><?=lang('fixed_rate')?></label>
								<div class="col-lg-12">
									<label class="switch">
										<input type="checkbox" <?php if($project->fixed_rate == 'Yes'){ echo "checked=\"checked\""; } ?> name="fixed_rate" id="fixed_rate" >
										<span></span>
									</label>
								</div>
							</div>

							</div>
							<div class="col-lg-6">
							<div id="hourly_rate" <?php if($project->fixed_rate == 'Yes'){ echo "style=\"display:none\""; }?>>
								<div class="form-group">
									<label class="col-lg-12 control-label"><?=lang('hourly_rate')?>  (<?=lang('eg')?> 50 )<span class="text-danger">*</span></label>
									<div class="col-lg-12">
										<input type="text" class="form-control money" value="<?=$project->hourly_rate?>" name="hourly_rate">
									</div>
								</div>
							</div>
							<div id="fixed_price" <?php if($project->fixed_rate == 'No'){ echo "style=\"display:none\""; }?>>
								<div class="form-group">
									<label class="col-lg-12 control-label"><?=lang('fixed_price')?> (<?=lang('eg')?> 300 )<span class="text-danger">*</span></label>
									<div class="col-lg-12">
										<input type="text" class="form-control" value="<?=$project->fixed_price?>" name="fixed_price">
									</div>
								</div>
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group">
								<label class="col-lg-12 control-label"><?=lang('estimated_hours')?> <span class="text-danger">*</span></label>
								<div class="col-lg-12">
									<input type="text" class="form-control" value="<?=$project->estimate_hours?>" name="estimate_hours">
								</div>
							</div>	
							</div>
							<div class="col-lg-12">	
							<div class="form-group">
								<label class="col-lg-12 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
								<div class="col-lg-12">
									<textarea name="description" class="form-control foeditor-project-edit" placeholder="<?=lang('about_the_project')?>" required><?=$project->description?></textarea>
									<div class="row">
									<div class="col-md-6">
									<label id="project_description_error" class="error display-none" style="position:inherit;top:0">Description must not empty</label>
									</div>
									</div>
								</div>
							</div>
							</div>
							<div class="col-lg-12">
							<div class="submit-section">
								<button id="project_edit_dashboard" class="btn btn-primary submit-btn"><?=lang('save_changes')?></button>
							</div>
						</div>
					</div>
						</form>
						<?php } ?>
					</div>
				</section>
			</div>
			</div>
			</div>
		</div>
	</div>