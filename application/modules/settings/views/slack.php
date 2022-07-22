<div class="p-0">
	<!-- Start Form test -->
	<div class="col-lg-12 p-0">
		<?php
		$attributes = array('class' => 'bs-example form-horizontal','data-validate'=>'parsley','id'=> 'settingsSystemSlack');
		echo form_open('settings/slack_conf', $attributes); ?>
			<div class="card panel-white">
				<div class="card-header">
					<h3 class="card-title p-5">Slack Integration</h3>
				</div>
				<div class="card-body">
					<?php echo validation_errors(); ?>
					<input type="hidden" name="settings" value="<?=$load_setting?>">
					<div class="form-group form-row">
						<label class="col-lg-3 control-label">Slack Notification</label>
						<div class="col-lg-3">
							<label class="switch">
								<input type="hidden" value="off" name="slack_notification" />
								<input type="checkbox" <?php if(config_item('slack_notification') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="slack_notification">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-lg-3 control-label">Slack Username <span class="text-danger">*</span></label>
						<div class="col-lg-3">
							<input type="text" class="form-control" value="<?=config_item('slack_username')?>" name="slack_username" data-required="true">
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-lg-3 control-label">Slack Channel <span class="text-danger">*</span></label>
						<div class="col-lg-3">
							<input type="text" class="form-control" value="<?=config_item('slack_channel')?>" name="slack_channel" data-required="true">
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-lg-3 control-label">Slack Webhook <span class="text-danger">*</span></label>
						<div class="col-lg-8">
							<input type="text" class="form-control" value="<?=config_item('slack_webhook')?>" name="slack_webhook" data-required="true">
						</div>
					</div>
					<div class="text-center m-t-30">
						<button id="settings_system_slack" class="btn btn-primary btn-lg"><?=lang('save_changes')?></button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<!-- End Form -->
</div>