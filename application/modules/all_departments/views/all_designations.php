<div class="content">
	<div class="page-header">
    <div class="row">
        <div class="col-sm-8">
			<?php $depart = $this->db->get_where('departments',array('deptid'=>$department_id))->row_array(); ?>
            <h4 class="page-title"><?php echo $depart['deptname']; ?> Positions</h4>
             <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active"><?php echo $depart['deptname']; ?> Positions</li>
            </ul>
        </div>
        <div class="col-sm-4 text-right m-b-30">
			<a href="<?=base_url()?>all_departments" class="btn add-btn float-right ml-2"><i class="fa fa-chevron-left"></i> Back</a>
			<?php if(App::is_permit('menu_departments','create'))
			{?>
			<a href="<?=base_url()?>all_departments/designations/<?php echo $department_id; ?>" class="btn add-btn" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Position</a>
			<?php 
			} ?>
        </div>
    </div>
</div>

    <div class="row">
        <!-- Invoice Items -->
        <div class="col-lg-12">
				<div class="table-responsive">
					<table id="table-templates-2" class="table table-striped custom-table m-b-0 AppendDataTables">
						<thead>
							<tr>
								<th>#</th>
								<th><?=lang('department_name')?> </th>
								<th>Position Title</th>
								<th>Level</th>
								<?php 
								if(App::is_permit('menu_departments','write')==true || App::is_permit('menu_departments','delete')==true)
								{
								?>
									<th class="col-options no-sort text-right"><?=lang('action')?></th>
								<?php
								}
								?>
							</tr>
						</thead>
						<tbody>
							<?php
						if (!empty($all_designation)) {
							$i = 1;
							foreach ($all_designation as $key => $des) { 

								$dep_det = $this->db->get_where('departments',array('deptid'=>$des->department_id))->row_array();

								?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?=$dep_det['deptname']?></td>
								<td>
										<?=$des->designation?>
								</td>
								<td>
									<?php $grade_details = $this->db->get_where('grades',array('grade_id'=>$des->grade))->row_array(); ?>
                                    <?php echo $grade_details['grade_name']; ?>
								</td>
								<?php if(App::is_permit('menu_departments','write')==true || App::is_permit('menu_departments','delete')==true)
								{
								?>
								<td class="text-right">
									<?php if(App::is_permit('menu_departments','write')){?>
									<a href="<?=base_url()?>all_departments/edit_designation/<?=$des->id?>" class="btn btn-success btn-sm" data-toggle="ajaxModal">
										<i class="fa fa-edit"></i>
									</a>
									<?php } ?>
									<?php if(App::is_permit('menu_departments','delete')){?>
									<a href="<?=base_url()?>all_departments/delete_designation/<?=$des->id?>" class="btn btn-danger btn-sm" data-toggle="ajaxModal">
										<i class="fa fa-trash-o"></i>
									</a>
									<?php } ?>
								</td>
								<?php
								}
								?>
							</tr>
							<?php $i++;  } 
							}
							else
							{ ?>
								<tr>No Result found</tr>
						<?php  } ?>
						</tbody>
					</table>
				</div>
        </div>
        <!-- End Invoice Items -->
    </div>

</div>