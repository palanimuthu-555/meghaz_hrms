<div class="content">
    <div class="page-header">
	<div class="row">
		<div class="col-sm-6  align-item-center">
			<h4 class="page-title"><?=lang('departments')?></h4>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active"><?=lang('departments')?></li>
            </ul>
		</div>
        <div class="col-sm-6  text-right m-b-20">
            <?php if(App::is_permit('menu_departments','create')){?><a class="btn add-btn" href="<?=base_url()?>all_departments/departments/" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Department</a><?php }?>
        </div>
	</div>
</div>

    <div class="row">
        <!-- Project Tasks -->
        <div class="col-lg-12">
                <div class="table-responsive">
                    <table id="table-templates-1" class="table table-striped custom-table m-b-0 AppendDataTables">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?=lang('department_name')?></th>
                                <th>Positions</th>
								<?php if(App::is_permit('menu_departments','write')==true || App::is_permit('menu_departments','read')==true)
								{
								?>
                                <th class="col-options no-sort text-center"><?=lang('action')?></th>
								<?php
								}
								?>
								
							</tr>
                        </thead>
                        <tbody>
                            <?php 
                            $departments = $this -> db -> get('departments') -> result();
                            if (!empty($departments)) {
                                $j =1;
                                foreach ($departments as $key => $d) { ?>
                            <tr>
								<td>
                                    <?php echo $j; ?>
                                </td>
                                <td>
                                    <?=$d->deptname?>
                                </td>
                                <td>
                                	<?php $all_des = $this->db->order_by('designation')->get_where('designation',array('department_id'=>$d->deptid))->result_array(); 
                                		if(count($all_des) != 0)
                                		{
                                			foreach($all_des as $des){
                                                $grade_details = $this->db->get_where('grades',array('grade_id'=>$des['grade']))->row_array();
                                	?>
										<div><?php echo $des['designation']; ?></div>
									<?php } }else{ ?>
										<div>-</div>
									<?php } ?>

                                </td>
								<?php if(App::is_permit('menu_departments','write')==true || App::is_permit('menu_departments','read')==true)
								{
								?>
                                <td class="text-center">
									
									 <?php 
									if(App::is_permit('menu_departments','read'))
									{
									?>
									<a href="<?php echo base_url(); ?>all_departments/view_designation/<?=$d->deptid?>" class="btn btn-info btn-sm">
										Position
									</a>
									
                                    <?php 
									}
									if(App::is_permit('menu_departments','create'))
									{
									?><a href="<?=base_url()?>all_departments/edit_dept/<?=$d->deptid?>" class="btn btn-success btn-sm" data-toggle="ajaxModal">
                                        <i class="fa fa-edit"></i>
                                    </a>
									<?php
									}
									?>
                                   <!--  <a href="<?=base_url()?>items/delete_task/<?=$task->template_id?>" class="btn btn-danger btn-sm" data-toggle="ajaxModal">
                                        <i class="fa fa-trash-o"></i>
                                    </a> -->
                                </td>
								<?php
								}
								?>
                            </tr>
                            <?php $j++; } } else{ ?>
                                <tr>No Results</tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
        </div>
        <!-- End Project Tasks -->
    </div>

</div>