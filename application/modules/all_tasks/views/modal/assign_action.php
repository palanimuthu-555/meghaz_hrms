<div class="modal-dialog modal-dialog-centered" id="assign_modl">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">Assign Task</h4>
        </div>
        <div class="modal-body">
            <form id="assigned_task" method="post" action="#">
                <input type="hidden" name="project" id="project" value="<?=$project?>">
                <input type="hidden" name="task" id="task" value="<?=$task_id?>">
                <input type="hidden" name="type" id="type" value="<?=$action?>">
      <?php    

          if($action=='Due')
        {
            ?>
                <div class="form-group">
                    <label><?=lang('due_date')?></label>
                    <div class="cal-icon">
                        <input type="hidden"  id="assigned_to" value="">
                        <input class="form-control datepicker-input" id="add_task_date_due" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
                    </div>
                </div>
<?php
}
                if($action=='Assign')
                {
                ?>
                <?php if(!User::is_client()){ ?>
                <div class="form-group">
                    <label><?=lang('assigned_to')?></label>
                    <input type="hidden"  id="add_task_date_due" value="">
                    <select name="assigned_to[]" id="assigned_to" class="form-control AssigneDTo" multiple="multiple">
                        <option value="" disabled> Choose Assigned to</option>
                         <?php $whr = array('role_id !='=>2,'activated'=>1,'banned'=>0);
                                $all_userss = $this->db->get_where('users',$whr)->result();
                                $all_members = $this->db->get_where('assign_tasks',array('task_assigned'=>$task_id))->result_array(); 
                 
                 foreach ($all_userss as $value) { ?>
                    <option value="<?=$value->id?>" <?php foreach ($all_members as $user) {
                        if ($user['assigned_user'] == $value->id) { ?> selected = "selected" <?php } } ?>>
                        <?php echo ucfirst(User::displayName($value->id)); ?></option>
                 <?php } ?>
                    </select>
                    <span class="help-block m-b-none"><?=lang('select_team_help')?></span>
                </div>
                <?php } } ?>
                <div class="submit-section">
                    <!-- <div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a> -->
                    <button type="button" data-dismiss="modal" class="btn btn-primary submit-btn" onclick="assign_form_submit()" id="project_assign_tasks">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#add_task_date_start').datepicker({
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
        $('#add_task_date_due').datepicker('setStartDate', minDate);
        if($('#add_task_date_start').val() > $('#add_task_date_due').val())
        $('#add_task_date_due').val('');
    });

    $('#add_task_date_due').datepicker({
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