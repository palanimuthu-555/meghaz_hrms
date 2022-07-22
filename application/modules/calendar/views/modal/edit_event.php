<style>
.datepicker{ z-index:1151 !important; }

</style>
<div class="modal-dialog  modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('edit_event')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example','id'=>'calendarEditEvent');
          echo form_open(base_url().'calendar/edit_event',$attributes); ?>
		<div class="modal-body">
		<input type="hidden" name="id" value="<?=$event->id?>">

			 <div class="form-group">
				<label><?=lang('event_name')?> <span class="text-danger">*</span></label>
					<input type="text" class="form-control" value="<?=$event->event_name;?>" name="event_name">
				</div>

				<div class="form-group">
					<label><?=lang('description')?> <span class="text-danger">*</span></label>
					<textarea class="form-control ta" name="description"><?=$event->description;?></textarea>
				</div>


				<div class="form-group">
                    <label><?=lang('start_date')?> <span class="text-danger">*</span></label>
                    <div class="row">
						<div class="col-sm-8">
							<div class="cal-icon">
                                <input class="form-control" id="edit_event_date_fromU" readonly type="text" value="<?=strftime(config_item('date_format'),strtotime($event->start_date));?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" data-date-start-date="0d" >
                            </div>
						</div>
						<div class="col-sm-4">
							<div class='time-icon date time_picker'> 
								 <input type="text" class="form-control datetimepicker-input event_time" data-toggle="datetimepicker" name="from_time" value="<?php  if(isset($event) && ($event->from_time == '00:00:00')){
													echo '';
												} else {  echo (isset($event) && !empty($event->from_time))?$event->from_time:"";}?>">
							</div>
						</div>
					</div>
                </div>

                <div class="form-group">
                    <label><?=lang('end_date')?> <span class="text-danger">*</span></label>
                     <div class="row">
                        <div class="col-sm-8">
                            <div class="cal-icon">
                                <input class="form-control" id="edit_event_date_toU" readonly type="text" value="<?=strftime(config_item('date_format'),strtotime($event->end_date));?>" name="end_date" data-date-format="<?=config_item('date_picker_format');?>" data-date-start-date="0d">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class='time-icon date time_picker'> 
                                 <input type="text" class="form-control datetimepicker-input event_time" data-toggle="datetimepicker" name="to_time" value="<?php  if(isset($event) && ($event->to_time == '00:00:00')){
                                                    echo '';
                                                } else {  echo (isset($event) && !empty($event->to_time))?$event->to_time:"";}?>">
                            </div>
                        </div>
                    </div>
                </div>
				<div class="form-group">
                    <label><?=lang('project')?> <span class="text-danger">*</span></label>
                    <select class="select2-option form-control" name="project" >
                        <optgroup label="<?=lang('none')?>">
                            <option value="<?=$event->project?>" selected="selected"><?=($event->project > 0) ? Project::by_id($event->project)->project_title : lang('none');?>

                            </option>
                        </optgroup>
                        <optgroup label="<?=lang('projects')?>">
							<?php if(User::is_admin()) : ?>
								<?php $list = Project::all(); ?>
							<?php else: ?>
								<?php $list = $this->db->join('assign_projects','project_assigned = project_id')
								                  ->where('assigned_user',User::get_id())->get('projects')->result();
								?>
							<?php endif; ?>
                            <?php foreach ($list as $p){ ?>
                            <option value="<?=$p->project_id?>"><?=$p->project_title?></option>
                            <?php } ?>
                        </optgroup>
                    </select>
                </div>

                <div class="form-group">
					<label><?=lang('event_color')?> <span class="text-danger">*</span></label>
					<input type="text" id="event_cpU" name="color" value="<?php echo $event->color?>" class="form-control" /> 
				</div>



		</div>
		<div class="modal-footer"> 
		    <button type="submit" class="btn btn-success" id="calendar_edit_event"><?=lang('save_changes')?></button>
            <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		</div>
		</form>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">
    $('.datepicker-input').datepicker({ language: locale, autoclose: true});
</script>
<script type="text/javascript">
    $(".select2-option").select2();
</script>

<script type="text/javascript">
    $('#edit_event_date_fromU').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#add_event_date_to').datepicker('setStartDate', minDate);
        if($('#add_event_date_from').val() > $('#add_event_date_to').val())
        $('#add_event_date_to').val('');
    });

    $('#edit_event_date_toU').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    });

</script>
<script type="text/javascript">
	$(".select2-optionU").select2();
	$(".event-from-timeU").select2();
</script>
<script type="text/javascript">
	$('#event_cpU').minicolors({
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

	$('.event_time').datetimepicker({
    format: 'HH:mm',

            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down",
                next: 'fa fa-angle-right',
                previous: 'fa fa-angle-left'
            }
  });
</script>
