<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-sm-8 col-4">
			<h4 class="page-title"><?='Edit Holiday';?></h4>
			<ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active"><?='Holidays of <span class="yr_cls_h">'.date('Y');?></li>
            </ul>
		</div>
	</div>
</div>
	<div class="row">
		<div class="col-md-6 offset-lg-3">
			<div class="card">
				<div class="card-body">
				<?php  
				 if(!isset($holidays_det) || empty($holidays_det)){ redirect(base_url().'holidays');} 
				 $attributes = array('class' => 'bs-example','id'=> 'employeeEditHoliday');
				echo form_open(base_url().'holidays/edit',$attributes); ?> 
					<div class="form-group">
						<label>Holiday Name <span class="text-danger">*</span><span id="check_holiday_name" style="display: none;color:red;">Holiday Already Exist!</span></label>
						<input type="text" class="form-control" value="<?=$holidays_det[0]['title']?>" name="holiday_title" id="edit_holiday_name">						
					</div>
					<div class="form-group">
						<label>Holiday Date <span class="text-danger">*</span></label>
						<input class="datepicker-input form-control" readonly type="text"  value="<?=date('d-m-Y',strtotime($holidays_det[0]['holiday_date']))?>" name="holiday_date" data-date-format="dd-mm-yyyy">
						
					</div>
					<div class="form-group">
						<label>Holiday Description <span class="text-danger">*</span></label>
						<textarea class="form-control" name="holiday_description"> <?=$holidays_det[0]['description']?></textarea>
					</div>
					<div class="m-t-20 text-center submit-section">
					<input type="hidden" name="holiday_tbl_id" value="<?=$holidays_det[0]['id']?>" id="holiday_id">
						<button class="btn btn-primary submit-btn" id="employee_edit_holiday"> Update Holiday</button>
						<a class="btn btn-danger submit-btn" href="<?php echo base_url().'holidays';?>" >
							Cancel
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> 

