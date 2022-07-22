<div class="content">
	<div class="page-header">
	<div class="row">
		 <div class="col-md-6 col-md-offset-3">
			<h4 class="page-title"><?='Create Holiday';?></h4>
			<ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url()?>holidays"><?='Holidays';?> </a></li>
                <li class="breadcrumb-item active"><?='Create Holiday';?></li>
            </ul>
		</div>
		
	</div>
</div>

   <div class="row">
	   <div class="col-md-6 offset-lg-3">
			<div class="card">
        <div class="card-body">
			<?php $attributes = array('class' => 'bs-example','id'=> 'employeeCreateHoliday'); echo form_open(base_url().'holidays/add',$attributes); ?> 
				<div class="form-group">
					<label>Holiday Name <span class="text-danger">*</span><span id="check_holiday_name" style="display: none;color:red;">Holiday Already Exist!</span></label>
					<input type="text" class="form-control" value="" name="holiday_title" id="holiday_name">
				</div>
				<div class="form-group">
					<label>Holiday Date <span class="text-danger">*</span></label>
					<input class="datepicker-input form-control" readonly size="16" type="text"  value="" name="holiday_date" data-date-format="dd-mm-yyyy" > 
					
				</div>
				<div class="form-group">
					<label>Holiday Description <span class="text-danger">*</span></label>
					<textarea class="form-control" name="holiday_description"></textarea>
				</div>
				<div class="m-t-20 text-center submit-section">
					<button class="btn btn-primary submit-btn" id="employee_create_holiday">Create Holiday</button>
					<a class="btn btn-danger submit-btn" type="submit" href="<?php echo base_url().'holidays';?>" >
						 Cancel 
					</a>
				</div>
			</form>
	   </div>
   </div>
</div>
</div>
</div>