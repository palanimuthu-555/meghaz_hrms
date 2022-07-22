<div class="p-0">
    <!-- Start Form -->
    <div class="col-lg-12 p-0">
        <?php
        $attributes = array('class' => 'bs-example','id'=>'dataimport_form','enctype'=>'multipart/form-data');
        echo form_open_multipart('settings/data_import', $attributes); ?>
            <div class="card-box">
                        <a href="<?php echo base_url(); ?>settings/user_excel" class="btn btn-primary btn-sm" style="float: right;" ><?=lang('employee_export')?></a>
                        <a href="<?php echo base_url(); ?>assets/sample_files/user.xlsx" class="btn btn-info btn-sm mr-2" style="float: right;" download><?=lang('sample_import_format')?></a>
					<h3 class="card-title"><?=lang('employee_import')?></h3>
                    <input type="hidden" name="settings" value="<?=$load_setting?>">
                    <div class="tab-content tab-content-fix">
                        <div class="tab-pane fade show active" id="tab-english">
                            <div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label>Choose upload file<span class="text-danger">*</span></label>
										<input type="file" name="data_import" id="data_import" class="form-control" >
									</div>
								</div>
                            </div>
                        </div>
					<div class="submit-section">
                        <button id="general_settings_save" class="btn btn-primary submit-btn"><?=lang('import')?></button>
					</div>
				</div>
            </div>
        </form>
    </div>
    <!-- End Form -->
</div>